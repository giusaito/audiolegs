import Layout from '@/layout';

const plansRoutes = {
  path: '/planos',
  component: Layout,
  name: 'Planos de assinatura',
  meta: {
    title: 'Planos & Cupons',
    icon: 'edit',
  },
  children: [
    {
      path: 'lista',
      component: () => import('@/views/plans/Index'),
      name: 'Planos',
      meta: { title: 'plans' },
    },
    {
      path: 'cupons',
      component: () => import('@/views/plans/Cupons'),
      name: 'Cupons',
      meta: { title: 'coupons' },
    },
  ],
};

export default plansRoutes;
