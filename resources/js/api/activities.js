import request from '@/utils/request';

export function fetchActivities(query) {
  return request({
    url: '/v1/bw/atividades',
    method: 'get',
    params: query,
  });
}

