<template>
	<q-page :class="{'dark-bgindex': darkmode, 'bgindex': !darkmode}" >
		<div class="row row_page">
			<div class="col-12 col-sm-6">
				<div class="row">
					<div class="col-12 offset-sm-1">
						<h1 class="welcomeWorldBooks">Bem-vindo ao World Books</h1>
					</div>
				</div>
				<div class="row">
					<div class="col offset-sm-1">
						<h3 class="segundaPlataforma">Segunda plataforma mais amada do mundo</h3>
					</div>
				</div>			
				<div class="row mt-2">
					<div class="col offset-1">
						<p class="texto">World Books sua plataforma para te conectar com milhões de livros e escritores pelo mundo.
							 Explore sua imaginação, desenvolva histórias e interaja com vários autores pelo mundo.
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12 offset-sm-1 align_btn">
						<q-btn style="color: #fff" label="Iniciar leitura" class="btn_iniciar-leitura" @click="iniciarLeitura" unelevated/>
						<q-btn style="color: #fff" label="Comece a escrever" class="btn_comece-escrever q-ml-xl" @click="comeceEscrever" unelevated/>
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

			@usuarioLogado="(value) => {
				this.user = value
				this.logar = false
				this.logado = true
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
    import eventBus from '../boot/eventBus'

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
				logado: false,
                darkmode: false,
				user: null
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
			let user = JSON.parse( this.$q.sessionStorage.getItem('auth') )

			if(user !== null) {
				this.user = user
				this.logado = true
			}
		},
		created() {
            setTimeout(() => {
                let dark = this.$q.localStorage.getItem('darkmode')
                this.darkmode = dark == 'true' ? true : false
            }, 500)
            eventBus.$on('att-darkmode', async (option) => {
                setTimeout(async() => {
                    this.darkmode = option
                }, 500);
            });
		},
		methods:{
			iniciarLeitura(){
				this.$router.push({ path: '/categorias' })
			},
			comeceEscrever(){
				if(this.user != null){
					this.$router.push({ path: '/iniciar_leitura' })
				}
				else {
					this.logar = !this.logar
				}
			}
		},
	};
</script>

<style lang="scss" scoped>

	@import '../css/index.scss';
	@import '../css/darkMode/index-dark.scss';

</style>