<template>
  <div class="app-container">
    <div class="plans-choose">
      <h2 class="step"><small>1</small> Escolha o Plano:</h2>
      <div class="plans">
        <div v-for="plano in planos" :key="plano.id" v-loading="listLoading" class="box-plan el-card box-card is-always-shadow" :plano="plano.id">
          <div class="el-card__header">{{ plano.name }}</div>
          <div class="el-card__body">
            <el-switch
              v-model="planos[planChoose(planos, plano.id)].value"
              active-color="#13ce66"
              @change="activePlan(planos, plano.id)"
            />
          </div>
        </div>
      </div>
    </div>
    <xls-csv-parser :columns="columns" :help="help" @on-validate="onValidate" />
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
import Resource from '@/api/resource';
const PlanResource = new Resource('planos');
export default {
  name: 'App',
  components: {
    XlsCsvParser,
    Simplert,
  },
  data() {
    return {
      columns: [
        { name: 'Nome', value: 'name', isOptional: true },
        { name: 'E-mail', value: 'email', isOptional: true },
        { name: 'Telefone', value: 'main_telephone', isOptional: true },
      ],
      results: null,
      // help: 'Campos necessários: login, firstname and lastname',
      help: 'Campos necessários: Nome, E-mail e Telefone',
      listLoading: true,
      planos: [],
      listQuery: {
        page: 1,
        limit: 10,
      },
    };
  },
  created() {
    this.getPlans();
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
        console.log(response);
      }).catch(function(error) {
        if (error) {
          console.log(error.stack);
        }
        refsModal.simplert.openSimplert(falhaModal);
        // console.log(error);
      });
      this.results = results;
    },
    async getPlans() {
      this.listLoading = true;
      const { data } = await PlanResource.list({});
      this.total = data.total;
      this.planos = data.data;
      this.listLoading = false;
    },
    planChoose(list, id) {
      // alert(id);
      return list.findIndex((e) => e.id === id);
    },
    activePlan(list, id){
      for (var i = 0; i < list.length; i++) {
        if (list[i].id !== id){
          // this.planChoose = true;
          console.log(list[i].id);
        }
      }
      return true;
    },
  },
};
</script>
<style lang="scss">
  .plans {
    display:flex;
    justify-content:space-between;
    .box-plan {
      width: calc(33.3333% - 15px);
      margin: 0 0 15px 15px;
    }
  }
  h2.step {
    small {
      font-weight: 700;
      color: #dbb200;
      display: inline-block;
      &:after {
        content:") ";
      }
    }
  }
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
