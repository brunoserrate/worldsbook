<template>
    <q-page :class="{'dark-sobre_empresa': darkmode, 'sobre_empresa': !darkmode}">
        <div class="row">
            <div class="col-12">
                <h1 class="somos_netdev"><b>{{ i18n.somos.label }}</b></h1>
            </div>
            <div class="col-10 offset-1">
                <p class="p_about1" v-html="i18n.somos.descricao"></p>
            </div>
            <div class="col-12 align_logo mt-5">
                <q-img src="~assets/netdev_logo.png" style="width: 36%;"></q-img>
            </div>
            <!-- <hr  style="margin: 0 0 70px 0;"/> -->
            <div class="col-12 mt-5">
                <h1 class="valores_netdev"><b>{{ i18n.valores.label }}</b></h1>
            </div>
            <div class="col-10 offset-1">
                <p class="p_about1" v-html="i18n.valores.descricao"></p>
            </div>
            <!-- <hr  style="margin: 0 0 70px 0;"/> -->
            <div class="col-12">
                <h1 class="valores_netdev"><b>{{ i18n.missao.label }}</b></h1>
            </div>
            <div class="col-10 offset-1">
                <p class="p_about1">{{ i18n.missao.descricao }}</p>
            </div>
            <!-- <hr  style="margin: 0 0 70px 0;"/> -->
            <div class="col-12">
                <h1 class="valores_netdev"><b>{{ i18n.visao.label }}</b></h1>
            </div>
            <div class="col-10 offset-1">
                <p class="p_about1">{{ i18n.visao.descricao }}</p>
            </div>
        </div>
    </q-page>
</template>
<script>
    import eventBus from '../boot/eventBus'
    export default {
        name: 'sobre-empresa',
        data(){
            return {
                darkmode: false,
                i18n: {}
            }
        },
        created() {
            this.i18n = this.$i18n.sobre_empresa
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
                    this.i18n = this.$i18n.sobre_empresa
                }, 500)
            });
        }
    }
</script>
<style lang="scss" scoped>
	@import '../css/sobre_empresa.scss';
	@import '../css/darkMode/sobre_empresa-dark.scss';
</style>