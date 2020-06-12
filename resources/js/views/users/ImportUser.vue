<template>
  <div class="app-container">
    <xls-csv-parser :columns="columns" :help="help" lang="en" @on-validate="onValidate" />
    <pre>{{ JSON.stringify(results, null, 2) }}</pre>
  </div>
</template>

<script>
import { XlsCsvParser } from 'vue-xls-csv-parser';
export default {
  name: 'App',
  components: {
    XlsCsvParser,
  },
  data() {
    return {
      columns: [
        { name: 'Student login', value: 'login', isOptional: true },
        { name: 'Student firstname', value: 'firstname', isOptional: true },
        { name: 'Student lastname', value: 'lastname', isOptional: true },
        { name: 'Other', value: 'other', isOptional: true },
      ],
      results: null,
      help: 'Necessary columns are: login, firstname and lastname',
    };
  },
  methods: {
    onValidate(results) {
      this.results = results;
    },
  },
};
document.getElementsByClassName('dropzone-title').innerHTML = 'Por favor selecione o arquivo';
</script>
<style lang="scss">
  .catalog-column-chooser {
    display: flex;
    flex-wrap: wrap;
    .header-tool, .text-right {
      flex:100%;
      border:none !important;
    }
    > div {
        padding: 10px 20px;
        margin: 15px;
        border: 2px solid #52BAD4;
        flex-basis: 20%;
        &:first-child {
          margin-left:0;
        }
        &:last-child {
          margin-right:0;
        }
        .panel-heading {
          p {
            margin-top:0;
            font-weight: 700;
            color: #666;
          }
        }
        .panel-body {
          margin-bottom:20px;
        }
        table {
          table-layout: fixed;
          border-collapse: separate;
          width:100%;
          thead {
            color: #909399;
            font-weight: 500;
          }
          .el-table tr {
            background-color: #fff;
          }
          th {
            overflow: hidden;
            user-select: none;
            background-color: #fff;
            padding: 12px 0;
            min-width: 0;
            box-sizing: border-box;
            text-overflow: ellipsis;
            vertical-align: middle;
            position: relative;
            text-align: left;
          }
          td {
            border-bottom: 1px solid #ebeef5;
            padding: 12px 0;
            min-width: 0;
            box-sizing: border-box;
            text-overflow: ellipsis;
            vertical-align: middle;
            position: relative;
            text-align: left;
          }
        }
    }
  }
</style>
