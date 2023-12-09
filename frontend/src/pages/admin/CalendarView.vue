<template>
  <div>
    <div class="btn-top">
      <va-button @click="addNew"> Novo Agendamento </va-button>
      <va-button class="settings" preset="plain" @click="showConfig = !showConfig">
        <va-icon name="settings" size="large"></va-icon>
      </va-button>
    </div>

    <!--MODAL NOVO AGENDAMENTO-->
    <va-modal
      :mobile-fullscreen="false"
      fullscreen
      v-model="showModalNew"
      cancelText="Cancelar"
      okText="Agendar"
      @ok="clickTestAddItem"
      class="myModal"
      size="small"
    >
      <template #content="{ ok, cancel }">
        <div class="new-container box">
          <div class="header-container">
            <h1>Novo Agendamento</h1>
          </div>

          <div class="date-time-container">
            <va-date-input class="date" label="Data" :format="formatFn" v-model="date" stickToEdges></va-date-input>
            <br />
            <va-time-input class="time" label="Hora" v-model="time"></va-time-input>
          </div>
          <br />

          <div class="modal-container">
            <va-select
              class="input-select"
              v-model="state.newItemClientName"
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
              v-model="state.newItemService"
              :options="optionsService"
              :track-by="(option: any) => option.id"
              placeholder="Selecione o serviço"
              allow-create="unique"
              multiple
              @create-new="addNewService"
              label="Serviços"
            ></va-select>
          </div>

          <div class="btn-group">
            <va-button class="btn-cancel" preset="plain" @click="cancel"
              ><span style="font-size: 20px">Cancelar</span></va-button
            >
            <va-button class="btn-save" @click="ok" :disabled="invalidDate"
              ><span style="font-size: 20px">Agendar</span></va-button
            >
          </div>
        </div>
      </template>
    </va-modal>
  </div>
  <!--FIM MODAL NOVO AGENDAMENTO-->

  <!--MODAL CLICK ITEM-->
  <va-modal
    :mobile-fullscreen="false"
    fullscreen
    v-model="showModal"
    okText="Confirmar"
    cancelText="Cancelar"
    @ok="confirm(state.id)"
  >
    <template #content="{ ok, cancel }">
      <div class="new-container box">
        <div class="header-container-check">
          <h1 class="title-modal">Agendamento</h1>
          <div class="img-container" @click="showEdit = true">
            <va-popover message="Editar">
              <img class="update" src="../../assets/update.svg" alt="" />
            </va-popover>
          </div>
        </div>

        <div class="service">
          <img class="img-service" src="../../assets/pestanas.jpg" alt="" />
          <div style="width: 100%">
            <div class="service-item-flex">
              <p class="item-service">Serviço:</p>
              <p class="item-service">{{ state.service }}</p>
            </div>

            <div class="service-item-flex">
              <p class="item-service">Preço:</p>
              <p class="item-service">€ {{ state.price }}</p>
            </div>
            <div class="service-item-flex">
              <p class="item-service">Cliente:</p>
              <p class="item-service">{{ state.client }}</p>
            </div>
            <div class="service-item-flex">
              <p class="item-service">Data:</p>
              <p class="item-service">{{ state.data }}</p>
            </div>
          </div>
        </div>

        <!--EDIT MODAL-->
        <va-modal v-model="showEdit" okText="Guardar" cancelText="Cancelar" @ok="updateEvent()" fullscreen>
          <template #content="{ ok, cancel }">
            <div class="new-container-edit">
              <div class="header-container-check" style="margin-bottom: 15px">
                <h1 class="title-modal">Opções de Edição</h1>
              </div>

              <div class="edit-modal">
                <va-collapse
                  class="min-w-96"
                  header="Editar Agendamento"
                  color="primary"
                  v-model="colDelete"
                  @click="colEdit = false"
                >
                  <div class="collapse">
                    <va-date-picker v-model="serviceDate" class="col-date" label="Editar Data"></va-date-picker>
                    <va-time-picker class="col-time" v-model="serviceTime"></va-time-picker>
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

                    <va-button color="#434343" class="btn-delete" @click="onDeleteItem(state.id)"
                      ><span style="font-size: 20px">Eliminar</span></va-button
                    >
                  </div>
                </va-collapse>
              </div>
              <div class="btn-option">
                <va-button preset="plain" class="btn-cancel" @click="cancel"
                  ><span style="font-size: 20px">Cancelar</span></va-button
                >
                <va-button class="btn-guardar" @click="ok" :disabled="invalidDate"
                  ><span style="font-size: 20px">Guardar</span></va-button
                >
              </div>
            </div>
          </template>
        </va-modal>

        <!--BOTÃO DE CONFIRMAÇÃO-->
        <div class="btn-group">
          <va-button class="btn-cancel" preset="plain" @click="cancel"
            ><span style="font-size: 20px">Cancelar</span></va-button
          >
          <va-button class="btn-save" @click="ok"><span style="font-size: 20px">Confirmar</span></va-button>
        </div>
      </div>
    </template>
  </va-modal>

  <!-- <va-modal :mobile-fullscreen="false" v-model="showModal" okText="Confirmar" cancelText="Cancelar"
    @ok="confirm(state.id)">
    <div style="font-size: 18px; width: 300px; border-radius: 10x; display: block" v-if="state.message"
      class="notification is-success">
      {{ state.message }}
    </div>
    <div style="font-size: 18px; padding: 0px" class="notification">
      <div v-if="state.data" class="modal_body">
        <span>
          <strong>Agendamento: &nbsp;</strong>
        </span>
        <span>
          {{ state.data }}
        </span>
      </div>
      <div v-if="state.data" class="modal_body">
        <span>
          <strong>Serviço: &nbsp;</strong>
        </span>
        <span> {{ state.client }}, {{ state.service }}, €{{ state.price }} </span>
        <va-button @click="onDeleteItem(state.id)">Apagar</va-button>
      </div>
    </div>
  </va-modal>-->
  <!--FIM MODAL CLICK ITEM-->

  <!--CALENDAR CONFIG-->
  <va-modal v-model="showConfig" okText="Encerrar" cancelText="" size="small" fullscreen>
    <template #content="{ ok }">
      <div class="calendar-controls">
        <div class="box">
          <h4 class="title is-5">Opções do Menu</h4>
          <div class="field2">
            <div class="control">
              <div class="">
                <va-select label="Período" v-model="value" :options="periodOpt"> </va-select>
              </div>
            </div>
          </div>

          <div class="field2">
            <div class="control">
              <div class="">
                <va-select :options="periodCountOpt" v-model="state.displayPeriodCount" label="Contagem do Período">
                </va-select>
              </div>
            </div>
          </div>

          <div class="field">
            <div class="control">
              <div class="">
                <va-select
                  v-model="state.startingDayOfWeek"
                  :options="dayNameOpt"
                  :value-by="(option: any) => option.id"
                  label="Dia inicial da semana"
                >
                </va-select>
              </div>
            </div>
          </div>

          <div class="field">
            <label class="checkbox">
              <input v-model="state.useTodayIcons" type="checkbox" />
              Use o ícone para o período de hoje
            </label>
          </div>

          <div class="field">
            <label class="checkbox">
              <input v-model="state.displayWeekNumbers" type="checkbox" />
              Mostrar número da semana
            </label>
          </div>

          <div class="field">
            <label class="checkbox">
              <input v-model="state.showTimes" type="checkbox" />
              Mostrar horários
            </label>
          </div>

          <div class="field">
            <label class="label">Temas</label>
            <label class="checkbox">
              <input v-model="state.useDefaultTheme" type="checkbox" />
              Padrão
            </label>
          </div>

          <div class="field">
            <label class="checkbox">
              <input v-model="state.useHolidayTheme" type="checkbox" />
              Feriados
            </label>
          </div>
          <va-button @click="ok" class="btn-config"><span style="font-size: 20px"> Concluir</span></va-button>
        </div>
      </div>
    </template>
  </va-modal>

  <!--CALENDAR FULL-->
  <div id="example-full">
    <!-- eslint-disable -->
    <div class="calendar-parent">
      <CalendarView
        :items="state.items"
        :show-date="state.showDate"
        :time-format-options="{ hour: '2-digit', minute: '2-digit' }"
        :enable-drag-drop="true"
        :disable-past="state.disablePast"
        :disable-future="state.disableFuture"
        :show-times="state.showTimes"
        :date-classes="myDateClasses()"
        :display-period-uom="state.displayPeriodUom"
        :display-period-count="state.displayPeriodCount"
        :starting-day-of-week="state.startingDayOfWeek"
        :class="themeClasses"
        :period-changed-callback="periodChanged"
        :current-period-label="state.useTodayIcons ? 'icons' : ''"
        :displayWeekNumbers="state.displayWeekNumbers"
        :enable-date-selection="true"
        :selection-start="state.selectionStart"
        :selection-end="state.selectionEnd"
        @date-selection-start="setSelection"
        @date-selection="setSelection"
        @date-selection-finish="finishSelection"
        @drop-on-date="onDrop"
        @click-date="onClickDay"
        @click-item="onClickItem"
      >
        <template #header="{ headerProps }">
          <CalendarViewHeader :header-props="headerProps" @input="setShowDate" />
        </template>
      </CalendarView>
    </div>
  </div>
</template>
<script setup lang="ts">
  // Using the publish version, you would do this instead:
  //import { CalendarView, CalendarViewHeader, CalendarMath } from "vue-simple-calendar"
  import { ref, watch } from 'vue'
  import CalendarView from '../../components/calendar/CalendarView.vue'
  import CalendarViewHeader from '../../components/calendar/CalendarViewHeader.vue'
  import CalendarMath from '../../components/calendar/CalendarMath'
  import { ICalendarItem, INormalizedCalendarItem } from '../../components/calendar/ICalendarItem'

  import { onMounted, reactive, computed } from 'vue'
  import axios from 'axios'
  import { API_PATH } from '../../stores/global-store'
  import { StringDecoder } from 'string_decoder'
  import emailjs from '@emailjs/browser'
  import { useToast } from 'vuestic-ui'

  const { init: initToast } = useToast()
  const colDelete = ref()
  const colEdit = ref()
  const showEdit = ref()
  const showConfig = ref()
  //Add new modal
  const clientName = ref('')
  const phone = ref()
  const service = ref()
  const serviceDate = ref(new Date())
  const serviceTime = ref(new Date())
  const invalidDate = ref(true)

  const value = ref('Mês')
  const month = ref()
  const week = ref()
  const year = ref()
  const showModal = ref()
  const showModalNew = ref()
  /* eslint-disable */
  const clients = ref([new Array()])
  const popupData = ref()
  const popupHora = ref()
  const date = ref(new Date())
  const d = ref()
  const time = ref(new Date())
  const noContribuinte = ref()
  //Email
  const messageClientName = ref()
  const from_email = ref()
  const send_to = ref()
  const subject = ref()
  const emailMessage = ref()
  const to_name = ref()
  const templateParams = ref()
  const clientEmail = ref(new Array())

  const periodOpt = ['Mês', 'Ano', 'Semana']

  const dayNameOpt = [
    {
      id: 0,
      text: 'Domingo',
    },
    {
      id: 1,
      text: 'Segunda-feira',
    },
    {
      id: 2,
      text: 'Terça-feira',
    },
    {
      id: 3,
      text: 'Quarta-feira',
    },
    {
      id: 4,
      text: 'Quinta-feira',
    },
    {
      id: 5,
      text: 'Sexta-feira',
    },
    {
      id: 6,
      text: 'Sábado',
    },
  ]

  const periodCountOpt = [1, 2, 3, 4, 5]

  const dayOfWeekOpt = ['']

  const optionsClient = ref([
    {
      id: 0,
      text: '',
    },
  ])

  const optionsService = ref([
    {
      id: 1,
      text: 'Micropigmentação',
    },
    {
      id: 2,
      text: 'Renna',
    },
    {
      id: 3,
      text: 'Pestanas',
    },
  ])

  watch(value, function () {
    filterStateDisplayUom(value.value)
  })
  //<<<<<<<<<Add new event
  function addNew() {
    showModalNew.value = !showModalNew.value
  }

  function formatFn() {
    d.value = date.value.toLocaleDateString('pt-PT', { year: 'numeric', month: '2-digit', day: '2-digit' })
    return `${date.value.getFullYear()}-${date.value.getMonth() + 1}-${date.value.getDate()}`
  }

  function confirm(uniqueId: any) {
    console.log('confirm', uniqueId)
    var data = new FormData()
    data.append('uniqueId', uniqueId)
    axios
      .post(API_PATH + '/src/api/calendar_api.php?action=confirm', data)
      .then((res) => {
        console.log('CONFIRM: ', res.data.confirm)
        sendEmail()
        getCalendar()
      })
      .catch((error) => {
        console.log('Erro: ', error)
      })
  }

  function clearEmailParams() {
    templateParams.value = {
      subject: '',
      message: '',
      send_to: '',
      to_name: '',
      from_name: '',
      from_email: '',
      reply_to: '',
    }
  }

  function fillEmailParams(
    title: string,
    matricula: string,
    marca: string,
    modelo: string,
    data: string,
    uniqueId: string,
  ) {
    var obj = {
      title: title,
      matricula: matricula,
      marca: marca,
      modelo: modelo,
      data: data,
      uniqueId: uniqueId,
    }
    //findClientByMatricula(obj)
    //console.log("OBJ: ", obj)
  }

  function sendEmail() {
    console.log('TEMPLATE PARAMS: ', templateParams.value)
    emailjs.send('service_uu92rmg', 'template_07yssqs', templateParams.value, 'NK14_RXyGbLohutQx').then(
      (response) => {
        console.log('SUCCESS!', response.status, response.text)
        alert('Mensagem de confirmação enviada ao cliente!')
        clearEmailParams()
      },
      (err) => {
        console.log('FAILED...', err)
      },
    )
  }

  function filterStateDisplayUom(value: any) {
    switch (value) {
      case 'Mês':
        state['displayPeriodUom'] = 'month'
        break
      case 'Semana':
        state['displayPeriodUom'] = 'week'
        break
      case 'Ano':
        state['displayPeriodUom'] = 'year'
        break
    }
  }

  async function getCalendar() {
    await axios
      // .get(API_PATH + '/src/api/calendar_api.php?action=getCalendar')
      .get('https://rosemeri-beauty.vinim.eu/api/calendar/read_calendar.php')
      .then((res) => {
        console.log('GET CALENDAR: ', res.data)
        fillCalendarItems(res.data.calendar)
      })
      .catch((error) => {
        console.log('Erro: ', error)
      })
  }

  function fillCalendarItems(value: any) {
    state.items = []

    let i = 0
    while (i < value.length) {
      // console.log('COR ', value[i]['confirm'])

      var dataHora = new Date(value[i]['dataHora'])
      var obj = {
        client: value[i]['client'],
        service: value[i]['service'],
        price: value[i]['price'],
        startDate: dataHora,
        endDate: dataHora,
        title: value[i]['client'],
        id: value[i]['uniqueId'],
        style: fillColorItems(value[i]['confirm']),
      }
      state.items = [...state.items, { ...obj }]
      i++
    }
    console.log('VALUE: ', value)
  }

  function fillColorItems(value: any) {
    switch (value) {
      case 1:
        //console.log('COR VERDE')
        return 'background-color: #23d160'
        break
      case 0:
        //console.log('COR AMARELA')
        return 'background-color: #ffff66 '
        break
      default:
        return
        break
    }
  }

  function findClienteByNumber(noContribuinte: any) {
    console.log('CLIENTE: ')
    var obj = {
      noContribuinte: 0,
      email: '',
      uniqueId: '',
    }

    for (let i = 0; i < clients.value[0].length; i++) {
      var num = clients.value[0][i]['noContribuinte']

      if (noContribuinte == num) {
        obj.noContribuinte = noContribuinte
        obj.email = clients.value[0][i]['email']
        obj.uniqueId = clients.value[0][i]['uniqueId']
        clientEmail.value.push(obj)
        console.log('CLIENT EMAIL: ', clientEmail.value)
        return clients.value[0][i]['nome']
      }
    }
  }

  const thisMonth = (d: number, h?: number, m?: number): Date => {
    const t = new Date()
    return new Date(t.getFullYear(), t.getMonth(), d, h || 0, m || 0)
  }

  interface IExampleState {
    showDate: Date
    id: string
    data: string
    client: string
    service: string
    price: string
    marca: string
    modelo: string
    matricula: string
    hora: string
    message: string
    startingDayOfWeek: number
    disablePast: boolean
    disableFuture: boolean
    displayPeriodUom: string
    displayPeriodCount: number
    displayWeekNumbers: boolean
    showTimes: boolean
    selectionStart?: Date
    selectionEnd?: Date
    newItemTitle: string
    newItemStartDate: string
    newItemEndDate: string
    newItemMatricula: string
    newItemClientName: string
    newItemService: string
    newItemPhone: string
    newItemMarca: string
    newItemModelo: string
    useDefaultTheme: boolean
    useHolidayTheme: boolean
    useTodayIcons: boolean
    items: ICalendarItem[]
  }

  const state = reactive({
    showDate: thisMonth(1),
    id: '',
    data: '',
    hora: '',
    client: '',
    service: '',
    price: '',
    marca: '',
    modelo: '',
    matricula: '',
    message: '',
    startingDayOfWeek: 0,
    disablePast: false,
    disableFuture: false,
    displayPeriodUom: 'month',
    displayPeriodCount: 1,
    displayWeekNumbers: false,
    showTimes: true,
    selectionStart: undefined,
    selectionEnd: undefined,
    newItemTitle: '',
    newItemStartDate: '',
    newItemEndDate: '',
    newItemMatricula: '',
    newItemMarca: '',
    newItemClientName: '',
    newItemService: '',
    newItemPhone: '',
    newItemModelo: '',
    useDefaultTheme: true,
    useHolidayTheme: true,
    useTodayIcons: false,
    items: [
      /*{
id: "e0",
startDate: "2018-01-05",
},
*/
    ],
  } as IExampleState)

  const userLocale = computed((): string => CalendarMath.getDefaultBrowserLocale())

  const dayNames = computed((): string[] => CalendarMath.getFormattedWeekdayNames(userLocale.value, 'long', 0))

  const themeClasses = computed(() => ({
    'theme-default': state.useDefaultTheme,
    'holiday-us-traditional': state.useHolidayTheme,
    'holiday-us-official': state.useHolidayTheme,
  }))

  const myDateClasses = (): Record<string, string[]> => {
    // This was added to demonstrate the dateClasses prop. Note in particular that the
    // keys of the object are `yyyy-mm-dd` ISO date strings (not dates), and the values
    // for those keys are strings or string arrays. Keep in mind that your CSS to style these
    // may need to be fairly specific to make it override your theme's styles. See the
    // CSS at the bottom of this component to see how these are styled.
    const o = {} as Record<string, string[]>
    const theFirst = thisMonth(1)
    const ides = [2, 4, 6, 9].includes(theFirst.getMonth()) ? 15 : 13
    const idesDate = thisMonth(ides)
    o[CalendarMath.isoYearMonthDay(idesDate)] = ['ides']
    o[CalendarMath.isoYearMonthDay(thisMonth(21))] = ['do-you-remember', 'the-21st']
    return o
  }

  onMounted((): void => {
    getClients()
    getServices()
    getCalendar()

    state.newItemStartDate = CalendarMath.isoYearMonthDay(CalendarMath.today())
    state.newItemEndDate = CalendarMath.isoYearMonthDay(CalendarMath.today())
  })

  const periodChanged = (): void => {
    // range, eventSource) {
    // Demo does nothing with this information, just including the method to demonstrate how
    // you can listen for changes to the displayed range and react to them (by loading items, etc.)
    //console.log(eventSource)
    //console.log(range)
    console.log('period: mudou')
  }

  const periodChanged2 = (range: any, eventSource: any) => {
    console.log('period: mudou')
  }

  const onClickDay = (d: Date): void => {
    state.selectionStart = undefined
    state.selectionEnd = undefined
    state.message = `Você selecionou: ${d.toLocaleDateString()}`
    //showModal.value = true
  }

  const onClickItem = (e: INormalizedCalendarItem): void => {
    //getClientById( `${e.id}`,`${e.originalItem['idCliente']}`)
    //console.log('E: ', e)

    state.message = `Cliente: ${e.title}`
    state.data = setPopupDateHour(`${e.startDate}`)
    state.hora = setPopupDateHour(`${e.startDate}`)
    state.client = `${e.originalItem['client']}`
    state.service = `${e.originalItem['service']}`
    state.price = `${e.originalItem['price']}`
    state.id = `${e.id}`
    showModal.value = true
    serviceDate.value = new Date(`${e.startDate}`)
    serviceTime.value = new Date(`${e.startDate}`)
    //console.log('E: ', serviceDate.value)
    //fillEmailParams(`${e.title}`, state.matricula, state.marca, state.modelo, state.data, state.id)
  }

  //DELETE EVENTS ON CALENDAR
  async function onDeleteItem(id: any) {
    let data = JSON.stringify({
      uniqueId: id,
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
        console.log('DELETE: ', res.data)
        showModal.value = false
        getCalendar()
      })
      .catch()
  }

  function getClientById(uniqueId: string, uniqueIdClient: string) {
    var data = new FormData()
    data.append('uniqueId', uniqueId)
    data.append('uniqueIdClient', uniqueIdClient)
    new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/src/api/calendar_api.php?action=getClientById', data)
      .then((res) => {
        console.log('GET CLIENTE: ', res.data)
        return res.data
      })
      .catch()
    return
  }

  function setPopupDateHour(value: string) {
    date.value = new Date(value)
    d.value = date.value.toLocaleDateString('pt-PT', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
    })
    return d.value
  }

  const setShowDate = (d: Date): void => {
    state.message = `Alterando a exibição do calendário para ${d.toLocaleDateString()}`
    state.showDate = d
  }

  const setSelection = (dateRange: Date[]): void => {
    state.selectionEnd = dateRange[1]
    state.selectionStart = dateRange[0]
  }

  const finishSelection = (dateRange: Date[]): void => {
    setSelection(dateRange)
    state.message = `You selected: ${state.selectionStart?.toLocaleDateString() ?? 'n/a'} - ${
      state.selectionEnd?.toLocaleDateString() ?? 'n/a'
    }`
  }

  const clickTestAddItem = (): void => {
    var t = time.value.getHours() + ':' + time.value.getMinutes()
    state.newItemEndDate = formatFn() + ' ' + t
    var date = new Date(state.newItemEndDate)
    /*state.items.push({
matricula: state.newItemMatricula,
modelo: state.newItemModelo,
marca: state.newItemMarca,
//startDate: CalendarMath.fromIsoStringToLocalDate(state.newItemStartDate),
//endDate: CalendarMath.fromIsoStringToLocalDate(state.newItemEndDate),
startDate: date,
endDate: date,
title: state.newItemTitle,
id: '',
//id: 'e' + Math.random().toString(36).substring(2, 11),
})
state.items = [...state.items, { ...obj }]*/
    var obj = {
      clientName: state.newItemClientName,
      phone: state.newItemPhone,
      service: state.newItemService,
      marca: state.newItemMarca,
      startDate: date,
      endDate: date,
      title: state.newItemTitle,
      id: '',
      //id: 'e' + Math.random().toString(36).substring(2, 11),
    }
    console.log('OBJ NEW: ', obj)
    addManualItem(obj)
    state.message = 'Você adicionou um item no calendário!'
  }

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
          })
        }
        //getCalendar()
      })
      .catch((error) => {
        console.log('Erro: ', error)
      })
  }

  //INSERT NEW SERVICE INTO DATABASE
  function addNewService(newOption: any) {
    console.log('SERVICE: ', newOption)

    const option = {
      id: optionsService.value.length,
      text: String(newOption),
      //value: newOption,
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
  //UPDATE EVENT INFO
  async function updateEvent() {
    var d = serviceDate.value
    var t = serviceTime.value
    var info = `${d.getFullYear()}-${
      d.getMonth() + 1
    }-${d.getDate()} ${t.getHours()}:${t.getMinutes()}:${t.getSeconds()}`
    // console.log("service info: ", info, state.id)

    let data = JSON.stringify({
      uniqueId: state.id,
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
        showModal.value = false
        showEdit.value = false
        getCalendar()
      })
      .catch()
  }

  //UPDATE EVENT DATE ON DROP
  const onDrop = (item: INormalizedCalendarItem, date: Date): void => {
    state.message = `Você alterou '${item.title}' para ${date.toLocaleDateString()}`
    // Determine the delta between the old start date and the date chosen,
    // and apply that delta to both the start and end date to move the item.
    const eLength = CalendarMath.dayDiff(item.startDate, date)
    item.originalItem.startDate = CalendarMath.addDays(item.startDate, eLength)
    item.originalItem.endDate = CalendarMath.addDays(item.endDate, eLength)
    item.originalItem.style = 'background-color: #ffff66 '
    if (item.originalItem.startDate < new Date()) {
      let msg = 'Agendamento com data inválida.'
      let color = '#ff0000'
      notify(msg, color)
      getCalendar()
    } else {
      let msg = 'Agendamento alterado com sucesso'
      let color = '#008000'
      notify(msg, color)
      updateDroppedItem(item.originalItem.id, item.originalItem.startDate)
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
        //console.log('UPDATE: ', res.data)
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

  //CREATE NEW EVENT ON CALENDAR
  async function addManualItem(obj: any) {
    var d = new Date(obj['startDate'])
    var t = `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()} ${d.getHours()}:${d.getMinutes()}:${d.getSeconds()}`

    let serviceList = []
    for (let i = 0; i < obj.service.length; i++) {
      serviceList.push(obj.service[i].id)
    }

    //console.log("ADD NEW EVENT: ", serviceList)

    let data = JSON.stringify({
      confirm: '0',
      uniqueId: '0',
      uniqueIdCliente: obj.clientName.id,
      uniqueIdServico: serviceList,
      dataHora: t,
    })

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
        // console.log("create: ", res.data)
        if (res.data.create[0]) {
          let msg = 'Agendamento realizado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Agendamento não realizado'
          let color = '#ff0000'
          notify(msg, color)
        }
        getCalendar()
      })
      .catch((error) => {
        console.log(error)
      })
  }
  //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

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
  @import 'https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css';
  /* For apps using the npm package, the below URLs should reference /node_modules/vue-simple-calendar/dist/css/ instead */
  @import '/node_modules/vue-simple-calendar/dist/css/gcal.css';
  @import '/node_modules/vue-simple-calendar/dist/css/holidays-us.css';
  @import '/node_modules/vue-simple-calendar/dist/css/holidays-ue.css';

  #example-full {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    font-family: Calibri, sans-serif;
    max-width: 80vw;
    min-width: 5rem;
    max-width: 100rem;
    min-height: 30rem;
    margin-left: auto;
    margin-right: auto;
    overflow-y: auto;
  }

  #example-full .calendar-controls {
    margin-right: 1rem;
    min-width: 2rem;
    max-width: 14rem;
  }

  div.va-modal__inner {
    min-width: 300px !important;
  }

  /**CONFIG MODAL */
  .calendar-controls {
    width: 96%;
    margin: auto;
    position: fixed;
    bottom: 5%;
    right: 2%;
  }

  .btn-config {
    margin-bottom: 40px;
    margin-top: 30px;
    width: 100%;
    height: 50px;
  }

  /**^^^^^^ */

  #example-full .calendar-parent {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    overflow-x: hidden;
    overflow-y: hidden;
    max-height: 80vh;
    background-color: white;
  }

  /* For long calendars, ensure each week gets sufficient height. The body of the calendar will scroll if needed */
  #example-full .cv-wrapper.period-month.periodCount-2 .cv-week,
  #example-full .cv-wrapper.period-month.periodCount-3 .cv-week,
  #example-full .cv-wrapper.period-year .cv-week {
    min-height: 6rem;
  }

  /* These styles are optional, to illustrate the flexbility of styling the calendar purely with CSS. */

  /* Add some styling for items tagged with the "birthday" class */
  #example-full .theme-default .cv-item.birthday {
    background-color: #e0f0e0;
    border-color: #d7e7d7;
  }

  #example-full .theme-default .cv-item.birthday::before {
    content: '\1F382';
    /* Birthday cake */
    margin-right: 0.5em;
  }

  /* The following classes style the classes computed in myDateClasses and passed to the component's dateClasses prop. */
  #example-full .theme-default .cv-day.ides {
    background-color: #ffe0e0;
  }

  #example-full .ides .cv-day-number::before {
    content: '\271D';
  }

  #example-full .cv-day.do-you-remember.the-21st .cv-day-number::after {
    content: '\1F30D\1F32C\1F525';
  }

  .modal_body {
    display: flex;
    flex-direction: row;
  }

  .purple {
    background-color: purple;
  }

  .text-input {
    width: 200px;
    margin-bottom: 5px;
  }

  .input-select {
    padding: 0;
    width: 200px;
    margin: 5px 0px;
  }

  .modal-container {
    height: 150px;
  }

  .date {
    height: 30px;
    width: 50px;
    margin-bottom: 30px;
  }

  .date-time-container {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    margin: 10px 0px 5px;
    width: 100%;
    height: fit-content;
  }

  .header-container {
    display: flex;
    width: 100%;
    height: 50px;
    background-color: rgb(107, 107, 106);
  }

  .new-container {
    height: 90%;
    position: fixed;
    background-color: white;
    bottom: 0%;
    width: 96%;
    right: 2%;
    overflow: auto;
  }

  .new-container-edit {
    height: 90%;
    position: fixed;
    background-color: white;
    bottom: 0%;
    width: 96%;
    right: 2%;
    overflow: auto;
  }

  h1 {
    font-size: 1.5rem;
    color: white;
    margin: auto;
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

  .btn-top {
    display: flex;
    justify-content: space-between;
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

  .update {
    position: relative;
    right: 20px;
    width: 30px;
    color: white;
  }

  .img-service {
    width: 100%;
    height: 150px;
    object-fit: cover;
  }

  .service-item-flex {
    display: flex;
    justify-content: space-between;
    background-color: rgb(239, 239, 239);
    margin: 4px 0px 0px 0px;
    padding-left: 3px;
    width: 100%;
    height: 40px;
    padding: 0px 15px;
  }

  .item-service {
    font-size: 1.5rem;
  }

  .edit-modal {
    width: 100%;
    font-size: 20px;
    height: 500px;
  }

  .collapse {
    display: flex;
    flex-wrap: wrap;
    justify-content: start;
    height: 230px;
    background-color: rgb(243, 240, 240);
    margin-bottom: 10px;
  }

  .col-date {
    margin-left: 5px;
    background-color: white;
    box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
    -webkit-box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
    -moz-box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
  }

  .col-time {
    margin-left: 10px;
    height: 100%;
    background-color: white;
    box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
    -webkit-box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
    -moz-box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
  }

  .delete-col {
    padding-top: 30px;
    background-color: rgb(243, 240, 240);
    height: 150px;
    display: flex;
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
    position: relative;
    display: flex;
    flex-direction: column-reverse;
    bottom: 20%;
  }

  .btn-guardar {
    width: 100%;
    height: 50px;
  }

  .btn-cancel {
    width: 100%;
  }

  /*****/
  @media screen and (min-width: 500px) {
    .header-container {
      display: flex;
      width: 100%;
      height: 50px;
      background-color: rgb(107, 107, 106);
    }

    .new-container {
      height: 550px;
      position: fixed;
      background-color: white;
      bottom: 10%;
      max-width: 50%;
      right: 25%;
      overflow: hidden;
      -webkit-box-shadow: -1px 0px 28px -18px rgba(0, 0, 0, 0.75);
      -moz-box-shadow: -1px 0px 28px -18px rgba(0, 0, 0, 0.75);
      box-shadow: -1px 0px 28px -18px rgba(0, 0, 0, 0.75);
    }

    .new-container-edit {
      height: fit-content;
      position: fixed;
      background-color: white;
      bottom: 10%;
      width: 50%;
      right: 25%;
      overflow: auto;
      padding: 10px;
      border-radius: 5px;
      -webkit-box-shadow: -1px 0px 28px -18px rgba(0, 0, 0, 0.75);
      -moz-box-shadow: -1px 0px 28px -18px rgba(0, 0, 0, 0.75);
      box-shadow: -1px 0px 28px -18px rgba(0, 0, 0, 0.75);
    }

    .btn-option {
      position: relative;
      display: flex;
      flex-direction: row;
      margin-top: 30px;
      bottom: 0%;
      padding: 10px;
    }

    .calendar-controls {
      width: 50%;
      margin: auto;
      position: fixed;
      bottom: 80%;
      right: 25%;
      height: 100px;
    }

    .btn-config {
      margin-bottom: 10px;
      margin-top: 20px;
      width: 100%;
      height: 50px;
    }

    h1 {
      font-size: 1.5rem;
      color: white;
      margin: auto;
    }

    .header-container {
      display: flex;
      height: 50px;
      background-color: rgb(107, 107, 106);
    }

    .input-container {
      height: 100px;
    }

    .select {
      width: 100%;
    }

    .date-time-container {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      margin: 10px 0px 5px;
    }

    .date {
      width: 48%;
    }

    .time {
      width: 48%;
    }

    .modal-container {
      height: 150px;
    }

    .btn-group {
      display: flex;
      flex-direction: row;
      margin-top: 50px;
    }

    .btn-cancel {
      width: 50%;
    }

    .btn-save {
      width: 50%;
    }

    .myModal {
      --va-modal-width: 100px;
      width: 400px;
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

    .title-modal {
      width: 100%;
      padding-left: 10px;
    }

    .img-container {
      display: flex;
      justify-content: center;
      width: 50px;
    }

    .update {
      margin: auto;
      width: 30px;
      color: white;
    }

    .update:hover {
      cursor: pointer;
    }

    .service {
      display: block;
      margin-bottom: 20px;
      min-width: 300px;
    }

    .item-service {
      font-size: 1.5rem;
    }

    .img-service {
      width: 100%;
      height: 150px;
      object-fit: cover;
    }

    .service-item-flex {
      display: flex;
      justify-content: space-between;
      background-color: rgb(239, 239, 239);
      margin: 4px 0px 0px 0px;
      padding-left: 3px;
      width: 100%;
      height: 40px;
      padding: 0px 15px;
    }

    .btn-top {
      display: flex;
      justify-content: space-between;
    }

    .settings {
      margin-bottom: 10px;
    }

    .edit-modal {
      padding: 10px;
      width: 100%;
      height: 370px;
      overflow: hidden;
    }

    .collapse {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      height: 230px;
      background-color: rgb(243, 240, 240);
      margin-bottom: 10px;
    }

    .col-date {
      margin-left: 50px;
      background-color: white;
      box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
      -webkit-box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
      -moz-box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
    }

    .col-time {
      margin-right: 50px;
      height: 100%;
      background-color: white;
      box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
      -webkit-box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
      -moz-box-shadow: -1px 1px 20px -2px rgba(0, 0, 0, 0.75);
    }

    .delete-col {
      padding-top: 30px;
      background-color: rgb(243, 240, 240);
      height: 150px;
      display: flex;
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

    .btn-guardar {
      width: 50%;
      height: 50px;
    }

    .btn-cancel {
      width: 50%;
    }
  }
</style>
