//SIDEBAR ADM
export interface INavigationRoute {
  name: string
  displayName: string
  meta: { icon: string }
  children?: INavigationRoute[]
}

export default {
  root: {
    name: '/',
    displayName: 'navigationRoutes.home',
  },
  routes: [
    {
      name: 'page-1',
      displayName: 'Home',
      meta: {
        icon: 'vuestic-iconset-dashboard',
      },
    },
    {
      name: 'calendar',
      displayName: 'Agenda',
      meta: {
        icon: 'vuestic-iconset-dashboard',
      },
    },
    /** {
      name: 'statistics',
      displayName: 'menu.statistics',
      meta: {
        icon: 'vuestic-iconset-statistics',
      },
      disabled: true,
      children: [
        {
          name: 'charts',
          displayName: 'menu.charts',
        },
        {
          name: 'progress-bars',
          displayName: 'menu.progressBars',
        },
      ],
    },*/
  ] as INavigationRoute[],
}
