import Layout from '@/layout';

const plansRoutes = {
  path: '/planos',
  component: Layout,
  name: 'Planos de assinatura',
  meta: {
    title: 'Planos',
    icon: 'edit',
  },
  children: [
    {
      path: 'lista',
      component: () => import('@/views/plans/Index'),
      name: 'Planos',
      meta: { title: 'plans', icon: 'edit' },
    },
  ],
};

export default plansRoutes;
