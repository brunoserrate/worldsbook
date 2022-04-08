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
		<div class="row">
            <div class="col-2" v-for="(livro, i) in livros.slice(0, 5)" :key="i" style="margin: 0px 0px 0 51px;">
                <q-card class="card_imagem">
                    <h4 class="titulo_livro">{{livro.attributes.titulo}}</h4>
					<hr />
					<q-card>
						<div class="img_cover">
							<div class="absolute-full text-subtitle2 flex flex-center coverImage">
								Capa
							</div>
						</div>
					</q-card>
					<!-- <q-card class="cover">
						{{livro.attributes.capa}}
					</q-card> -->
                    <p>{{livro.attributes.sinopse}} ...</p>

                </q-card>
            </div>
		</div>
    </q-page>
</template>
<script>

export default {
	// props:['breadcrumbs'],
	data (){
		return {
			sessao: false,
			livros:[]
		}
	},
	methods:{
		async getLivros(){
			this.livros = await this.$axios.get('livros');
			this.livros = this.livros.data.data
			console.log("livros: ", this.livros)
			this.cutSinopse()
		},
		cutSinopse(){
			for(let i=0; i < this.livros.length; i++){
				this.livros[i].attributes.sinopse = this.livros[i].attributes.sinopse.substring(0, 200);
				// console.log(this.livros[i].attributes.sinopse)
			}
		},
	},
	mounted(){
		this.getLivros()
	},
};
</script>
<style lang="scss" scoped>
    @import '../css/iniciar-leitura.scss';
</style>