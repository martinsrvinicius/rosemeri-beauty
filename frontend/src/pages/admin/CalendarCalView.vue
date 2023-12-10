<template>
  <div>
    <div class="btn-top">
      <va-button @click="addNew"> Novo Agendamento </va-button>
      <va-button class="settings" preset="plain" @click="showConfig = !showConfig">
        <va-icon name="settings" size="large"></va-icon>
      </va-button>
    </div>

    <!--MODAL NOVO AGENDAMENTO-->
    <va-modal :mobile-fullscreen="false" v-model="showModalNew" cancelText="Cancelar" okText="Agendar">
      <template #content>
        <AddNewService @cancel="showModalNew = false" @calendar="handleAddNewService" />
      </template>
    </va-modal>
  </div>
  <!--FIM MODAL NOVO AGENDAMENTO-->

  <!--CALENDÁRIO-->
  <va-inner-loading :loading="!refresh">
    <vue-cal
      style="height: 80vh"
      class="vuecal--blue-theme"
      :events="events"
      today-button
      :time-step="30"
      locale="pt-br"
      :disable-views="['years']"
      click-to-navigate
      :time-from="7 * 60"
      :time-to="24 * 60"
      :on-event-click="logEvents"
      @event-drop="handleOnDrop"
      :editable-events="{ title: false, drag: true, resize: false, delete: true, create: false }"
      v-if="refresh"
    >
    </vue-cal>
  </va-inner-loading>

  <!--MODAL SELECTED ITEM-->
  <va-modal v-model="showModal" hide-default-actions :mobile-fullscreen="false">
    <EventDetails :item="selectedItem" @cancel="closeModal = true" @confirm="getCalendar" />
    <!--<va-button @click="closeModal = true">Voltar</va-button>-->
  </va-modal>
</template>

<script setup lang="ts">
  import VueCal from 'vue-cal'
  import 'vue-cal/dist/vuecal.css'
  import { ref, onMounted, watch } from 'vue'
  import axios from 'axios'
  import { useGlobalStore } from '../../stores/global-store'
  import AddNewService from '../../components/modal/AddNewService.vue'
  import EventDetails from '../../components/modal/EventDetails.vue'
  import { useToast } from 'vuestic-ui'

  const { init: initToast } = useToast()
  const refresh = ref(true)
  const selectedItem = ref()
  const showModal = ref()
  const closeModal = ref()
  const showConfig = ref()
  const showModalNew = ref()

  //Eventos
  const events = ref([
    {
      id: '',
      start: new Date(),
      end: new Date(),
      title: 'Need to go shopping',
      content: '<i class="icon material-icons">shopping_cart</i>',
    },
  ])

  onMounted(() => {
    getCalendar()
  })

  watch([showModal, closeModal], () => {
    if (!showModal.value) {
      showModal.value = true
    }
    if (closeModal.value) {
      showModal.value = false
    }
  })

  //ON DROP
  function handleOnDrop(event: any) {
    if (event.newDate < new Date()) {
      let msg = 'Agendamento com data inválida.'
      let color = '#ff0000'
      notify(msg, color)
      getCalendar()
    } else {
      let msg = 'Agendamento alterado com sucesso'
      let color = '#008000'
      notify(msg, color)
      updateDroppedItem(event.originalEvent.contentFull.uniqueId, event.newDate)
    }
  }

  async function updateDroppedItem(uniqueId: string, dataHora: any) {
    var d = new Date(dataHora)
    var t = `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()} ${d.getHours()}:${d.getMinutes()}:${d.getSeconds()}`
    let data = JSON.stringify({
      uniqueId: uniqueId,
      dataHora: t,
      confirm: 0,
    })
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/calendar/update_ondrop.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'POST',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        console.log('UPDATE: ', res.data)
        if (res.data) {
          let msg = 'Agendamento alterado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Agendamento não alterado'
          let color = '#ff0000'
          notify(msg, color)
        }
        getCalendar()
      })
      .catch((error) => {
        console.log('Erro: ', error)
      })
  }
  //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

  //Close modal and update calendar after new insert
  function handleAddNewService() {
    getCalendar()
    showModalNew.value = false
  }

  async function getCalendar() {
    closeModal.value = true
    refresh.value = false
    await axios
      // .get(API_PATH + '/src/api/calendar_api.php?action=getCalendar')
      .get('https://rosemeri-beauty.vinim.eu/api/calendar/read_calendar.php')
      .then((res) => {
        //console.log('GET CALENDAR: ', res.data)
        fillCalendarItems(res.data.calendar)
      })
      .catch((error) => {
        console.log('Erro: ', error)
      })
  }

  //Open modal with selected item
  function logEvents(event: any, e: any) {
    closeModal.value = false
    showModal.value = true
    selectedItem.value = event
    //console.log('event: ', event)
    e.stopPropagation()
  }

  function fillCalendarItems(value: any) {
    events.value = []

    let i = 0
    while (i < value.length) {
      var dataHora = new Date(value[i]['dataHora'])
      var obj = {
        id: String(value[i].uniqueId),
        start: dataHora,
        end: calculateServiceTime(value[i].dataHora, value[i].duracao),
        title:
          '<p style="background-color:#ff3399; color:white; height:40px;padding-top:8px"><strong>' +
          value[i].service +
          '</strong></p>',
        content: '<p>Cliente: ' + value[i].client + '</p>',
        contentFull: value[i],
      }
      events.value = [...events.value, { ...obj }]
      i++
    }
    refresh.value = true
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
    return new Date(split[0] + ' ' + s[0] + ':' + min)
  }

  //<<<<<<<<<Add new event
  function addNew() {
    showModalNew.value = !showModalNew.value
  }

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

<style>
  .vuecal__no-event {
    display: flex;
  }

  .vuecal__event {
    background-color: #ffcce6;
  }

  .btn-top {
    display: flex;
    justify-content: space-between;
  }

  body > div.va-modal > div.va-modal__container > div > div {
    min-width: 330px !important;
    overflow: hidden !important;
  }

  @media screen and (min-width: 450px) {
    .btn-top {
      display: flex;
      justify-content: space-between;
    }
    .settings {
      margin-bottom: 10px;
    }
  }
</style>
