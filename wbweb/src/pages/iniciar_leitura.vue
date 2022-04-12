<template>
    <q-page>
        <div class="row" >
            <div class="col-12">
                <p class="text-over-cover">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque et sollicitudin nisi.</p>
            </div>
            <div class="col-12 align-cover">
                <img alt="Cover" src="~assets/abstract_cover_7.jpg" class="cover"/>
            </div>
            <div class="row">
				<div class="col-12">
					<h3 class="welcomeUser">Bem-vindo, User</h3>
				</div>
				<div class="col-12">
					<p class="bestChooses">As melhores escolhas para si</p>
				</div>
			</div>
        </div>
		<!-- <div class="row">
            <div class="col-2" v-for="(livro, i) in livros.slice(0, 5)" :key="i" style="margin: 0px -34px 0px 51px;">
                <q-card class="card_imagem">
					<q-card>
						<div>
							<div class="flex flex-center">
								<img :src="livro.caminho_capa" class="cover_style"/>
							</div>
						</div>
					</q-card>

                </q-card>
            </div>
		</div> -->
		<div class="row">
			<div class="col">
				<vueper-slides
					class="no-shadow slides_style"
					:visible-slides="5"
					:arrows="true"
					:slide-ratio="1 / 4"
					 slide-multiple
					:gap="3"
					:bullets="false"
					:dragging-distance="70">
						<vueper-slide
							v-for="(livro, i) in livros" :key="i"
							:image="livro.caminho_capa"
						/>
				</vueper-slides>
			</div>
		</div>
		<div class="row">
			<div class="col-2">
				
			</div>
		</div>
    </q-page>
</template>
<script>
import { VueperSlides, VueperSlide } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'
export default {
	// props:['breadcrumbs'],
	data (){
		return {
			sessao: false,
			livros:[],
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
		// this.getLivros()
		this.buscarLivros()
	},
	components: { VueperSlides, VueperSlide },
	methods:{
		 /*async getLivros(){
			this.livros = await this.$axios.get('livros');
			this.livros = this.livros.data.data
			console.log("livros: ", this.livros)
			this.cutSinopse()
		}, */
		
		buscarLivros(){
			let that = this

			that.$axios.get(that.$pathAPI + '/historia?limit=10')
			.then((res) => {
				that.livros = res.data.data
				console.log(that.livros)
			})
			.catch((err) => {
				console.log(err.response)
			})
		},
		cutSinopse(){
			for(let i=0; i < this.livros.length; i++){
				this.livros[i].attributes.sinopse = this.livros[i].attributes.sinopse.substring(0, 200);
				// console.log(this.livros[i].attributes.sinopse)
			}
		},
	},
};
</script>
<style lang="scss" scoped>
    @import '../css/iniciar-leitura.scss';
</style>