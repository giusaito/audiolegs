<template>
  <el-table
    v-loading="loading"
    :data="list"
    style="width: 100%;padding-top: 15px;"
  >
    <el-table-column label="Id do pedido #" min-width="200">
      <template slot-scope="scope">
        {{ scope.row && scope.row.order_no | orderNoFilter }}
      </template>
    </el-table-column>
    <el-table-column label="Nome" width="195" align="center">
      <template slot-scope="scope">
        {{ scope.row && scope.row.name }}
      </template>
    </el-table-column>
    <el-table-column label="Valor" width="195" align="center">
      <template slot-scope="scope">
        R$ {{ scope.row && scope.row.price | toThousandFilter }}
      </template>
    </el-table-column>
    <el-table-column label="Data" width="195" align="center">
      <template slot-scope="scope">
        {{ scope.row && scope.row.date }}
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import { fetchList } from '@/api/order';

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
    this.fetchData();
  },
  methods: {
    async fetchData() {
      const { data } = await fetchList();
      this.list = data.items.slice(0, 8);
      this.loading = false;
    },
  },
};
</script>
