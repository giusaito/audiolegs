import request from '@/utils/request';
// import Resource from '@/api/resource';

export function fetchUniversities(query) {
  return request({
    url: '/v1/bw/universidades/lista',
    method: 'get',
    params: query,
  });
}

