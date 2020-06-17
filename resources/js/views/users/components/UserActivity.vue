<template>
  <el-card>
    <el-tabs v-model="activeActivity" @tab-click="handleClick">
      <el-tab-pane label="Timeline" name="first">
        <div class="block">
          <el-timeline>
            <el-timeline-item timestamp="16/05/2020" placement="top">
              <el-card>
                <h4>Renovação assinatura plano mensal</h4>
                <p>16/05/2020 às 20:46</p>
              </el-card>
            </el-timeline-item>
            <el-timeline-item timestamp="16/05/2020" placement="top">
              <el-card>
                <h4>Assinatura plano mensal</h4>
                <p>16/04/2020 às 20:46</p>
              </el-card>
            </el-timeline-item>
            <el-timeline-item timestamp="06/04/2020" placement="top">
              <el-card>
                <h4>Cadastro</h4>
                <p>Cadastro realizado no dia 06/04/2020 às 19:46</p>
              </el-card>
            </el-timeline-item>
          </el-timeline>
        </div>
      </el-tab-pane>
      <el-tab-pane v-loading="updating" label="Minha conta" name="second">
        <el-form ref="user" :model="user.user_profile" :rules="rules">
          <el-form-item required>
            <el-col :span="12">
              <el-form-item label="Nome" prop="name">
                <el-input v-model="user.name" maxlength="191" show-word-limit />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Email" prop="email">
                <el-input v-model="user.email" maxlength="191" show-word-limit />
              </el-form-item>
            </el-col>
          </el-form-item>
          <el-form-item required>
            <el-col :span="12">
              <el-form-item label="Apelido">
                <el-input v-model="user.user_profile.nickname" maxlength="50" show-word-limit />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Senha">
                <el-input maxlength="191" show-word-limit show-password />
              </el-form-item>
            </el-col>
          </el-form-item>
          <el-form-item required>
            <el-col :span="12">
              <el-form-item label="CPF" prop="cpf">
                <el-input v-model="user.user_profile.cpf" maxlength="14" show-word-limit />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="RG" prop="rg">
                <el-input v-model="user.user_profile.rg" maxlength="20" show-word-limit />
              </el-form-item>
            </el-col>
          </el-form-item>
          <el-form-item required>
            <el-col :span="12">
              <el-form-item label="Estado">
                <!-- <el-input v-model="user.user_profile.state_id" /> -->
                <el-input v-model="estado" maxlength="20" show-word-limit />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Cidade">
                <!-- <el-input v-model="user.user_profile.city_id" /> -->
                <el-input v-model="cidade" maxlength="30" show-word-limit />
              </el-form-item>
            </el-col>
          </el-form-item>
          <el-form-item required>
            <el-col :span="6">
              <el-form-item label="Cep" prop="cep">
                <el-input v-model="user.user_profile.cep" maxlength="20" show-word-limit />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Endereço" prop="address">
                <el-input v-model="user.user_profile.address" maxlength="255" show-word-limit />
              </el-form-item>
            </el-col>
            <el-col :span="6">
              <el-form-item label="Número" prop="number_address">
                <el-input v-model="user.user_profile.number_address" maxlength="10" show-word-limit />
              </el-form-item>
            </el-col>
          </el-form-item>
          <el-form-item required>
            <el-col :span="12">
              <el-form-item label="Telefone" prop="telephone">
                <el-input v-model="user.user_profile.telephone" maxlength="30" show-word-limit />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="WhatsApp">
                <el-input v-model="user.user_profile.whatsapp" maxlength="30" show-word-limit />
              </el-form-item>
            </el-col>
          </el-form-item>
          <el-form-item required>
            <el-col :span="12">
              <el-form-item label="Linkedin">
                <el-input v-model="user.user_profile.linkedin" maxlength="100" show-word-limit />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Facebook">
                <el-input v-model="user.user_profile.facebook" maxlength="100" show-word-limit />
              </el-form-item>
            </el-col>
          </el-form-item>
          <el-form-item required>
            <el-col :span="12">
              <el-form-item label="Instagram">
                <el-input v-model="user.user_profile.instagram" maxlength="100" show-word-limit />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Twitter">
                <el-input v-model="user.user_profile.twitter" maxlength="100" show-word-limit />
              </el-form-item>
            </el-col>
          </el-form-item>
          <el-form-item label="Youtube">
            <el-input v-model="user.user_profile.youtube" maxlength="100" show-word-limit />
          </el-form-item>
          <el-form-item label="Sobre mim">
            <el-input v-model="user.user_profile.biography" type="textarea" maxlength="250" show-word-limit />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" :disabled="user.role === 'admin'" @click="onSubmit('user')">
              Atualizar
            </el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
    </el-tabs>
  </el-card>
</template>

<script>
import Resource from '@/api/resource';
const userResource = new Resource('users');

export default {
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          avatar: '',
          roles: [],
        };
      },
    },

  },
  data() {
    return {
      cidade: 'Cascavel',
      estado: 'Paraná',
      activeActivity: 'first',
      updating: false,
      rules: {
        // name: [
        //   { required: true, message: 'Por favor, informe seu nome completo', trigger: 'change' },
        //   { min: 3, message: 'Preencha no mínimo 3 caracteres', trigger: 'change' },
        // ],
        // email: [
        //   { required: true, message: 'Por favor, informe um Email', trigger: 'change' },
        // ],
        cpf: [
          { required: true, message: 'Por favor, informe seu CPF', trigger: 'change' },
        ],
        rg: [
          { required: true, message: 'Por favor, informe o RG', trigger: 'change' },
        ],
        cep: [
          { required: true, message: 'Por favor, informe o Cep', trigger: 'change' },
        ],
        address: [
          { required: true, message: 'Por favor, informe o Endereço', trigger: 'change' },
        ],
        number_address: [
          { required: true, message: 'Por favor, informe o número da casa ou apartamento', trigger: 'change' },
        ],
        telephone: [
          { required: true, message: 'Por favor, informe o número do telefone', trigger: 'change' },
        ],
      },
    };
  },
  methods: {
    handleClick(tab, event) {
      console.log('Switching tab ', tab, event);
    },
    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.updating = true;
          userResource
            .update(this.user.id, this.user)
            .then(response => {
              this.updating = false;
              this.$message({
                message: 'Perfil atualizado com sucesso',
                type: 'success',
                duration: 5 * 1000,
              });
              this.getList();
            })
            .catch(error => {
              console.log(error);
              this.updating = false;
            });
        }
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.user-activity {
  .user-block {
    .username, .description {
      display: block;
      margin-left: 50px;
      padding: 2px 0;
    }
    img {
      width: 40px;
      height: 40px;
      float: left;
    }
    :after {
      clear: both;
    }
    .img-circle {
      border-radius: 50%;
      border: 2px solid #d2d6de;
      padding: 2px;
    }
    span {
      font-weight: 500;
      font-size: 12px;
    }
  }
  .post {
    font-size: 14px;
    border-bottom: 1px solid #d2d6de;
    margin-bottom: 15px;
    padding-bottom: 15px;
    color: #666;
    .image {
      width: 100%;
    }
    .user-images {
      padding-top: 20px;
    }
  }
  .list-inline {
    padding-left: 0;
    margin-left: -5px;
    list-style: none;
    li {
      display: inline-block;
      padding-right: 5px;
      padding-left: 5px;
      font-size: 13px;
    }
    .link-black {
      &:hover, &:focus {
        color: #999;
      }
    }
  }
  .el-carousel__item h3 {
    color: #475669;
    font-size: 14px;
    opacity: 0.75;
    line-height: 200px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }
}
</style>
