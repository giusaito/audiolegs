<template>
  <div class="app-container">
    <!-- FILTROS -->
    <div class="filter-container">
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreatePlaylist">
        Adicionar playlist
      </el-button>
    </div>
    <!-- /fim FILTROS -->
    <div id="playlists">
      <!-- LISTA DE PLAYLISTS -->
      <el-table
        :data="playlists"
        border
        style="width: 100%"
        highlight-current-row
        @current-change="handleEditPlaylist"
      >
        <el-table-column
          prop="name"
          label="Playlist"
          width="380"
        />
        <el-table-column
          prop="author_id"
          label="Responsável"
        >
          <template slot-scope="scope">
            {{ scope.row.user.name }}
          </template>
        </el-table-column>
        <el-table-column
          prop="status"
          label="Status"
        >
          <template slot-scope="scope">
            <el-tag
              :type="scope.row.status === 'PUBLIC' ? 'success' : 'warning'"
              disable-transitions
            >
              <span v-if="scope.row.status === 'PUBLIC'">público</span>
              <span v-if="scope.row.status === 'PRIVATE'">privado</span>
            </el-tag>
          </template>
        </el-table-column>
      </el-table>
      <!-- /fim LISTA DE PLAYLISTS -->
    </div>
    <!-- SIDEBAR -->
    <el-drawer
      ref="drawer"
      :with-header="false"
      :visible.sync="dialog"
      :direction="direction"
      :modal="modal"
      :append-to-body="appendToBody"
      :before-close="cancelForm"
    >
      <!-- HEADER SIDEBAR -->
      <header id="el-drawer__title" class="el-drawer__header el-special-drawer__header">
        <span role="heading" tabindex="0" title="" />
        <el-tag
          :key="status.label"
          :type="status.type"
          :disable-transitions="true"
          effect="dark"
          @click="toggleStatus"
        >
          {{ status.label }}
        </el-tag>
        <button aria-label="close drawer" type="button" class="el-drawer__close-btn" @click="cancelForm">
          <i class="el-dialog__close el-icon el-icon-arrow-right" />
        </button>
      </header>
      <!-- /fim HEADER SIDEBAR -->
      <div class="drawer__content">
        <el-form :model="currentPlaylist">
          <el-form-item>
            <el-input v-model="currentPlaylist.name" autocomplete="off" placeholder="Escreva o nome da Playlist" class="playlist_name" />
          </el-form-item>
          <el-form-item label="Capa" :label-width="formLabelWidth">
            <el-upload
              ref="cover"
              class="cover-uploader"
              action=""
              :multiple="false"
              :limit="1"
              :show-file-list="false"
              :on-success="handleCoverSuccess"
              :on-change="handleCoverPath"
              :before-upload="beforeCoverUpload"
              :auto-upload="false"
            >
              <img v-if="currentPlaylist.cover_image" :src="currentPlaylist.cover_image" class="cover">
              <i v-else class="el-icon-plus cover-uploader-icon" />
            </el-upload>
          </el-form-item>
          <el-form-item label="Descrição" :label-width="formLabelWidth">
            <el-input v-model="currentPlaylist.description" type="textarea" placeholder="Sem descrição definida" />
          </el-form-item>
        </el-form>
        <!-- RODAPÉ SIDEBAR -->
        <div class="drawer__footer">
          <el-button @click="cancelForm">Cancelar</el-button>
          <el-button type="primary" :loading="loading" @click="handleFormSubmit">{{ loading ? 'Salvando ...' : 'Enviar' }}</el-button>
        </div>
        <!-- /fim RODAPÉ SIDEBAR -->
      </div>
    </el-drawer>
    <!-- /fim SIDEBAR -->
  </div>
</template>

<script>
import axios from 'axios';
import { getToken } from '@/utils/auth';

export default {
  data() {
    return {
      loading: false,
      dialog: false,
      direction: 'rtl',
      modal: false,
      appendToBody: true,
      saved: false,
      formLabelWidth: '80px',
      timer: null,
      playlists: [],
      currentPlaylist: {
        name: '',
        description: '',
        cover_image: '',
        status: 'PUBLIC',
        type: 'ADMIN',
      },
      status: {
        type: 'success',
        label: 'Público',
      },
      counter: 1,
    };
  },
  mounted() {
    this.getList();
  },
  methods: {
    handleCreatePlaylist() {
      this.playlistDrawerTitle = 'Adicionar nova playlist';
      this.btnInsertUpdate = 'Adicionar playlist';
      this.playlists.push({
        id: this.counter++,
        name: 'Escreva o nome da Playlist',
        user: {
          name: '---',
        },
        status: 'PUBLIC',
      });
      this.currentPlaylist = {
        name: '',
        description: '',
        cover_image: '',
        status: 'PUBLIC',
        type: 'ADMIN',
      };
      this.saved = false;
      this.dialog = true;
    },
    handleEditPlaylist(val) {
      this.playlistDrawerTitle = 'Editar playlist';
      this.btnInsertUpdate = 'Editar playlist';
      if (val !== null) {
        this.currentPlaylist = val;
        console.warn('=================');
        console.warn(this.currentPlaylist);
        console.warn('=================');
        this.saved = false;
        this.dialog = true;
      }
    },
    handleFormSubmit(done){
      if (this.loading) {
        return;
      }
      this.loading = true;

      // EDITAR PLAYLIST
      if (this.currentPlaylist.id !== undefined){
        this.timer = setTimeout(() => {
          axios({
            method: 'put',
            url: `api/v1/bw/playlist/` + this.currentPlaylist.id,
            headers: {
              'Authorization': 'Bearer ' + getToken(),
            },
            data: this.currentPlaylist,
          }).then((response) => {
            // this.getList();
            setTimeout(() => {
              this.status = {
                type: 'success',
                label: 'Público',
              };
              this.saved = true;
              this.cancelForm();
            }, 400);
          }).catch(error => console.log(error));
        }, 2000);
      // CRIAR PLAYLIST
      } else {
        this.timer = setTimeout(() => {
          axios({
            method: 'post',
            url: `api/v1/bw/playlist`,
            headers: {
              'Authorization': 'Bearer ' + getToken(),
            },
            data: this.currentPlaylist,
          }).then((response) => {
            this.getList();
            setTimeout(() => {
              console.log(this.currentPlaylist);
              this.status = {
                type: 'success',
                label: 'Público',
              };
              this.saved = true;
              this.cancelForm();
            }, 400);
          }).catch(error => console.log(error));
        }, 2000);
      }
    },
    handleCoverSuccess(res, file) {
      console.log(file);
      // this.currentPlaylist.cover_image = URL.createObjectURL(file.raw);
    },
    handleCoverPath(file, fileList) {
      // Certificate upload component on-change event
      // this.cert_path = fileList;
      console.log(file);
      this.currentPlaylist.cover_image = URL.createObjectURL(file.raw);
      // this.currentPlaylist.cover_image = file;
    },
    beforeCoverUpload(file) {
      const isJPG = file.type === 'image/jpeg';
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error('A imagem de capa deve ser no formato JPG!');
      }
      if (!isLt2M) {
        this.$message.error('O tamanho da imagem de capa não pode exceder os 2MB!');
      }
      return isJPG && isLt2M;
    },
    toggleStatus(){
      if (this.status.type === 'success') {
        this.status = { type: 'warning', label: 'Privado' };
        this.currentPlaylist.status = 'PRIVATE';
        this.playlists[this.playlists.length - 1].status = 'PRIVATE';
      } else {
        this.status = { type: 'success', label: 'Público' };
        this.currentPlaylist.status = 'PUBLIC';
        this.playlists[this.playlists.length - 1].status = 'PUBLIC';
      }
    },
    cancelForm() {
      this.loading = false;
      this.dialog = false;
      clearTimeout(this.timer);
      if (this.saved === false && this.currentPlaylist.id === undefined) {
        this.playlists.pop();
      }
    },
    async getList(id = null) {
      this.loading = true;
      var urlList = `api/v1/bw/playlist/lista`;
      axios({
        method: 'get',
        url: urlList,
        headers: {
          'Authorization': 'Bearer ' + getToken(),
        },
      }).then((response) => {
        // console.log(response);
        this.playlists = response.data.data;
        console.log(this.playlists);
        this.loading = false;
      }).catch(error => console.log(error));
    },
  },
};
</script>
