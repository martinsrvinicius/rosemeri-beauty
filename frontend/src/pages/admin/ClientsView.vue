<template>
  <h1 class="clients">Clientes</h1>
  <div class="add-container">
    <va-button class="settings" preset="plain" @click="showAdd = !showAdd">
      <va-icon name="add" size="large"></va-icon>Novo
    </va-button>
  </div>

  <va-modal v-model="showAdd" hide-default-actions :mobile-fullscreen="false">
    <div class="modal-container-form add-modal">
      <h1>Novo Cliente</h1>
      <va-input class="mb-3 mt-5 input" label="Nome" v-model="newClient.nome"></va-input>
      <va-input class="mb-3 input" label="Email" v-model="newClient.email"></va-input>
      <va-input class="mb-3 input" label="Telefone" v-model="newClient.telefone"></va-input>
      <va-input class="mb-3 input" label="Whatsapp" v-model="newClient.whatsapp"></va-input
      ><va-date-input
        class="mb-3 input"
        label="Data de Nascimento"
        v-model="newClient.data_nasc"
        mode="single"
      ></va-date-input>
      <va-input class="mb-3 input" label="Rua" v-model="newClient.rua"></va-input>
      <va-input class="mb-3 input" label="Código Postal" v-model="newClient.zipcode"></va-input>
      <va-input class="mb-3 input" label="Localidade" v-model="newClient.localidade"></va-input>
      <va-input class="mb-3 input" label="País" v-model="newClient.pais"></va-input>
    </div>
    <div class="btn-form-option">
      <va-button preset="plain" class="btn-form-cancel" @click="showAdd = false"
        ><span style="font-size: 20px">Cancelar</span></va-button
      >
      <va-button class="btn-form-guardar" @click="addNew"><span style="font-size: 20px">Guardar</span></va-button>
    </div>
  </va-modal>

  <va-card class="main-table">
    <va-data-table
      :items="items"
      :columns="columns"
      sticky-header
      :wrapper-size="200"
      :bench="10"
      height="500px"
      :loading="loading"
    >
      <template #cell(Id)="{ rowIndex }">
        <div class="name-container">
          <span>{{ rowIndex + 1 }}</span>
        </div>
      </template>

      <template #cell(client)="{ rowIndex }">
        <div class="name-container">
          <span>{{ items[rowIndex].client }}</span>
        </div>
      </template>

      <template #cell()="{ row, isExpanded }">
        <va-button preset="plain" :icon="isExpanded ? 'va-arrow-up' : 'va-arrow-down'" @click="row.toggleRowDetails()">
          {{ isExpanded ? 'Menos' : 'Mais' }}
        </va-button>
      </template>
      <template #expandableRow="{ rowData }">
        <ClientForm :client="rowData" @update="getClientFullInfo" />
      </template>
    </va-data-table>
  </va-card>
</template>

<script setup lang="ts">
  import { ref, onMounted, reactive } from 'vue'
  import ClientForm from '../../components/form/ClientForm.vue'
  import axios from 'axios'
  import { useToast } from 'vuestic-ui'

  const { init: initToast } = useToast()
  const showAdd = ref()
  const loading = ref()
  const columns = [{ key: 'Id' }, { key: 'client', label: 'Nome' }, { key: 'telefone', label: 'Telefone' }, { key: '' }]

  interface IClient {
    nome: string
    email: string
    telefone: string
    whatsapp: string
    data_nasc: Date
    rua: string
    zipcode: string
    localidade: string
    pais: string
    nif: number
  }

  const items = ref<IClient | any>()

  const newClient = reactive({
    nome: '',
    email: '',
    telefone: '',
    whatsapp: '',
    data_nasc: new Date(),
    rua: '',
    zipcode: '',
    localidade: '',
    pais: '',
    nif: 0,
  } as IClient)

  onMounted(() => {
    loading.value = true
    getClientFullInfo()
  })

  //GET CLIENTS FULL INFO
  async function getClientFullInfo() {
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/clients/read_clients_full.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'GET',
        maxBodyLength: Infinity,
      })
      .then((res) => {
        // console.log("clients: ", res.data.clientsFull)
        items.value = []
        items.value = res.data.clientsFull
        loading.value = false
      })
      .catch()
  }

  //Create New Client
  async function addNew() {
    let day = newClient.data_nasc.getDate()
    let month = newClient.data_nasc.getMonth() + 1
    let year = newClient.data_nasc.getFullYear()
    let data = JSON.stringify({
      nome: newClient.nome,
      email: newClient.email,
      telefone: newClient.telefone,
      whatsapp: newClient.whatsapp,
      data_nasc: year + '-' + month + '-' + day,
      rua: newClient.rua,
      zipcode: newClient.zipcode,
      localidade: newClient.localidade,
      pais: newClient.pais,
    })
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/clients/create_client_full.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'POST',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        showAdd.value = false
        console.log('Criado: ', res.data[0])
        if (res.data) {
          let msg = 'Cliente criado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Não foi possível criar o Cliente'
          let color = '#ff0000'
          notify(msg, color)
        }
        getClientFullInfo()
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
  .main-table {
    padding-top: 5px;
  }

  .va-data-table__table-tr--expanded td {
    background: var(--va-background-border);
  }

  .va-data-table__table-expanded-content td {
    background-color: var(--va-background-element);
  }

  .clients {
    text-align: center;
    font-size: 20px;
  }

  .add-container {
    display: flex;
    justify-content: flex-end;
    position: relative;
    bottom: 10px;
    right: 10px;
  }

  .name-container {
    display: flex;
    flex-wrap: wrap;
    max-width: 120px;
    word-wrap: break-word;
    text-wrap: wrap;
    height: 30px;
    text-overflow: ellipsis;
    overflow: hidden;
    text-wrap: wrap;
  }
  .add-modal {
    margin-top: 60px;
  }

  .btn-form-option {
    margin-bottom: 60px;
  }
  @media screen and (min-width: 450px) {
    .name-container {
      display: flex;
      flex-wrap: wrap;
      max-width: unset;
      word-wrap: break-word;
      text-wrap: wrap;
      height: 30px;
      text-overflow: ellipsis;
      overflow: hidden;
      text-wrap: wrap;
    }

    .add-modal {
      margin-top: 0px;
    }
    .btn-form-option {
      margin-bottom: 0px;
    }
  }
</style>
