<template>
  <div class="app-container">
    <el-button-group>
      <el-button type="primary" icon="el-icon-plus" @click="createFolder">Nova Pasta</el-button>
    </el-button-group>

    <el-dialog :title="formTitle " :visible.sync="dialogActionVisible" :close-on-click-modal="false" :destroy-on-close="true" :before-close="beforeClose">
      <div v-loading="cupomEditing" class="form-container">
        <el-form ref="currentFolder" :model="currentFolder" :rules="rules" label-position="left" label-width="200px" style="max-width: 600px;">
          <el-form-item ref="nome_input" label="Nome" prop="nome">
            <el-input v-model="currentFolder.nome" />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button>
            Cancelar
          </el-button>
          <el-button type="primary">
            Adicionar
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'Files',
  data() {
    return {
      dialogActionVisible: false,
      currentFolder: {},
    };
  },
  methods: {
    createFolder() {
      this.dialogActionVisible = true;
      this.formTitle = 'Adicionar nova pasta';
    },
  },
};
</script>
<style scoped>

</style>
