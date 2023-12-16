<template>
  <div class="main-form">
    <!--CLIENT INFO PROPS-->
    <div class="info-form">
      <p class="mb-5"><strong>Nome:&nbsp;</strong> {{ client.Nome }}</p>
      <p class="mb-5">
        <strong>Endereço:&nbsp;</strong> Rua José Ribeiro Vieira de Castro, 1023, Fafe, 4820-273 Portugal
      </p>
      <p class="mb-5"><strong>Email:&nbsp;</strong> martinsrvinicius@gmail.com</p>
      <p class="mb-5"><strong>Data de Nascimento:&nbsp;</strong> 12/05/1988</p>
      <p><strong>Telefone:&nbsp;</strong> +351 962 468 873</p>
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
      <va-input class="mb-3 mt-5 input" label="Nome"></va-input>
      <va-input class="mb-3 input" label="Email"></va-input>
      <va-input class="mb-3 input" label="Telefone"></va-input>
      <va-date-input class="mb-3 input" label="Data de Nascimento"></va-date-input>
      <va-input class="mb-3 input" label="Rua"></va-input>
      <va-input class="mb-3 input" label="Código Postal"></va-input>
      <va-select class="mb-3 input" label="País"></va-select>
    </div>
    <div class="btn-form-option">
      <va-button preset="plain" class="btn-form-cancel" @click="showEdit = false"
        ><span style="font-size: 20px">Cancelar</span></va-button
      >
      <va-button class="btn-form-guardar" @click="updateEvent" :disabled="invalidDate"
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
      <va-button class="btn-form-guardar" @click="updateEvent" :disabled="invalidDate"
        ><span style="font-size: 20px">Eliminar</span></va-button
      >
    </div>
  </va-modal>
</template>

<script setup lang="ts">
  import { ref, onMounted } from 'vue'
  const showEdit = ref()
  const showDelete = ref()
  const props = defineProps(['client'])

  onMounted(() => {
    //console.log(props.client.Id)
  })
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
    max-width: 300px;
  }

  .info-form > p {
    text-wrap: wrap;
    overflow-wrap: break-word;
    word-break: break-all;
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
      max-height: 620px;
    }

    .input {
      margin-top: 5px !important;
      margin-bottom: 5px !important;
    }

    .btn-form-option {
      margin-top: 10px;
    }

    .info-form {
      max-width: unset;
    }
  }
</style>
