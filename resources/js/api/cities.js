import request from '@/utils/request';
// import Resource from '@/api/resource';

export function fetchCities(query) {
  return request({
    url: '/v1/bw/cidades/lista',
    method: 'get',
    params: query,
  });
}

