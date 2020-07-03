<template>
  <div class="app-container">
    <div class="filter-container">
      <!-- <el-input v-model="query.keyword" placeholder="Pesquisar" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" /> -->
      <el-date-picker
        v-model="value2"
        type="datetimerange"
        format="dd/MM/yyyy"
        value-format="yyyy-MM-dd"
        range-separator="-"
        start-placeholder="Início"
        end-placeholder="Fim"
        :picker-options="pickerOptions"
        class="datetimeRange"
      />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        Procurar
      </el-button>
      <el-button v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        Exportar
      </el-button>
    </div>
    <el-table v-loading="listLoading" :data="list" style="width: 100%" :default-sort="{prop: 'total_pageview', order: 'descending'}">
      <el-table-column align="center" label="ID" width="80" prop="id" sortable>
        <template slot-scope="{row}">
          <span> {{ row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Título da lei" prop="title" sortable>
        <template slot-scope="{row}">
          <span>{{ row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Acessos site" prop="total_pageview_phone" sortable>
        <template slot-scope="{row}">
          <span>{{ row.total_pageview_phone }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Acessos aplicativo" prop="total_pageview_app" sortable>
        <template slot-scope="{row}">
          <span>{{ row.total_pageview_app }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Quantidade total de acessos" prop="total_pageview" sortable>
        <template slot-scope="{row}">
          <span>{{ row.total_pageview }}</span>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getLaws" />
  </div>
</template>

<script>
import { fetchLaws } from '@/api/reports';
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
      downloading: false,
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
    handleDownload() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['id', 'Título', 'Quantidade total de acessos', 'Acessos site', 'Acessos aplicativo'];
        const filterVal = ['id', 'title', 'total_pageview', 'total_pageview_phone', 'total_pageview_app'];
        const data = this.formatJson(filterVal, this.list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'Leis mais acessadas',
        });
        this.downloading = false;
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
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
