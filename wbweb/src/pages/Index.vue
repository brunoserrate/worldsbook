<template>
	<q-page class="bgindex" >

		<div class="row" style="margin-top: 120px">
			<div class="col-6">

				<div class="row q-ml-xl">
					<div class="col">
						<h1 class="welcomeWorldBooks">Bem-vindo ao World Books</h1>
					</div>
				</div>

				<div class="row q-ml-xl">
					<div class="col">
						<h3 class="segundaPlataforma">Segunda plataforma mais amada do mundo</h3>
					</div>
				</div>

				<div class="row q-ml-xl">
					<div class="col">
						<p class="texto">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
							 Sed eu sem eu sem semper sollicitudin hendrerit sit amet nunc.
							 Sed at enim eleifend, efficitur est vitae, placerat magna.
							 Morbi gravida dolor augue, a pellentesque velit aliquam vel.
						</p>
					</div>
				</div>
				<div class="row q-ml-xl">
					<div class="col">
						<q-btn style="color: #fff" label="Iniciar leitura" class="btn_iniciar-leitura" @click="goIniciarLeitura"/>
						<q-btn style="color: #fff" label="Comece a escrever" class="btn_comece-escrever q-ml-xl"/>
					</div>
				</div>

			</div>
			<div class="col-4">
				<img alt="Bookcquin" src="~assets/mascote.png"  class="mascote-image"/>
			</div>
		</div>

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
	</q-page>
</template>

<script>
export default {
	// props:['breadcrumbs'],
	data (){
		return {
			sessao: false,
			livros:[],
			usuarios: [],
			index: false
		}
	},
	mounted(){
		let that = this

	},
	methods:{
		goIniciarLeitura(){
			this.$router.push({path: `iniciar_leitura`})
		},
	},

};
</script>

<style lang="scss" scoped>

	@import '../css/index.scss';

</style>