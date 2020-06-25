<template>
  <div class="app-container">
    <div class="filter-container">
      <!-- <el-input v-model="query.keyword" placeholder="Pesquisar" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" /> -->
      <el-select v-model="query.role" placeholder="Tipo de ação" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option v-for="item in tipoAcao" :key="item" :label="item | uppercaseFirst" :value="item" />
      </el-select>
      <el-date-picker
        v-model="value2"
        type="daterange"
        align="left"
        unlink-panels
        range-separator="-"
        start-placeholder="Início"
        end-placeholder="Fim"
        :picker-options="pickerOptions"
        class="datetimeRange"
      />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        Procurar
      </el-button>
    </div>
    <el-table v-loading="listLoading" :data="list" style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="{row}">
          <span> {{ row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Título da lei">
        <template slot-scope="{row}">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Quantidade de acessos">
        <template slot-scope="{row}">
          <span>5.212</span>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getLaws" />
  </div>
</template>

<script>
import { fetchLaws } from '@/api/laws';
import Pagination from '@/components/Pagination';
import waves from '@/directive/waves';
export default {
  name: 'Plans',
  components: { Pagination },
  directives: { waves },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger',
      };
      return statusMap[status];
    },
    statusPlans(value){
      if (value === 'PUBLISHED'){
        return 'Publicado';
      } else {
        return 'Rascunho';
      }
    },
  },
  data() {
    return {
      list: [],
      tipoAcao: ['Login', 'Planos', 'Usuário'],
      total: 0,
      listLoading: true,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
        role: '',
      },
      pickerOptions: {
        shortcuts: [{
          text: '7 dias',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          },
        },
        {
          text: '30 dias',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          },
        },
        {
          text: '90 dias',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          },
        }],
      },
      value1: '',
      value2: '',
      listQuery: {
        page: 1,
        limit: 10,
      },
    };
  },
  created() {
    this.getLaws();
  },
  methods: {
    async getLaws() {
      this.listLoading = true;
      const { data } = await fetchLaws(this.listQuery);
      this.total = data.total;
      this.list = data.data;
      this.listLoading = false;
    },
    handleFilter() {
      this.listQuery.page = 1;
      this.getLaws();
    },
  },
};
</script>

<style scoped>
  .edit-input {
    padding-right: 100px;
  }
  .cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
  }
</style>
