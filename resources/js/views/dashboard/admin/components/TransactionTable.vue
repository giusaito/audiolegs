<template>
  <el-table v-loading="listLoading" :data="list" style="width: 100%" :default-sort="{prop: 'date', order: 'descending'}">
    <el-table-column align="center" label="Data" prop="date" sortable>
      <template slot-scope="{row}">
        <span>{{ row.date }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Ordem" prop="order_no" sortable>
      <template slot-scope="{row}">
        <span>{{ row.order_no }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Nome" prop="name" sortable>
      <template slot-scope="{row}">
        <span>{{ row.name }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Valor" prop="price" sortable>
      <template slot-scope="{row}">
        <span>{{ row.price }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Status" prop="status" sortable>
      <template slot-scope="{row}">
        <span>{{ row.status }}</span>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import { fetchTransactions } from '@/api/reports';

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        success: 'success',
        pending: 'danger',
      };
      return statusMap[status];
    },
    orderNoFilter(str) {
      return str;
    },
  },
  data() {
    return {
      list: [{ order_no: '1', price: '2', status: 'pending' }],
      loading: true,
    };
  },
  created() {
    this.getLaws();
  },
  methods: {
    async getLaws() {
      this.listLoading = true;
      const { data } = await fetchTransactions(this.listQuery);
      this.total = data.total;
      this.list = data.data;
      this.listLoading = false;
    },
  },
};
</script>
