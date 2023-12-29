<template>
  <div id="background">
    <p class="txt-background">
      <!--<img style="width: 300px; height: 100px; object-fit: contain" src="https://rosemeri-beauty.vinim.eu/assets/logo.png" alt="" />-->
    </p>
  </div>
</template>

<script setup>
  import { ref } from 'vue'
</script>

<style scoped>
  #background {
    background-image: url(../../assets/rose.jpg);
    height: 300px;
    background-size: cover;
    background-repeat: no-repeat;
  }

  .txt-background {
    padding: 30px;
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    color: white;
  }

  #background-img {
    object-fit: cover;
    height: 40vh;
    width: 100vw;
  }

  @media only screen and (min-width: 768px) {
    #background-img {
      background-repeat: no-repeat;
      background-attachment: fixed;
      object-fit: cover;
      height: 100%;
      width: 100vw;
      position: absolute;
      z-index: -1;
    }
  }

  @media only screen and (min-width: 769px) {
    #background {
      background-image: url(../../assets/rose.jpg);
      height: 100vh;
      width: 800px;
      background-size: cover;
      background-repeat: no-repeat;
    }
  }
</style>
