import Layout from '@/layout';

const reportsRoutes = {
  path: '/relatorios',
  component: Layout,
  name: 'Relatórios',
  meta: {
    title: 'Relatórios',
    icon: 'chart',
  },
  children: [
    {
      path: 'leis-mais-acessadas',
      component: () => import('@/views/reports/AcessLaws'),
      name: 'Leis mais acessadas',
      meta: { title: 'AcessLaws' },
    },
    {
      path: 'assinatura-e-cancelamento',
      component: () => import('@/views/reports/SubsOrCancel'),
      name: 'Assinatura/Cancelamento',
      meta: { title: 'SubsOrCancel' },
    },
    {
      path: 'assinaturas',
      component: () => import('@/views/reports/Subscriptions'),
      name: 'Assinaturas',
      meta: { title: 'Subscriptions' },
    },
    {
      path: 'cancelamento',
      component: () => import('@/views/reports/Cancels'),
      name: 'Cancelamentos',
      meta: { title: 'PlanCancels' },
    },
    {
      path: 'hora-acessada',
      component: () => import('@/views/reports/AcessHour'),
      name: 'Horários mais acessados',
      meta: { title: 'AcessHours' },
    },
    {
      path: 'transacoes',
      component: () => import('@/views/reports/Transactions'),
      name: 'Transações',
      meta: { title: 'Transactions' },
    },
    // {
    //   path: 'planos-usuarios',
    //   component: () => import('@/views/reports/UserPlan'),
    //   name: 'Planos e usuários',
    //   meta: { title: 'UserPlan' },
    // },
  ],
};

export default reportsRoutes;
