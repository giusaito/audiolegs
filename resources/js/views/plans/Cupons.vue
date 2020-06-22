<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreateForm">
        Adicionar cupom de desconto
      </el-button>
    </div>

    <el-table v-loading="listLoading" :data="cupons" style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="{row}">
          <span> {{ row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Chave">
        <template slot-scope="{row}">
          <span>{{ row.chave }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Desconto">
        <template slot-scope="{row}">
          <span>
            <span v-if="row.desconto" class="cupom-desconto">
              <el-button size="mini" type="warning" plain round>R$ {{ formatPrice(row.desconto) }}</el-button>
            </span>
            <span v-if="row.desconto_porcentagem" class="cupom-desconto-porc">
              <el-button size="mini" type="success" plain round>{{ row.desconto_porcentagem }}%</el-button>
            </span>
          </span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Quantidade">
        <template slot-scope="{row}">
          <span>
            <span v-if="row.quantidade_total > 0">
              <el-button-group>
                <el-tooltip class="item" effect="dark" content="Quantidade total" placement="top-start">
                  <el-button size="mini" type="success" round>{{ row.quantidade_total }}</el-button>
                </el-tooltip>
                <el-tooltip class="item" effect="dark" content="Quantidade usada" placement="top-start">
                  <el-button size="mini" type="warning" round>{{ row.quantidade_usado-row.quantidade_total }}</el-button>
                </el-tooltip>
              </el-button-group>
            </span>
            <span v-else> - </span>
          </span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Status">
        <template slot-scope="{row}">
          <span>{{ row.status | statusVouchers }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Ações" width="300">
        <template slot-scope="scope">
          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleEdit(scope.row.id, scope.row.chave, scope.row.desconto, scope.row.desconto_porcentagem, scope.row.quantidade_total, scope.row.quantidade_usado, scope.row.status)">
            Editar
          </el-button>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.id, scope.row.chave);">
            Remover
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>10" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

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
                @change="toggleDescontoMandatory"
              />
            </el-col>
            <el-col :span="11">
              <el-form-item ref="desconto_input" prop="desconto">
                <el-input v-model="currentVoucher.desconto" v-money="money" placeholder="Valor (R$)" :disabled="tipo_desconto" />
              </el-form-item>
            </el-col>
            <el-col class="line" :span="2">-</el-col>
            <el-col :span="11">
              <el-form-item ref="desconto_porcentagem_input" prop="desconto_porcentagem">
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
              type="datetimerange"
              format="dd/MM/yyyy HH:mm:ss"
              value-format="yyyy-MM-dd HH:mm:ss"
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
const Str = require('@supercharge/strings');
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
      cupons: [],
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
      descontoMandatory: false,
      descontoPorcentagemMandatory: false,
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
          required: this.descontoMandatory,
          validator: this.customValidator,
          trigger: 'blur',
        },
        desconto_porcentagem: {
          required: this.descontoPorcentagemMandatory,
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
      this.cupons = data.data;
      this.listLoading = false;
    },
    toggleDescontoMandatory() {
      if (this.tipo_desconto === true) {
        this.descontoPorcentagemMandatory = true;
        this.descontoMandatory = false;
      } else {
        this.descontoPorcentagemMandatory = false;
        this.descontoMandatory = true;
      }
      setTimeout(() => this.$refs.desconto_input.clearValidate(), 0);
      setTimeout(() => this.$refs.desconto_porcentagem_input.clearValidate(), 0);
    },
    customValidator(rule, value, callback) {
      console.log(rule);
      if (rule.field === 'desconto' && value === 'R$ 0,00') {
        callback(new Error('Insira um valor (R$)'));
      }
      if ((rule.field === 'desconto' && this.descontoMandatory === true) || (rule.field === 'desconto_porcentagem' && this.descontoPorcentagemMandatory === true)) {
        rule.required = true;
      }
      if (!value && rule.required) {
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
      const errors = [];
      // const errors = [{
      //   'message': 'email address is invalid',
      //   'path': ['desconto'],
      // },
      // {
      //   'message': 'example error for password field',
      //   'path': ['password'],
      // },
      // ];
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
        chave: Str.random(10), // Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5), // this.randomString(12, '#AA')
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
                chave: '',
                desconto: '',
                desconto_porcentagem: '',
                quantidade_total: 0,
                data_expiracao: '',
                statusSwitch: true,
              };
              this.planFormVisible = false;
              this.getList();
            }).catch(err => {
              console.log('quase');
              console.log(err);
            }).finally(() => {
              console.log('fim');
              this.dialogFormVisible = false;
            });
          }
        }
      });
    },
    handleEdit(id, chave, desconto, desconto_porcentagem, quantidade_total, quantidade_usado, status){
      this.currentVoucher = this.cupons.find(category => category.id === id);
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
        quantidade_total: quantidade_total,
        quantidade_usado: quantidade_usado,
        status: status,
        statusSwitch: statusSwitch,
      };
      console.log(this.currentVoucher);
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
    formatPrice(value) {
      const val = (value / 1).toFixed(2).replace('.', ',');
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
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
  .item {
    margin-bottom: 18px;
  }
  hr {
    background: #ccc !important;
    border: 1px solid #eee;
  }
  .el-button-group button {
      margin-bottom:3px;
  }
  small {
    font-size: 11px !important;
    color: #999;
    font-weight: 700;
  }
</style>
