<template>
  <div class="new-container-edit">
    <div class="header-container-check" style="margin-bottom: 15px">
      <h1 class="title-modal">Opções de Edição</h1>
    </div>

    <div class="edit-modal">
      <va-collapse class="min-w-96" header="Editar Agendamento" v-model="colDelete" @click="colEdit = false">
        <div class="collapse">
          <va-date-picker v-model="serviceDate" class="col-date" label="Editar Data"></va-date-picker>
          <div class="col-time-container">
            <va-time-input label="Horas" class="col-time" v-model="serviceTime"></va-time-input>
          </div>
        </div>
      </va-collapse>

      <va-collapse
        class="min-w-96"
        header="Eliminar Agendamento"
        v-model="colEdit"
        color="#ffff"
        @click="colDelete = false"
      >
        <!--CANCELAR EVENTO-->
        <div class="delete-col">
          <p class="p-delete">Tem certeza que deseja eliminar o agendamento?</p>

          <va-button color="#434343" class="btn-delete" @click="onDeleteItem"
            ><span style="font-size: 20px">Eliminar</span></va-button
          >
        </div>
      </va-collapse>
    </div>
    <div class="btn-option">
      <va-button preset="plain" class="btn-cancel" @click="emit('cancel', true)"
        ><span style="font-size: 20px">Cancelar</span></va-button
      >
      <va-button class="btn-guardar" @click="updateEvent" :disabled="invalidDate"
        ><span style="font-size: 20px">Guardar</span></va-button
      >
    </div>
  </div>
</template>

<script setup lang="ts">
  import axios from 'axios'
  import { ref, watch, onMounted } from 'vue'
  import { useToast } from 'vuestic-ui'

  const { init: initToast } = useToast()
  const colDelete = ref()
  const colEdit = ref()
  const serviceDate = ref()
  const serviceTime = ref(new Date())
  const invalidDate = ref(true)
  const props = defineProps(['item'])
  const emit = defineEmits(['cancel', 'confirm'])

  onMounted(() => {
    serviceTime.value = new Date(props.item.contentFull.dataHora)
    serviceDate.value = new Date(props.item.contentFull.dataHora)
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

  //UPDATE EVENT INFO
  async function updateEvent() {
    var d = serviceDate.value
    var t = serviceTime.value
    var info = `${d.getFullYear()}-${
      d.getMonth() + 1
    }-${d.getDate()} ${t.getHours()}:${t.getMinutes()}:${t.getSeconds()}`

    let data = JSON.stringify({
      uniqueId: props.item.contentFull.uniqueId,
      dataHora: info,
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
        if (res.data) {
          let msg = 'Agendamento alterado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Agendamento não alterado'
          let color = '#ff0000'
          notify(msg, color)
        }
        //close modal after updating
        emit('confirm', true)
      })
      .catch()
  }

  //DELETE EVENTS ON CALENDAR
  async function onDeleteItem() {
    let data = JSON.stringify({
      uniqueId: props.item.contentFull.uniqueId,
    })
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/calendar/delete_event.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'post',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        // console.log('DELETE: ', res.data)
        //close modal after updating
        emit('confirm', true)
      })
      .catch()
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

<style scoped>
  .new-container-edit {
    background-color: white;
    overflow: hidden;
    height: fit-content;
  }

  .header-container-check {
    color: white;
    background-color: #808080;
    width: 100%;
    text-align: center;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .collapse {
    display: flex;
    flex-wrap: wrap;
    justify-content: start;
    background-color: rgb(243, 240, 240);
    margin-bottom: 10px;
  }

  .edit-modal {
    width: 100%;
    font-size: 20px;
  }

  .col-date {
    margin: 5px auto 15px;
    border: 1px solid rgb(215, 215, 215);
  }

  .col-time {
    margin: 10px auto !important;
  }

  .delete-col {
    background-color: rgb(243, 240, 240);
    height: 150px;
    display: flex;
    width: 100%;
    padding: 30px 5px 0px;
    flex-direction: column;
    justify-content: center;
  }

  .p-delete {
    text-align: center;
  }

  .btn-delete {
    width: 100px;
    margin: auto;
    background-color: #434343;
  }

  .btn-option {
    margin-top: 20px;
    display: flex;
    flex-direction: column-reverse;
  }

  .btn-cancel {
    height: 50px;
  }

  .btn-guardar {
    width: 100%;
    height: 50px;
  }

  h1 {
    font-size: 20px;
  }

  .col-time-container {
    margin: 0px auto 10px;
    width: 230px;
  }

  @media screen and (min-width: 450px) {
    .collapse {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      background-color: rgb(243, 240, 240);
      margin-bottom: 10px;
    }

    .col-time-container {
      width: 150px;
      margin-right: 10px;
      padding-top: 10px;
    }

    .btn-option {
      position: relative;
      display: flex;
      flex-direction: row;
      margin-top: 30px;
      bottom: 0%;
      padding: 10px;
    }

    .btn-cancel {
      width: 50%;
    }

    .btn-save {
      width: 50%;
    }

    .btn-guardar {
      width: 50%;
      height: 50px;
    }
  }
</style>
