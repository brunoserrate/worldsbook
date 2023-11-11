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
                v-for="(livro, i) in livros" :key="i"
                :image="livro.caminho_capa" class="slides_historias"
                @click.native="openDialog(livro)"
            />
        </vueper-slides>
		
		<q-dialog v-model="livro_dialog">
			<q-card :class="{ 'dark-card_detail_historia_mobile_index': darkmode, 'card_detail_historia_mobile_index': !darkmode }">
				<div class="row" style="height: 100%;">
					<div class="col-6">
						<img alt="Cover" :src="livro_detail.caminho_capa" class="cover_detail_historia"/>
					</div>
					<div class="col-6">
						<h1 class="title_dialog_historia">{{livro_detail.titulo}}</h1>
						<div class="row">
							<div class="col-12" style="display: flex; justify-content: center;">
								<hr style="margin: 0 0 0 0; width: 80%;"/>
							</div>
							<div class="col-10 col_btn_detail">
								<q-btn unelevated :label="i18n.iniciar_leitura" class="btn_detail_iniciar_leitura" @click="getLivro(livro_detail)"/>
							</div>
							<div class="col-2 col_btn_detail">
								<q-btn unelevated label="+" class="btn_detail_iniciar_leitura"/>
							</div>
							<div class="col-12 col_btn_detail">
								<p class="col_descricao_detail">{{livro_detail.descricao | cutDescricao}}</p>
							</div>
							<div class="col-12">
								<q-separator class="separador"></q-separator>
							</div>
							<div class="col-12 col_btn_detail">
								<p class="col_data_atualizacao"><span>{{i18n.data_atualizacao}}: </span>{{ livro_detail.data_atualizacao | formatDateTime }}</p>
							</div>
						</div>
						<!-- <template q-slot="footer">
							<div class="row">
								<div class="col-12 col_btn_detail">
								</div>
							</div>
						</template> -->
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
					categoria_id: '',
					conteudo_adulto: '',
					data_atualizacao: '',
					data_criacao: '',
					descricao: '',
					direitos_autorais_id: '',
					idioma_id: '',
					publico_alvo_id: '',
					titulo: '',
					usuario_id: '',
				},
				slide: 1,
				slides: [
					{
						title: 'Slide #1',
						content: 'Slide content.'
					}
				]

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
					console.log("darkmode: ", this.darkmode)
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
				this.$router.push({path: `livro/` + livro_detail.id})
			},
			openDialog(livro){
				this.livro_dialog = true
				this.livro_detail = livro
			},
			buscarLivros(){
				let that = this

				let url = '/historia?limit=15';

				if(this.categoriaID != ''){
					url += '&categoria_id=' + this.categoriaID
				}

				that.$axios.get(that.$pathAPI + url)
				.then((res) => {
					that.livros = res.data.data
					// console.log(that.livros)
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