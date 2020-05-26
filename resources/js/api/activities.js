import request from '@/utils/request';

export function fetchActivities(query) {
  return request({
    url: '/atividades',
    method: 'get',
    params: query,
  });
}

