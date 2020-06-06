<template>
  <div class="app-container">
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
import moment from 'moment';
export default {
  name: 'InlineEditTable',
  components: { Pagination },
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
      jsonParse: {},
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
      },
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
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
</style>
