<template>
<q-page class="flex flex-center">
	<q-page class="background">
		<div class="image-background">
			<img alt="Background" src="~assets/background1.png" class="image-background" />
		</div>
		<div style="width: 53%;">
			<h1 class="welcomeWorldBooks">Bem-vindo ao World Books</h1>
		</div>
		<div>
			<h3 class="segundaPlataforma">Segunda plataforma mais amada do mundo</h3>
		</div>
		<div class="row">
			<div class="col texto-div">
				<p class="texto">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu sem eu sem semper sollicitudin hendrerit sit amet nunc. Sed at enim eleifend, efficitur est vitae, placerat magna. Morbi gravida dolor augue, a pellentesque velit aliquam vel. Maecenas ullamcorper sed justo id malesuada. Donec lorem velit, rhoncus nec est nec, ornare malesuada est.</p>
			</div>
		</div>
        <div class="row">
			<div  class="col">
				<q-btn style="color: #fff" label="Iniciar leitura" class="btn_iniciar-leitura" />
			</div>
			<div  class="col" style="margin: 0 301px 0 0px;">
				<q-btn style="color: #fff" label="Comece a escrever" class="btn_comece-escrever"  @click="prompt = true" />
			</div>
        </div>
		<div class="row" style="width: 100%;">
			<div class="col">
				<img alt="Bookcquin" src="~assets/mascote.png"  class="mascote-image"/>
			</div>
		</div>
		<q-dialog v-model="prompt" persistent>
			<q-card style="min-width: 350px">
			<q-card-section>
				<div class="text-h6">Your address</div>
			</q-card-section>

			<q-card-section class="q-pt-none">
				<q-input dense v-model="address" autofocus @keyup.enter="prompt=false" />
			</q-card-section>

			<q-card-actions align="right" class="text-primary">
				<q-btn flat label="Cancel" v-close-popup @click="prompt=false"/>
				<q-btn flat label="Add address" v-close-popup />
			</q-card-actions>
			</q-card>
		</q-dialog>
	</q-page>
	<!-- <img alt="Quasar logo" src="~assets/quasar-logo-vertical.svg"  style="width:30%"/> -->
</q-page>
</template>
<style lang="scss" scoped>
	.background{
		width: 100%;
	
	}
</style>
<script>
import { ref } from 'vue'
export default {
	// props:['breadcrumbs'],
	data (){
		return {
			prompt: false,
			address: ref('')
		}
	},
	mounted(){
	},
	methods:{
	}
};
</script>
