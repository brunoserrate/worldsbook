<template>
    <q-page :class="{'dark-pesquisa_livros': darkmode, 'pesquisa_livros': !darkmode}">
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
						            <img alt="Cover" :src="livro.caminho_capa" class="cover_historia"/>
                                </div>
                                <div class="col-6 col-detalhes-historia">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="livro_titulo">{{livro.titulo}} </p>
                                        </div>
                                        <div class="col-12">
                                            <p class="livro_autor">{{i18n.de}} {{livro.apelido_usuario}} </p>
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
                                            <q-chip v-for="(tag, i) in livro.tags.slice(0, 4)" :key="i" >{{tag}}</q-chip> <!--Dar um slice-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </q-card>
                    </div>
                </div>
            </div>
        </div>
		<q-dialog v-model="livro_dialog_mobile" class="navbar_classe_mobile">
			<q-card :class="{ 'dark-card_detail_historia_mobile': darkmode, 'card_detail_historia_mobile': !darkmode }">
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
								<q-btn unelevated :label="i18n.dialogs.iniciar_leitura" class="btn_detail_iniciar_leitura" @click="goLivro(livro_detail)"/>
							</div>
						</div>
						<q-separator></q-separator>
						<template q-slot="footer">
							<div class="row">
								<div class="col-12 col_btn_detail">
									<p class="col_data_atualizacao"><span>{{i18n.dialogs.data_atualizacao}}: </span>{{ livro_detail.data_atualizacao | formatDateTime }}</p>
								</div>
							</div>
						</template>
					</div>
				</div>
			</q-card>
		</q-dialog>
		<q-dialog v-model="livro_dialog" class="navbar_classe">
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
								<q-btn unelevated :label="i18n.dialogs.iniciar_leitura" class="btn_detail_iniciar_leitura" @click="goLivro(livro_detail)"/>
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
									<p class="col_data_atualizacao"><span>{{i18n.dialogs.data_atualizacao}}: </span>{{ livro_detail.data_atualizacao | formatDateTime }}</p>
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
            showSimulatedReturnData: false
        }
    },
    mounted(){
        this.getLivros()
        // console.log(this.pesquisa)
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
		getLivros(){
			let that = this

            that.visible = true
            that.showSimulatedReturnData = false
            // console.log(this.$route.params.pesquisa)
			that.$axios.get(that.$pathAPI + `/historia/pesquisa?pesquisa=${this.pesquisa}`)
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
                this.erroCarregar(err, this.avisos.erro_carregar)
			})
		},
        getLivro(livro){
            // console.log(livro)
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
    @import '../css/pesquisa_livros.scss';
    @import '../css/dialogs.scss';
    /* DARK MODE */
    @import '../css/darkMode/livro_categorias-dark.scss';
    @import '../css/darkMode/pesquisa_livros-dark.scss';
    @import '../css/darkMode/dialogs-dark.scss';
</style>