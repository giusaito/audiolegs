<template>
  <div class="dashboard-editor-container">
    <!-- <github-corner style="position: absolute; top: 0px; border: 0; right: 0;" /> -->

    <panel-group @handleSetLineChartData="handleSetLineChartData" />

    <el-row style="background:#fff;padding:16px 16px 0;margin-bottom:32px;">
      <p class="text-center">Assinatura / Cancelamento</p>
      <line-chart :chart-data="lineChartData" />
    </el-row>

    <el-row style="background:#fff;padding:16px 16px 0;margin-bottom:32px;">
      <p class="text-center">Usuários / Planos</p>
      <user-line-chart :chart-data="userChartData" />
    </el-row>
    <el-row style="background:#fff;padding:16px 16px 0;margin-bottom:32px;">
      <p class="text-center">Horario mais acessado</p>
      <hourHeatMap />
    </el-row>

    <el-row :gutter="32">
      <!-- <el-col :xs="24" :sm="24" :lg="8">
        <div class="chart-wrapper">
          <raddar-chart />
        </div>
      </el-col> -->
      <el-col :xs="24" :sm="24" :lg="12">
        <div class="chart-wrapper">
          <p class="text-center">5 leis mais acessadas</p>
          <pie-chart />
          <router-link :to="'/relatorios/leis-mais-acessadas'" class="bw-btn bw-btn-all">
            Veja mais
          </router-link>
        </div>
      </el-col>
      <!-- <el-col :xs="24" :sm="24" :lg="12">
        <div class="chart-wrapper">
          <p class="text-center">Usuários ativos em cada plano</p>
          <bar-chart />
          <router-link :to="'/relatorios/planos-usuarios'" class="bw-btn bw-btn-all">
            Veja mais
          </router-link>
        </div>
      </el-col> -->
      <el-col :xs="24" :sm="24" :lg="12">
        <div class="chart-wrapper">
          <p class="text-center">Horário mais acessado do dia</p>
          <PieChartHour />
          <router-link :to="'/relatorios/hora-acessada'" class="bw-btn bw-btn-all">
            Veja mais
          </router-link>
        </div>
      </el-col>
    </el-row>

    <el-row :gutter="8">
      <el-col :xs="{span: 24}" :sm="{span: 24}" :md="{span: 24}" :lg="{span: 24}" :xl="{span: 24}" style="padding-right:8px;margin-bottom:-30px;">
        <el-card class="box-card-component">
          <transaction-table />
          <router-link :to="'/relatorios/transacoes'" class="bw-btn bw-btn-all" style="margin-top:10px;">
            Veja mais
          </router-link>
        </el-card>
      </el-col>
      <!-- <el-col :xs="{span: 24}" :sm="{span: 12}" :md="{span: 12}" :lg="{span: 6}" :xl="{span: 6}" style="margin-bottom:30px;">
        <todo-list />
      </el-col> -->
      <!-- <el-col :xs="{span: 24}" :sm="{span: 12}" :md="{span: 12}" :lg="{span: 6}" :xl="{span: 6}" style="margin-bottom:30px;">
        <box-card />
      </el-col> -->
    </el-row>
  </div>
</template>

<script>
// import GithubCorner from '@/components/GithubCorner';
import PanelGroup from './components/PanelGroup';
import LineChart from './components/LineChart';
import UserLineChart from './components/UserPlanLineChart';
import hourHeatMap from './components/HourHeatMap';
// import RaddarChart from './components/RaddarChart';
import PieChart from './components/PieChart';
// import BarChart from './components/BarChart';
import PieChartHour from './components/PieChartHourAcess';
import TransactionTable from './components/TransactionTable';
// import TodoList from './components/TodoList';
// import BoxCard from './components/BoxCard';

const lineChartData = {
  newVisitis: {
    expectedData: [300, 205, 209, 260, 268, 280, 275],
    actualData: [120, 82, 91, 121, 60, 10, 19],
  },
  messages: {
    expectedData: [200, 192, 120, 144, 160, 130, 140],
    actualData: [180, 160, 151, 106, 145, 150, 130],
  },
  purchases: {
    expectedData: [80, 100, 121, 104, 105, 90, 100],
    actualData: [120, 90, 100, 138, 142, 130, 130],
  },
  shoppings: {
    expectedData: [130, 140, 141, 142, 145, 150, 160],
    actualData: [120, 82, 91, 154, 162, 140, 130],
  },
};

const userChartData = {
  planUser: {
    planOne: [500, 205, 209, 260, 268, 280, 275],
    planTwo: [400, 190, 82, 121, 60, 10, 19],
    planThree: [300, 162, 85, 356, 90, 70, 190],
  },
};

export default {
  name: 'DashboardAdmin',
  components: {
    // GithubCorner,
    PanelGroup,
    LineChart,
    UserLineChart,
    hourHeatMap,
    // RaddarChart,
    PieChart,
    // BarChart,
    PieChartHour,
    TransactionTable,
    // TodoList,
    // BoxCard,
  },
  data() {
    return {
      lineChartData: lineChartData.newVisitis,
      userChartData: userChartData.planUser,
    };
  },
  methods: {
    handleSetLineChartData(type) {
      this.lineChartData = lineChartData[type];
    },
    handleSetUserChartData(type) {
      this.userChartData = userChartData[type];
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.dashboard-editor-container {
  padding: 32px;
  background-color: rgb(240, 242, 245);
  .chart-wrapper {
    background: #fff;
    padding: 16px 16px 0;
    margin-bottom: 32px;
  }
}
.text-center {
  text-align: center !important;
}
.bw-btn {
  display: inline-block;
  line-height: 1;
  white-space: nowrap;
  cursor: pointer;
  background: #FFFFFF;
  border: 1px solid #DCDFE6;
  border-color: #DCDFE6;
  color: #606266;
  -webkit-appearance: none;
  text-align: center;
  box-sizing: border-box;
  outline: none;
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
  padding: 3px;
  border-radius: 6px;
  margin-bottom: 10px;
}
.bw-btn-all {
  color: #FFFFFF !important;
  background-color: #dbb200 !important;
  border-color: #dbb200 !important;
}
</style>
