<template>
  <div class="main-form">
    <!--CLIENT INFO PROPS-->
    <div class="info-form">
      <p class="mb-5">
        <strong>Nome:&nbsp;</strong><br />
        {{ props.client.client }}
      </p>
      <p class="mb-5">
        <strong>Endereço:&nbsp;</strong><br />
        {{ props.client.rua }}<br />
        {{ props.client.localidade }}, <br />{{ props.client.zipcode }} {{ props.client.pais }}
      </p>
      <p class="mb-5">
        <strong>Email:&nbsp;</strong><br />
        {{ props.client.email }}
      </p>
      <p class="mb-5"><strong>Data de Nascimento:&nbsp;</strong> {{ props.client.data_nasc }}</p>
      <p class="mb-5"><strong>Telefone:&nbsp;</strong> {{ props.client.telefone }}</p>
      <p class="mb-5"><strong>Whatsapp:&nbsp;</strong> {{ props.client.whatsapp }}</p>
    </div>
    <div class="btn-group-form">
      <va-button style="height: 50%" preset="plain" icon="edit" @click="showEdit = true"></va-button>
      <div style="border-bottom: 1px solid rgb(193, 193, 193)"></div>
      <va-button style="height: 50%" preset="plain" icon="delete" @click="showDelete = true"></va-button>
    </div>
  </div>

  <!--MODAL EDIT CLIENT-->
  <va-modal v-model="showEdit" hide-default-actions :mobile-fullscreen="false">
    <div class="modal-container-form">
      <h1>Dados do Cliente</h1>
      <va-input class="mb-3 mt-5 input" label="Nome" v-model="edited.nome"></va-input>
      <va-input class="mb-3 input" label="Email" v-model="edited.email"></va-input>
      <va-input class="mb-3 input" label="Telefone" v-model="edited.telefone"></va-input>
      <va-input class="mb-3 input" label="Whatsapp" v-model="edited.whatsapp"></va-input>
      <va-date-input
        class="mb-3 input"
        label="Data de Nascimento"
        v-model="edited.data_nasc"
        manual-input
        mode="single"
      ></va-date-input>
      <va-input class="mb-3 input" label="Rua" v-model="edited.rua"></va-input>
      <va-input class="mb-3 input" label="Código Postal" v-model="edited.zipcode"></va-input>
      <va-input class="mb-3 input" label="Localidade" v-model="edited.localidade"></va-input>
      <va-input class="mb-3 input" label="País" v-model="edited.pais"></va-input>
    </div>
    <div class="btn-form-option">
      <va-button preset="plain" class="btn-form-cancel" @click="showEdit = false"
        ><span style="font-size: 20px">Cancelar</span></va-button
      >
      <va-button class="btn-form-guardar" @click="updateClientInfo"
        ><span style="font-size: 20px">Guardar</span></va-button
      >
    </div>
  </va-modal>

  <!--MODAL DELETE CLIENT-->
  <va-modal v-model="showDelete" hide-default-actions :mobile-fullscreen="false">
    <div class="modal-container-form">
      <h1 class="mb-5">Eliminar Cliente</h1>
      <p class="mt-5">Tem certeza que deseja eliminar o cliente?</p>
    </div>
    <div class="btn-form-option">
      <va-button preset="plain" class="btn-form-cancel" @click="showDelete = false"
        ><span style="font-size: 20px">Cancelar</span></va-button
      >
      <va-button class="btn-form-guardar" @click="deleteClient"
        ><span style="font-size: 20px">Eliminar</span></va-button
      >
    </div>
  </va-modal>
</template>

<script setup lang="ts">
  import axios from 'axios'
  import { ref, onMounted, reactive } from 'vue'
  import { useToast } from 'vuestic-ui'

  const { init: initToast } = useToast()
  const showEdit = ref()
  const showDelete = ref()
  const props = defineProps(['client'])
  const emit = defineEmits(['update'])

  const edited = reactive({
    uniqueId: '',
    nome: '',
    email: '',
    telefone: '',
    whatsapp: '',
    data_nasc: new Date(),
    rua: '',
    zipcode: '',
    localidade: '',
    pais: '',
  })

  onMounted(() => {
    //Initiate value inside inputs
    edited.uniqueId = props.client.uniqueId
    if (props.client.client !== null) {
      edited.nome = props.client.client
    }
    if (props.client.email !== null) {
      edited.email = props.client.email
    }
    if (props.client.telefone !== null) {
      edited.telefone = props.client.telefone
    }
    if (props.client.whatsapp !== null) {
      edited.whatsapp = props.client.whatsapp
    }
    if (props.client.data_nasc !== null) {
      edited.data_nasc = new Date(props.client.data_nasc)
    }
    if (props.client.rua !== null) {
      edited.rua = props.client.rua
    }
    if (props.client.zipcode !== null) {
      edited.zipcode = props.client.zipcode
    }
    if (props.client.zipcode !== null) {
      edited.localidade = props.client.localidade
    }
    if (props.client.zipcode !== null) {
      edited.pais = props.client.pais
    }
  })

  //UPDATE CLIENT FULL INFO
  async function updateClientInfo() {
    let day = edited.data_nasc.getDate()
    let month = edited.data_nasc.getMonth() + 1
    let year = edited.data_nasc.getFullYear()
    let data = JSON.stringify({
      uniqueId: edited.uniqueId,
      nome: edited.nome,
      email: edited.email,
      telefone: edited.telefone,
      whatsapp: edited.whatsapp,
      data_nasc: year + '-' + month + '-' + day,
      rua: edited.rua,
      zipcode: edited.zipcode,
      localidade: edited.localidade,
      pais: edited.pais,
    })
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/clients/update_client.php',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        method: 'POST',
        maxBodyLength: Infinity,
        data: data,
      })
      .then((res) => {
        showEdit.value = false
        emit('update', true)
        if (res.data) {
          let msg = 'Cliente atualizado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Cliente não atualizado'
          let color = '#ff0000'
          notify(msg, color)
        }
      })
      .catch()
  }

  //DELETE CLIENT
  async function deleteClient() {
    let data = JSON.stringify({ uniqueId: props.client.uniqueId })
    await axios
      .request({
        url: 'https://rosemeri-beauty.vinim.eu/api/clients/delete_client.php',
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
          let msg = 'Cliente eliminado com sucesso'
          let color = '#008000'
          notify(msg, color)
        } else {
          let msg = 'Cliente não eliminado'
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
  }
</style>
