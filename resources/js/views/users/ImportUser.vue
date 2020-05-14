<template>
  <div class="app-container">
    <upload-excel-component :on-success="handleSuccess" :before-upload="beforeUpload" />
    <el-table :data="tableData" border highlight-current-row style="width: 100%;margin-top:20px;">
      <el-table-column v-for="item of tableHeader" :key="item" :prop="item" :label="item" />
    </el-table>
  </div>
</template>

<script>
import request from '@/utils/request';
import UploadExcelComponent from '@/components/UploadExcel/import-user.vue';
import waves from '@/directive/waves'; // Waves directive
export default {
  name: 'UploadExcel',
  components: { UploadExcelComponent },
  directives: { waves },
  data() {
    return {
      tableData: [],
      tableHeader: [],
    };
  },
  methods: {
    beforeUpload(file) {
      const isLt1M = file.size / 1024 / 1024 < 1;

      if (isLt1M) {
        return true;
      }

      this.$message({
        message: 'Please do not upload files larger than 1m in size.',
        type: 'warning',
      });
      return false;
    },
    handleSuccess({ results, header }) {
      this.tableData = results;
      this.tableHeader = header;
      request({
        url: '/usuario/importar-usuario',
        method: 'post',
        data: results,
      })
        .then(function(response) {
        // this.$message({
        //         message: 'add',
        //         type: 'success',
        //         duration: 5 * 1000,
        //       });
          alert('Usuários importados com sucesso');
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
  },
};
</script>
