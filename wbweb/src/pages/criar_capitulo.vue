<template>
    <q-page :class="{'dark-criar_capitulo': darkmode, 'criar_capitulo': !darkmode}">
        <div class="row justify-end items-center content-center navbar_capitulo">
            <div class="col-4 col-sm-2 col_btn">
                <q-btn flat label="Publicar" class="btn_seguinte" @click="setCapitulo">
                    <q-inner-loading 
                        :showing="visible"
                        label-class="text-teal"
                        label-style="font-size: 1.1em"
                    >
                    </q-inner-loading>
                </q-btn>
            </div>
            <div class="col-4 col-sm-2">
                <q-btn flat label="Cancelar" class="btn_cancelar" @click="cancelar"></q-btn>
            </div>
        </div>
        <q-separator></q-separator>
        <div class="fit row justify-center items-center content-center row_icones"> 
            <div class="col-6" style="text-align: right;">
                <q-icon name="perm_media" color="black" size="25px" class="row_icone"/>
            </div>
            <div class="col-6">
                <q-icon name="videocam" color="black" size="25px" class="row_icone"/>
            </div>
            <div class="capa_img_2" v-if="capitulo.caminho_capa"> </div>
        </div>
        <div class="fit row justify-center items-center content-center">
            <div class="col-9 col-sm-6">
                <q-input v-model="capitulo.titulo" class="title_capitulo"/>
            </div>
            <div class="col-10 col-sm-8 offset-0" style="margin: 53px 0 0 0;">
                <q-editor
                    class="editor-text"
                    toolbar-toggle-color="primary"
                    :toolbar-text-color="darkmode ? 'grey-6' : ''"
                    v-model="capitulo.capitulo"
                    :definitions="{
                        bold: {label: 'Bold', icon: null, tip: 'My bold tooltip'}
                    }"
                />
            </div>
        </div>
    </q-page>
</template>
<script>
    import eventBus from '../boot/eventBus'
    export default {
        data (){
            return {
                historia_id: this.$route.params.historia_id,
                capitulo: {
                    titulo: 'Capítulo sem nome',
                    capitulo: 'Comece a escrever...',
                    historia_id: this.$route.params.historia_id,
                    caminho_capa: null,
                    votacao: 0,
                    quantidade_visualizacao: 0
                },
                capitulos: [],
                darkmode: false,
                visible: false,
                showSimulatedReturnData: false
            }
        },
        created() {
			setTimeout(() => {
				let dark = this.$q.localStorage.getItem('darkmode')
				this.darkmode = dark == 'true' ? true : false
			}, 500)
			eventBus.$on('att-darkmode', async (option) => {
				setTimeout(async() => {
					this.darkmode = option
				}, 500);
			});
        },
        methods: {
            setCapitulo(){
                let that = this

                that.visible = true
                that.showSimulatedReturnData = false

                that.$axios.post(that.$pathAPI + '/capitulo', that.capitulo)
                .then((res) => {
                    // console.log("res: ", res)
                    that.capitulos = res.data.data
                    // console.log(that.capitulos)
                    that.visible = false
                    that.showSimulatedReturnData = true
                    that.sucesso()
                    // this.capituloCriadoSucesso()

                    this.$router.push({path: `/livro/capitulo/` + res.data.data.id})
                })
                .catch((err) => {
                    console.log(err.response)
                    this.falha()
                    // this.erroCriacaoCapitulo()
                })
                .finally(() => {
                    that.visible = false
                    that.showSimulatedReturnData = true
                })
            },
            cancelar(){
                this.$router.push({path: `/livro/` + this.$route.params.historia_id})
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import '../css/criar_capitulo.scss';
    @import '../css/darkMode/criar_capitulo-dark.scss';
</style>