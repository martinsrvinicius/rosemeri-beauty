<template>
  <div>
    <p>Home Admin</p>
    <p>Teste</p>
    <p>{{ items }}</p>
  </div>
</template>

<script setup>
  import axios from 'axios'
  import { onMounted, ref } from 'vue'
  import { API_PATH } from '../../stores/global-store'

  const items = ref()

  onMounted(() => {
    testAPI()
  })

  async function testAPI() {
    await axios('https://vinim.eu/api/read_single.php?id=4')
      .then((res) => {
        console.log('request API teste: ', res.data)
        items.value = res.data
      })
      .catch()
  }
</script>
