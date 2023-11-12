<template>
  <va-dropdown class="color-dropdown" :offset="[13, 70]" position="bottom">
    <template #anchor>
      <va-icon-color />
    </template>
    <va-dropdown-content class="color-dropdown__content pl-12 pr-4 pt-2 pb-2">
      <div>
        <div class="flex items-center gap-3">
          Paleta de cores:
          <VaColorPalette v-model="cor" :palette="palette" />
        </div>
        <div class="flex items-center gap-3">
          Tema Escuro:
          <VaSwitch v-model="currentTheme" true-value="dark" false-value="light" size="small" />
        </div>
      </div>
    </va-dropdown-content>
  </va-dropdown>
</template>
<script setup lang="ts">
  import VaIconColor from '../../../icons/VaIconColor.vue'
  import { useColors } from 'vuestic-ui'
  import { ref, watchEffect, watch, onMounted } from 'vue'
  const { presets, applyPreset, colors, setColors, getColors, getColor } = useColors()
  const cor = ref()
  const colorsName = Object.keys(colors)
  const currentTheme = ref('light')
  const palette = ['#2C82E0', '#EF476F', '#FFD166', '#06D6A0', '#8338EC']
  const themeOptions = Object.keys(presets.value).map((themeName) => ({
    value: themeName,
    label: themeName,
  }))

  onMounted(() => {
    console.log('Colors: ', getColors().primary)
  })

  watchEffect(() => {
    applyPreset(currentTheme.value)
    // applyPreset('light')
  })

  function changeColor() {
    localStorage.setItem('color', cor.value)
    colors.primary = cor.value
    // console.log('mudou', localStorage.getItem('color'))
  }

  watch(cor, () => {
    changeColor()
  })

  onMounted(() => {
    const isColor = localStorage.getItem('color')
    if (isColor) {
      cor.value = localStorage.getItem('color')
      colors.primary = cor.value
      //console.log('começou com cor')
      return
    }
    //console.log('começou sem cor', localStorage.getItem('color'))
  })
</script>

<style lang="scss" scoped>
  .color-dropdown {
    cursor: pointer;

    &__icon {
      position: relative;
      display: flex;
      align-items: center;
    }

    .va-dropdown__anchor {
      display: inline-block;
    }

    &__toggle {
      width: 100%;
      display: flex;
      justify-content: stretch;
    }
  }

  .button-restore {
    display: flex;
    margin: 0.375rem auto;
  }

  table {
    margin: 1rem 0;
  }

  @media screen and (max-width: 640px) {
    .color-dropdown__content {
    }
  }
</style>
