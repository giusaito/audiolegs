<template>
  <div class="app-container">
    Criar playlist
  </div>
</template>

<script>
import axios from 'axios';
import { getToken } from '@/utils/auth';

export default {
  data() {
    return {
      loading: false,
    };
  },

  async getList(id = null) {
    this.loading = true;
    var urlList = `api/v1/bw/playlist`;
    if (id) {
      urlList = `api/v1/bw/playlist/` + id;
    }
    axios({
      method: 'get',
      url: urlList,
      headers: {
        'Authorization': 'Bearer ' + getToken(),
      },
    }).then((response) => {
      console.log(response.data.data);
      this.leis = response.data.data;
      this.loading = false;
    }).catch(error => console.log(error));
  },
  created() {
    this.getList();
  },
};
</script>
