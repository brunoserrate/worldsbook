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
						<q-btn style="color: #fff" label="Iniciar leitura" class="btn_iniciar-leitura" @click="logar = !logar" />
						<q-btn style="color: #fff" label="Comece a escrever" class="btn_comece-escrever q-ml-xl" @click="logar = !logar" />
					</div>
				</div>

			</div>
			<div class="col-4">
				<img alt="Bookcquin" src="~assets/mascote.png"  class="mascote-image"/>
			</div>
		</div>


		<login-register-forgot
			:loginModal="logar"
			:cadastrarModal="sessao"
			:forgotModal="esqueciSenhaModal"

			@hideLogin="(value) => {
				this.logar = value
			}"

			@hideCadastro="(value) => {
				this.sessao = value
			}"

			@hideForgot="(value) => {
				this.esqueciSenhaModal = value
			}"
		/>

	</q-page>
</template>

<script>
import LoginRegisterForgot from '../components/LoginRegisterForgot.vue';
import SelectIdioma from '../components/utils/SelectIdioma.vue';
import SelectCategoria from '../components/utils/SelectCategoria.vue';
import SelectDireitosAutorais from '../components/utils/SelectDireitosAutorais.vue';
import SelectPublicoAlvo from '../components/utils/SelectPublicoAlvo.vue';

export default {
	name:'IndexNoAuth',
	data (){
		return {
			logar: false,
			sessao: false,
			esqueciSenhaModal: false,
			idioma_id: '',
			categoria_id: '',
			direitos_autorais_id: '',
			publico_alvo_id: '',
		}
	},
	components: {
		LoginRegisterForgot,
		SelectIdioma,
		SelectCategoria,
		SelectDireitosAutorais,
		SelectPublicoAlvo,
	},
	mounted(){
	},
	methods:{
	},

};
</script>

<style lang="scss" scoped>

	@import '../css/index.scss';

</style>