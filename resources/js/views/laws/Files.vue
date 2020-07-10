<template>
  <div class="app-container">
    <el-button-group>
      <el-button type="primary" icon="el-icon-plus" @click="createFolder">Nova Pasta</el-button>
    </el-button-group>

    <el-dialog :title="formTitle " :visible.sync="dialogActionVisible" :close-on-click-modal="false" :destroy-on-close="true">
      <div v-loading="folderEditing" class="form-container">
        <el-form ref="currentFolder" :model="currentFolder" :rules="rules" label-position="left" label-width="200px" style="max-width: 600px;">
          <el-form-item ref="nome_input" label="Nome" prop="nome">
            <el-input v-model="currentFolder.nome" />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button>
            Cancelar
          </el-button>
          <el-button type="primary" @click="handleSubmit('currentFolder')">
            Adicionar
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import axios from 'axios';
import { getToken } from '@/utils/auth';
export default {
  name: 'Files',
  data() {
    return {
      dialogActionVisible: false,
      currentFolder: {},
      formTitle: '',
      folderEditing: false,
      rules: {
        nome: [
          { required: true, message: 'Por favor, preencha o nome da pasta', trigger: 'change' },
          { min: 3, max: 50, message: 'Preencha no mínimo 3 caracteres e no máximo 50', trigger: 'change' },
        ],
      },
    };
  },
  methods: {
    createFolder() {
      this.dialogActionVisible = true;
      this.formTitle = 'Adicionar nova pasta';
      this.currentFolder = {
        nome: '',
      };
    },
    handleSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if (this.currentFolder.id !== undefined){
            alert('edit');
          } else {
            axios({
              method: 'post',
              url: `api/v1/bw/controle-de-leis/arquivos-das-leis`,
              headers: {
                'Authorization': 'Bearer ' + getToken(),
              },
              data: {
                nome: this.currentFolder.nome,
              },
            }).then((response) => {
              console.log(response);
            }).catch(error => console.log(error));
          }
        }
      });
    },
  },
};
</script>
<style scoped>

</style>
