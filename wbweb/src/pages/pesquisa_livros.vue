<template>
    <q-page :class="{'dark-pesquisa_livros': darkmode, 'pesquisa_livros': !darkmode}" id="pesquisa-livros">
        <q-inner-loading
            :showing="visible"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            :label="i18n.carregando+'...'"
        ></q-inner-loading>
        <div class="row row_categorias" >
            <div class="col-12">
                <q-card style="height: 100%;" class="card_search">
                    <div class="row">
                        <div class="col-12">
                            <p class="p_text"><q-icon name="search"></q-icon> {{i18n.sua_pesquisa_retornou}} {{ livros.length }} {{ (livros.length == 1) ? i18n.qtd_resultados.singular : i18n.qtd_resultados.plural }} </p>
                        </div>
                    </div>
                </q-card>
            </div>
            <div class="col-12 align_livros">
                <div class="row row_livros_mobile">
                    <div class="col-12"><p class="total_historias">{{livros.length}} {{ (livros.length == 1) ? i18n.qtd_historias.singular : i18n.qtd_historias.plural }}</p></div>
                    <div class="col-12 col-md-6" v-for="(livro, i) in livros" :key="i">
                        <q-card class="card-categorias" @click="openDialog(livro)">
                            <div class="row">
                                <div class="col-6 col-sm-4 col-md-6 col-lg-4">
						            <img alt="Cover" :src="livro.caminho_capa ? (livro.caminho_capa ? `${path_cover}/${livro.caminho_capa}` : ``) : `${path_cover}/default.png`" class="cover_historia"/>
                                </div>
                                <div class="col-6 col-detalhes-historia">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="livro_titulo">{{livro.titulo}} </p>
                                        </div>
                                        <div class="col-12">
                                            <p class="livro_autor">{{i18n.de}} {{livro.usuario ? livro.usuario.name : ''}} </p>
                                        </div>
                                        <div class="col-12">
                                            <div class="row  row_icones">
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
                                        </div>
                                        <div class="col-12">
                                            <p class="livro_descricao">{{livro.descricao | cutDescricao}}</p>
                                        </div>
                                        <div class="col-12">
                                            <q-chip v-for="(tag, i) in livro.tags.slice(0, 4)" :key="i" >{{tag.nome}}</q-chip> <!--Dar um slice-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </q-card>
                    </div>
                    <div class="col-12 mt-4 paginacao d-flex justify-content-center">
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
        </div>
		<q-dialog v-model="livro_dialog_mobile" class="navbar_classe_mobile">
			<q-card :class="{'dark-card_detail_historia': darkmode, 'card_detail_historia': !darkmode}">
				<div class="row" style="height: 100%;">
					<div class="col-12 cover_dialog">
						<img alt="Cover" :src="livro_detail.caminho_capa ? (livro_detail.caminho_capa ? `${path_cover}/${livro_detail.caminho_capa}` : ``) : `${path_cover}/default.png`" class="cover_detail_historia"/>
					</div>
					<div class="col-12"> 
						<h1 class="title_dialog_historia">{{livro_detail.titulo}}</h1>
						<div class="row">
							<div class="col-12" style="display: flex; justify-content: center;">
								<hr style="margin: 0 0 0 0; width: 80%;" class="w-100" />
							</div>
							<div class="col-12 col_btn_detail px-5">
								<p class="col_descricao_detail">{{livro_detail.descricao | cutDescricao}}</p>
							</div>
							<div class="col-12 col_btn_detail px-5">
								<q-btn unelevated :label="i18n.dialogs.iniciar_leitura" class="btn_detail_iniciar_leitura w-100" @click="goLivro(livro_detail)"/>
							</div>
						</div>
						<q-separator></q-separator>
						<template q-slot="footer">
							<div class="row">
								<div class="col-12 col_btn_detail">
									<p class="col_data_atualizacao"><span>{{i18n.dialogs.data_atualizacao}}: </span>{{ livro_detail.updatedAt | formatDateTime }}</p>
								</div>
							</div>
						</template>
					</div>
				</div>
			</q-card>
		</q-dialog>
		<q-dialog v-model="livro_dialog">
			<q-card :class="{ 'dark-card_detail_historia_mobile_index': darkmode, 'card_detail_historia_mobile_index': !darkmode }">
				<div class="row" style="height: 100%;">
					<div class="col-6">
						<img alt="Cover" :src="livro_detail.caminho_capa ? (livro_detail.caminho_capa ? `${path_cover}/${livro_detail.caminho_capa}` : ``) : `${path_cover}/default.png`" class="cover_detail_historia"/>
					</div>
					<div class="col-6">
						<div class="row h-100 d-flex justify-content-space-between">
							<div class="col-12">
								<h1 class="title_dialog_historia">{{livro_detail.titulo}}</h1>
								<q-separator class="separador mb-4"></q-separator>

								<div class="row m-0 p-0 mt-4">
									<div class="col-12 col_btn_detail d-flex align-items-center px-4">
										<q-btn unelevated :label="i18n.dialogs.iniciar_leitura" class="btn_detail_iniciar_leitura me-2" @click="goLivro(livro_detail)"/>
										<q-btn unelevated label="+" class="btn_detail_iniciar_leitura_mais"/>
									</div>
									<div class="col-12 col_btn_detail px-4">
										<p class="col_descricao_detail">{{livro_detail.descricao | cutDescricao}}</p>
									</div>
								</div>
							</div>
							<div class="col-12 col_btn_detail d-flex flex-direction-column justify-content-end">
								<q-separator class="separador mb-4"></q-separator>
								<p class="col_data_atualizacao"><span>{{i18n.dialogs.data_atualizacao}}: </span>{{ livro_detail.updatedAt | formatDateTime }}</p>
							</div>
						</div>
					</div>
				</div>
			</q-card>
		</q-dialog>
    </q-page>
</template>
<script>
    import eventBus from '../boot/eventBus'
    import { environment } from 'src/helpers/environment';

    export default {
        name:'livro-categoria',
        data (){
            return {
                pesquisa: this.$route.params.pesquisa,
                categoria: {},
                livro_dialog: false,
                livro_dialog_mobile: false,
                darkmode: false,
                livro_detail: {},
                avisos: {},
                i18n: {},
                livros:[],
                livro: {
                    apelido_usuario: '',
                    caminho_capa: '',
                    capitulos: '',
                    categoria: '',
                    conteudo_adulto: '',
                    data_atualizacao: '',
                    data_criacao: '',
                    descricao: '',
                    direito_autoral: '',
                    direitos_autorais: '',
                    foto_perfil: '',
                    historia_finalizada: '',
                    id: '',
                    idioma: '',
                    nome_usuario: '',
                    publico_alvo: '',
                    tags: '',
                    titulo: '',
                    total_capitulos: '',
                    total_visualizacoes: '',
                    total_votos: '',
                    usar_apelido: '',
                    usuario: '',
                },
                window: {
                    width: 0,
                },
                visible: false,
                page: 1,
                pagesNumber: 0,
                limit: 10,
                showSimulatedReturnData: false,
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
            }
        },
        mounted(){
            this.getLivros()
        },
        created() {
            window.addEventListener('resize', this.handleResize);
            this.handleResize();
            this.i18n = this.$i18n.livro_categorias
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
                    this.i18n = this.$i18n.livro_categorias
                    this.avisos = this.$i18n.avisos
                }, 500)
            });
        },
        destroyed() {
            window.removeEventListener('resize', this.handleResize);
        },
        watch:{
            '$route' (to,from){
                this.livros = [];
                this.pesquisa = this.$route.params.pesquisa
                this.getLivros()
                // this.$set(this,'token', this.$route.params.token)
            },
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
            handleResize() {
                this.window.width = window.innerWidth;
            },
            async changePage(e) {
                await this.getLivros()

                let element = document.getElementById('pesquisa-livros');
                if (element) {
                    let pixels_parar = 50;
                    let offsetTop = element.offsetTop - pixels_parar;
                    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            },
            async getLivros(){
                this.visible = true
                this.showSimulatedReturnData = false
                await this.$api.get(`historias?pesquisa=${this.pesquisa}&limit=${this.limit}&page=${this.page}`)
                .then((res) => {
                    this.livros = res.data.historias
                    this.pagesNumber = Math.ceil(res.data.count / this.limit)

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
            getLivro(livro){
                this.livro_detail = livro
                this.livro_dialog = true
            },
            openDialog(livro){
                if (this.window.width > 980){
                    this.getLivro(livro)
                }else { 
                    this.getLivroMobile(livro)
                }   
            },
            getLivroMobile(livro){
                this.livro_detail = livro
                this.livro_dialog_mobile = true
            },
            goLivro(livro_detail){
                this.$router.push({path: `/livro/${livro_detail._id}`})
            }
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
    @import '../css/livro_categorias.scss';
    @import '../css/pesquisa_livros.scss';
    @import '../css/dialogs.scss';
    /* DARK MODE */
    @import '../css/darkMode/livro_categorias-dark.scss';
    @import '../css/darkMode/pesquisa_livros-dark.scss';
    @import '../css/darkMode/dialogs-dark.scss';
</style>