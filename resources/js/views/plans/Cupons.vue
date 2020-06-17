<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreateForm">
        Adicionar cupom de desconto
      </el-button>
    </div>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
    <el-dialog :title="formTitle " :visible.sync="dialogFormVisible">
      <div v-loading="cupomEditing" class="form-container">
        <el-form ref="currentVoucher" :model="currentVoucher" :rules="rules" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="Chave" prop="chave">
            <el-input v-model="currentVoucher.chave" />
            <p class="text-danger" v-if="hasError('chave')" v-html="first('chave')"></p>
          </el-form-item>
          <el-form-item label="Desconto" prop="desconto">
            <el-input v-model="currentVoucher.desconto" />
          </el-form-item>
          <el-form-item label="Porcentagem" prop="desconto_porcentagem">
            <el-input v-model="currentVoucher.desconto_porcentagem" />
          </el-form-item>
         <!--  <el-form-item label="Descrição" prop="desconto">
            <el-input v-model="currentVoucher.desconto" />
          </el-form-item>

          <el-form-item label="Valor" prop="price">
            <el-input v-model="currentVoucher.price" />
          </el-form-item>

          <el-form-item label="Dias" prop="days">
            <el-input v-model="currentVoucher.days" />
          </el-form-item> -->

          <el-form-item label="Status" prop="status">
            <el-switch
              v-model="currentVoucher.statusSwitch"
              active-color="#13ce66"
              inactive-color="#ff4949"
            />
          </el-form-item>
        </el-form>

        <div slot="footer" class="dialog-footer">
          <el-button @click="resetForm('ruleForm')">
            Cancelar
          </el-button>
          <el-button type="primary" @click="handleSubmit('currentVoucher')">
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
const VoucherResource = new Resource('cupons');
export default {
  name: 'Vouchers',
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
    statusVouchers(value){
      if (value === 'PUBLISHED'){
        return 'Ativo';
      } else {
        return 'Inativo';
      }
    },
  },
  data() {
    return {
      list: [],
      total: 0,
      formTitle: '',
      currentVoucher: {},
      dialogFormVisible: false,
      listLoading: true,
      btnInsertUpdate: '',
      cupomEditing: false,
      listQuery: {
        page: 1,
        limit: 10,
      },
      rules: {
        chave: [
          { required: true, message: 'Por favor, preencha com a chave (nome) do cupom', trigger: 'change' },
          { min: 3, max: 50, message: 'Preencha no mínimo 3 caracteres e no máximo 50', trigger: 'change' },
        ],
        desconto_porcentagem: [
          { required: true, message: 'Por favor, informe o valor do cupom', trigger: 'change' },
        ],
        // days: [
        //   { required: true, message: 'Por favor, informe por quantos dias a assinatura é válida', trigger: 'change' },
        // ],
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data } = await VoucherResource.list({});
      this.total = data.total;
      this.list = data.data;
      this.listLoading = false;
    },
    handleCreateForm() {
      this.dialogFormVisible = true;
      this.formTitle = 'Adicionar novo cupom';
      this.btnInsertUpdate = 'Adicionar cupom';
      this.currentVoucher = {
        chave: this.randomString(12, '#AA'),
        desconto: '',
        desconto_porcentagem: '',
        days: '',
        statusSwitch: true,
      };
    },
    handleSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if (this.currentVoucher.id !== undefined){
            VoucherResource.update(this.currentVoucher.id, this.currentVoucher).then(response => {
              this.$message({
	              type: 'success',
                message: 'O cupom ' + this.currentVoucher.chave + ' atualizado com sucesso',
                duration: 5 * 1000,
              });
              this.getList();
            }).catch(() => {
              this.$message({
                type: 'error',
                message: 'Ocorreu um erro ao tentar atualizar o cupom ' + this.currentVoucher.chave + ' por favor, tente novamente mais tarde',
                duration: 5 * 1000,
              });
            }).finally(() => {
              console.log('fim');
              this.dialogFormVisible = false;
            });
          } else {
            VoucherResource.store(this.currentVoucher).then(response => {
              this.$message({
                message: 'O cupom ' + this.currentVoucher.chave + ' foi criado com sucesso.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.currentVoucher = {
                name: '',
                desconto: '',
                desconto_porcentagem: '',
                days: '',
                status: '',
              };
              this.planFormVisible = false;
              this.getList();
            }).catch(err => {
              console.log(err);
            }).finally(() => {
              console.log('fim');
              this.dialogFormVisible = false;
            });
          }
        }
      });
    },
    handleEdit(id, chave, desconto, desconto_porcentagem, days, status){
      this.currentVoucher = this.list.find(category => category.id === id);
      this.dialogFormVisible = true;
      this.formTitle = 'Editar cupom ' + chave;
      this.btnInsertUpdate = 'Atualizar cupom';
      var statusSwitch = status;
      if (statusSwitch === 'PUBLISHED') {
        statusSwitch = true;
      } else {
        statusSwitch = false;
      }
      this.currentVoucher = {
        id: id,
        chave: chave,
        desconto: desconto,
        desconto_porcentagem: desconto_porcentagem,
        days: days,
        status: status,
        statusSwitch: statusSwitch,
      };
    },
    handleDelete(id, chave) {
      this.$confirm('Tem certeza que deseja remover permanentemente o cupom ' + chave + ' ?', 'ATENÇÃO', {
        confirmButtonText: 'Deletar',
        cancelButtonText: 'Cancelar',
        type: 'warning',
      }).then(() => {
        VoucherResource.destroy(id).then(response => {
          this.$message({
            type: 'success',
            message: 'Cupom deletado com sucesso',
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
    randomString(length, chars) {
	    var mask = '';
	    if (chars.indexOf('a') > -1) {
	    	mask += 'abcdefghijklmnopqrstuvwxyz';
	    }
	    if (chars.indexOf('A') > -1) {
	    	mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    }
	    if (chars.indexOf('#') > -1) {
	    	mask += '0123456789';
	    }
	    if (chars.indexOf('!') > -1) {
	    	mask += '~`!@#$%^&*()_+-={}[]:";\'<>?,./|\\';
	    }
	    var result = '';
	    for (var i = length; i > 0; --i) {
	    	result += mask[Math.round(Math.random() * (mask.length - 1))];
	    }
	    return result;
    },
    hasError(field) {
        for (var key in this.$store.state.errors) {
          if (key === field) {
            return true
          }
        }
      return false
    },
    first(field) {
      return this.$store.state.errors[field][0]
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
