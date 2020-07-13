<template>
  <div class="app-container">
    <el-button-group>
      <el-button type="primary" icon="el-icon-plus" @click="createFolder">Nova Pasta</el-button>
    </el-button-group>

    <div id="list" v-loading="listLoading">
      <div id="breadcrumb">
        <ul>
          <li v-for="breadcrumbsUrl in breadcrumbsUrls" :key="breadcrumbsUrl.id">
            <a href="files"><span class="folderName">{{ breadcrumbsUrl.split('/')[breadcrumbsUrl.split('/').length-1] }}</span></a>
          </li>
        </ul>
      </div>
      <ul class="data">
        <li v-for="lei in leis" :key="lei.id" :class="leisClass(lei.type)">
          <!-- {{ lei.children.length }} -->
          <div :title="lei.path" :class="leisClass(lei.type)" @click="executeActionClick(lei.id, lei.type, lei.path)">
            <span v-if="lei.type === 'folder' && lei.children.length === 0" class="icon folder" />
            <span v-if="lei.type === 'folder' && lei.children.length > 0" class="icon folder full" />
            <span v-if="lei.type === 'file'" class="icon file" :class="'f-'+(lei.name.split('.')[lei.name.split('.').length-1])">.{{ lei.name.split('.')[lei.name.split('.').length-1] }}</span>
            <span class="name">{{ lei.name }}</span>
            <span class="details">
              <span v-if="lei.type === 'folder'">{{ lei.children.length }} <span v-if="lei.children.length > 1">itens</span><span v-else>item</span></span>
              <span v-if="lei.type === 'file'">{{ formatBytes(lei.size) }}</span>
            </span>
          </div>
        </li>
      </ul>
    </div>

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
      leis: [],
      formTitle: '',
      breadcrumbsUrls: [],
      folderEditing: false,
      listLoading: true,
      rules: {
        nome: [
          { required: true, message: 'Por favor, preencha o nome da pasta', trigger: 'change' },
          { min: 3, max: 50, message: 'Preencha no mínimo 3 caracteres e no máximo 50', trigger: 'change' },
        ],
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    createFolder() {
      this.dialogActionVisible = true;
      this.formTitle = 'Adicionar nova pasta';
      this.currentFolder = {
        nome: '',
      };
    },
    leisClass(tipo) {
      var $class = '';
      if (tipo === 'folder') {
        $class = 'folders';
      } else {
        $class = 'files';
      }
      return $class;
    },
    formatBytes(bytes, decimals = 2) {
      if (bytes === 0) {
        return '0 Bytes';
      }

      const k = 1024;
      const dm = decimals < 0 ? 0 : decimals;
      const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

      const i = Math.floor(Math.log(bytes) / Math.log(k));

      return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    },
    async getList() {
      this.listLoading = true;
      axios({
        method: 'get',
        url: `api/v1/bw/controle-de-leis/lista`,
        headers: {
          'Authorization': 'Bearer ' + getToken(),
        },
      }).then((response) => {
        console.log(response.data.data);
        this.leis = response.data.data;
        this.listLoading = false;
      }).catch(error => console.log(error));
    },
    executeActionClick(id, tipo, nextDir) {
      if (tipo === 'folder') {
        const findDataById = (id) => {
          /* eslint-disable */
          const [key, lei] = Object.entries(this.leis).find(([key, lei]) => lei.id === id);
          /* eslint-enable */
          return lei.children;
        };
        this.breadcrumbsUrls = this.generateBreadcrumbs(nextDir);
        this.leis = findDataById(id);
      } else {
        alert('file');
      }
    },
    generateBreadcrumbs(nextDir){
      var path = nextDir.split('/').slice(0);
      for (var i = 1; i < path.length; i++) {
        path[i] = path[i - 1] + '/' + path[i];
      }
      return path;
    },
    handleSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if (this.currentFolder.id !== undefined){
            alert('edit');
          } else {
            axios({
              method: 'post',
              url: `api/v1/bw/controle-de-leis/leis`,
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
.data {
	margin-top: 60px;
	z-index: -3;
}
.data.animated {
	-webkit-animation: showSlowlyElement 700ms; /* Chrome, Safari, Opera */
	animation: showSlowlyElement 700ms; /* Standard syntax */
}
.data li {
  border-radius: 3px;
  background-color: #373743;
  width: 307px;
  height: 118px;
  list-style-type: none;
  margin: 10px;
  display: inline-block;
  position: relative;
  overflow: hidden;
  padding: 0.3em;
  z-index: 1;
  cursor: pointer;
  box-sizing: border-box;
  transition: 0.3s background-color;
}
.data li:hover {
	background-color: #42424E;
}
.data li a {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}
.data li .name {
	color: #ffffff;
	font-size: 15px;
	font-weight: 700;
	line-height: 20px;
	width: 150px;
	white-space: nowrap;
	display: inline-block;
	position: absolute;
	overflow: hidden;
	text-overflow: ellipsis;
	top: 40px;
}

.data li .details {
	color: #b6c1c9;
	font-size: 13px;
	font-weight: 400;
	width: 55px;
	height: 10px;
	top: 64px;
	white-space: nowrap;
	position: absolute;
	display: inline-block;
}

@media all and (max-width:965px) {

	.data li {
		width: 100%;
		margin: 5px 0;
	}

}

@-webkit-keyframes showSlowlyElement {
	100%   	{ transform: scale(1); opacity: 1; }
	0% 		{ transform: scale(1.2); opacity: 0; }
}

/* Standard syntax */
@keyframes showSlowlyElement {
	100%   	{ transform: scale(1); opacity: 1; }
	0% 		{ transform: scale(1.2); opacity: 0; }
}

.icon {
	font-size: 23px;
}
.icon.folder {
	display: inline-block;
	margin: 1em;
	background-color: transparent;
	overflow: hidden;
}
.icon.folder:before {
	content: '';
	float: left;
	background-color: #7ba1ad;

	width: 1.5em;
	height: 0.45em;

	margin-left: 0.07em;
	margin-bottom: -0.07em;

	border-top-left-radius: 0.1em;
	border-top-right-radius: 0.1em;

	box-shadow: 1.25em 0.25em 0 0em #7ba1ad;
}
.icon.folder:after {
	content: '';
	float: left;
	clear: left;

	background-color: #a0d4e4;
	width: 3em;
	height: 2.25em;

	border-radius: 0.1em;
}
.icon.folder.full:before {
	height: 0.55em;
}
.icon.folder.full:after {
	height: 2.15em;
	box-shadow: 0 -0.12em 0 0 #ffffff;
}

.icon.file {
	width: 2.5em;
	height: 3em;
	line-height: 3em;
	text-align: center;
	border-radius: 0.25em;
	color: #FFF;
	display: inline-block;
	margin: 0.9em 1.2em 0.8em 1.3em;
	position: relative;
	overflow: hidden;
	box-shadow: 1.74em -2.1em 0 0 #A4A7AC inset;
}
.icon.file:first-line {
	font-size: 13px;
	font-weight: 700;
}
.icon.file:after {
	content: '';
	position: absolute;
	z-index: -1;
	border-width: 0;
	border-bottom: 2.6em solid #DADDE1;
	border-right: 2.22em solid rgba(0, 0, 0, 0);
	top: -34.5px;
	right: -4px;
}

.icon.file.f-avi,
.icon.file.f-flv,
.icon.file.f-mkv,
.icon.file.f-mov,
.icon.file.f-mpeg,
.icon.file.f-mpg,
.icon.file.f-mp4,
.icon.file.f-m4v,
.icon.file.f-wmv {
	box-shadow: 1.74em -2.1em 0 0 #7e70ee inset;
}
.icon.file.f-avi:after,
.icon.file.f-flv:after,
.icon.file.f-mkv:after,
.icon.file.f-mov:after,
.icon.file.f-mpeg:after,
.icon.file.f-mpg:after,
.icon.file.f-mp4:after,
.icon.file.f-m4v:after,
.icon.file.f-wmv:after {
	border-bottom-color: #5649c1;
}

.icon.file.f-mp2,
.icon.file.f-mp3,
.icon.file.f-m3u,
.icon.file.f-wma,
.icon.file.f-xls,
.icon.file.f-xlsx {
	box-shadow: 1.74em -2.1em 0 0 #5bab6e inset;
}
.icon.file.f-mp2:after,
.icon.file.f-mp3:after,
.icon.file.f-m3u:after,
.icon.file.f-wma:after,
.icon.file.f-xls:after,
.icon.file.f-xlsx:after {
	border-bottom-color: #448353;
}

.icon.file.f-doc,
.icon.file.f-docx,
.icon.file.f-psd{
	box-shadow: 1.74em -2.1em 0 0 #03689b inset;
}

.icon.file.f-doc:after,
.icon.file.f-docx:after,
.icon.file.f-psd:after {
	border-bottom-color: #2980b9;
}

.icon.file.f-gif,
.icon.file.f-jpg,
.icon.file.f-jpeg,
.icon.file.f-pdf,
.icon.file.f-png {
	box-shadow: 1.74em -2.1em 0 0 #e15955 inset;
}
.icon.file.f-gif:after,
.icon.file.f-jpg:after,
.icon.file.f-jpeg:after,
.icon.file.f-pdf:after,
.icon.file.f-png:after {
	border-bottom-color: #c6393f;
}

.icon.file.f-deb,
.icon.file.f-dmg,
.icon.file.f-gz,
.icon.file.f-rar,
.icon.file.f-zip,
.icon.file.f-7z {
	box-shadow: 1.74em -2.1em 0 0 #867c75 inset;
}
.icon.file.f-deb:after,
.icon.file.f-dmg:after,
.icon.file.f-gz:after,
.icon.file.f-rar:after,
.icon.file.f-zip:after,
.icon.file.f-7z:after {
	border-bottom-color: #685f58;
}

.icon.file.f-html,
.icon.file.f-rtf,
.icon.file.f-xml,
.icon.file.f-xhtml {
	box-shadow: 1.74em -2.1em 0 0 #a94bb7 inset;
}
.icon.file.f-html:after,
.icon.file.f-rtf:after,
.icon.file.f-xml:after,
.icon.file.f-xhtml:after {
	border-bottom-color: #d65de8;
}

.icon.file.f-js {
	box-shadow: 1.74em -2.1em 0 0 #d0c54d inset;
}
.icon.file.f-js:after {
	border-bottom-color: #a69f4e;
}

.icon.file.f-css,
.icon.file.f-saas,
.icon.file.f-scss {
	box-shadow: 1.74em -2.1em 0 0 #44afa6 inset;
}
.icon.file.f-css:after,
.icon.file.f-saas:after,
.icon.file.f-scss:after {
	border-bottom-color: #30837c;
}
#breadcrumb {
	color: #000;
  font-size: 14px;
  font-weight: 700;
  line-height: 35px;
  background: #ffffff;
  border-bottom: 1px solid #ccc;
  margin-top: 30px;
}

#breadcrumb a:link, #breadcrumb a:visited {
	color: #000;
	text-decoration: none;
}

#breadcrumb a:hover {
	text-decoration: underline;
}
#breadcrumb ul {
  display: flex;
  margin:0;
  padding:0;
}
#breadcrumb ul li {
  list-style:none;
}

#breadcrumb ul li:not(:last-child):after {
  content:"→";
	color:  #6a6a72;
	font-size: 14px;
	font-weight: 700;
  line-height: 20px;
  margin:0 5px;
}
</style>
