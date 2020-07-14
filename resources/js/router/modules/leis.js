import Layout from '@/layout';

const controleDeLeisRoutes = {
  path: '/controle-de-leis',
  component: Layout,
  name: 'Controle de leis',
  meta: {
    title: 'Controle de leis',
    icon: 'edit',
  },
  children: [
    {
      path: 'leis',
      component: () => import('@/views/laws/Laws'),
      name: 'Leis',
      meta: { title: 'Leis' },
    },
    {
      path: 'controle-de-playlists',
      component: () => import('@/views/laws/Playlists'),
      name: 'Controle de Playlists',
      meta: { title: 'Controle de Playlists' },
    },
  ],
};

export default controleDeLeisRoutes;
