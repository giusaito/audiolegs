<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreateForm">
        Adicionar cupom de desconto
      </el-button>
    </div>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
    <el-dialog :title="formTitle " :visible.sync="dialogFormVisible" :close-on-click-modal="false">
      <div v-loading="cupomEditing" class="form-container">
        <el-form ref="currentVoucher" :model="currentVoucher" :rules="rules" label-position="left" label-width="200px" style="max-width: 600px;">
          <el-form-item label="Chave" prop="chave">
            <el-input v-model="currentVoucher.chave" />
          </el-form-item>
          <el-form-item label="Desconto">
            <el-col :span="24">
              <el-switch
                v-model="tipo_desconto"
                active-text="%"
                inactive-text="R$"
                active-color="#999"
                inactive-color="#999"
              />
            </el-col>
            <el-col :span="11">
              <el-form-item prop="desconto">
                <el-input v-model="currentVoucher.desconto" v-money="money" placeholder="Valor (R$)" :disabled="tipo_desconto" />
              </el-form-item>
            </el-col>
            <el-col class="line" :span="2">-</el-col>
            <el-col :span="11">
              <el-form-item prop="desconto_porcentagem">
                <el-input v-model="currentVoucher.desconto_porcentagem" v-mask="'9{1,3}'" placeholder="Valor (%)" :disabled="!tipo_desconto">
                  <i slot="suffix" class="el-input__icon">%</i>
                </el-input>
              </el-form-item>
            </el-col>
          </el-form-item>
          <el-form-item label="Quantidade de utilização">
            <el-switch
              v-model="quantidade_total_checked"
              active-text="Habilitar"
              inactive-text="Desabilitar"
            />
            <el-slider
              v-show="quantidade_total_checked"
              v-model="currentVoucher.quantidade_total"
              :max="5000"
              show-input
              :disabled="!quantidade_total_checked"
            />
          </el-form-item>
          <el-form-item label="Data de expiração">
            <el-switch
              v-model="data_expiracao_checked"
              active-text="Habilitar"
              inactive-text="Desabilitar"
            />
            <el-date-picker
              v-show="data_expiracao_checked"
              v-model="currentVoucher.data_expiracao"
              type="daterange"
              format="dd/MM/yyyy"
              range-separator="-"
              start-placeholder="Início"
              end-placeholder="Fim"
              :disabled="!data_expiracao_checked"
            />
          </el-form-item>
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
import Inputmask from 'inputmask';
import { VMoney } from 'v-money';
import { getToken } from '@/utils/auth';
import axios from 'axios';
const VoucherResource = new Resource('cupons');
// Vue.directive('mask', {
//     bind: function (el, binding) {
//         Inputmask(binding.value).mask(el.getElementsByTagName('INPUT')[0])
//     }
// });
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
  directives: {
    money: VMoney,
    mask: {
      bind: function(el, binding) {
        Inputmask(binding.value).mask(el.getElementsByTagName('INPUT')[0]);
      },
    },
  },
  props: {
    // eslint-disable-next-line
    max: {
      type: Number,
      required: false,
    },
  },
  data() {
    return {
      list: [],
      total: 0,
      formTitle: '',
      currentVoucher: {},
      quantidade_total_checked: false,
      data_expiracao_checked: false,
      tipo_desconto: false,
      dialogFormVisible: false,
      listLoading: true,
      btnInsertUpdate: '',
      cupomEditing: false,
      baseUrlApi: '/api/v1/bw/cupons',
      listQuery: {
        page: 1,
        limit: 10,
      },
      rules: {
        chave: {
          required: true,
          validator: this.customValidator,
          trigger: 'blur',
        },
        desconto: {
          required: false,
          validator: this.customValidator,
          trigger: 'blur',
        },
        desconto_porcentagem: {
          required: false,
          validator: this.customValidator,
          trigger: 'blur',
        },
      },
      money: {
        decimal: ',',
        thousands: '.',
        prefix: 'R$ ',
        precision: 2,
        masked: false,
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
    customValidator(rule, value, callback) {
      console.log(rule);
      if (!value) {
        callback(new Error('O campo é obrigatório'));
      }

      if (rule.field === 'chave'){
        console.log(value);
        axios.get(this.baseUrlApi + '/buscar-chave?chave=' + value, {
          headers: {
            'Authorization': 'Bearer ' + getToken(),
          },
        }).then(response => {
          if (response.data.count === 1) {
            callback(new Error('Esta chave já está cadastrada no sistema. Por favor, tente outra.'));
          }
        });
      }
      const errors = [{
        'message': 'email address is invalid',
        'path': ['desconto'],
      },
      {
        'message': 'example error for password field',
        'path': ['password'],
      },
      ];
      setTimeout(() => {
        this.errors = errors;

        if (this.isErrorForField(rule.fullField, this.errors)) {
          callback(new Error(this.getErrorForField(rule.fullField, this.errors)));
        }
        callback();
      }, 500);
    },
    isErrorForField(field, errors) {
      if (!errors && !errors.length) {
        return false;
      }
      const filtered = errors.filter(error => {
        return error.path[0] === field;
      });
      if (filtered.length) {
        return filtered;
      }
    },
    getErrorForField(field, errors) {
      if (!errors && !errors.length) {
        return false;
      }
      const filtered = errors.filter(error => {
        return error.path[0] === field;
      });
      if (filtered.length) {
        return filtered[0].message;
      }
    },
    supportGlobalErrorMessage() {
      this.errors.forEach(error => {
        if (!error.path.length) {
          this.$message({
            message: error.message,
            type: 'error',
          });
        }
      });
    },
    handleCreateForm() {
      this.dialogFormVisible = true;
      this.formTitle = 'Adicionar novo cupom';
      this.btnInsertUpdate = 'Adicionar cupom';
      this.currentVoucher = {
        chave: this.randomString(12, '#AA'),
        desconto: '',
        desconto_porcentagem: '',
        quantidade_total: 0,
        data_expiracao: '',
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
  .line {
    text-align: center;
  }
</style>
