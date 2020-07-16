<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreateForm">
        Adicionar playlist
      </el-button>
      <el-dialog :title="formTitle" :visible.sync="dialogVisible" :close-on-click-modal="false">
        <div v-loading="loading" class="form-container">
          <el-row :gutter="20">
            <el-col v-for="plano in playlists" :key="plano.id" :plano-atual="plano" :span="8" />
          </el-row>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { getToken } from '@/utils/auth';

export default {
  data() {
    return {
      loading: false,
      dialogVisible: false,
      title: '',
      playlists: [],
    };
  },
  mounted() {
    this.getList();
  },
  methods: {
    handleCreateForm() {
      this.dialogVisible = true;
      this.formTitle = 'Adicionar nova playlist';
      this.btnInsertUpdate = 'Adicionar playlist';
      this.currentVoucher = {
        tipo_desconto: false,
        desconto: '',
        desconto_porcentagem: '',
        quantidade_total: 0,
        data_expiracao: '',
        statusSwitch: true,
      };
    },
    handleEdit(id, chave, desconto, desconto_porcentagem, quantidade_total, quantidade_usado, data_inicio, data_fim, status){
      this.currentVoucher = this.cupons.find(category => category.id === id);
      this.dialogVisible = true;
      this.formTitle = 'Editar playlist ' + chave;
      this.btnInsertUpdate = 'Atualizar playlist';

      if (quantidade_total !== 0){
        this.quantidade_total_checked = true;
      } else {
        this.quantidade_total_checked = false;
      }
      var datas = null;
      this.data_expiracao_checked = false;
      if (data_inicio && data_fim) {
        this.data_expiracao_checked = true;
        datas = [data_inicio, data_fim];
      }
      if (!desconto || desconto === 0 || desconto === 'R$ 0,00') {
        this.currentVoucher.tipo_desconto = true;
      } else {
        this.currentVoucher.tipo_desconto = false;
      }
      var statusSwitch = status;
      if (statusSwitch === 'PUBLISHED') {
        statusSwitch = true;
      } else {
        statusSwitch = false;
      }
      var tipoDesconto = this.currentVoucher.tipo_desconto;
      this.currentVoucher = {
        id: id,
        chave: chave,
        tipo_desconto: tipoDesconto,
        desconto: desconto,
        desconto_porcentagem: desconto_porcentagem,
        quantidade_total: quantidade_total,
        quantidade_usado: quantidade_usado,
        data_expiracao: datas,
        status: status,
        statusSwitch: statusSwitch,
      };
    },

    async getList(id = null) {
      this.loading = true;
      // alert('list');
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
        console.log(response.data);
        this.playlists = response.data;
        this.loading = false;
      }).catch(error => console.log(error));
    },
  },
};
</script>
