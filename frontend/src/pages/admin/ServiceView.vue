<template>
  <h1 class="clients">Serviços</h1>
  <div class="add-container">
    <va-button class="settings" preset="plain" @click="showAdd = !showAdd">
      <va-icon name="add" size="large"></va-icon>Novo
    </va-button>
  </div>

  <va-modal v-model="showAdd" hide-default-actions :mobile-fullscreen="false">
    <div class="modal-container-form add-modal">
      <h1>Novo Serviço</h1>
      <va-file-upload
        file-types="jpg,png,heif,jpeg"
        type="gallery"
        class="mb-3 mt-5 input"
        label="Foto"
        v-model="foto"
      ></va-file-upload>
      <va-input class="mb-3 mt-5 input" label="Título" v-model="newService.titulo"></va-input>
      <va-input class="mb-3 input" label="Descrição" :max-length="100" counter v-model="newService.descricao">
        <template #counter="{ valueLength, maxLength }">
          <b
            class="ml-auto"
            :style="{
              color: valueLength > maxLength ? 'var(--va-danger)' : 'var(--va-success)',
            }"
          >
            {{ maxLength - valueLength }}
          </b>
        </template>
      </va-input>
      <va-input type="number" class="mb-3 input" label="Preço" v-model="newService.preco">
        <template #prependInner> € </template>
      </va-input>
      <va-input type="number" class="mb-3 input" label="Duração em Minutos" v-model="newService.duracao">
        <template #appendInner> minutos </template>
      </va-input>
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

      <template #cell(titulo)="{ rowIndex }">
        <div class="name-container">
          <span>{{ items[rowIndex].titulo }}</span>
        </div>
      </template>

      <template #cell()="{ row, isExpanded }">
        <va-button preset="plain" :icon="isExpanded ? 'va-arrow-up' : 'va-arrow-down'" @click="row.toggleRowDetails()">
          {{ isExpanded ? 'Menos' : 'Mais' }}
        </va-button>
      </template>
      <template #expandableRow="{ rowData }">
        <ServiceForm :service="rowData" @update="getServiceFull" />
      </template>
    </va-data-table>
  </va-card>
</template>

<script setup lang="ts">
  import { ref, onMounted, reactive } from 'vue'
  import ServiceForm from '../../components/service/ServiceForm.vue'
  import axios from 'axios'
  import { useToast } from 'vuestic-ui'
  import { Buffer } from 'buffer'

  const { init: initToast } = useToast()
  const showAdd = ref()
  const loading = ref()
  const foto = ref([])
  const columns = [{ key: 'Id' }, { key: 'titulo', label: 'Titulo' }, { key: 'preco', label: 'preco' }, { key: '' }]

  interface IService {
    foto: any
    titulo: string
    descricao: string
    preco: number
    duracao: number
  }

  const items = ref<IService | any>()

  const newService = reactive({
    foto: '',
    titulo: '',
    descricao: '',
    preco: 0,
    duracao: 0,
  } as IService)

  onMounted(() => {
    loading.value = true
    getServiceFull()
  })

  //GET CLIENTS FULL INFO
  async function getServiceFull() {
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
        //console.log('Serviços: ', res.data.service)
        items.value = []
        items.value = res.data.service

        for (let i = 0; i < res.data.service.length; i++) {
          if (res.data.service[i].foto) {
            //If image blob on database
            // items.value[i].foto = `data:image/*;base64,` + res.data.service[i].foto

            //Convert base64 image path
            var b = Buffer.from(res.data.service[i].foto, 'base64')
            items.value[i].foto = 'https://rosemeri-beauty.vinim.eu/api/service/' + b.toString()
          }
        }
        loading.value = false
      })
      .catch()
  }

  //Create New Service FormData
  async function addNew() {
    newService.foto = foto.value[0]
    let data = new FormData()
    data.append('foto', foto.value[0])
    data.append('titulo', newService.titulo)
    data.append('descricao', newService.descricao)
    data.append('preco', String(newService.preco))
    data.append('duracao', String(newService.duracao))
    // new Response(data).text().then(console.log);
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/service/create_service_full.php',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        method: 'POST',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        showAdd.value = false
        // console.log('Criado: ', res.data)
        if (res.data) {
          let msg = 'Serviço criado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Não foi possível criar o Serviço'
          let color = '#ff0000'
          notify(msg, color)
        }
        getServiceFull()
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
