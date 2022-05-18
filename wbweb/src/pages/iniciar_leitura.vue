<template>
    <q-page class="bgIniciar-leitura">
        <div class="row" >
            <div class="col-12">
                <p class="text-over-cover">Navegue pelas categorias explorando diversos livros, será uma experiência incrível!</p>
            </div>
            <div class="col-12 align-cover">
                <img alt="Cover" src="~assets/abstract_cover_7.jpg" class="cover"/>
            </div>
            <div class="row">
				<div class="col-12">
					<h3 class="welcomeUser">Bem-vindo(a){{ (user !== null) ? ', ' + (user.usar_apelido ? ucWordsFiltro(user.apelido) : ucWordsFiltro(user.name)) : '' }}</h3>
				</div>
				<div class="col-11 offset-2">
					<p class="bestChooses">As melhores escolhas para si</p>
				</div>
			</div>
        </div>
		<div class="row" style="padding: 0px 41px;">
			<div class="col-12 colLivros">
				<categoria-historia categoriaID="">
				</categoria-historia>
			</div>
			<div class="col-12">
				<h3 class="welcomeUser">Encontre as melhores entre os gêneros!</h3>
			</div>
			<div class="col-12 ">
				<p class="bestChooses">Categoria de Romance</p>
			</div>
			<div class="col-12 colLivros">
				<categoria-historia categoriaID="3">
				</categoria-historia>
			</div>
			<div class="col-12 ">
				<p class="bestChooses">Categoria de Terror</p>
			</div>
			<div class="col-12 colLivros">
				<categoria-historia categoriaID="4">
				</categoria-historia>
			</div>
			<div class="col-11">
				<p class="bestChooses">Categoria de Aventura</p>
			</div>
			<div class="col-12 colLivros">
				<categoria-historia categoriaID="2">
				</categoria-historia>
			</div>
		</div>
    </q-page>
</template>
<script>
import { VueperSlides, VueperSlide } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'
import slideCategoriaVue from 'src/components/IniciarLeitura/slideCategoria.vue';
export default {
	// props:['breadcrumbs'],
	data (){
		return {
			sessao: false,
			livros:[],
			livro_dialog: false,
			slide: 1,
			slides: [
				{
					title: 'Slide #1',
					content: 'Slide content.'
				}
			],
			user: {}
		}
	},
	mounted(){
		// this.getLivros()
		this.user = JSON.parse( this.$q.sessionStorage.getItem('auth') )
		// this.buscarLivros()
	},
	components: {
		VueperSlides,
		VueperSlide,
		categoriaHistoria: slideCategoriaVue
	},
	methods:{
		click(){
            console.log("click")
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
    @import '../css/iniciar-leitura.scss';
</style>