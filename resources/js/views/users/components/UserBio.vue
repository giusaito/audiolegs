<template>
  <el-card class="box-card user-bio">
    <div slot="header" class="clearfix">
      <span>Sobre mim</span>
    </div>
    <div class="user-education user-bio-section">
      <div class="user-bio-section-body">
        <div class="text-muted">
          {{ user.user_profile.biography }}
        </div>
      </div>
    </div>
    <div class="user-skills user-bio-section">
      <div class="user-bio-section-header">
        <span>Redes sociais</span>
      </div>
      <div class="user-bio-section-body">
        <div v-if="user.user_profile.linkedin" class="social">
          <a :href="user.user_profile.linkedin" target="_blank"><span>Linkedin</span></a>
        </div>
        <div v-if="user.user_profile.facebook" class="social">
          <a :href="user.user_profile.facebook" target="_blank"><span>Facebook</span></a>
        </div>
        <div v-if="user.user_profile.twitter" class="social">
          <a :href="user.user_profile.twitter" target="_blank"><span>Twitter</span></a>
        </div>
        <div v-if="user.user_profile.youtube" class="social">
          <a :href="user.user_profile.youtube" target="_blank"><span>Youtube</span></a>
        </div>
      </div>
    </div>
  </el-card>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          roles: [],
        };
      },
    },
  },
};
</script>

<style lang="scss" scoped>
.user-bio {
  margin-top: 20px;
  color: #606266;
  span {
    padding-left: 4px;
  }
  .user-bio-section {
    font-size: 14px;
    padding: 15px 0;
    .user-bio-section-header {
      border-bottom: 1px solid #dfe6ec;
      padding-bottom: 10px;
      margin-bottom: 10px;
      font-weight: bold;
    }
  }
  .social {
    line-height: 2;
    font-size: 1.2em;
  }
}
</style>
