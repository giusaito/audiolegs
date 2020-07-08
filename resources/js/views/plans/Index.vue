<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreateForm">
        Adicionar plano
      </el-button>
    </div>

    <el-table v-loading="listLoading" :data="list" style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="{row}">
          <span> {{ row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Nome do plano">
        <template slot-scope="{row}">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Descrição">
        <template slot-scope="{row}">
          <span>{{ row.description }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Dias">
        <template slot-scope="{row}">
          <span>{{ row.quantity_days }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Valor">
        <template slot-scope="{row}">
          <span>R$ {{ row.price }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Status">
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
    <el-dialog :title="formTitle" :close-on-click-modal="false" :visible.sync="dialogFormVisible">
      <div v-loading="planoEditing" class="form-container">
        <el-form ref="currentPlan" :model="currentPlan" :rules="rules" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="Nome" prop="name">
            <el-input v-model="currentPlan.name" />
          </el-form-item>

          <el-form-item label="Descrição" prop="description">
            <el-input v-model="currentPlan.description" />
          </el-form-item>

          <el-form-item label="Valor" prop="price">
            <el-input v-model="currentPlan.price" />
          </el-form-item>

          <el-form-item label="Dias" prop="days">
            <el-input v-model="currentPlan.days" />
          </el-form-item>

          <el-form-item label="Status" prop="status">
            <el-switch
              v-model="currentPlan.statusSwitch"
              active-color="#13ce66"
              inactive-color="#ff4949"
            />
          </el-form-item>
        </el-form>

        <div slot="footer" class="dialog-footer">
          <el-button @click="resetForm('ruleForm')">
            Cancelar
          </el-button>
          <el-button type="primary" @click="handleSubmit('currentPlan')">
            {{ btnInsertUpdate }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
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
        return 'Publicado';
      } else {
        return 'Rascunho';
      }
    },
  },
  data() {
    return {
      list: [],
      total: 0,
      formTitle: '',
      currentPlan: {},
      dialogFormVisible: false,
      listLoading: true,
      btnInsertUpdate: '',
      planoEditing: false,
      listQuery: {
        page: 1,
        limit: 10,
      },
      rules: {
        name: [
          { required: true, message: 'Por favor, preencha o nome do plano', trigger: 'change' },
          { min: 3, max: 50, message: 'Preencha no mínimo 3 caracteres e no máximo 50', trigger: 'change' },
        ],
        description: [
          { required: true, message: 'Por favor, informe uma descrição curta', trigger: 'change' },
          { min: 3, max: 50, message: 'Preencha no mínimo 3 caracteres e no máximo 50', trigger: 'change' },
        ],
        price: [
          { required: true, message: 'Por favor, informe o valor do plano', trigger: 'change' },
        ],
        days: [
          { required: true, message: 'Por favor, informe por quantos dias a assinatura é válida', trigger: 'change' },
        ],
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
      this.btnInsertUpdate = 'Adicionar plano';
      this.currentPlan = {
        name: '',
        description: '',
        price: '',
        days: '',
        statusSwitch: true,
      };
    },
    handleSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if (this.currentPlan.id !== undefined){
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
        }
      });
    },
    handleEdit(id, name, description, price, days, status){
      this.currentPlan = this.list.find(category => category.id === id);
      this.dialogFormVisible = true;
      this.formTitle = 'Editar plano ' + name;
      this.btnInsertUpdate = 'Atualizar plano';
      var statusSwitch = status;
      if (statusSwitch === 'PUBLISHED') {
        statusSwitch = true;
      } else {
        statusSwitch = false;
      }
      this.currentPlan = {
        id: id,
        name: name,
        description: description,
        price: price,
        days: days,
        status: status,
        statusSwitch: statusSwitch,
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
          message: 'Operação cancelada!',
        });
      });
    },
    resetForm(formName) {
      this.dialogFormVisible = false;
      this.$refs[formName].resetFields();
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
