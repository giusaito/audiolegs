import request from '@/utils/request';

const baseUrlApi = 'v1/bw/cupons';
export function fetchVouchers(query) {
  return request({
    url: baseUrlApi + '/listar',
    method: 'get',
    params: query,
  });
}

export function updateVoucher(data) {
  return request({
    url: baseUrlApi + '/atualizar',
    method: 'post',
    data,
  });
}
