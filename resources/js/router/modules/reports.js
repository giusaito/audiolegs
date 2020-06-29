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
  ],
};

export default reportsRoutes;
