<template>
    <q-page :class="{ 'dark-livro_categorias': darkmode, 'livro_categorias': !darkmode }">
        <q-inner-loading
            :showing="visible"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            label="Carregando livros..."
        ></q-inner-loading>
        <div class="row row_categorias" >
            <div class="col-12">
                <q-card style="height: 100%;" class="card_search">
                    <div class="row">
                        <div class="col-12">
                            <p class="p_search">Aqui estão os melhores livro na categoria de {{categoria | lowercase}}</p>
                        </div>
                        <div class="col-12">
                            <p class="p_text">Diversos livro para todos os gostos!</p>
                        </div>
                    </div>
                </q-card>
            </div>
            <div class="col-12 align_livros">
                <div class="row row_livros_mobile">
                    <div class="col-6"><p class="total_historias">{{livros.length}} histórias</p></div>
                    <div class="col-6 text-right">
                        <q-btn flat style="color: #7A22A7" label="Voltar" @click="goBackPageCategorias"/>
                    </div>
                    <div class="col-12 col-md-6" v-for="(livro, i) in livros" :key="i">
                        <q-card class="card-categorias" @click="openDialog(livro)">
                            <div class="row">
                                <div class="col-6 col-sm-3 col-md-5">
						            <img alt="Cover" :src="livro.caminho_capa" class="cover_historia"/>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="livro_titulo">{{livro.titulo}} </p>
                                        </div>
                                        <div class="col-12">
                                            <p class="livro_autor">de {{livro.apelido_usuario}} </p>
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
                                            <q-chip v-for="(tag, i) in livro.tags.slice(0,4)" :key="i" class="tags">{{tag}}</q-chip> <!--Dar um slice-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </q-card>
                    </div>
                </div>
            </div>
        </div>
		<q-dialog v-model="livro_dialog_mobile">
			<q-card :class="{'dark-card_detail_historia_mobile': darkmode, 'card_detail_historia_mobile': !darkmode}">
				<div class="row" style="height: 100%;">
					<div class="col-12 cover_dialog">
						<img alt="Cover" :src="livro_detail.caminho_capa" class="cover_detail_historia"/>
					</div>
					<div class="col-12"> 
						<h1 class="title_dialog_historia">{{livro_detail.titulo}}</h1>
						<div class="row">
							<div class="col-12" style="display: flex; justify-content: center;">
								<hr style="margin: 0 0 0 0; width: 80%;"/>
							</div>
							<div class="col-12 col_btn_detail">
								<p class="col_descricao_detail_mobile">{{livro_detail.descricao | cutDescricao}}</p>
							</div>
							<div class="col-12 col_btn_detail">
								<q-btn unelevated label="Iniciar leitura" class="btn_detail_iniciar_leitura" @click="goLivro(livro_detail)"/>
							</div>
						</div>
						<q-separator></q-separator>
						<template q-slot="footer">
							<div class="row">
								<div class="col-12 col_btn_detail">
									<p class="col_data_atualizacao"><span>Data de atualização: </span>{{ livro_detail.data_atualizacao | formatDateTime }}</p>
								</div>
							</div>
						</template>
					</div>
				</div>
			</q-card>
		</q-dialog>
		<q-dialog v-model="livro_dialog">
			<q-card :class="{'dark-card_detail_historia_desktop': darkmode, 'card_detail_historia_desktop': !darkmode}">
				<div class="row" style="height: 100%;">
					<div class="col-6">
						<img alt="Cover" :src="livro_detail.caminho_capa" class="cover_detail_historia"/>
					</div>
					<div class="col-6">
						<h1 class="title_dialog_historia_desktop">{{livro_detail.titulo}}</h1>
						<div class="row">
							<div class="col-12" style="display: flex; justify-content: center;">
								<hr style="margin: 0 0 0 0; width: 80%;"/>
							</div>
							<div class="col-10 col_btn_detail">
								<q-btn unelevated label="Iniciar leitura" class="btn_detail_iniciar_leitura" @click="goLivro(livro_detail)"/>
							</div>
							<div class="col-2 col_btn_detail">
								<q-btn unelevated label="+" class="btn_detail_iniciar_leitura"/>
							</div>
							<div class="col-12 col_btn_detail_desktop">
								<p class="col_descricao_detail_desktop">{{livro_detail.descricao | cutDescricao}}</p>
							</div>
						</div>
						<q-separator></q-separator>
						<template q-slot="footer">
							<div class="row">
								<div class="col-12 col_btn_detail">
									<p class="col_data_atualizacao"><span>Data de atualização: </span>{{ livro_detail.data_atualizacao | formatDateTime }}</p>
								</div>
							</div>
						</template>
					</div>
				</div>
			</q-card>
		</q-dialog>
    </q-page>
</template>
<script>
    import eventBus from '../boot/eventBus'
    export default {
        name:'livro-categoria',
        data (){
            return {
                categoria_id: this.$route.params.categoria_id,
                categoria: this.$route.params.categoria_nome,
                livro_dialog: false,
                livro_dialog_mobile: false,
                livro_detail: {},
                livros:[],
                livro: {
                    apelido_usuario: '',
                    caminho_capa: '',
                    capitulos: '',
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
                    tags: '',
                    titulo: '',
                    total_capitulos: '',
                    total_visualizacoes: '',
                    total_votos: '',
                    usar_apelido: '',
                    usuario_id: '',
                },
                window: {
                    width: 0,
                },
                visible: false,
                showSimulatedReturnData: false,
                darkmode: false
            }
        },
        mounted(){
            this.getLivros()
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
        created() {
            window.addEventListener('resize', this.handleResize);
            this.handleResize();
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
        destroyed() {
            window.removeEventListener('resize', this.handleResize);
        },
        methods: {
            handleResize() {
                this.window.width = window.innerWidth;
            },
            getLivros(){
                let that = this

                that.visible = true
                that.showSimulatedReturnData = false

                that.$axios.get(that.$pathAPI + `/historia/categoria/pesquisa?categoria_id=${this.categoria_id}`)
                .then((res) => {
                    that.livros = res.data.data
                    // console.log("livros", that.livros)

                    that.visible = false
                    that.showSimulatedReturnData = true
                })
                .catch((err) => {
                    console.log(err.response)
                    that.visible = false
                    that.showSimulatedReturnData = true
                    this.erroCarregar()
                })
            },
            goBackPageCategorias(){
                this.$router.push({ path: `/categorias` })
            },
            openDialog(livro){
                if (this.window.width > 980){
                    this.getLivro(livro)
                }else { 
                    this.getLivroMobile(livro)
                }   
            },
            getLivro(livro){
                // console.log(livro)
                this.livro_detail = livro
                this.livro_dialog = true
            },
            getLivroMobile(livro){
                // console.log(livro)
                this.livro_detail = livro
                this.livro_dialog_mobile = true
            },
            goLivro(livro_detail){
                // console.log(livro_detail)
                this.$router.push({path: `/livro/` + livro_detail.id})
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import '../css/livro_categorias.scss';
    @import '../css/dialogs.scss';
    @import '../css/darkMode/livro_categorias-dark.scss';
    @import '../css/darkMode/dialogs-dark.scss';
</style>