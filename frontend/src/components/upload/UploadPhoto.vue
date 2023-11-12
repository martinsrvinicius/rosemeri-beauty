<template>
  <va-file-upload
    file-types="jpg,png,heif,jpeg"
    v-model="photo"
    type="gallery"
    undo
    undo-button-text="cancelar"
    deleted-file-message="Arquivo apagado"
  >
    <div class="custom-upload-file-area">
      <p class="mb-2">Clique ou arraste e solte o arquivo para carregar</p>
    </div>
  </va-file-upload>

  <va-modal v-model="showModal" cancelText="" okText="Continuar" @ok="disabledBtnSave">
    <div class="modal">Foto adicionada ao curriculo com sucesso!</div>
  </va-modal>

  <va-button :disabled="isPhoto" @click="addNewItem">Salvar foto</va-button>
</template>

<script setup lang="ts">
  import axios from 'axios'
  import { ref, watch } from 'vue'
  import { API_PATH } from '../../stores/global-store'

  const showModal = ref(false)
  const isPhoto = ref(false)
  const photo = ref([])
  const emit = defineEmits(['update:photo'])

  watch(photo, () => {
    //console.log('watch photo', photo.value[0])
    isPhoto.value = !isPhoto.value
    emit('update:photo', photo.value)
  })

  function addNewItem() {
    var data = new FormData()
    var uniqueId = localStorage.getItem('uniqueId')
    data.append('foto', photo.value[0])
    data.append('uniqueId', String(uniqueId))
    new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/src/api/curriculo_api.php?action=savePhoto', data, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then((res) => {
        console.log('Success send Client', res)
        getPhoto()
      })
      .catch((error) => {
        console.log('Error')
        console.log(error)
      })
  }

  function disabledBtnSave() {
    photo.value = []
  }

  function getPhoto() {
    showModal.value = !showModal.value
  }
</script>

<style lang="scss" scoped>
  .custom-upload-file-area {
    width: 100%;
    padding: 1rem;
    border: 1px solid #cccccc;
    text-align: center;
  }

  #img-upload {
    width: 60%;
  }

  @media only screen and (max-width: 600px) {
    .modal {
      margin-top: 50vh;
      text-align: center;
    }
  }
</style>
