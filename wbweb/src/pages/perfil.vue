<template>
    <q-page :class="{'dark-perfil': darkmode, 'perfil': !darkmode}">
        <q-inner-loading
            :showing="visible"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            :label="i18n.carregando_perfil+'...'"
        ></q-inner-loading>
        <div class="fit row justify-center items-center content-center user-cover background-cover">
            <div class="col-12">
                <q-img src="https://img.freepik.com/fotos-gratis/fundo-aquarela-pintado-a-mao-com-forma-de-ceu-e-nuvens_24972-1095.jpg?w=2000" class="cover"></q-img>
            </div> 
            <div class="col-12 col_perfil_cover">
                <q-avatar size="80px" class="avatar" style="margin: 61px 0 0 0;">
                    <img :src="usuario.foto_perfil" />
                </q-avatar>
            </div>
            <div class="col-12 col_perfil_cover">
                <p class="cover_apelido">{{usuario.name}}</p>
            </div>
            <div class="col-12 col_perfil_cover">
                <p class="cover_nome">@{{usuario.apelido}}</p>
            </div>
        </div>
        <div class="row">
            <div cols="12" style="width: 100%;">
                <q-card class="card_barra">
                    <p class="a-seguir">{{ i18n.a_seguir }}</p>
                    <q-btn v-if="usuario.user_id == user.user_id" flat style="primary" :label="i18n.editar_perfil" icon="settings" class="btn-editar-perfil" @click="goEditPerfil"/>
                </q-card>
            </div>
        </div>
        <div class="row historias">
            <div class="col-10 col-md-4 offset-1">
                <q-card class="card_desc_user">
                    <div class="row">
                        <div class="col-11 offset-1">
                            <h3 class="p-descricao-usuario">{{ i18n.descricao_usuario }}</h3>
                        </div>
                        <div class="col-12">
                            <q-separator color="gray" inset />
                        </div>
                        <div class="col-11 offset-1">
                            <p class="p-descricao">{{ usuario.sobre }}</p>
                        </div>
                    </div>
                </q-card>
            </div>
            <div class="col-10 offset-1 mobile-carousel">
                <q-card class="carousel-card">
                    <div class="row">
                        <div class="col-10 offset-1 col-md-12 offset-md-0">
                            <h4 class="historias-de-usuario">{{ i18n.historias.historias_de }} {{ usuario.apelido }}</h4>
                        </div>
                        <div class="col-10 offset-1 col-md-12 offset-md-0">
                            <p class="qtd_historias">{{usuario.qtd_historias}} {{ i18n.historias.historias_publicadas }}</p>
                        </div>
                        <div class="col-12">
                            <q-carousel
                                v-model="slide"
                                transition-prev="slide-right"
                                transition-next="slide-left"
                                swipeable
                                animated
                                control-color="white"
                                padding
                                arrows
                                class="shadow-2 rounded-borders carousel-format"
                                >
                                <q-carousel-slide :name="i" class="column no-wrap" v-for="(livro, i) in usuario.historias" :key="i" @click="goLivro(livro)">
                                    <div class="row">
                                        <div class="col-12 col-sm-7 align_cover_carousel">
                                            <img :src="livro.caminho_capa" class="livro_cover-carousel">
                                        </div>
                                        <div class="col-12 col-sm-5">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="livro_titulo_carousel">{{livro.titulo}}</p>
                                                </div>
                                                <div class="row" style="width: 100%; margin: 0 0px 0 -17px; justify-content: center;">
                                                    <div class="col-2 align_icone">
                                                        <q-icon name="grade" class="icons_card" />
                                                    </div>
                                                    <div class="col-2 align_result"> {{livro.total_votos}} </div>
                                                    <div class="col-2 align_icone">
                                                        <q-icon name="visibility" class="icons_card" />
                                                    </div>
                                                    <div class="col-2 align_result"> {{livro.total_visualizacoes}} </div>
                                                    <div class="col-2 align_icone">
                                                        <q-icon name="list" class="icons_card" />
                                                    </div>
                                                    <div class="col-2 align_result"> {{livro.total_capitulos}} </div>
                                                </div>
                                                <div class="col-12">
                                                    <p class="p_descricao"> {{livro.descricao | cutDescricao}} </p>
                                                </div>
                                                <div class="col-12">
                                                    <q-chip class="historia_finalizada">{{getHistoriaFinalizada(livro.historia_finalizada)}}</q-chip>
                                                </div>
                                                <div class="col-12">
                                                    <p class="p_data">{{ i18n.historias.data_atualizacao }}: {{ livro.data_atualizacao | formatDateTime }} </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </q-carousel-slide>
                            </q-carousel>
                        </div>
                    </div>
                </q-card>
            </div>
            <div class="col-10 offset-1 offset-md-0 col-md-6 desktop-carousel">
                <q-card class="card_historias">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="historias-de-usuario">{{ i18n.historias.historias_de }} {{ usuario.apelido }}</h4>
                        </div>
                        <div class="col-12">
                            <p class="qtd_historias">{{usuario.qtd_historias}} {{ i18n.historias.historias_publicadas }}</p>
                        </div>
                        <div class="row row_livros" v-for="(livro, i) in usuario.historias" :key="i" @click="goLivro(livro)">
                            <div class="col-4 col-lg-3">
                                <img :src="livro.caminho_capa" class="livro_cover"/>
                            </div>
                            <div class="col-7  col-lg-8" style="margin: 0 0px 0 13px;">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="livro_titulo">{{livro.titulo}}</p>
                                    </div>
                                    <div class="row align-icons-historias">
                                        <div class="col-1 align_icone">
                                            <q-icon name="grade" class="icons_card" />
                                        </div>
                                        <div class="col-1 align_result"> {{livro.total_votos}} </div>
                                        <div class="col-1 align_icone">
                                            <q-icon name="visibility" class="icons_card" />
                                        </div>
                                        <div class="col-1 align_result"> {{livro.total_visualizacoes}} </div>
                                        <div class="col-1 align_icone">
                                            <q-icon name="list" class="icons_card" />
                                        </div>
                                        <div class="col-1 align_result"> {{livro.total_capitulos}} </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="p_descricao"> {{livro.descricao | cutDescricao}} </p>
                                    </div>
                                    <div class="col-12">
                                        <q-chip class="historia_finalizada">{{getHistoriaFinalizada(livro.historia_finalizada)}}</q-chip>
                                    </div>
                                    <div class="col-12">
                                        <p class="p_data">{{ i18n.historias.data_atualizacao }}: {{ livro.data_atualizacao | formatDateTime }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
    </q-page>
</template>
<script>
    import eventBus from '../boot/eventBus'
    export default {
        name: 'criar-historia',
        data(){
            return {
                user: {},
                i18n: {},
                avisos: {},
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
                slide: 1,
                descricao: 'Siga-me nas redes sociais! :D',
                visible: false,
                showSimulatedReturnData: false,
				darkmode: false,
            }
        },

        mounted(){
            let param_id = this.$route.params.perfil_id

            if(param_id != undefined){
                this.getUser()
                this.getUserEndpoint(param_id)
            }
            else {
                this.getUser()
                this.getUserEndpoint(0)
            }
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
                // console.log(livro)
                this.$router.push({path: `/livro/` + livro.id})
            },
            goEditPerfil(){
                this.$router.push({path: `/editar_perfil/` + this.user.user_id})
            },
            getUserEndpoint(user_id){
                let that = this

                that.visible = true
                that.showSimulatedReturnData = false

                let url = `/user/pesquisa?pesquisa=${this.user.user_id}`

                if(user_id != 0){
                    url = `/user/pesquisa?pesquisa=${user_id}`
                }

                that.$axios.get(that.$pathAPI + url)
                .then((res) => {
                    this.usuario = res.data.data
                    // console.log(that.usuario)

                    that.visible = false
                    that.showSimulatedReturnData = true
                })
                .catch((err) => {
                    console.log(err.response)
                    that.visible = false
                    that.showSimulatedReturnData = true
                    this.erroCarregar(err, this.avisos.erro_carregar)
                })
            },
            getHistoriaFinalizada(historia_finalizada){
                if(historia_finalizada == 0){ return this.i18n.em_andamento }
                return this.i18n.concluida
            },
        }
    }
</script>
<style lang="scss" scoped>
    @import '../css/perfil.scss';
    @import '../css/darkMode/perfil-dark.scss';
</style>