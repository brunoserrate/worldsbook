<template>
    <q-page :class="{'bgIniciar-leitura': !darkmode, 'dark-bgIniciar-leitura': darkmode}" >
        <div class="row row-cover" >
            <div class="col-10 offset-1 mt-5">
                <p class="text-over-cover">{{ i18n.label_inicio }}</p>
            </div>
            <div class="col-12 align-cover mb-lg-5" v-if="!darkmode">
                <img alt="Cover" src="~assets/abstract_cover_8.png" class="cover"/>
            </div>
            <div class="col-12 align-cover mb-lg-5" v-if="darkmode">
                <img alt="Cover" src="~assets/abstract_cover_8_dark.png" class="cover"/>
            </div>
            <div class="row row-saudacoes w-100 mt-5 mb-5">
				<div class="col-12 w-100">
					<h3 class="welcomeUser">{{ i18n.bem_vindo }}{{ (user !== null) ? ', ' + (user.usar_apelido ? ucWordsFiltro(user.apelido) : ucWordsFiltro(user.name)) : '' }}</h3>
				</div>
				<div class="col-12 w-100">
					<p class="bestChooses">{{ i18n.bem_vindo_subtitulo }}</p>
				</div>
			</div>
        </div>
		<div class="row row_livros">
			<div class="col-12 col-livros mb-5" v-if="window.width > 980">
				<categoria-historia categoriaID="" class="categorias">
				</categoria-historia>
			</div>
			<div class="col-12 col-livros mb-5" v-if="window.width < 980">
				<categoria-historia-mobile categoriaID="" class="categorias">
				</categoria-historia-mobile>
			</div>

			<div class="col-12 mt-5">
				<h3>{{ i18n.melhores_generos }}</h3>
			</div>

			<div class="col-12 chooses_titulos chooses-1">
				<p class="title-categorias">{{ i18n.categoria_romance }}</p>
			</div>
			<div class="col-12 col-categorias col-categorias-1" v-if="window.width > 980">
				<categoria-historia categoriaID="3" class="categorias">
				</categoria-historia>
			</div>
			<div class="col-12 col-categorias col-categorias-1" v-if="window.width < 980">
				<categoria-historia-mobile categoriaID="3" class="categorias">
				</categoria-historia-mobile>
			</div>

			<div class="col-12 chooses_titulos chooses-2">
				<p class="title-categorias" :class="{ 'text-grey-4': darkmode }">{{ i18n.categoria_terror }}</p>
			</div>
			<div class="col-12 col-categorias col-categorias-2" v-if="window.width > 980">
				<categoria-historia categoriaID="4" class="categorias">
				</categoria-historia>
			</div>
			<div class="col-12 col-categorias col-categorias-2" v-if="window.width < 980">
				<categoria-historia-mobile categoriaID="4" class="categorias">
				</categoria-historia-mobile>
			</div>
			
			<div class="col-12 chooses_titulos chooses-1">
				<p class="title-categorias">{{ i18n.categoria_aventura }}</p>
			</div>
			<div class="col-12 col-categorias col-categorias-1" v-if="window.width > 980">
				<categoria-historia categoriaID="2" class="categorias">
				</categoria-historia>
			</div>
			<div class="col-12 col-categorias col-categorias-1"  v-if="window.width < 980">
				<categoria-historia-mobile categoriaID="2" class="categorias">
				</categoria-historia-mobile>
			</div>
		</div>
    </q-page>
</template>
<script>
	import { VueperSlides, VueperSlide } from 'vueperslides'
	import 'vueperslides/dist/vueperslides.css'
	import slideCategoriaVue from 'src/components/IniciarLeitura/slideCategoria.vue';
	import slideCategoriaVueMobile from 'src/components/IniciarLeitura/slideCategoriaMobile.vue';
	import categoriasVue from './categorias.vue';
  	import eventBus from '../boot/eventBus'

	export default {
		// props:['breadcrumbs'],
		data (){
			return {
				sessao: false,
				livros:[],
				livro_dialog: false,
				darkmode: false,
				slide: 1,
				i18n: {},
				slides: [
					{
						title: 'Slide #1',
						content: 'Slide content.'
					}
				],
				user: {},
				window: {
					width: 0,
				}
			}
		},
		mounted(){
			this.user = JSON.parse( this.$q.sessionStorage.getItem('auth') )
		},
		created() {
			window.addEventListener('resize', this.handleResize);
			this.handleResize();
            this.i18n = this.$i18n.iniciar_leitura
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
                    this.i18n = this.$i18n.iniciar_leitura
                }, 500)
            });
		},
		destroyed() {
			window.removeEventListener('resize', this.handleResize);
		},
		watch: {
			
		},
		components: {
			VueperSlides,
			VueperSlide,
			categoriaHistoria: slideCategoriaVue,
			categoriaHistoriaMobile: slideCategoriaVueMobile
		},
		methods:{
			handleResize() {
				this.window.width = window.innerWidth;
			},
			buscarLivros(){
				let that = this

				that.$axios.get(that.$pathAPI + '/historia?limit=15')
				.then((res) => {
					that.livros = res.data.data
					// console.log(that.livros)
				})
				.catch((err) => {
					// console.log(err.response)
				})
			},
			cutSinopse(){
				for(let i=0; i < this.livros.length; i++){
					this.livros[i].attributes.sinopse = this.livros[i].attributes.sinopse.substring(0, 200);
					// console.log(this.livros[i].attributes.sinopse)
				}
			},
			ucFirstFiltro(valor){
				if(valor != undefined || valor != null || valor === ''){
					return valor[0].toUpperCase() + valor.slice(1).toLowerCase()
				}
			},
			ucWordsFiltro(valor){
				if(valor != undefined || valor != null || valor === ''){
					let arr = valor.split(' ')
					let result

					for (let i = 0; i < arr.length; i++) {
						arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1);
					}

					result = arr.join(' ')

					return result
				}
			},
		},
	};
</script>
<style lang="scss" scoped>
   @import '../css/iniciar-leitura-2.scss';
   @import '../css/darkMode/iniciar-leitura-dark.scss';
   /* @import '../css/iniciar-leitura-mobile.scss'; */
</style>