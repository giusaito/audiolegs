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
      <el-table-column align="center" label="ID">
        <template slot-scope="{row}">
          <span> {{ row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Tipo de ação">
        <template slot-scope="{row}">
          <span>{{ row.log_name }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Mensagem">
        <template slot-scope="{row}">
          <span>{{ row.description }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Url">
        <template>
          <span>{{ jsonParse.url }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="ip">
        <template>
          <span>{{ jsonParse.ip }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Data e Hora">
        <template slot-scope="{row}">
          <span>{{ row.created_at | format_date }}</span>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getActivities" />
  </div>
</template>

<script>
import { fetchActivities } from '@/api/activities';
import Pagination from '@/components/Pagination';
import waves from '@/directive/waves';
import moment from 'moment';
export default {
  name: 'InlineEditTable',
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
    format_date(value){
      if (value) {
        moment.locale('pt-br');
        return moment(String(value)).format('lll');
      }
    },
  },
  data() {
    return {
      list: [],
      tipoAcao: ['Login', 'Planos', 'Usuário'],
      query: {
        page: 1,
        limit: 15,
        keyword: '',
        role: '',
      },
      jsonParse: {},
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
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
    };
  },
  created() {
    this.getActivities();
  },
  methods: {
    async getActivities() {
      this.listLoading = true;
      const { data } = await fetchActivities(this.listQuery);
      console.log(data);
      this.total = data.total;
      this.list = data.data;
      this.jsonParse = JSON.parse(this.list[0].properties);
      this.listLoading = false;
    },
    handleFilter() {
      this.listQuery.page = 1;
      this.getActivities();
    },
  },
};
</script>

<style scoped>
.edit-input {
  padding-right: 100px;
}
.datetimeRange {
  padding: 7px 10px !important;
}
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
</style>
