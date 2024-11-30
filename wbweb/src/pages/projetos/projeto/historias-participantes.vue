<template>
    <q-page :class="{'historias-participantes-dark': darkmode}" class="historias-participantes" id="historias-participantes" >
        <div class="row m-0 py-5">
            <div class="col-12 px-4 mt-3">
                <h1 class="mb-0 pb-0">Conheça as histórias participantes</h1>
				<h3 class="m-0 p-0 text-center mt-1 subtitulo" v-if="projeto.parceiro">Conheça as histórias participantes da competição do projeto {{ projeto.nome }} </h3>
            </div>
            <div class="col-12 mt-5 d-flex justify-content-center align-items-center flex-direction-column" v-if="count_historias == 0">
                <p class="text-center">Este projeto ainda não tem histórias participando</p>
                <q-btn unelevated rounded color="primary" class="btn type-2 mt-3" @click="$router.push({ path: `/projetos/${projeto_id}` })" label="Voltar" />
            </div>
            <div class="col-12 align_livros mt-4" v-else>
                <livros-lista 
                    :historias="livros" 
                    :count_historias="count_historias" 
                    :pagesNumber="pagesNumber" 
                    :page="page" 
                    @alterPage="(value) => {
                        this.page = value
                        this.getLivros()
                    }"
                />
            </div>
            <div class="col-12 mt-5 mb-5 col-btn-votacao d-flex justify-content-center" v-if="count_historias > 0">
                <q-btn unelevated rounded color="primary" class="btn" :class="{ 'type-2': darkmode, 'type-1': !darkmode }" @click="$router.push({ path: `/projetos/votacao/${projeto_id}` })" label="Ir para a página de votação" />
            </div>
        </div>
    </q-page>
</template>
<script>
	import { VueperSlides, VueperSlide } from 'vueperslides'
	import 'vueperslides/dist/vueperslides.css'
  	import eventBus from 'src/boot/eventBus'
    import { environment } from 'src/helpers/environment';
	import Livros from 'src/components/Livros/Index.vue'

	export default {
		data (){
			return {
                projeto_id: this.$route.params.projeto_id,
				autoplay: true,
				livros: [],
                count_historias: 0,
                page: 1,
                pagesNumber: 0,
                limit: 30,
				darkmode: false,
				i18n: {},
				user: {},
				projeto: {},
                count: 0,
                page: 1,
                limit: 10,
                pagesNumber: 0,
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
                path_cover_parceiro: `${environment.host}parceiros/parceiro-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
			}
		},
        components: {
            LivrosLista: Livros
        },
		mounted(){
			this.user = this.currentUser ? this.currentUser.usuario : null
			this.getProjeto()
		},
		created() {
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
		methods:{
            async changePage(e) {
                
                await this.getProjeto()
                
                let element = document.getElementById('historias-participantes');
                if (element) {
                    let pixels_parar = 50;
                    let offsetTop = element.offsetTop - pixels_parar;
                    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
			},
            async getProjeto() {
                try {
                    let projeto = await this.$api.get(`projetos/${this.projeto_id}?&limit=${this.limit}&page=${this.page}&sort=titulo`)
                    this.projeto = projeto.data
                    this.livros = projeto.data.historias.historias
                    this.count_historias = projeto.data.historias.count
                    this.pagesNumber = Math.ceil(this.count_historias / this.limit)
                } catch (error) {
                    console.log(error)
                }
            },
		},
	};
</script>
<style lang="scss" scoped>
   @import 'src/css/projetos/projeto/historias-participantes.scss';
   @import 'src/css/darkMode/projetos/projeto/historias-participantes.scss';

</style>