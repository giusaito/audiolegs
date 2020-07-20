<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreatePlaylist">
        Adicionar playlist
      </el-button>
    </div>
    <div id="playlists">
      <el-table :data="playlists" border style="width: 100%">
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
    </div>
    <el-drawer
      ref="drawer"
      :with-header="false"
      :visible.sync="dialog"
      :direction="direction"
      :modal="modal"
      :append-to-body="appendToBody"
      :before-close="handleFormSubmit"
    >
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
      <div class="drawer__content">
        <el-form :model="currentPlaylist">
          <el-form-item>
            <el-input v-model="currentPlaylist.name" autocomplete="off" placeholder="Escreva o nome da Playlist" class="playlist_name" @input="playlists[playlists.length - 1].name = currentPlaylist.name" />
          </el-form-item>
          <el-form-item label="Capa" :label-width="formLabelWidth">
            <el-upload
              ref="cover"
              class="cover-uploader"
              action="https://jsonplaceholder.typicode.com/posts/"
              :show-file-list="false"
              :on-success="handleCoverSuccess"
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
        <div class="drawer__footer">
          <el-button @click="cancelForm">Cancelar</el-button>
          <el-button type="primary" :loading="loading" @click="$refs.drawer.closeDrawer()">{{ loading ? 'Salvando ...' : 'Enviar' }}</el-button>
        </div>
      </div>
    </el-drawer>
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
      saved: true,
      title: '',
      // playlists: [{name:'oi',status:"dsafasd",author_id:2}],
      playlists: [],
      currentPlaylist: {
        name: '',
        description: '',
        cover_image: '',
        status: '',
        type: 'ADMIN',
      },
      status: {
        type: 'success',
        label: 'Público',
      },
      formLabelWidth: '80px',
      timer: null,
    };
  },
  computed: {
    lastItemOfPlaylist() {
      return this.playlists.slice(-1)[0];
    },
  },
  mounted() {
    this.getList();
  },
  methods: {
    handleCreatePlaylist() {
      this.playlistDrawerTitle = 'Adicionar nova playlist';
      this.btnInsertUpdate = 'Adicionar playlist';
      // if (typeof this.playlists[this.playlists.length - 1] === 'undefined' || this.playlists[this.playlists.length - 1].created_at !== '') {
      if (this.saved) {
        this.playlists.push({
          name: 'Escreva o nome da Playlist',
          author_id: '',
          status: 'PUBLIC',
        });
        this.currentPlaylist = {
          name: '',
          description: '',
          cover_image: '',
          status: '',
          type: 'ADMIN',
        };
        this.saved = false;
      }
      this.dialog = true;
    },
    cancelForm() {
      this.loading = false;
      this.dialog = false;
      this.saved = false;
      clearTimeout(this.timer);
    },
    handleFormSubmit(done){
      if (this.loading) {
        return;
      }
      if (Object.keys(this.currentPlaylist.name).length > 0) {
        this.loading = true;
        this.timer = setTimeout(() => {
          axios({
            method: 'post',
            url: `api/v1/bw/playlist`,
            headers: {
              'Authorization': 'Bearer ' + getToken(),
            },
            data: this.currentPlaylist,
          }).then((response) => {
            // console.log(response);
            // this.dialogFolderActionVisible = false;
            // this.getList(this.currentId);
            this.getList();
            done();
            setTimeout(() => {
              console.log(this.currentPlaylist);
              this.status = {
                type: 'success',
                label: 'Público',
              };
              this.loading = false;
              this.saved = true;
            }, 400);
          }).catch(error => console.log(error));
        }, 2000);
      } else {
        this.cancelForm();
      }
    },
    handleCoverSuccess(res, file) {
      this.currentPlaylist.cover_image = URL.createObjectURL(file.raw);
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

    async getList(id = null) {
      this.loading = true;
      // alert('list');
      var urlList = `api/v1/bw/playlist/lista`;
      if (id) {
        urlList = `api/v1/bw/playlist/lista/` + id;
      }
      axios({
        method: 'get',
        url: urlList,
        headers: {
          'Authorization': 'Bearer ' + getToken(),
        },
      }).then((response) => {
        console.log(response);
        this.playlists = response.data.data;
        this.loading = false;
      }).catch(error => console.log(error));
    },
  },
};
</script>
