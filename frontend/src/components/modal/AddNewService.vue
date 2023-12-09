<template>
  <div class="new-container">
    <div class="header-container">
      <h1>Novo Agendamento</h1>
    </div>

    <div class="date-time-container">
      <va-date-input
        class="date"
        style="font-size: 20px; overflow-y: auto"
        label="Data"
        v-model="date"
        stickToEdges
      ></va-date-input>
      <br />
      <va-time-input class="time" label="Hora" style="font-size: 20px; overflow-y: auto" v-model="time"></va-time-input>
    </div>
    <br />

    <div class="modal-container">
      <va-select
        class="input-select"
        style="height: 60px; font-size: 20px; overflow-y: auto"
        v-model="item.client"
        label="Nome do Cliente"
        :options="optionsClient"
        :track-by="(option: any) => option.id"
        placeholder="Selecione um cliente"
        allow-create="unique"
        @create-new="addNewClient"
      />
      <br />
      <br />
      <va-select
        class="input-select"
        v-model="item.service"
        :options="optionsService"
        style="height: 60px; font-size: 20px; overflow-y: auto"
        :track-by="(option: any) => option.id"
        placeholder="Selecione o serviço"
        allow-create="unique"
        multiple
        @create-new="addNewService"
        label="Serviços"
      ></va-select>
    </div>

    <div class="btn-group">
      <va-button class="btn-cancel" preset="plain" @click="emit('cancel', true)"
        ><span style="font-size: 20px">Cancelar</span></va-button
      >
      <va-button class="btn-save" @click="addNewEvent" :disabled="invalidDate"
        ><span style="font-size: 20px">Agendar</span></va-button
      >
    </div>
  </div>
</template>
<script setup lang="ts">
  import axios from 'axios'
  import { formatFn } from '../../utils/utils.js'
  import { ref, reactive, watch, onMounted } from 'vue'
  import { useToast } from 'vuestic-ui'

  const { init: initToast } = useToast()
  const emit = defineEmits(['cancel', 'calendar'])

  const date = ref(new Date())
  const time = ref(new Date())
  const invalidDate = ref(true)
  const serviceDate = ref(new Date())
  const serviceTime = ref(new Date())
  const cancel = ref()
  const ok = ref()
  const clients = ref()

  const item = reactive({
    client: { id: '' },
    service: [],
    startDate: new Date(),
    endTime: new Date(),
  })

  const optionsClient = ref([
    {
      id: 0,
      text: '',
    },
  ])

  const optionsService = ref([
    {
      id: '',
      text: '',
      duracao: 0,
    },
  ])

  onMounted(() => {
    getClients()
    getServices()
  })

  //VALIDATE DATE AND TIME BEFORE UPDATE EVENT INFO
  watch([serviceDate, serviceTime], () => {
    let d = serviceDate.value
    let t = serviceTime.value
    let eventUpdate = new Date(
      d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate() + ' ' + t.getHours() + ':' + t.getMinutes(),
    )
    if (eventUpdate < new Date()) {
      invalidDate.value = true
    } else {
      invalidDate.value = false
    }
  })

  watch([date, time], () => {
    let d = date.value
    let t = time.value
    let event = new Date(
      d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate() + ' ' + t.getHours() + ':' + t.getMinutes(),
    )
    if (event < new Date()) {
      invalidDate.value = true
    } else {
      invalidDate.value = false
    }
  })

  //CREATE NEW EVENT ON CALENDAR
  async function addNewEvent() {
    var d = formatFn(date.value)
    let dataHora = `${d} ${time.value.getHours()}:${time.value.getMinutes()}:${time.value.getSeconds()}`

    let duration = []
    let serviceList = []
    let start = []
    start.push(dataHora)

    for (let i = 0; i < item.service.length; i++) {
      serviceList.push(item.service[i].id)
      duration.push(item.service[i].duracao)
      start.push(String(calculateServiceTime(start[i], item.service[i].duracao)))
    }
    console.log('duration list: ', duration)
    console.log('start list: ', start)

    let data = JSON.stringify({
      confirm: '0',
      uniqueId: '0',
      uniqueIdCliente: item.client.id,
      uniqueIdServico: serviceList,
      dataHora: start,
    })
    console.log('DATA: ', data)
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/calendar/create_event.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'POST',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        console.log('create: ', res.data)
        if (res.data.create[0]) {
          let msg = 'Agendamento realizado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Agendamento não realizado'
          let color = '#ff0000'
          notify(msg, color)
        }
        emit('calendar', true)
      })
      .catch((error) => {
        console.log(error)
      })
  }

  //Function to calculate the duration of the service to set the end time on calendar
  function calculateServiceTime(start: string, time: string) {
    //Start is the begining of the event date and hours
    let split = start.split(' ')
    //Split hours and minutes
    let splitStart = split[1].split(':')
    //Convert to minutes
    let toMinutes = Number(splitStart[0]) * 60
    toMinutes += Number(time) + Number(splitStart[1])
    //Convert to hour
    let toHour = toMinutes / 60
    let min = null
    let s = String(toHour).split('.')
    if (s[1]) {
      //If hour is split
      min = (Number(s[1]) * 60) / 100
      if (String(min).length > 1) {
        //If minutes has more than 2 digits
        if (String(min)[0] == '9' && String(min)[1] == '9') {
          //If minutes is 99999
          min = 10
        } else {
          //If there is two digits and number string can be parsed to number
          min = Number(String(min)[0] + String(min)[1])
        }
      }
    }
    //If min is == null it receives 0 zero
    if (!min) {
      min = '0'
    }
    //If the duration is lower than 10 minutes it receives the value of 10 minutes minimum
    if (Number(time) < 10) {
      min = '10'
    }
    //If it past the 24h it receives the later value available in a day
    if (Number(s[0]) > 24) {
      s[0] = '23'
      min = '59'
    }
    //Append a zero to a one digit number result
    if (String(min).length < 2) {
      min = min + '0'
    }
    return String(split[0] + ' ' + s[0] + ':' + min + ':00')
  }

  //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

  //GET ALL CLIENTS
  async function getClients() {
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/clients/read_clients.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'GET',
        maxBodyLength: Infinity,
      })
      .then((res) => {
        //console.log("GET ALL CLIENTES: ", res.data.clients[0].client)
        clients.value = []
        clients.value.push(res.data.clients)
        optionsClient.value = []
        for (let i = 0; i < res.data.clients.length; i++) {
          optionsClient.value.push({
            id: res.data.clients[i].uniqueId,
            text: res.data.clients[i].client,
          })
        }
      })
      .catch((error) => {
        console.log('Erro: ', error)
      })
  }

  //GET ALL SERVICES
  async function getServices() {
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/service/read_service_list.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'GET',
        maxBodyLength: Infinity,
      })
      .then((res) => {
        console.log('GET ALL SERVICES: ', res.data.service)
        optionsService.value = []
        for (let i = 0; i < res.data.service.length; i++) {
          optionsService.value.push({
            id: res.data.service[i].uniqueId,
            text: res.data.service[i].titulo,
            duracao: res.data.service[i].duracao,
          })
        }
        //getCalendar()
      })
      .catch((error) => {
        console.log('Erro: ', error)
      })
  }

  //INSERT NEW CLIENT INTO DATABASE
  function addNewClient(newOption: any) {
    console.log('NOVO CLIENTE: ', newOption)
    const option = {
      id: Number(optionsClient.value.length),
      text: String(newOption),
      //value: newOption,
    }
    insertNewClient(option)
    optionsClient.value = [...optionsClient.value, option]
  }

  async function insertNewClient(option: any) {
    let data = JSON.stringify({
      name: option.text,
    })

    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/clients/create_client.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'POST',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        console.log('INSERT NEW: ', res.data)
        if (res.data) {
          let msg = 'Cliente adicionado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Cliente não adicionado'
          let color = '#ff0000'
          notify(msg, color)
        }
      })
      .catch((error) => {
        console.log('Erro: ', error)
      })
  }
  //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

  //INSERT NEW SERVICE INTO DATABASE
  function addNewService(newOption: any) {
    const option = {
      id: String(optionsService.value.length),
      text: String(newOption),
      duracao: 0,
    }
    insertNewService(option)
    optionsService.value = [...optionsService.value, option]
  }

  async function insertNewService(option: any) {
    let data = JSON.stringify({
      titulo: option.text,
    })

    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/service/create_service.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'POST',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        console.log('INSERT NEW: ', res.data)
        if (res.data) {
          let msg = 'Serviço adicionado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Serviço não adicionado'
          let color = '#ff0000'
          notify(msg, color)
        }
      })
      .catch((error) => {
        console.log('Erro: ', error)
      })
  }
  //<<<<<<<<<<<<<<<<<<<<<<<<<

  //NOTIFY EVENT CHANGES
  function notify(name: string, color: string) {
    initToast({
      message: `${name}`,
      position: 'top-right',
      color: color,
    })
  }
  //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
</script>

<style scoped>
  * {
    user-select: none;
  }

  .new-container {
    width: 100%;
    overflow: hidden;
  }

  .date-time-container {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    margin: 10px 0px 5px;
    height: fit-content;
  }

  .modal-container {
    height: 150px;
  }

  .btn-group {
    display: flex;
    flex-direction: column-reverse;
    margin-top: 10px;
  }

  .btn-save {
    height: 50px;
    margin-bottom: 1px;
  }

  .btn-cancel {
    height: 50px;
  }

  .date {
    height: 80px !important;
    width: 50px;
    margin-bottom: 30px;
    font-size: 40px;
  }

  .header-container {
    display: flex;
    width: 100%;
    height: 50px;
    background-color: rgb(107, 107, 106);
  }

  h1 {
    font-size: 1.5rem;
    color: white;
    margin: auto;
  }

  @media screen and (min-width: 450px) {
    .new-container {
      right: 25%;
      -webkit-box-shadow: -1px 0px 28px -18px rgba(0, 0, 0, 0.75);
      -moz-box-shadow: -1px 0px 28px -18px rgba(0, 0, 0, 0.75);
      box-shadow: -1px 0px 28px -18px rgba(0, 0, 0, 0.75);
    }

    .date-time-container {
      width: 90%;
      margin: 10px auto 0px;
    }

    .modal-container {
      width: 90%;
      margin: 0px auto;
    }

    .btn-group {
      display: flex;
      width: 90%;
      flex-direction: column-reverse;
      margin: 10px auto;
    }
  }
</style>
