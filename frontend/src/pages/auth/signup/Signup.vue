<template>
  <form @submit.prevent="onsubmit()">
    <va-input
      v-model="name"
      class="mb-3"
      type="email"
      :label="t('auth.name')"
      :error="!!nameErrors.length"
      :error-messages="nameErrors"
    />

    <va-input
      v-model="email"
      class="mb-3"
      type="email"
      :label="t('auth.email')"
      :error="!!emailErrors.length"
      :error-messages="emailErrors"
    />

    <va-input
      v-model="password"
      class="mb-3"
      type="password"
      :label="t('auth.password')"
      :error="!!passwordErrors.length"
      :error-messages="passwordErrors"
    />

    <div class="auth-layout__options d-flex align-center justify-space-between">
      <div class="checkbox-container">
        <va-checkbox
          v-model="agreedToTerms"
          class="mb-0"
          :error="!!agreedToTermsErrors.length"
          :error-messages="agreedToTermsErrors"
        >
        </va-checkbox>
        <span @click="showModal = !showModal" class="va-link terms">{{ t('auth.termsOfUse') }}</span>
      </div>
      <router-link class="ml-1 va-link" :to="{ name: 'recover-password' }">
        {{ t('auth.recover_password') }}
      </router-link>
    </div>
    <div class="d-flex justify-center mt-3">
      <!--<va-button class="my-0" @click="onsubmit">{{ t('auth.sign_up') }}</va-button>-->
      <va-button class="my-0" @click="onsubmit">Inscrever-se</va-button>
    </div>
  </form>

  <va-modal v-model="showModal" okText="Aceitar" cancelText="Cancelar" @ok="agreedToTerms = true">
    <div>
      <AgreedToTerms />
    </div>
  </va-modal>

  <!--<va-modal v-model="showModalSuccess" okText="Fechar" cancelText="Voltar">
    <div class="modal">
      {{ txtModal }}
    </div>
  </va-modal>-->
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue'
  import { useRouter } from 'vue-router'
  import { useI18n } from 'vue-i18n'
  import axios from 'axios'
  import { API_PATH, useGlobalStore } from '../../../stores/global-store'
  import router from '../../../router'
  import AgreedToTerms from '../../../components/terms/AgreedToTerms.vue'
  import { useToast } from 'vuestic-ui'

  const { t } = useI18n()
  const { init: initToast } = useToast()

  const name = ref('')
  const email = ref('')
  const password = ref('')
  const agreedToTerms = ref(false)
  const nameErrors = ref<string[]>([])
  const emailErrors = ref<string[]>([])
  const passwordErrors = ref<string[]>([])
  const agreedToTermsErrors = ref<string[]>([])
  const data = new FormData()
  const uName = ref()
  const currentUser = useGlobalStore()
  const showModal = ref()
  const showModalSuccess = ref()
  const txtModal = ref()

  const formReady = computed(() => {
    return !(
      nameErrors.value.length ||
      emailErrors.value.length ||
      passwordErrors.value.length ||
      agreedToTermsErrors.value.length
    )
  })

  function onsubmit() {
    console.log('suubmit', formReady.value)
    //if (!formReady.value) return

    nameErrors.value = name.value ? [] : ['Insira um nome']
    emailErrors.value = email.value ? [] : ['O email é obrigatório']
    passwordErrors.value = password.value ? [] : ['Password é obrigatório']
    agreedToTermsErrors.value = agreedToTerms.value ? [] : ['Você deve aceitar os termos de uso para continuar.']

    if (name.value != '' && email.value != '' && password.value != '' && agreedToTerms.value != false) {
      validateEmail(email.value)
    }

    //useRouter().push({ name: 'dashboard' })
    //onRegister()
  }

  function validateEmail(email: any) {
    var data = new FormData()
    data.append('email', email)
    //new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/src/api/login_api.php?action=validateEmail', data)
      .then((res) => {
        if (res.data.error) {
          console.log('email: ', res.data.error)
        } else {
          console.log('email exists: ', res.data)
          onRegister()
        }
      })
      .catch((error) => {
        console.log('error: ', error)
      })
  }

  function onRegister() {
    var data = new FormData()
    data.append('name', name.value)
    data.append('email', email.value)
    data.append('password', password.value)
    if (agreedToTerms.value == true) {
      data.append('terms', '1')
    } else {
      data.append('terms', '0')
    }

    axios
      .post(API_PATH + '/src/api/login_api.php?action=register', data)
      .then((res) => {
        console.log('SIGN: ', res.data)
        showModalSuccess.value = !showModalSuccess.value

        if (res.data.error) {
          //console.log('Error', res.data.error)
          txtModal.value = 'Não foi possível criar conta. Email já registado. '
          notify(txtModal.value, 'danger')
        } else {
          txtModal.value = 'Email registado com sucesso!'
          notify(txtModal.value, 'success')
          getCurrentUser(data)
          console.log('Success', res.data)
        }
      })
      .catch((error) => {
        console.log('Error')
        console.log(error)
      })
  }

  function getCurrentUser(data: object) {
    axios
      .post(API_PATH + '/src/api/login_api.php?action=getCurrentUser', data)
      .then((res) => {
        console.log('Current User: ', res.data)
        localStorage.setItem('userName', res.data.currentUser['username'])
        localStorage.setItem('name', res.data.currentUser['name'])
        localStorage.setItem('email', res.data.currentUser['email'])
        localStorage.setItem('adm', res.data.currentUser['adm'])
        localStorage.setItem('uniqueId', res.data.currentUser['uniqueId'])
        var adm = res.data.currentUser['adm']
        uName.value = localStorage.getItem('name')
        currentUser.changeUserName(uName.value)
        console.log('admin, ', adm)
        //createForm(adm)
        if (adm == 1) {
          router.push({ name: 'page-1' })
        } else {
          router.push({ name: 'home-user' })
        }
      })
      .catch()
    console.log('Error')
  }

  function notify(name: string, color: string) {
    initToast({
      message: `${name}`,
      position: 'top-right',
      color: color,
    })
  }
</script>

<style scoped>
  @media only screen and (max-width: 600px) {
    .modal {
      margin-top: 50vh;
      text-align: center;
    }
  }

  .checkbox-container {
    display: flex;
  }

  .terms {
    margin: auto;
    padding-left: 5px;
  }
</style>
