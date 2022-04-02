<template>
	<q-page class="flex flex-center" style="background-image: url('~assets/background1.png'); background-repeat: no-repeat; background-position: right; background-size: contain;">
		<div style="background-image: url('~assets/background1.png'); background-repeat: no-repeat; background-position: right; background-size: contain;">
			<q-dialog v-model="sessao">
				<q-card style="min-width: 350px">
					<q-card-section>
						<div class="text-h6">Your address</div>
					</q-card-section>

					<q-card-section class="q-pt-none">
						<!-- <q-input dense v-model="address" autofocus @keyup.enter="sessao=false" /> -->
					</q-card-section>

					<q-card-actions align="right" class="text-primary">
						<q-btn flat label="Cancel" v-close-popup @click="sessao=false"/>
						<q-btn flat label="Add address" v-close-popup />
					</q-card-actions>
				</q-card>
			</q-dialog>
			<q-page class="background">
				<div class="row">
					<div class="col-12">
						<!-- <div class="image-background">
							<img alt="Background" src="~assets/background1.png" class="image-background" />
						</div> -->
						<div style="width: 53%;">
							<h1 class="welcomeWorldBooks">Bem-vindo ao World Books</h1>
						</div>
						<div>
							<h3 class="segundaPlataforma">Segunda plataforma mais amada do mundo</h3>
						</div>
						<div>
							<p class="texto">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu sem eu sem semper sollicitudin hendrerit sit amet nunc. Sed at enim eleifend, efficitur est vitae, placerat magna. Morbi gravida dolor augue, a pellentesque velit aliquam vel.</p>
						</div>
						<div>
							<q-btn style="color: #fff" label="Iniciar leitura" class="btn_iniciar-leitura" @click="goIniciarLeitura"/>
							<q-btn style="color: #fff" label="Comece a escrever" class="btn_comece-escrever"/>
						</div>
						<div>
							<img alt="Bookcquin" src="~assets/mascote.png"  class="mascote-image"/>
						</div>
					</div>
				</div>
			</q-page>
		</div>
	</q-page>
</template>
<style lang="scss" scoped>
	// @import '../css/tela-inicial.scss';
	@import '../css/index.scss';
	.background{
		width: 100%;
	}
	body.desktop.no-touch.body--light {
		background-image: url('~assets/background1.png') ;
		background-repeat: no-repeat;
		background-position: right;
		background-size: contain;
	}
</style>
<script>
export default {
	// props:['breadcrumbs'],
	data (){
		return {
			sessao: false,
			livros:[],
			usuarios: []
		}
	},
	methods:{
		goIniciarLeitura(){
			this.$router.push({path: `iniciar_leitura`})
		},
		async getUsuarios(){
			this.usuarios = await this.$axios.get('users');
			this.usuarios = this.usuarios.data
			console.log("usuarios: ", this.usuarios)
		}
	},
	mounted(){
		this.getUsuarios()
	},
};
</script>
