<template>
    <q-page class="criar_capitulo">
        <div class="row justify-end items-center content-center navbar_capitulo">
            <div class="col-2 col_btn">
                <q-inner-loading 
                    :showing="visible_page"
                    label-class="text-teal"
                    label-style="font-size: 1.1em"
                    label="Carregando capítulo..."
                >
                </q-inner-loading>
                <q-btn flat label="Publicar" class="btn_seguinte" @click="setCapitulo">
                </q-btn>
            </div>
            <div class="col-2">
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
            <div class="col-6">
                <q-input v-model="capitulo.titulo" class="title_capitulo"/>
            </div>
            <div class="col-8 offset-0" style="margin: 53px 0 0 0;">
                <q-editor
                    class="editor-text"
                    toolbar-toggle-color="primary"
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
                visible: false,
                visible_page: false,
                showSimulatedReturnData: false
            }
        },
        mounted(){
            console.log(this.capitulo_id)
            this.getCapitulo()
        },
        methods: {
            async getCapitulo(){
                let that = this

                that.visible_page = true
                that.showSimulatedReturnData = false

                that.$axios.get(that.$pathAPI + '/capitulo/' + this.capitulo_id)
                .then((res) => {
                    that.capitulo = res.data.data
                    console.log("cap", that.capitulo)
                    
                    that.visible_page = false
                    that.showSimulatedReturnData = true
                })
                .catch((err) => {
                    console.log(err.response)
                    this.erroCarregar()
                    that.visible_page = false
                    that.showSimulatedReturnData = true
                })
                
                
            },
            setCapitulo(){
                let that = this

                that.visible = true
                that.showSimulatedReturnData = false

                that.$axios.patch(that.$pathAPI + '/capitulo', that.capitulo)
                .then((res) => {
                    console.log("res: ", res)
                    that.capitulos = res.data.data
                    console.log(that.capitulos)
                    that.visible = false
                    that.showSimulatedReturnData = true
                    this.capituloCriadoSucesso()

                    this.$router.push({path: `../livro/capitulo/` + res.data.data.id})
                })
                .catch((err) => {
                    console.log(err.response)
                    this.erroCriacaoCapitulo()
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
</style>