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
      path: 'arquivos-das-leis',
      component: () => import('@/views/laws/Files'),
      name: 'Arquivos das Leis',
      meta: { title: 'Arquivos das Leis' },
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
