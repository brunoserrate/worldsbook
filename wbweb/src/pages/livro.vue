<template>
    <q-page class="livro" :class="{ 'dark-livro': darkmode }">
        <q-inner-loading
            :showing="visible_page"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            :label="i18n.carregando_historia + '...'"
        ></q-inner-loading>
        <div class="row geral">
            <div class="col-12">
                <q-card class="card_livro py-3">
                    <div class="row justify-center">
                        <div class="col-6 offset-sm-0 col-sm-auto offset-md-2 d-flex justify-content-center justify-sm-content-start">
                            <img alt="Cover" :src="livro.caminho_capa ? (livro.caminho_capa ? `${path_photo}/${livro.caminho_capa}` : ``) : `${path_photo}/default.png`" class="capa_card h-100 me-sm-5"/>
                        </div>
                        <div class="col-10 col-sm-6 offset-sm-0 ">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="title_card text-center text-md-start">{{ livro.titulo }}</h3>
                                </div>
                                <div class="col-12 col-md-10">
                                    <div class="row align_icons_status">
                                        <div class="col-3"> 
                                            <div class="row">
                                                <div class="col-4">
                                                    <q-icon name="visibility" class="icons_card"/>
                                                </div>
                                                <div class="col-6 labels_icon">
                                                    <p>{{ i18n.reacoes.visualizacoes }}</p> 
                                                </div>
                                                <div class="col-6 col-md-12 align_text_details">
                                                    {{ livro.total_visualizacoes }}
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
                                                    <p>{{ i18n.reacoes.votos }}</p>
                                                </div>
                                                <div class="col-6 col-md-12 align_text_details">
                                                    {{ livro.total_votos }}
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
                                                    <p>{{ i18n.reacoes.capitulos }}</p>
                                                </div>
                                                <div class="col-6 col-md-12 align_text_details">
                                                    {{ livro.total_capitulos }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10 col-sm-10 col-md-7">
                                    <q-btn unelevated :label="i18n.iniciar_leitura" icon="import_contacts" class="btn_iniciar_leitura_livro" @click="goChapter(livro.capitulos[0])"/>
                                </div>
                                <div class="col-2 col-md-1">
                                    <q-btn unelevated icon="add" class="btn_add_lista"/>
                                </div>
                                <div class="col-12 mt-3 col-icon-id">
                                    <span>
                                        <b>Código da história: </b>{{ livro._id }}
                                    </span>
                                    <q-icon class="help ms-2" name="question_mark">
                                        <q-tooltip content-class="bg-purple tooltips" anchor="center right" self="center left" :offset="[10, 10]">
                                            ID/Código de indentificação da história
                                        </q-tooltip>
                                    </q-icon>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 offset-4 offset-sm-0 mt-3">
                                            <q-btn flat icon="edit" class="btn-editar-historia me-2" @click="goEditHistoria" v-if="livro.usuario ? (livro.usuario._id == (currentUser ? (currentUser.usuario ? currentUser.usuario._id : false) : false)) : false"/>
                                            <q-btn flat icon="delete" class="btn-deletar-historia" @click="delete_historia = true" v-if="livro.usuario ? (livro.usuario._id == (currentUser ? (currentUser.usuario ? currentUser.usuario._id : false) : false)) : false"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
        <div class="row descricao mt-4">
            <div class="col-10 offset-2">
                <q-avatar size="40px" style="background-color: #ddd;">
                    <img :src="livro.usuario ? (livro.usuario.foto_perfil ? `${path_photo_profile}/${livro.usuario.foto_perfil}` : ``) : `${path_photo_profile}/default.jpg`" />
                </q-avatar>
                <span class="apelido_usuario" @click="goToPerfil(livro.usuario._id)">{{ livro.usuario ? livro.usuario.name : '' }}</span>
            </div>
            <div class="col-10 offset-2">
                <q-chip class="historia_finalizada">{{ getHistoriaFinalizada(livro.historia_finalizada )}}</q-chip>
                <q-chip class="historia_finalizada">{{ getConteudoAdulto(livro.conteudo_adulto) }}</q-chip>
            </div>
            <div class="col-8 offset-2 col-sm-8">
                <p class="descricao_p">{{livro.descricao}}</p>
            </div>
            <div class="col-10 offset-2">
                <p style="font-weight: bold;" class="d-flex align-items-center"><q-icon name="copyright" class="me-2"></q-icon> {{ livro.direitos_autorais ? livro.direitos_autorais.tipo_autoral : '' }}</p>
            </div>
            <div class="col-12">
                <q-separator class="separator_livro" />
            </div>
        </div>
        <div class="row tags-generos">
            <div class="col-8 offset-2 col-sm-8">
                <q-chip v-for="(tag, i) in livro.tags" :key="i" class="tag-genero" >{{ tag.nome }}</q-chip>
            </div>
        </div>
        <div class="row capitulos">
            <div class="col-10 offset-1 offset-sm-2 col-sm-8">
                <q-card class="card_indice">
                    <div class="row">
                        <div class="col-6">
                            <h3 class="title_indice_card">{{ i18n.indice }}</h3>
                        </div>
                        <div class="col-6 row_add_capitulo">
                            <q-btn flat :disable="livro.historia_finalizada" :label="i18n.adicionar_capitulo" class="btn_adicionar" @click="goAddCapitulo" v-if="livro.usuario ? (livro.usuario._id == (currentUser ? (currentUser.usuario ? currentUser.usuario._id : false) : false)) : false">
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
                        <q-item v-if="livro.capitulos ? livro.capitulos.length == 0 : 0">Essa história ainda não tem capítulos!</q-item>
                        <div class="row">
                            <div class="" :class="(livro.usuario ? (livro.usuario._id == (currentUser ? (currentUser.usuario ? currentUser.usuario._id : false) : false)) : false) ? 'col-10' : 'col-12'">
                                <q-item clickable v-for="(capitulo, i) in livro.capitulos" :key="i" class="item_list me-2" @click="goChapter(capitulo)" >
                                    {{ capitulo.titulo }}
                                </q-item>
                            </div>
                            <div v-if="livro.usuario ? (livro.usuario._id == (currentUser ? (currentUser.usuario ? currentUser.usuario._id : false) : false)) : false" class="col-1">
                                <q-item clickable v-for="(capitulo, i) in livro.capitulos" :key="i" class="item_edit" @click="goEditCapitulo(capitulo)">
                                    <q-icon name="edit" color="#7A22A7"></q-icon>
                                </q-item>
                            </div>
                            <div v-if="livro.usuario ? (livro.usuario._id == (currentUser ? (currentUser.usuario ? currentUser.usuario._id : false) : false)) : false" class="col-1">
                                <q-item clickable v-for="(capitulo, i) in livro.capitulos" :key="i" class="item_edit" @click="confirmDelCapitulo(capitulo._id, i)">
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
                    <span class="ms-4">{{i18n.dialogs.deletar_historia}}</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat :label="i18n.dialogs.deletar" color="primary" @click="delHistoria"/>
                    <q-btn flat :label="i18n.dialogs.cancelar" color="primary" v-close-popup class="btn-cancelar"/>
                </q-card-actions>
            </q-card>
        </q-dialog>
        <q-dialog v-model="delete_capitulo" persistent class="delete">
            <q-card :class="{'dark-card-delete': darkmode, 'card-delete': !darkmode}">
                <q-card-section class="row items-center">
                    <q-avatar icon="delete" color="primary" text-color="white" />
                    <span class="q-ml-sm">{{i18n.dialogs.deletar_capitulo}}</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat :label="i18n.dialogs.deletar" color="primary" @click="delCapitulo" />
                    <q-btn flat :label="i18n.dialogs.cancelar" color="primary" v-close-popup class="btn-cancelar" @click="function(){
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
    import { environment } from 'src/helpers/environment';

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
                    categoria: {},
                    conteudo_adulto: {},
                    data_atualizacao: '',
                    data_criacao: '',
                    descricao: '',
                    direito_autoral: '',
                    direitos_autorais: {},
                    foto_perfil: '',
                    historia_finalizada: '',
                    id: '',
                    idioma_id: '',
                    nome_usuario: '',
                    publico_alvo: '',
                    tags: [],
                    titulo: '',
                    total_capitulos: '',
                    total_visualizacoes: '',
                    total_votos: '',
                    usar_apelido: '',
                    usuario: {},
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
                capitulo: {},
                i18n: {},
                avisos: {},
                path_photo: `${environment.host}historias/capa-image`,
                path_photo_profile: `${environment.host}usuarios/profile-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
            }
        },
        async mounted(){
            await this.carregarLivro(this.livro_id)
            await this.getAvatar()
            await this.getUser()
        },
        created() {
            this.i18n = this.$i18n.livro
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
                    this.i18n = this.$i18n.livro
                    this.avisos = this.$i18n.avisos
                }, 500)
            });
        },
        methods: {
            async carregarLivro(livro_id){
                
                this.visible_page = true
                this.showSimulatedReturnData = false

                await this.$api.get(`historias/${livro_id}`)
                .then((res) => {
                    this.livro = res.data
                    this.getAvatar()
                    this.getHistoriaFinalizada()

                    this.visible_page = false
                    this.showSimulatedReturnData = true
                })
                .catch((err) => {
                    console.log(err)
                    this.visible_page = false
                    this.showSimulatedReturnData = true
                    this.falha()
                    this.erroCarregar(err, this.avisos.erro_carregar)
                })
            },
            async delHistoria(){
                
                Loading.show()

                await this.$api.delete(`historias/${this.livro._id}`)
                    .then((res) => {
                        this.sucesso()
                        this.$router.push({path: `/perfil`})
                    })
                    .catch((err) => {
                        this.falha()
                    })
                    .finally(() => {
                        this.delete_historia = false
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
            async delCapitulo(){
                
                Loading.show()

                await this.$api.delete(`capitulos/${this.capitulo.capitulo_id}`)
                .then((res) => {
                    this.livro.capitulos.splice(this.capitulo.index, 1)
                    this.capitulo = {}
                    this.sucesso()
                })
                .catch((err) => {
                    console.log(err)
                    this.falha()
                })
                .finally(() => {
                    this.delete_capitulo = false
                    Loading.hide()
                })
            },
            goToPerfil(usuario_id){
                this.$router.push({path: `/perfil/${usuario_id}`}) 
            },
            goEditHistoria(){
                this.$router.push({path: `/editar_livro/${this.livro_id}`})
            },
            goEditCapitulo(capitulo){
                this.$router.push({path: `/editar_capitulo/${capitulo._id}`})
            },
            goAddCapitulo(){
                this.$router.push({path: `/criar_historia/${this.livro_id}`})
            },
            goChapter(capitulo){
                this.$router.push({path: `capitulo/${capitulo._id}`})
            },
            getAvatar(){
                // this.user = {
                //     user_id: 0,
                //     nome: '',
                //     apelido: '',
                //     foto_perfil: 'https://avatars.dicebear.com/api/initials/v.svg',
                // }

                // let user = JSON.parse( this.$q.sessionStorage.getItem('auth').usuario )

                // if(user !== null) {
                //     this.user = user
                // }

            },
            getHistoriaFinalizada(historia_finalizada){
                if(historia_finalizada == 0){ return this.i18n.em_andamento }
                return this.i18n.concluida
            },
            getConteudoAdulto(conteudo_adulto){
                if (conteudo_adulto){return this.i18n.conteudo_adulto}
                return this.i18n.conteudo_livre
            }
        }
    };
</script>
<style lang="scss" scoped>
    @import '../css/livro.scss';
    @import '../css/darkMode/livro-dark.scss';
</style>