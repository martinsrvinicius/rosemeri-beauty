<template>
  <form @submit.prevent="onsubmit">
    <va-input
      v-model.trim="email"
      class="mb-3"
      type="email"
      :label="t('auth.email')"
      :error="!!emailErrors.length"
      :error-messages="emailErrors"
    />

    <va-input
      v-model.trim="password"
      class="mb-3"
      type="password"
      :label="t('auth.password')"
      :error="!!passwordErrors.length"
      :error-messages="passwordErrors"
    />

    <div class="auth-layout__options d-flex align-center justify-space-between">
      <va-checkbox v-model="keepLoggedIn" class="mb-0" :label="t('auth.keep_logged_in')" />
      <router-link class="ml-1 va-link" :to="{ name: 'recover-password' }">{{
        t('auth.recover_password')
      }}</router-link>
    </div>

    <div class="d-flex justify-center mt-3">
      <va-button class="my-0" @click="onsubmit">{{ t('auth.login') }}</va-button>
    </div>
  </form>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { useI18n } from 'vue-i18n'
  import axios from 'axios'
  import { useGlobalStore } from '../../../stores/global-store'
  import { API_PATH } from '../../../stores/global-store'

  const { t } = useI18n()
  const email = ref('')
  const password = ref('')
  const keepLoggedIn = ref(false)
  const emailErrors = ref<string[]>([])
  const passwordErrors = ref<string[]>([])
  const router = useRouter()
  const data = new FormData()
  const currentUser = useGlobalStore()
  const uName = ref()

  const formReady = computed(() => !emailErrors.value.length && !passwordErrors.value.length)

  function onsubmit() {
    if (!formReady.value) return

    emailErrors.value = email.value ? [] : ['Email is required']
    passwordErrors.value = password.value ? [] : ['Password is required']

    onLogin()
  }

  function onLogin() {
    data.append('email', email.value)
    data.append('pass', password.value)
    new Response(data).text().then(console.log)

    axios
      .post(API_PATH + '/api/users/login.php?action=login', data)
      .then((res) => {
        console.log('data', res.data.username)
        if (res.data.adm == 1) {
          router.push({ name: 'page-1' })
        } else {
          router.push({ name: 'home-user' })
        }

       /* if (res.data.error) {
          console.log('Error', res.data.error)
          alert('O Email e/ou a Palavra-Passe estão incorretos. Tente novamente.')
        } else {
          //getCurrentUser(data)
          console.log('Success', res.data)
        }*/
      })
      .catch((error) => {
        console.log('Error')
        console.log(error)
      })
  }

  function getCurrentUser(data: object) {
    axios
      .post(API_PATH + '/api/login_api.php?action=getCurrentUser', data)
      .then((res) => {
        //console.log('Current User: ', res.data)
        localStorage.setItem('userName', res.data.currentUser['username'])
        localStorage.setItem('name', res.data.currentUser['name'])
        localStorage.setItem('email', res.data.currentUser['email'])
        localStorage.setItem('adm', res.data.currentUser['adm'])
        localStorage.setItem('uniqueId', res.data.currentUser['uniqueId'])
        var adm = res.data.currentUser['adm']
        uName.value = localStorage.getItem('name')
        currentUser.changeUserName(uName.value)

        if (adm == 1) {
          router.push({ name: 'page-1' })
        } else {
          router.push({ name: 'home-user' })
        }
      })
      .catch()
  }
</script>
