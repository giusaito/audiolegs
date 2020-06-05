<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreateForm">
        Adicionar plano
      </el-button>
    </div>

    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="{row}">
          <span> {{ row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column width="130px" align="center" label="Nome do plano">
        <template slot-scope="{row}">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column width="220px" align="center" label="Descrição">
        <template slot-scope="{row}">
          <span>{{ row.description }}</span>
        </template>
      </el-table-column>

      <el-table-column width="80px" align="center" label="Dias">
        <template slot-scope="{row}">
          <span>{{ row.quantity_days }}</span>
        </template>
      </el-table-column>

      <el-table-column width="100px" align="center" label="Valor">
        <template slot-scope="{row}">
          <span>R$ {{ row.price }}</span>
        </template>
      </el-table-column>

      <el-table-column width="180px" align="center" label="Status">
        <template slot-scope="{row}">
          <span>{{ row.status | statusPlans }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Ações" width="300">
        <template slot-scope="scope">
          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleEdit(scope.row.id, scope.row.name, scope.row.description, scope.row.price, scope.row.quantity_days, scope.row.status)">
            Editar
          </el-button>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.id, scope.row.name);">
            Remover
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
    <el-dialog :title="formTitle " :visible.sync="dialogFormVisible">
      <div v-loading="planoEditing" class="form-container">
        <el-form ref="userForm" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="Nome" prop="name">
            <el-input v-model="currentPlan.name" />
          </el-form-item>
        </el-form>
        <el-form ref="userForm" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="Descrição" prop="description">
            <el-input v-model="currentPlan.description" />
          </el-form-item>
        </el-form>
        <el-form ref="userForm" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="Valor" prop="price">
            <el-input v-model="currentPlan.price" />
          </el-form-item>
        </el-form>
        <el-form ref="userForm" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="Dias" prop="days">
            <el-input v-model="currentPlan.days" />
          </el-form-item>
        </el-form>

        <!--  <el-form-item :label="$t('user.role')" prop="role">
          <el-select v-model="currentPlan.status" class="filter-item" placeholder="Please select role">
            <el-option :value="currentPlan.status" />
            <el-option :value="currentPlan.status" />
          </el-select>
        </el-form-item> -->

        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">
            Cancelar
          </el-button>
          <el-button type="primary" @click="handleSubmit()">
            Atualizar
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
// import { fetchPlans } from '@/api/plans';
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';

const PlanResource = new Resource('planos');

export default {
  name: 'Plans',
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
    statusPlans(value){
      if (value === 'PUBLISHED'){
        return 'PUBLICADO';
      } else {
        return 'RASCUNHO';
      }
    },
  },
  data() {
    return {
      list: [],
      jsonParse: {},
      total: 0,
      formTitle: '',
      currentPlan: {},
      dialogFormVisible: false,
      listLoading: true,
      planoEditing: false,
      listQuery: {
        page: 1,
        limit: 10,
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data } = await PlanResource.list({});
      this.total = data.total;
      this.list = data.data;
      this.listLoading = false;
    },

    handleCreateForm() {
      this.dialogFormVisible = true;
      this.formTitle = 'Adicionar novo plano';
      this.currentPlan = {
        name: '',
        description: '',
        price: '',
        days: '',
        status: '',
      };
    },

    handleSubmit() {
      if (this.currentPlan.id !== undefined) {
        PlanResource.update(this.currentPlan.id, this.currentPlan).then(response => {
          this.$message({
            type: 'success',
            message: 'O plano ' + this.currentPlan.name + ' atualizado com sucesso',
            duration: 5 * 1000,
          });
          this.getList();
        }).catch(() => {
          this.$message({
            type: 'error',
            message: 'Ocorreu um erro ao tentar atualizar o plano ' + this.currentPlan.name + ' por favor, tente novamente mais tarde',
            duration: 5 * 1000,
          });
        }).finally(() => {
          console.log('fim');
          this.dialogFormVisible = false;
        });
      } else {
        PlanResource
          .store(this.currentPlan)
          .then(response => {
            this.$message({
              message: 'O plano ' + this.currentPlan.name + ' foi criado com sucesso.',
              type: 'success',
              duration: 5 * 1000,
            });
            this.currentPlan = {
              name: '',
              description: '',
              price: '',
              days: '',
              status: '',
            };
            this.planFormVisible = false;
            this.getList();
          })
          .catch(err => {
            console.log(err);
          }).finally(() => {
            console.log('fim');
            this.dialogFormVisible = false;
          });
      }
    },

    handleEdit(id, name, description, price, days, status){
      this.currentPlan = this.list.find(category => category.id === id);
      this.dialogFormVisible = true;
      this.formTitle = 'Editar plano ' + name;
      this.currentPlan = {
        id: id,
        name: name,
        description: description,
        price: price,
        days: days,
        status: status,
      };
    },

    handleDelete(id, name) {
      this.$confirm('Tem certeza que deseja remover permanentemente o plano ' + name + ' ?', 'ATENÇÃO', {
        confirmButtonText: 'Deletar',
        cancelButtonText: 'Cancelar',
        type: 'warning',
      }).then(() => {
        PlanResource.destroy(id).then(response => {
          this.$message({
            type: 'success',
            message: 'Plano deletado com sucesso',
          });
          this.getList();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Operação cancelada pelo usuário',
        });
      });
    },

    handleFilter() {
      this.listQuery.page = 1;
      this.getList();
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
