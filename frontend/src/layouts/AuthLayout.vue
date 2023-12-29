<template>
  <div id="container">
    <LandingPage />
    <div class="auth-layout row align-content-center logo-align">
      <div class="flex xs12 pa-3 justify-center">
        <router-link class="py-5 justify-center d-flex" to="/">
          <!--<img style="width: 300px; height: 100px; object-fit: contain" src="https://rosemeri-beauty.vinim.eu/assets/logo.png" alt="" />-->
        </router-link>
      </div>

      <div class="flex xs12 pa-3 form-align">
        <div class="d-flex justify-center">
          <va-card class="auth-layout__card">
            <va-card-content>
              <div class="mb-1" style="display: flex; justify-content: center">
                <img
                  style="width: 300px; object-fit: contain"
                  src="https://rosemeri-beauty.vinim.eu/assets/logo.png"
                  alt="Logo"
                />
              </div>
              <va-tabs v-model="tabIndex" center>
                <template #tabs>
                  <va-tab name="login">{{ t('auth.login') }}</va-tab>
                  <va-tab name="signup">{{ t('auth.createNewAccount') }}</va-tab>
                </template>
              </va-tabs>

              <va-separator />

              <div class="pa-3">
                <router-view />
              </div>
            </va-card-content>
          </va-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { useI18n } from 'vue-i18n'
  import LandingPage from '../pages/landing-page/LandingPage.vue'

  export default {
    name: 'AuthLayout',
    components: { LandingPage },

    setup() {
      const { t } = useI18n()
      return { t }
    },
    data() {
      return {
        selectedTabIndex: 0,
      }
    },
    computed: {
      tabIndex: {
        set(tabName) {
          this.$router.push({ name: tabName })
        },
        get() {
          return this.$route.name
        },
      },
    },
  }
</script>

<style lang="scss">
  .auth-layout {
    background-image: linear-gradient(to right, var(--va-background-primary), var(--va-white));

    &__card {
      width: 100%;
      max-width: 600px;
    }
  }

  @media only screen and (min-width: 375px) {
    .logo-align {
    }
  }
  @media only screen and (min-width: 769px) {
    .logo-align {
      position: relative;
      bottom: 100px;
    }
    #container {
      display: flex;
    }
  }
</style>
