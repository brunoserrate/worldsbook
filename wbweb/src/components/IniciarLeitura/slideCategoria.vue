<template>
    <div>
		<!-- :visible-slides="6" -->
        <vueper-slides
            class="no-shadow slides_style"
			:breakpoints="breakpoints"
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
		
        <dialog-historia />
    </div>
</template>
<script>
	import { VueperSlides, VueperSlide } from 'vueperslides'
	import 'vueperslides/dist/vueperslides.css'
	import eventBus from 'src/boot/eventBus'
	import { environment } from 'src/helpers/environment';
    import DialogHistoria from 'src/components/Dialogs/Historia.vue'

	export default {
		// props:['breadcrumbs'],
		name: 'slideCategoriaVue',
		props: ['categoriaID'],
		data (){
			return {
				sessao: false,
				livro_dialog: false,
				darkmode: false,
				livros: [],
				i18n: {},
				livro_detail: {},
				index_livro: null,
				activeDialogHistoria: false,
				slide: 1,
				breakpoints: {
					20000: {
						slideRatio: 1 / 5,
						visibleSlides: 8
					},
					2100: {
						slideRatio: 1 / 5,
						visibleSlides: 6
					},
					900: {
						slideRatio: 1 / 2,
						visibleSlides: 3
					}
				},
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
		components: { 
			VueperSlides, 
			VueperSlide,
            DialogHistoria: DialogHistoria
		},
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
		methods: {
            openDialog(livro){
				this.$store.commit("dialog/OPEN_DIALOG", livro);
            },
			async buscarLivros(){
				await this.$api.get(`historias?limit=15&categoria=${this.categoriaID}&mode=index&sort=total_visualizacoes&ordem=-1`)
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
    @import 'src/css/iniciar-leitura-2.scss';
    @import 'src/css/darkMode/iniciar-leitura-dark.scss';
	
    @import 'src/css/dialogs.scss';
    @import 'src/css/darkMode/dialogs-dark.scss';
</style>