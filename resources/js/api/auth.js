import request from '@/utils/request';

export function login(data) {
  return request({
    url: '/auth/login',
    method: 'post',
    data: data,
  });
}

export function getInfo(token) {
  return request({
    url: '/v1/bw/auth/user',
    method: 'get',
  });
}

export function logout() {
  return request({
    url: '/v1/bw/auth/logout',
    method: 'post',
  });
}
