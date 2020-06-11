<template>
  <div class="app-container">
    <xls-csv-parser :columns="columns" :help="help" lang="ptBR" @on-validate="onValidate" />
    <!-- <pre>{{ JSON.stringify(results, null, 2) }}</pre> -->
    <simplert
      ref="simplert"
      :use-radius="true"
      :use-icon="true"
    />
  </div>
</template>

<script>
import { XlsCsvParser } from 'vue-xls-csv-parser';
import request from '@/utils/request';
import Simplert from 'vue2-simplert';
export default {
  name: 'App',
  components: {
    XlsCsvParser,
    Simplert,
  },
  data() {
    return {
      columns: [
        { name: 'Nome', value: 'nome', isOptional: true },
        { name: 'E-mail', value: 'email', isOptional: true },
        { name: 'Telefone', value: 'telefone', isOptional: true },
      ],
      results: null,
      // help: 'Campos necessários: login, firstname and lastname',
      help: 'Campos necessários: Nome, E-mail e Telefone',
    };
  },
  methods: {
    onValidate(results) {
      const newArray = [];
      const uniqueObject = {};
      let objTitle = '';
      let i;
      i = 0;
      for (i in results) {
        objTitle = results[i]['column'];
        uniqueObject[objTitle] = results[i];
      }
      for (i in uniqueObject) {
        newArray.push(uniqueObject[i]);
      }
      // console.log(newArray);
      const sucessoModal = {
        title: 'Sucesso!',
        message: 'Usuários importados com sucesso',
        type: 'success',
        onClose: this.onClose,
      };
      const falhaModal = {
        title: 'Ops!',
        message: 'Ocorreu um erro em salvar os usuários',
        type: 'info',
        onClose: this.onClose,
      };
      var refsModal = this.$refs;
      request({
        url: '/v1/bw/usuario/importar-usuario',
        method: 'post',
        data: newArray,
      }).then(function(response) {
        // alert('Usuários importados com sucesso');
        refsModal.simplert.openSimplert(sucessoModal);
        // console.log(response);
      }).catch(function(error) {
        if (error) {
          console.log(error.stack);
        }
        refsModal.simplert.openSimplert(falhaModal);
        // console.log(error);
      });

      this.results = results;
    },
  },
};
</script>
<style lang="scss">
  .catalog-column-chooser {
    display: flex;
    position:relative;
    .header-tool, .text-right {
      flex:100%;
      border:none !important;
    }
    .header-tool {
      position: absolute;
      right: 0;
      top: -73px;
      @media(max-width:484px){
        position:static;
      }
    }
    .text-right {
      position: absolute;
      bottom: -50px;
      right: 0;
    }
    > div {
      padding: 10px 20px;
      margin: 15px;
      border: 2px solid #52BAD4;
      flex-basis: 31.555%;
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
      &.text-right {
        padding-left:0 !important;
        padding-right:0 !important;
      }
    }
    @media(max-width:991px){
      flex-wrap: wrap;
      > div {
        flex-basis: 100%;
      }
    }
  }
</style>
