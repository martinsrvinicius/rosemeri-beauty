import { ref, computed } from 'vue'
import { useGlobalConfig } from 'vuestic-ui'

export const THEME_NAMES = {
  LIGHT: 'LIGHT',
  ORIGINAL: 'ORIGINAL',
  DARK: 'DARK',
  SEMI_DARK: 'SEMI_DARK',
  CUSTOM: 'CUSTOM',
}

export const COLOR_THEMES = [
  {
    name: THEME_NAMES.CUSTOM,
    colors: {
      primary: '#154ec1',
      secondary: '#767c88',
      success: '#3d9209',
      info: '#2c82e0',
      danger: '#e42222',
      warning: '#ffd43a',
      backgroundPrimary: '#242bff',
      backgroundSecondary: '#c22154',
    },
  },

  {
    name: THEME_NAMES.LIGHT,
    colors: {
      primary: '#154ec1',
      secondary: '#767c88',
      success: '#3d9209',
      info: '#2c82e0',
      danger: '#e42222',
      warning: '#ffd43a',
      backgroundPrimary: '#242bff',
    },
  },
  {
    name: THEME_NAMES.DARK,
    colors: {
      primary: '#242424',
      secondary: '#A8AEBA',
      success: '#3d9209',
      info: '#2c82e0',
      danger: '#e42222',
      warning: '#ffd43a',
    },
  },
  {
    name: THEME_NAMES.SEMI_DARK,
    colors: {
      primary: '#6F92DA',
      secondary: '#A8AEBA',
      success: '#3d9209',
      info: '#2c82e0',
      danger: '#e42222',
      warning: '#ffd43a',
    },
  },
  {
    name: THEME_NAMES.ORIGINAL,
    colors: {
      primary: '#242424',
      secondary: '#242424',
      success: '#242424',
      info: '#242424',
      danger: '#242424',
      warning: '#242424',
    },
  },
]

export function useTheme() {
  const themeNameRef = ref(THEME_NAMES.CUSTOM)
  const { mergeGlobalConfig } = useGlobalConfig()

  const setTheme = (themeName: keyof typeof THEME_NAMES) => {
    themeNameRef.value = themeName
    const theme = COLOR_THEMES.find((theme) => theme.name === themeName)

    if (!theme) {
      throw new Error('Theme not found')
    }

    mergeGlobalConfig({
      colors: { variables: theme.colors },
    })
  }

  const theme = computed(() => COLOR_THEMES.find((theme) => theme.name === themeNameRef.value))
  return { setTheme, themeName: themeNameRef, theme }
}
