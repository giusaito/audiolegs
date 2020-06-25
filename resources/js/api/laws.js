import request from '@/utils/request';

export function fetchLaws(query) {
  return request({
    url: '/v1/bw/relatorios/leis-acessadas',
    method: 'get',
    params: query,
  });
}

