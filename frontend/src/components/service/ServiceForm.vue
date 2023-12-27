<template>
  <div class="main-form">
    <!--SERVICE INFO PROPS-->
    <div class="info-form">
      <p class="mb-5">
        <strong>Foto:&nbsp;</strong><br />
        <img :class="[isMobile ? 'mobile' : 'wide']" :src="`${props.service.foto}`" />
      </p>
      <p class="mb-5">
        <strong>Título:&nbsp;</strong><br />
        {{ props.service.titulo }}
      </p>
      <p class="mb-5">
        <strong>Descrição:&nbsp;</strong><br />
        {{ props.service.descricao }}
      </p>
      <p class="mb-5">
        <strong>Duração:&nbsp;</strong><br />
        {{ props.service.duracao }} minutos
      </p>
      <p class="mb-2"><strong>Preço:&nbsp;</strong> € {{ props.service.preco }}</p>
    </div>
    <div class="btn-group-form">
      <va-button style="height: 50%" preset="plain" icon="edit" @click="showEdit = true"></va-button>
      <div style="border-bottom: 1px solid rgb(193, 193, 193)"></div>
      <va-button style="height: 50%" preset="plain" icon="delete" @click="showDelete = true"></va-button>
    </div>
  </div>

  <!--MODAL EDIT CLIENT-->
  <va-modal v-model="showEdit" hide-default-actions :mobile-fullscreen="false">
    <div class="modal-container-form edit-modal">
      <h1>Dados do Serviço</h1>
      <va-file-upload file-types="jpg,png,heif,jpeg" type="gallery" class="mb-3 mt-5 input" label="Foto" v-model="foto"
        ><div class="custom-upload-file-area">
          <va-button>Alterar foto</va-button>
        </div></va-file-upload
      >
      <va-input class="mb-3 mt-5 input" label="Título" v-model="edited.titulo"></va-input>
      <va-input class="mb-3 input" :max-length="100" counter label="Descrição" v-model="edited.descricao">
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
      <va-input type="number" class="mb-3 input" label="Duração" v-model="edited.duracao">
        <template #appendInner> minutos </template></va-input
      >
      <va-input class="mb-3 input" label="Preço" v-model="edited.preco">
        <template #prependInner> € </template>
      </va-input>
    </div>
    <div class="btn-form-option edit-modal-bottom">
      <va-button preset="plain" class="btn-form-cancel" @click="showEdit = false"
        ><span style="font-size: 20px">Cancelar</span></va-button
      >
      <va-button class="btn-form-guardar" @click="updateServiceInfo"
        ><span style="font-size: 20px">Guardar</span></va-button
      >
    </div>
  </va-modal>

  <!--MODAL DELETE CLIENT-->
  <va-modal v-model="showDelete" hide-default-actions :mobile-fullscreen="false">
    <div class="modal-container-form">
      <h1 class="mb-5">Eliminar Serviço</h1>
      <p class="mt-5">Tem certeza que deseja eliminar o serviço?</p>
    </div>
    <div class="btn-form-option">
      <va-button preset="plain" class="btn-form-cancel" @click="showDelete = false"
        ><span style="font-size: 20px">Cancelar</span></va-button
      >
      <va-button class="btn-form-guardar" @click="deleteService"
        ><span style="font-size: 20px">Eliminar</span></va-button
      >
    </div>
  </va-modal>
</template>

<script setup lang="ts">
  import axios from 'axios'
  import { ref, onMounted, reactive, watch } from 'vue'
  import { useToast } from 'vuestic-ui'

  const { init: initToast } = useToast()
  const showEdit = ref()
  const showDelete = ref()
  const foto = ref()
  const isMobile = ref()
  const props = defineProps(['service'])
  const emit = defineEmits(['update'])

  const edited = reactive({
    foto: '',
    uniqueId: '',
    titulo: '',
    descricao: '',
    preco: 0,
    duracao: 0,
  })

  onMounted(() => {
    //Get window size
    if (window.innerWidth <= 450) {
      isMobile.value = true
    }
    //Initiate value inside inputs
    edited.uniqueId = props.service.uniqueId
    if (props.service.titulo !== null) {
      edited.titulo = props.service.titulo
    }
    if (props.service.descricao !== null) {
      edited.descricao = props.service.descricao
    }
    if (props.service.preco !== null) {
      edited.preco = props.service.preco
    }
    if (props.service.duracao !== null) {
      edited.duracao = props.service.duracao
    }
  })

  watch(foto, () => {
    updatePhoto(foto.value)
  })

  //UPDATE SERVICE PHOTO
  async function updatePhoto(foto: any) {
    edited.foto = foto[0]
    let data = new FormData()
    data.append('uniqueId', String(edited.uniqueId))
    data.append('foto', edited.foto)
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/service/update_service_photo.php',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        method: 'POST',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        emit('update', true)
        if (res.data) {
          let msg = 'Foto atualizada com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Foto não atualizada'
          let color = '#ff0000'
          notify(msg, color)
        }
      })
      .catch()
  }

  //UPDATE SERVICE FULL INFO
  async function updateServiceInfo() {
    let data = new FormData()
    data.append('titulo', edited.titulo)
    data.append('descricao', edited.descricao)
    data.append('preco', String(edited.preco))
    data.append('duracao', String(edited.duracao))
    data.append('uniqueId', String(edited.uniqueId))
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/service/update_service.php',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        method: 'POST',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        showEdit.value = false
        emit('update', true)
        if (res.data) {
          let msg = 'Serviço atualizado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Serviço não atualizado'
          let color = '#ff0000'
          notify(msg, color)
        }
      })
      .catch()
  }

  //DELETE CLIENT
  async function deleteService() {
    let data = JSON.stringify({ uniqueId: props.service.uniqueId })
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/service/delete_service.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'POST',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        showDelete.value = false
        emit('update', true)
        if (res.data) {
          let msg = 'Serviço eliminado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Serviço não eliminado'
          let color = '#ff0000'
          notify(msg, color)
        }
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

<style>
  .main-form {
    border-top: 1px solid rgb(186, 186, 186);
    border-bottom: 1px solid rgb(186, 186, 186);
    display: flex;
    background-color: rgb(242, 241, 241);
    padding-left: 10px;
  }

  .info-form {
    padding-top: 20px;
    padding-bottom: 20px;
    width: 100%;
  }

  .info-form > p {
    text-wrap: wrap;
    overflow-wrap: break-word;
    word-break: break-all;
    overflow-wrap: break-word;
    word-break: break-word;
  }

  .btn-group-form {
    display: flex;
    flex-direction: column;
    width: 80px;
    background-color: rgb(255, 255, 255);
    box-shadow: -4px 4px 27px -12px rgba(0, 0, 0, 0.75);
    -webkit-box-shadow: -4px 4px 27px -12px rgba(0, 0, 0, 0.75);
    -moz-box-shadow: -4px 4px 27px -12px rgba(0, 0, 0, 0.75);
    justify-content: space-around;
  }

  body > div.va-modal > div.va-modal__container > div > div {
    min-width: 330px !important;
    overflow: hidden !important;
  }

  .form-input {
    margin-top: 10px !important;
  }

  .btn-form-option {
    margin-top: 20px;
    display: flex;
    flex-direction: column-reverse;
  }

  .edit-modal-bottom {
    margin-bottom: 60px;
  }

  .btn-form-cancel {
    height: 50px;
  }

  .btn-form-guardar {
    height: 50px;
  }

  .modal-container-form > h1 {
    text-align: center;
    font-size: 20px;
  }

  .edit-modal {
    margin-top: 60px;
  }

  .mobile {
    width: 70%;
  }

  .wide {
    height: 300px;
  }

  @media screen and (min-width: 450px) {
    .main-form {
      display: flex;
      justify-content: space-between;
    }

    body > div.va-modal > div.va-modal__container > div > div {
      width: 450px !important;
      overflow: hidden !important;
    }

    .input {
      margin-top: 5px !important;
      margin-bottom: 5px !important;
    }

    .btn-form-option {
      margin-top: 10px;
    }

    .info-form {
    }

    .edit-modal-bottom {
      margin-bottom: 0px;
    }

    .edit-modal {
      margin-top: 0px;
    }
  }
</style>
