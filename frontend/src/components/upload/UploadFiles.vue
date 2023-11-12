<template>
  <div class="container">
    <va-input label="Passaporte">
      <va-file-upload
        v-model="passport"
        type="list"
        undo
        undo-duration="5000"
        undo-button-text="cancelar"
        deleted-file-message="Arquivo apagado"
      >
        <div class="custom-upload-file-area">
          <img id="file-upload" src="./../../assets/upload.jpg" alt="upload" />
        </div>
      </va-file-upload>
    </va-input>
  </div>
  <va-button v-if="passport.length" class="btn-save" @click="savePassport">Salvar</va-button>

  <div class="container">
    <va-input label="Título de Residencia">
      <va-file-upload
        v-model="residence"
        type="list"
        undo
        undo-duration="5000"
        undo-button-text="cancelar"
        deleted-file-message="Arquivo apagado"
      >
        <div class="custom-upload-file-area">
          <img id="file-upload" src="./../../assets/upload.jpg" alt="upload" />
        </div>
      </va-file-upload>
    </va-input>
  </div>
  <va-button v-if="residence.length" class="btn-save" @click="saveResidence">Salvar</va-button>

  <div class="container">
    <va-input label="NIF">
      <va-file-upload
        v-model="nif"
        type="list"
        undo
        undo-duration="5000"
        undo-button-text="cancelar"
        deleted-file-message="Arquivo apagado"
      >
        <div class="custom-upload-file-area">
          <img id="file-upload" src="./../../assets/upload.jpg" alt="upload" />
        </div>
      </va-file-upload>
    </va-input>
  </div>
  <va-button v-if="nif.length" @click="saveNIF">Salvar</va-button>

  <div class="container">
    <va-input label="NISS">
      <va-file-upload
        v-model="niss"
        type="list"
        undo
        undo-duration="5000"
        undo-button-text="cancelar"
        deleted-file-message="Arquivo apagado"
      >
        <div class="custom-upload-file-area">
          <img id="file-upload" src="./../../assets/upload.jpg" alt="upload" />
        </div>
      </va-file-upload>
    </va-input>
  </div>
  <va-button v-if="niss.length" @click="saveNISS">Salvar</va-button>

  <div class="container">
    <va-input label="Nº Utente">
      <va-file-upload
        v-model="utent"
        type="list"
        undo
        undo-duration="5000"
        undo-button-text="cancelar"
        deleted-file-message="Arquivo apagado"
      >
        <div class="custom-upload-file-area">
          <img id="file-upload" src="./../../assets/upload.jpg" alt="upload" />
        </div>
      </va-file-upload>
    </va-input>
  </div>
  <va-button v-if="utent.length" @click="saveUtent">Salvar</va-button>

  <div class="container">
    <va-input label="Manifestação de Interesse">
      <va-file-upload
        v-model="sefManifest"
        type="list"
        undo
        undo-duration="5000"
        undo-button-text="cancelar"
        deleted-file-message="Arquivo apagado"
      >
        <div class="custom-upload-file-area">
          <img id="file-upload" src="./../../assets/upload.jpg" alt="upload" />
        </div>
      </va-file-upload>
    </va-input>
  </div>
  <va-button class="btn-save" v-if="sefManifest.length" @click="saveSefManifest">Salvar</va-button>

  <div class="container">
    <va-input label="Habilitações Académicas">
      <va-file-upload
        v-model="education"
        type="list"
        undo
        undo-duration="5000"
        undo-button-text="cancelar"
        deleted-file-message="Arquivo apagado"
      >
        <div class="custom-upload-file-area">
          <img id="file-upload" src="./../../assets/upload.jpg" alt="upload" />
        </div>
      </va-file-upload>
    </va-input>
  </div>
  <va-button v-if="education.length" @click="saveEducation">Salvar</va-button>

  <va-modal v-model="showModal" cancelText="" okText="Fechar">
    <div class="modal">Ficheiro salvo!</div>
  </va-modal>
</template>

<script setup lang="ts">
  import axios from 'axios'
  import { ref, watch } from 'vue'
  import { API_PATH } from '../../stores/global-store'

  const passport = ref([])
  const residence = ref([])
  const nif = ref([])
  const niss = ref([])
  const utent = ref([])
  const sefManifest = ref([])
  const education = ref([])
  const count = ref(-1)
  const showModal = ref(false)

  /*watch(passport, () => {
  addPassport()
})

watch(residence, () => {
  addResidence()
})

watch(nif, () => {
  addNIF()
})

watch(niss, () => {
  addNISS()
})

watch(utent, () => {
  addUtent()
})

watch(sefManifest, () => {
  addSefManifest()
})

watch(education, () => {
  addEducation(count.value)
  count.value++
  console.log("count: ", count.value)


  if (education.value.length == 1 && count.value == 0) {
    addEducation(0)
    return
  }



  if (education.value.length > 1) {
    for (let i = 0; i < education.value.length; i++) {
      addEducation(i)
    }
  }
})*/

  function deleteItem() {
    console.log('delete')
  }

  function savePassport() {
    if (passport.value.length > 1) {
      for (let i = 0; i < passport.value.length; i++) {
        addPassport(i)
      }
    } else {
      addPassport(0)
    }
  }

  function saveResidence() {
    if (residence.value.length > 1) {
      for (let i = 0; i < residence.value.length; i++) {
        addResidence(i)
      }
    } else {
      addResidence(0)
    }
  }

  function saveNIF() {
    if (nif.value.length > 1) {
      for (let i = 0; i < nif.value.length; i++) {
        addNIF(i)
      }
    } else {
      addNIF(0)
    }
  }

  function saveNISS() {
    if (niss.value.length > 1) {
      for (let i = 0; i < niss.value.length; i++) {
        addNISS(i)
      }
    } else {
      addNISS(0)
    }
  }

  function saveUtent() {
    if (utent.value.length > 1) {
      for (let i = 0; i < utent.value.length; i++) {
        addUtent(i)
      }
    } else {
      addUtent(0)
    }
  }

  function saveSefManifest() {
    if (sefManifest.value.length > 1) {
      for (let i = 0; i < sefManifest.value.length; i++) {
        addSefManifest(i)
      }
    } else {
      addSefManifest(0)
    }
  }

  function saveEducation() {
    if (education.value.length > 1) {
      for (let i = 0; i < education.value.length; i++) {
        addEducation(i)
      }
    } else {
      addEducation(0)
    }
  }

  function addPassport(i: number) {
    var data = new FormData()
    var uniqueId = localStorage.getItem('uniqueId')
    data.append('passport', passport.value[i])
    data.append('uniqueId', String(uniqueId))
    new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/src/api/curriculo_api.php?action=savePassport', data, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then((res) => {
        console.log('Success send Client', res)
        showModal.value = !showModal.value
      })
      .catch((error) => {
        console.log('Error')
        console.log(error)
      })
  }

  function addResidence(i: number) {
    var data = new FormData()
    var uniqueId = localStorage.getItem('uniqueId')
    data.append('residence', residence.value[i])
    data.append('uniqueId', String(uniqueId))
    new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/src/api/curriculo_api.php?action=saveResidence', data, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then((res) => {
        console.log('Success send Client', res)
        showModal.value = !showModal.value
      })
      .catch((error) => {
        console.log('Error')
        console.log(error)
      })
  }

  function addNIF(i: number) {
    var data = new FormData()
    var uniqueId = localStorage.getItem('uniqueId')
    data.append('nif', nif.value[i])
    data.append('uniqueId', String(uniqueId))
    new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/src/api/curriculo_api.php?action=saveNIF', data, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then((res) => {
        console.log('Success send Client', res)
        showModal.value = !showModal.value
      })
      .catch((error) => {
        console.log('Error')
        console.log(error)
      })
  }

  function addNISS(i: number) {
    var data = new FormData()
    var uniqueId = localStorage.getItem('uniqueId')
    data.append('niss', niss.value[i])
    data.append('uniqueId', String(uniqueId))
    new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/src/api/curriculo_api.php?action=saveNISS', data, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then((res) => {
        console.log('Success send Client', res)
        showModal.value = !showModal.value
      })
      .catch((error) => {
        console.log('Error')
        console.log(error)
      })
  }

  function addUtent(i: number) {
    var data = new FormData()
    var uniqueId = localStorage.getItem('uniqueId')
    data.append('utent', utent.value[i])
    data.append('uniqueId', String(uniqueId))
    new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/src/api/curriculo_api.php?action=saveUtent', data, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then((res) => {
        console.log('Success send Client', res)
        showModal.value = !showModal.value
      })
      .catch((error) => {
        console.log('Error')
        console.log(error)
      })
  }

  function addSefManifest(i: number) {
    var data = new FormData()
    var uniqueId = localStorage.getItem('uniqueId')
    data.append('sefManifest', sefManifest.value[i])
    data.append('uniqueId', String(uniqueId))
    new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/src/api/curriculo_api.php?action=saveSefManifest', data, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then((res) => {
        console.log('Success send Client', res)
        showModal.value = !showModal.value
      })
      .catch((error) => {
        console.log('Error')
        console.log(error)
      })
  }

  function addEducation(i: number) {
    var data = new FormData()
    var uniqueId = localStorage.getItem('uniqueId')
    data.append('education', education.value[i])
    data.append('uniqueId', String(uniqueId))
    // new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/src/api/curriculo_api.php?action=saveEducation', data, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then((res) => {
        console.log('Success send Client', res.data.error)
        if (!res.data.error) {
          console.log('count', count.value)
          showModal.value = !showModal.value
        }
      })
      .catch((error) => {
        console.log('Error')
        console.log(error)
      })
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
    width: 100%;
  }

  #file-upload {
    width: 100%;
  }

  .container {
    width: 100%;
    margin-bottom: 10px;
  }
  .btn-save {
    margin-bottom: 15px;
  }

  @media only screen and (max-width: 600px) {
    .modal {
      margin-top: 50vh;
      text-align: center;
    }
  }

  @media only screen and (min-width: 796px) {
    .container {
      border: 1px solid red;
      text-align: center;
    }

    #file-upload {
      width: 50%;
    }
    #container :hover {
      cursor: pointer;
    }

    .va-file-upload__field__input {
      cursor: pointer;
    }
  }
</style>
