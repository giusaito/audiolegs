import request from '@/utils/request';

const baseUrlApi = 'v1/bw/planos';
export function fetchPlans(query) {
  return request({
    url: baseUrlApi + '/lista',
    method: 'get',
    params: query,
  });
}

export function updatePlan(data) {
  return request({
    url: baseUrlApi + '/atualizar',
    method: 'post',
    data,
  });
}
