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

export function fetchAcessHour(query) {
  return request({
    url: '/v1/bw/relatorios/hora-acessada',
    method: 'get',
    params: query,
  });
}

export function fetchTransactions(query) {
  return request({
    url: '/v1/bw/relatorios/transacoes',
    method: 'get',
    params: query,
  });
}

export function fetchSubscriptions(query) {
  return request({
    url: '/v1/bw/relatorios/assinaturas',
    method: 'get',
    params: query,
  });
}

export function fetchCancels(query) {
  return request({
    url: '/v1/bw/relatorios/cancelamento',
    method: 'get',
    params: query,
  });
}
