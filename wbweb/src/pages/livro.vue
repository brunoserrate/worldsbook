<template>
    <q-page :class="{'dark-livro': darkmode, 'livro': !darkmode}">
        <q-inner-loading
            :showing="visible_page"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            label="Carregando história..."
        ></q-inner-loading>
        <div class="row geral">
            <div class="col-12">
                <q-card class="card_livro">
                    <div class="row justify-center">
                        <div class="col-6 offset-sm-0 col-sm-auto offset-md-2">
                            <img alt="Cover" :src="livro.caminho_capa" class="capa_card"/>
                        </div>
                        <div class="col-10 col-sm-6 offset-sm-0 ">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="title_card">{{livro.titulo}}</h3>
                                </div>
                                <div class="col-12 col-md-10">
                                    <div class="row align_icons_status">
                                        <div class="col-3"> 
                                            <div class="row">
                                                <div class="col-4">
                                                    <q-icon name="visibility" class="icons_card"/>
                                                </div>
                                                <div class="col-6 labels_icon">
                                                    <p>Visualizações</p> 
                                                </div>
                                                <div class="col-6 col-md-12 align_text_details">
                                                    {{livro.total_visualizacoes}}
                                                </div>
                                            </div>
                                        </div>
                                        <q-separator vertical class="separator_card"/>
                                        <div class="col-2">
                                            <div class="row">
                                                <div class="col-4">
                                                    <q-icon name="star_border" class="icons_card" />
                                                </div>
                                                <div class="col-6 labels_icon">
                                                    <p>Votos</p>
                                                </div>
                                                <div class="col-6 col-md-12 align_text_details">
                                                    {{livro.total_votos}}
                                                </div>
                                            </div>
                                        </div>
                                        <q-separator vertical class="separator_card"/>
                                        <div class="col-2">
                                            <div class="row">
                                                <div class="col-4">
                                                    <q-icon name="list" class="icons_card"/>
                                                </div>
                                                <div class="col-6 labels_icon">
                                                    <p>Capítulos</p>
                                                </div>
                                                <div class="col-6 col-md-12 align_text_details">
                                                    {{livro.total_capitulos}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10 col-sm-10 col-md-7">
                                    <q-btn unelevated label="Iniciar leitura" icon="import_contacts" class="btn_iniciar_leitura_livro" @click="goChapter(livro.capitulos[0])"/>
                                </div>
                                <div class="col-2 col-md-1">
                                    <q-btn unelevated icon="add" class="btn_add_lista"/>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-2 offset-4 offset-sm-0 col-sm-2 col-md-1">
                                            <q-btn flat icon="edit" class="btn-editar-historia" @click="goEditHistoria" v-if="livro.usuario_id == user.user_id"/>
                                        </div>
                                        <div class="col-1">
                                            <q-btn flat icon="delete" class="btn-deletar-historia" @click="delete_historia = true" v-if="livro.usuario_id == user.user_id"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
        <div class="row descricao">
            <div class="col-10 offset-2">
                <q-avatar size="40px" style="background-color: #ddd;">
                    <img :src="livro.foto_perfil" />
                </q-avatar>
                <span class="apelido_usuario" @click="goToPerfil(livro.usuario_id)">{{livro.apelido_usuario}}</span>
            </div>
            <div class="col-10 offset-2">
                <q-chip class="historia_finalizada">{{getHistoriaFinalizada(livro.historia_finalizada)}}</q-chip>
                <q-chip class="historia_finalizada">{{getConteudoAdulto(livro.conteudo_adulto)}}</q-chip>
            </div>
            <div class="col-8 offset-2 col-sm-8">
                <p class="descricao_p">{{livro.descricao}}</p>
            </div>
            <div class="col-10 offset-2">
                <p style="font-weight: bold;"><q-icon name="copyright"></q-icon> {{livro.direito_autoral}}</p>
            </div>
            <div class="col-12">
                <q-separator class="separator_livro" />
            </div>
        </div>
        <div class="row tags-generos">
            <div class="col-8 offset-2 col-sm-8">
                <q-chip v-for="(tag, i) in livro.tags" :key="i" class="tag-genero" >{{tag}}</q-chip>
            </div>
        </div>
        <div class="row capitulos">
            <div class="col-10 offset-1 offset-sm-2 col-sm-8">
                <q-card class="card_indice">
                    <div class="row">
                        <div class="col-6">
                            <h3 class="title_indice_card">Índice</h3>
                        </div>
                        <div class="col-6 row_add_capitulo">
                            <q-btn flat :disable="livro.historia_finalizada" label="Adicionar capítulo" class="btn_adicionar" @click="goAddCapitulo" v-if="livro.usuario_id == user.user_id">
                                <q-inner-loading
                                    :showing="visible"
                                    label-class="text-teal"
                                    label-style="font-size: 1.1em"
                                >
                                </q-inner-loading>
                            </q-btn>
                        </div>
                    </div>
                    <q-list class="lista">
                        <q-item v-if="livro.capitulos.length == 0">Essa história ainda não tem capítulos!</q-item>
                        <div class="row">
                            <div :class="(livro.usuario_id == user.user_id) ? 'col-10' : 'col-12'">
                                <q-item clickable v-for="(capitulo, i) in livro.capitulos" :key="i" class="item_list" @click="goChapter(capitulo)" >
                                    {{capitulo.titulo}}
                                </q-item>
                            </div>
                            <div v-if="livro.usuario_id == user.user_id" class="col-1">
                                <q-item clickable v-for="(capitulo, i) in livro.capitulos" :key="i" class="item_edit" @click="goEditCapitulo(capitulo)">
                                    <q-icon name="edit" color="#7A22A7"></q-icon>
                                </q-item>
                            </div>
                            <div v-if="livro.usuario_id == user.user_id" class="col-1">
                                <q-item clickable v-for="(capitulo, i) in livro.capitulos" :key="i" class="item_edit" @click="confirmDelCapitulo(capitulo.id, i)">
                                    <q-icon name="delete" color="#7A22A7"></q-icon>
                                </q-item>
                            </div>
                        </div>
                    </q-list>
                </q-card>
            </div>
        </div>

        <q-dialog v-model="delete_historia" persistent class="delete">
            <q-card :class="{'dark-card-delete': darkmode, 'card-delete': !darkmode}">
                <q-card-section class="row items-center">
                    <q-avatar icon="delete" color="primary" text-color="white" />
                    <span class="q-ml-sm">Deseja deletar essa história permanentemente?</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Deletar" color="primary" @click="delHistoria"/>
                    <q-btn flat label="Cancelar" color="primary" v-close-popup class="btn-cancelar"/>
                </q-card-actions>
            </q-card>
        </q-dialog>
        <q-dialog v-model="delete_capitulo" persistent class="delete">
            <q-card :class="{'dark-card-delete': darkmode, 'card-delete': !darkmode}">
                <q-card-section class="row items-center">
                    <q-avatar icon="delete" color="primary" text-color="white" />
                    <span class="q-ml-sm">Deseja deletar esse capítulo permanentemente?</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Deletar" color="primary" @click="delCapitulo" />
                    <q-btn flat label="Cancelar" color="primary" v-close-popup class="btn-cancelar" @click="function(){
                        capitulo_id = 0
                        delete_capitulo = false
                    }" />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </q-page>
</template>
<script>
    import {  Loading, QSpinnerGears } from 'quasar'
  	import eventBus from '../boot/eventBus'

    export default {
        name:'Livro',
        data (){
            return {
                livro_id: this.$route.params.livro_id,
                delete_historia: false,
                delete_capitulo: false,
                livro: {
                    apelido_usuario: '',
                    caminho_capa: '',
                    capitulos: [],
                    categoria_id: '',
                    conteudo_adulto: '',
                    data_atualizacao: '',
                    data_criacao: '',
                    descricao: '',
                    direito_autoral: '',
                    direitos_autorais_id: '',
                    foto_perfil: '',
                    historia_finalizada: '',
                    id: '',
                    idioma_id: '',
                    nome_usuario: '',
                    publico_alvo_id: '',
                    tags: [],
                    titulo: '',
                    total_capitulos: '',
                    total_visualizacoes: '',
                    total_votos: '',
                    usar_apelido: '',
                    usuario_id: '',
                },
                user: {
                    apelido: '',
                    avatar: '',
                    email: '',
                    nome: '',
                    token: '',
                    user_id: ''
                },
                usuario_livro_id: '',
                darkmode: false,
                visible: false,
                visible_page: false,
                showSimulatedReturnData: false,
                capitulo: {}
            }
        },
        mounted(){
            this.carregarLivro(this.livro_id)
            this.getAvatar()
            // console.log("user: ", this.user.user_id)
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
            carregarLivro(livro_id){
                let that = this

                that.visible_page = true
                that.showSimulatedReturnData = false

                that.$axios.get(that.$pathAPI + '/historia/' + livro_id)
                .then((res) => {
                    that.livro = res.data.data
                    // console.log("livro: ", that.livro)
                    this.getAvatar()
                    this.getHistoriaFinalizada()

                    that.visible_page = false
                    that.showSimulatedReturnData = true
                })
                .catch((err) => {
                    console.log(err.response)
                    that.visible_page = false
                    that.showSimulatedReturnData = true
                    that.falha()
                    // this.erroCarregar()
                })
            },
            delHistoria(){
                let that = this

                Loading.show()

                that.$axios.delete(that.$pathAPI + '/historia/' + that.livro.id)
                .then((res) => {
                    that.sucesso()
                    this.$router.push({path: `/iniciar_leitura`})
                })
                .catch((err) => {
                    that.falha()
                })
                .finally(() => {
                    that.delete_historia = false
                    Loading.hide()
                })

            },
            confirmDelCapitulo(capitulo_id, i){
                this.capitulo = {
                    capitulo_id: capitulo_id,
                    index: i
                }

                this.delete_capitulo = true
            },
            delCapitulo(){
                let that = this

                Loading.show()

                that.$axios.delete(that.$pathAPI + '/capitulo/' + that.capitulo.capitulo_id)
                .then((res) => {
                    that.livro.capitulos.splice(that.capitulo.index, 1)
                    that.capitulo = {}
                    that.sucesso()
                })
                .catch((err) => {
                    console.log(err)
                    that.falha()
                })
                .finally(() => {
                    that.delete_capitulo = false
                    Loading.hide()
                })
            },
            goToPerfil(usuario_id){
                this.$router.push({path: `/perfil/` + usuario_id}) 
            },
            goEditHistoria(){
                this.$router.push({path: `/editar_livro/` + this.livro_id})
            },
            goEditCapitulo(capitulo){
                // console.log(capitulo)
                this.$router.push({path: `/editar_capitulo/` + capitulo.id})
            },
            goAddCapitulo(){
                // console.log(this.livro_id)
                this.$router.push({path: `/criar_historia/` + this.livro_id})
            },
            goChapter(capitulo){
                this.$router.push({path: `capitulo/` + capitulo.id})
            },
            getAvatar(){
                this.user = {
                    user_id: 0,
                    nome: '',
                    apelido: '',
                    foto_perfil: 'https://avatars.dicebear.com/api/initials/v.svg',
                }

                let user = JSON.parse( this.$q.sessionStorage.getItem('auth') )

                if(user !== null) {
                    this.user = user
                }

            },
            getHistoriaFinalizada(historia_finalizada){
                if(historia_finalizada == 0){ return "Em andamento" }
                return "Concluída"
            },
            getConteudoAdulto(conteudo_adulto){
                if (conteudo_adulto){return "Conteúdo Adulto"}
                return "Conteúdo Livre"
            }
        }
    };
</script>
<style lang="scss" scoped>
    @import '../css/livro.scss';
    @import '../css/darkMode/livro-dark.scss';
</style>