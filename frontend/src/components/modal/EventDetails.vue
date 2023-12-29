<template>
  <div class="header-container-check">
    <h1 v-html="item.title" style="text-align: center"></h1>
    <div class="btn-edit-container">
      <va-button preset="plain" @click="showEdit = !showEdit"
        ><span>Editar</span> <img class="update" src="../../assets/update.svg" alt=""
      /></va-button>
    </div>
  </div>
  <div class="service">
    <!--<img class="img-service" src="../../assets/pestanas.jpg" alt="" />-->
    <img v-if="foto" class="img-service" :src="`${foto}`" alt="foto" />
    <img
      v-else
      class="img-service"
      style="object-fit: contain"
      src="https://rosemeri-beauty.vinim.eu/assets/background-service.png"
      alt="foto"
    />
    <div style="width: 100%">
      <div class="service-item-flex">
        <p class="item-service-title">Serviço:</p>
        <p class="item-service">{{ item.contentFull.service }}</p>
      </div>

      <div class="service-item-flex">
        <p class="item-service-title">Preço:</p>
        <p class="item-service">€ {{ item.contentFull.price }}</p>
      </div>
      <div class="service-item-flex">
        <p class="item-service-title">Cliente:</p>
        <p class="item-service">{{ item.contentFull.client }}</p>
      </div>
      <div class="service-item-flex">
        <p class="item-service-title">Data:</p>
        <p class="item-service">{{ formatFnDisplay(item.contentFull.dataHora) }}</p>
      </div>
    </div>
  </div>

  <div class="btn-group">
    <va-button class="btn-cancel" preset="plain" @click="emit('cancel', true)"
      ><span style="font-size: 20px">Cancelar</span></va-button
    >
    <va-button class="btn-save" @click="handleConfirm"><span style="font-size: 20px">Confirmar</span></va-button>
  </div>

  <va-modal v-model="showEdit" hide-default-actions :mobile-fullscreen="false">
    <template #content>
      <EditEvent :item="props.item" @cancel="showEdit = false" @confirm="handleConfirm" />
    </template>
  </va-modal>
</template>

<script setup lang="ts">
  import { formatFnDisplay } from '../../utils/utils.js'
  import EditEvent from './EditEvent.vue'
  import { ref, onMounted } from 'vue'
  import { Buffer } from 'buffer'

  const props = defineProps(['item'])
  const emit = defineEmits(['cancel', 'confirm'])
  const showEdit = ref()
  const foto = ref()

  onMounted(() => {
    if (props.item.contentFull.foto) {
      let b = Buffer.from(props.item.contentFull.foto, 'base64')
      foto.value = 'https://rosemeri-beauty.vinim.eu/api/service/' + b.toString()
    }
  })

  function handleConfirm() {
    showEdit.value = false
    emit('confirm', true)
  }
</script>

<style scoped>
  .btn-edit-container {
    display: flex;
    justify-content: end;
    align-items: center;
  }

  .img-service {
    width: 100%;
    object-fit: cover;
  }

  .service-item-flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: rgb(239, 239, 239);
    margin: 4px 0px 0px 0px;
    padding-left: 3px;
    width: 100%;
    height: 40px;
    padding: 0px 5px;
    text-wrap: nowrap;
  }

  .item-service {
    font-size: 1.2rem;
    text-overflow: ellipsis;
    overflow: hidden;
    padding-left: 5px;
  }

  .item-service-title {
    font-size: 1.2rem;
    text-overflow: ellipsis;
    overflow: hidden;
    min-width: 25%;
  }

  h1 {
    font-size: 20px;
  }

  .btn-group {
    display: flex;
    margin-top: 10px;
    flex-direction: column-reverse;
  }

  .btn-cancel {
    height: 50px;
  }

  .btn-save {
    height: 50px;
  }

  @media screen and (min-width: 450px) {
    .img-service {
      width: 450px;
      height: 250px;
      object-fit: cover;
    }

    .service {
      width: 450px;
    }

    .header-container-check {
      width: 450px;
    }

    .btn-group {
      display: flex;
      flex-direction: row;
      margin-top: 20px;
    }

    .btn-cancel {
      height: 50px;
      width: 50%;
    }

    .btn-save {
      width: 50%;
      height: 50px;
    }
  }
</style>
