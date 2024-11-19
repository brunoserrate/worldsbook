<template>
    <div>
		<!-- :bullets="false" -->
        <vueper-slides
            class="no-shadow slides_style"
            :visible-slides="3"
            :arrows="true"
            :slide-ratio="1 / 2"
            slide-multiple
            :gap="3"
            :dragging-distance="70"
            >
            <vueper-slide
                v-for="(livro, i) in livros.historias" :key="i"
                :image="livro.caminho_capa ? (livro.caminho_capa ? `${path_photo}/${livro.caminho_capa}` : ``) : `${path_photo}/default.png`" class="slides_historias"
                @click.native="openDialog(livro)"
            />
        </vueper-slides>
		<q-dialog v-model="livro_dialog" class="navbar_classe">
			<q-card :class="{'dark-card_detail_historia': darkmode, 'card_detail_historia': !darkmode}">
				<div class="row" style="height: 100%;">
					<div class="col-12 cover_dialog">
						<img alt="Cover" :src="livro_detail.caminho_capa ? (livro_detail.caminho_capa ? `${path_photo}/${livro_detail.caminho_capa}` : ``) : `${path_photo}/default.png`" class="cover_detail_historia"/>
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
								<q-btn unelevated :label="i18n.iniciar_leitura" class="btn_detail_iniciar_leitura w-100" @click="getLivro(livro_detail)"/>
							</div>
						</div>
						<q-separator></q-separator>
						<template q-slot="footer">
							<div class="row">
								<div class="col-12 col_btn_detail">
									<p class="col_data_atualizacao"><span>{{i18n.data_atualizacao}}: </span>{{ livro_detail.updatedAt | formatDateTime }}</p>
								</div>
							</div>
						</template>
					</div>
				</div>
			</q-card>
		</q-dialog>
    </div>
</template>
<script>
	import { VueperSlides, VueperSlide } from 'vueperslides'
	import 'vueperslides/dist/vueperslides.css'
	import eventBus from '../../boot/eventBus'
	import { environment } from 'src/helpers/environment';

	export default {
		// props:['breadcrumbs'],
		name: 'slideCategoriaVueMobile',
		props: ['categoriaID'],
		data (){
			return {
				sessao: false,
				darkmode: false,
				livro_dialog: false,
				livros:[],
				i18n: {},
				livro_detail: {
					caminho_capa: '', 
					categoria: '',
					conteudo_adulto: '',
					descricao: '',
					direitos_autorais: '',
					idioma: '',
					publico_alvo: '',
					titulo: '',
					usuario: '',
				},
				slide: 1,
				slides: [
					{
						title: 'Slide #1',
						content: 'Slide content.'
					}
				],
				
                path_photo: `${environment.host}historias/capa-image`,
                path_photo_profile: `${environment.host}usuarios/profile-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
			}
		},
		mounted(){
			this.buscarLivros()
		},
		components: { VueperSlides, VueperSlide },
		filters: {
			cutDescricao(value){
				let tamanho_max = 300;

				if(value != undefined && value != null) {
					if(value.length > tamanho_max) {
						return value.substring(0, tamanho_max) + '...'
					}
					return value
				}

			}
		},
		created() {
			this.i18n = this.$i18n.livro_dialogs
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
					this.i18n = this.$i18n.livro_dialogs
				}, 500)
			});
		},
		methods:{
			getLivro(livro_detail){
				this.$router.push({path: `livro/` + livro_detail.id})
			},
			openDialog(livro){
				this.livro_dialog = true
				this.livro_detail = livro
			},
			async buscarLivros(){
				await this.$api.get(`historias?limit=15&categoria=${this.categoriaID}&mode=index`)
				.then((res) => {
					this.livros = res.data
				})
				.catch((err) => {
					console.log(err.response)
				})
			},
		},
	};
</script>
<style lang="scss" scoped>
    @import '../../css/iniciar-leitura-2.scss';
    @import '../../css/dialogs.scss';

    @import '../../css/darkMode/iniciar-leitura-dark.scss';
    @import '../../css/darkMode/dialogs-dark.scss';


</style>