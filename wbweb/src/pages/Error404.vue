<template>
    <div class="fullscreen text-white text-center q-pa-md flex flex-center" :class="{'dark-error404': darkmode, 'error404': !darkmode}">
      <div class="row">
        <div class="col-5" style="">
          <div class="row">
            <div class="col-12 offset-2">
              <h1 class="notFound">404 not found</h1>
            </div>
            <div class="col-12 offset-2">
              <h4 class="pagina">{{ i18n.subtitulo }}</h4>
            </div>
            <div class="col-12 offset-2">
              <q-btn
                class="q-mt-xl botao"
                unelevated
                to="/"
                :label="i18n.voltar"
                no-caps
              />
            </div>
          </div>
        </div>
        <div class="col-7">
          <img src="~assets/mascote404.png" class="mascote" />
        </div>
      </div>
    </div>
</template>

<script>
  import eventBus from '../boot/eventBus'
  export default {
    name: 'Error404',
    data() {
      return {
        i18n: {},
        darkmode: false
      }
    },
    created() {
      this.i18n = this.$i18n.error404
      setTimeout(() => {
          let dark = this.$q.localStorage.getItem('darkmode')
          this.darkmode = dark == 'true' ? true : false
      }, 500)
      eventBus.$on('att-darkmode', async (option) => {
          setTimeout(async() => {
              this.darkmode = option
          }, 500);
      });
      eventBus.$on('att-idioma', async(option) => {
          this.selectedOption = option;
          setTimeout(() => {
              this.i18n = this.$i18n.error404
          }, 500)
      });
    },
  }
</script>
<style lang="scss" scoped>
  @import '../css/error404.scss';
  @import '../css/darkMode/error404-dark.scss';
</style>