import request from '@/utils/request';
// import Resource from '@/api/resource';

export function fetchStates(query) {
  return request({
    url: '/v1/bw/estados/lista',
    method: 'get',
    params: query,
  });
}

