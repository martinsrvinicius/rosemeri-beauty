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
      displayName: 'Painel Principal',
      meta: {
        icon: 'material-icons-add_chart',
      },
    },
    {
      name: 'calendar-cal',
      displayName: 'Agenda',
      meta: {
        icon: 'material-icons-event_repeat',
      },
    },
    {
      name: 'clients',
      displayName: 'Clientes',
      meta: {
        icon: 'material-icons-group',
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
