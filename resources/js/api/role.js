import request from '@/utils/request';
import Resource from '@/api/resource';

export function fetchRoles(id) {
  return request({
    url: '/v1/bw/roles/',
    method: 'get',
  });
}

class RoleResource extends Resource {
  constructor() {
    super('roles');
  }

  permissions(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'get',
    });
  }
}

export { RoleResource as default };
