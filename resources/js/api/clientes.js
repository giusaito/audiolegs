import request from '@/utils/request';
import Resource from '@/api/resource';

class UserResource extends Resource {
  constructor() {
    super('clientes');
  }

  permissions(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'get',
    });
  }

  updatePermission(id, permissions) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'put',
      data: permissions,
    });
  }
  updatePassword(id, password) {
    return request({
      url: '/v1/bw/' + this.uri + '/' + id + '/password',
      method: 'put',
      data: password,
    });
  }
}

export { UserResource as default };
