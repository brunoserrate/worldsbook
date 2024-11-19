<template>
    <div :class="{'dark-perfil': darkmode }" class="perfil">
        <q-inner-loading
            :showing="visible"
            class="loading"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            :label="i18n.carregando_perfil+'...'"
        ></q-inner-loading>
        <div class="fit row d-flex justify-content-center align-items-center background-cover">
            <div class="col-12">
                <q-img src="https://img.freepik.com/fotos-gratis/fundo-aquarela-pintado-a-mao-com-forma-de-ceu-e-nuvens_24972-1095.jpg?w=2000" class="cover"></q-img>
            </div> 
            <div class="col-12 col_perfil_cover d-flex align-items-center justify-content-center">
                <q-avatar size="80px" class="avatar" style="margin: 61px 0 0 0;">
                    <img :src="usuario ? (usuario.foto_perfil ? `${path_photo}/${usuario.foto_perfil}` : `${path_photo}/default.jpg`) : `${path_photo}/default.jpg`" />
                </q-avatar>
            </div>
            <div class="col-12 col_perfil_cover d-flex align-items-center justify-content-center">
                <p class="cover_apelido">{{ usuario.name }}</p>
            </div>
            <div class="col-12 col_perfil_cover d-flex align-items-center justify-content-center">
                <p class="cover_nome">@{{ usuario.apelido }}</p>
            </div>
        </div>
        <div class="row">
            <div cols="12" class="w-100">
                <q-card class="card_barra">
                    <q-tabs
                        v-model="tabUsuario"
                        align="justify"
                        narrow-indicator
                        class="tab-usuarios"
                        indicator-color="purple"
                    >
                        <q-tab class="tab-a-seguir" name="a_seguir" :label="i18n.a_seguir" />
                        <q-tab class="tab-dashboards" name="dashboard" :label="i18n.dashboard || 'Dashboards'" v-if="!$route.params.perfil_id || $route.params.perfil_id == currentUser._id" />
                        <q-tab class="tab-cadastros" name="cadastros" :label="i18n.cadastros || 'Cadastros'" v-if="!$route.params.perfil_id || $route.params.perfil_id == currentUser._id"/>
                    </q-tabs>
                    <!-- <q-btn v-if="usuario._id == currentUser._id" flat style="primary" :label="i18n.editar_perfil" icon="settings" class="btn-editar-perfil" @click="goEditPerfil"/> -->
                </q-card>
            </div>
        </div>
        <div class="row historias pb-5">
            <div class="col-12">
                <q-tab-panels v-model="tabUsuario" animated class="m-0 p-0 painel-perfil" id="painel-perfil">
                    <q-tab-panel name="a_seguir" class="m-0 p-0 painel-a-seguir">
                        <div class="row m-0 d-flex justify-content-center px-4 px-md-0">
                            <div class="col-12 col-md-4 descricao px-0 px-md-2">
                                <q-card class="card_desc_user mt-4">
                                    <div class="row">
                                        <div class="col-12 px-4">
                                            <h3 class="p-descricao-usuario">{{ i18n.descricao_usuario }}</h3>
                                        </div>
                                        <div class="col-12">
                                            <q-separator color="gray" inset />
                                        </div>
                                        <div class="col-12 offset-0 px-4">
                                            <p class="p-descricao">{{ usuario.sobre }}</p>
                                        </div>
                                    </div>
                                </q-card>
                            </div>
                            <div class="col-12 col-md-6 desktop-carousel px-0 px-md-2">
                                <q-card class="card_historias px-4 py-4 mt-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4 class="historias-de-usuario m-0 mt-3">{{ i18n.historias.historias_de }} {{ usuario.apelido }}</h4>
                                        </div>
                                        <div class="col-12">
                                            <p class="qtd_historias">{{ historias.count }} {{ i18n.historias.historias_publicadas }}</p>
                                        </div>
                                        <div class="row row_livros w-100 mt-3 mb-4 mb-sm-2" v-for="(livro, i) in historias.historias" :key="i" @click="goLivro(livro)">
                                            <div class="col-12 col-sm-4 col-lg-3 d-flex align-items-center justify-content-center justify-sm-content-start">
                                                <img :src="livro.caminho_capa ? (livro.caminho_capa ? `${path_cover}/${livro.caminho_capa}` : ``) : `${path_cover}/default.png`" class="livro_cover"/>
                                            </div>
                                            <div class="col-12 col-sm-7 col-lg-8 py-2 col-historia-details">
                                                <div class="row h-100 d-flex align-content-space-between">
                                                    <div class="col-12">
                                                        <p class="livro_titulo text-center text-sm-start">{{ livro.titulo ? livro.titulo : i18n.historias.historia_sem_titulo }}</p>
                                                        <div class="row align-icons-historias w-100 justify-content-center justify-sm-content-start">
                                                            <div class="col-2 align_icone d-flex align-items-center justify-content-center justify-sm-content-start">
                                                                <q-icon name="grade" class="icons_card me-1" />
                                                                <div class="col-1 align_result"> {{ livro.total_votos ? livro.total_votos : 0 }} </div>
                                                            </div>
                                                            <div class="col-2 align_icone d-flex align-items-center justify-content-center justify-sm-content-start">
                                                                <q-icon name="visibility" class="icons_card me-1" />
                                                                <div class="col-1 align_result"> {{ livro.total_visualizacoes ? livro.total_visualizacoes : 0 }} </div>
                                                            </div>
                                                            <div class="col-2 align_icone d-flex align-items-center justify-content-center justify-sm-content-start">
                                                                <q-icon name="list" class="icons_card me-1" />
                                                                <div class="col-1 align_result"> {{ livro.total_capitulos ? livro.total_capitulos : 0 }} </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mt-4">
                                                        <p class="p_descricao text-justify"> {{ livro.descricao | cutDescricao }} </p>
                                                    </div>
                                                    <div class="col-12 mt-1">
                                                        <div class="col-12">
                                                            <q-chip class="historia_finalizada m-0 me-2">{{ getHistoriaFinalizada(livro.historia_finalizada) }}</q-chip>
                                                            <q-chip class="historia_finalizada m-0 " :style="`background-color: ${livro.status ? (livro.status.color ? livro.status.color : '#000') : '#000'}`">{{ livro.status ? livro.status.nome : '' }}</q-chip>
                                                        </div>
                                                        <div class="col-12 mt-1">
                                                            <p class="p_data p-0 m-0"><strong>{{ i18n.historias.data_atualizacao }}:</strong> {{ livro.updatedAt | formatDateTime }} </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row w-100 mt-5 mb-4 mb-sm-2 paginacao" v-if="pagesNumber > 0">
                                            <div class="col-12">
                                                <q-pagination
                                                    v-model="page"
                                                    :max="pagesNumber"
                                                    direction-links
                                                    color="grey"
                                                    active-color="primary"
                                                    class="paginacao"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </q-card>
                            </div>
                        </div>
                    </q-tab-panel>
                    <q-tab-panel name="dashboard" class="m-0 p-0 painel-dashboard">
                        <div class="row m-0">
                            <div class="col-12">
                                <visao-gestor />
                            </div>
                        </div>
                    </q-tab-panel>
                    <q-tab-panel name="cadastros" class="m-0 p-0 painel-cadastros">
                        <div class="row m-0">
                            <div class="col-12">
                                <projeto-convite />
                            </div>
                        </div>
                    </q-tab-panel>
                </q-tab-panels>
            </div>
        </div>
    </div>
</template>
<script>
    import eventBus from '../boot/eventBus';
    import { environment } from 'src/helpers/environment';
    import ProjetosVisaoGestor from 'src/components/Projetos/Gestor/index.vue';
    import ProjetoConvite from 'src/components/Projetos/Gestor/Convite.vue';

    export default {
        name: 'criar-historia',
        data(){
            return {
                user: {},
                i18n: {},
                avisos: {},
                tabUsuario: 'a_seguir',
                perfil_id: '',
                usuario: {
                    apelido: '',
                    avatar: '',
                    email: '',
                    nome: '',
                    token: '',
                    usar_apelido: '',
                    capa: '',
                    historias: []
                },
                historias: [],
                slide: 1,
                page: 1,
                pagesNumber: 0,
                limit: 10,
                visible: false,
                showSimulatedReturnData: false,
				darkmode: false,
                isInitialLoad: true,
				currentUser: this.$q.sessionStorage.getItem('auth'),
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
            }
        },

        mounted(){
            let param_id = this.$route.params.perfil_id
            this.perfil_id = this.$route.params.perfil_id || ''
            this.currentUser = this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario : '') : ''
            
            this.tabUsuario = 'a_seguir'
            
            if(param_id != undefined){
                this.getUserEndpoint(param_id)
                this.getHistorias(param_id)
            }
            else {
                this.getUserEndpoint(0)
                this.getHistorias(this.currentUser._id)
            }
        },
        components: {
            VisaoGestor: ProjetosVisaoGestor,
            ProjetoConvite: ProjetoConvite
        },
        created() {
            this.i18n = this.$i18n.perfil
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
                    this.i18n = this.$i18n.perfil
                    this.avisos = this.$i18n.avisos
                }, 500)
            });
        },
        filters: {
            cutDescricao(value){
                let tamanho_max = 150;

                if(value != undefined && value != null) {
                    if(value.length > tamanho_max) {
                        return value.substring(0, tamanho_max) + '...'
                    }
                    return value
                }

            }
        },
        methods: {
            goLivro(livro){
                this.$router.push({path: `/livro/${livro._id}`})
            },
            goEditPerfil(){
                this.$router.push({path: `/editar_perfil/` + this.currentUser._id})
            },
            async changePage(e) {
                
                if(this.perfil_id != undefined){
                    this.getUserEndpoint(this.perfil_id)
                    this.getHistorias(this.perfil_id)
                }
                else {
                    this.getUserEndpoint(0)
                    this.getHistorias(this.currentUser._id)
                }
                
                let element = document.getElementById('painel-perfil');
                if (element) {
                    let pixels_parar = 50;
                    let offsetTop = element.offsetTop - pixels_parar;
                    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            },
            async getHistorias(user){
                this.visible_page = true
                this.showSimulatedReturnData = false

                await this.$api.get(`historias?usuario=${user}&user=${this.currentUser._id ? this.currentUser._id : ''}&limit=${this.limit}&page=${this.page}`)
                .then((res) => {
                    this.historias = res.data
                    this.pagesNumber = Math.ceil(this.historias.count / this.limit)
                })
                .catch((err) => {
                    console.log(err)
                    this.falha()
                    this.erroCarregar(err, this.avisos.erro_carregar)
                })
            },
            async getUserEndpoint(user_id){
                this.visible = true
                this.showSimulatedReturnData = false

                await this.$api.get(`usuarios/${user_id ? user_id : this.currentUser._id}`)
                .then((res) => {
                    this.usuario = res.data

                    this.visible = false
                    this.showSimulatedReturnData = true
                })
                .catch((err) => {
                    console.log(err.response)
                    this.visible = false
                    this.showSimulatedReturnData = true
                    this.erroCarregar(err, this.avisos.erro_carregar)
                })
            },
            getHistoriaFinalizada(historia_finalizada){
                if(historia_finalizada == 0){ return this.i18n.em_andamento }
                return this.i18n.concluida
            },
        },
        watch: {
            page() {
                this.changePage();
                if (!this.isInitialLoad) {
                } else {
                    this.isInitialLoad = false
                }
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import '../css/perfil.scss';
    @import '../css/darkMode/perfil-dark.scss';
</style>