import Layout from '@/layout';

const plansRoutes = {
  path: '/planos',
  component: Layout,
  redirect: '/planos/lista',
  name: 'Planos de assinatura',
  meta: {
    title: 'Planos',
    icon: 'edit',
  },
  children: [
    {
      path: 'criar',
      component: () => import('@/views/plans/Create'),
      name: 'Planos',
      meta: { title: 'Planos', icon: 'edit' },
    },
  ],
};

export default plansRoutes;
