import request from '@/utils/request';

export function fetchLaws(query) {
  return request({
    url: '/v1/bw/relatorios/leis-acessadas',
    method: 'get',
    params: query,
  });
}

export function fetchSubsOrCancel(query) {
  return request({
    url: '/v1/bw/relatorios/assinaturas-cancelamentos',
    method: 'get',
    params: query,
  });
}
