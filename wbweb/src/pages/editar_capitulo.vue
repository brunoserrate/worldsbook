<template>
    <q-page :class="{'dark-criar_capitulo': darkmode, 'criar_capitulo': !darkmode}">
        <div class="row justify-end items-center content-center navbar_capitulo">
            <div class="col-4 col-sm-2 col_btn">
                <q-inner-loading
                    :showing="visible_page"
                    label-class="text-teal"
                    label-style="font-size: 1.1em"
                    :label="i18n.carregando_capitulo"
                >
                </q-inner-loading>
                <q-btn flat :label="i18n.botoes.publicar" class="btn_seguinte" @click="setCapitulo">
                </q-btn>
            </div>
            <div class="col-4 col-sm-2">
                <q-btn flat :label="i18n.botoes.cancelar" class="btn_cancelar" @click="cancelar"></q-btn>
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
                <q-input v-model="capitulo.titulo" class="title_capitulo" :placeholder="i18n.capitulo_sem_nome"/>
            </div>
            <div class="col-10 col-sm-8 offset-0" style="margin: 53px 0 0 0;">
                <q-editor
                    class="editor-text"
                    :placeholder="i18n.comece_escrever"
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
                capitulo_id: this.$route.params.capitulo_id,
                capitulo: {
                    titulo: '',
                    capitulo: '',
                    historia_id: this.$route.params.historia_id,
                    caminho_capa: null,
                    votacao: 0,
                    quantidade_visualizacao: 0
                },
                capitulos: [],
                i18n: {},
                avisos: {},
                darkmode: false,
                visible: false,
                visible_page: false,
                showSimulatedReturnData: false
            }
        },
        mounted(){
            // console.log(this.capitulo_id)
            this.getCapitulo()
        },
        created() {
            this.i18n = this.$i18n.criar_capitulo
            this.avisos = this.$i18n.avisos
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
                    this.i18n = this.$i18n.criar_capitulo
                    this.avisos = this.$i18n.avisos
                }, 500)
            });
        },
        methods: {
            async getCapitulo(){
                let that = this

                that.visible_page = true
                that.showSimulatedReturnData = false

                that.$axios.get(that.$pathAPI + '/capitulo/' + this.capitulo_id)
                .then((res) => {
                    that.capitulo = res.data.data
                    // console.log("cap", that.capitulo)

                    that.visible_page = false
                    that.showSimulatedReturnData = true
                })
                .catch((err) => {
                    console.log(err.response)
                    this.erroCarregar(err, this.avisos.erro_carregar)
                    that.falha()
                    that.visible_page = false
                    that.showSimulatedReturnData = true
                })


            },
            setCapitulo(){
                let that = this

                that.visible = true
                that.showSimulatedReturnData = false
                // console.log(that.capitulo)

                that.$axios.patch(that.$pathAPI + `/capitulo/${this.capitulo_id}`, that.capitulo)
                .then((res) => {
                    // console.log("res: ", res)
                    that.capitulos = res.data.data
                    // console.log(that.capitulos)
                    that.visible = false
                    that.showSimulatedReturnData = true
                    this.capituloCriadoSucesso(this.avisos.capitulo_criado)
                    that.sucesso()

                    this.$router.push({path: `../livro/capitulo/` + res.data.data.id})
                })
                .catch((err) => {
                    console.log(err.response)
                    that.falha()
                })
            },
            cancelar(){
                this.$router.push({path: `../livro/` + this.capitulo.historia_id})
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import '../css/criar_capitulo.scss';
    @import '../css/darkMode/criar_capitulo-dark.scss';
</style>