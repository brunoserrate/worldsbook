<template>
    <div>
        <vueper-slides
            class="no-shadow slides_style"
            :visible-slides="6"
            :arrows="true"
            :slide-ratio="1 / 5"
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
		
		<q-dialog v-model="livro_dialog">
			<q-card :class="{ 'dark-card_detail_historia_mobile_index': darkmode, 'card_detail_historia_mobile_index': !darkmode }">
				<div class="row" style="height: 100%;">
					<div class="col-6">
						<img alt="Cover" :src="livro_detail.caminho_capa ? (livro_detail.caminho_capa ? `${path_photo}/${livro_detail.caminho_capa}` : ``) : `${path_photo}/default.png`" class="cover_detail_historia"/>
					</div>
					<div class="col-6">
						<div class="row h-100 d-flex justify-content-space-between">
							<div class="col-12">
								<h1 class="title_dialog_historia">{{livro_detail.titulo}}</h1>
								<q-separator class="separador mb-4"></q-separator>

								<div class="row m-0 p-0 mt-4">
									<div class="col-12 col_btn_detail d-flex align-items-center px-4">
										<q-btn unelevated :label="i18n.iniciar_leitura" class="btn_detail_iniciar_leitura me-2" @click="getLivro(livro_detail)"/>
										<q-btn unelevated label="+" class="btn_detail_iniciar_leitura_mais"/>
									</div>
									<div class="col-12 col_btn_detail px-4">
										<p class="col_descricao_detail">{{livro_detail.descricao | cutDescricao}}</p>
									</div>
								</div>
							</div>
							<div class="col-12 col_btn_detail d-flex flex-direction-column justify-content-end">
								<q-separator class="separador mb-4"></q-separator>
								<p class="col_data_atualizacao"><span>{{i18n.data_atualizacao}}: </span>{{ livro_detail.updatedAt | formatDateTime }}</p>
							</div>
						</div>
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
		name: 'slideCategoriaVue',
		props: ['categoriaID'],
		data (){
			return {
				sessao: false,
				livro_dialog: false,
				darkmode: false,
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
		methods:{
			getLivro(livro_detail){
				this.$router.push({path: `livro/` + livro_detail._id})
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
    @import '../../css/darkMode/iniciar-leitura-dark.scss';
    @import '../../css/dialogs.scss';
    @import '../../css/darkMode/dialogs-dark.scss';
</style>