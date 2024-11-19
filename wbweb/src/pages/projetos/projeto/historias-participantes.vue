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
            <div class="col-12 mt-5" v-if="count_historias > 0">
                <div class="row m-0 p-0">
                    <div class="col-12 col-lg-6 p-5 py-3 py-sm-5 col-historias" v-for="(historia, i) in historias" :key="i" @click="$router.push({ path: `/livro/${historia._id}` })">
                        <div class="row p-0 m-0">
                            <div class="col-12 d-flex d-sm-none justify-content-center mb-3">
                                <h3 class="m-0 p-0">{{ historia.titulo }}</h3>
                            </div>
                            <div class="col-12 col-sm-4 col-md-3 col-lg-4 d-flex justify-content-center justify-sm-content-start">
                                <img alt="Cover" :src="historia.caminho_capa ? (historia.caminho_capa ? `${path_cover}/${historia.caminho_capa}` : ``) : `${path_cover}/default.png`" class="cover-historia"/>
                            </div>
                            <div class="col-12 col-sm-8 col-md-9 col-lg-8 content-historia">
                                <div class="row p-0 m-0">
                                    <div class="col-12 d-flex justify-content-center justify-sm-content-start flex-direction-column">
                                        <h3 class="m-0 p-0 d-none d-sm-flex">{{ historia.titulo }}</h3>
                                        <span class="d-flex justify-content-center justify-sm-content-start mt-2 mt-sm-0">by &nbsp; <strong>{{ historia.usuario && historia.usuario.name }}</strong></span>
                                    </div>
                                    <div class="col-12 mt-3 descricao">
                                        <p class="text-justify"> {{ cutDescricao(historia.descricao, 200) }} </p>
                                    </div>
                                    <div class="col-12 mt-3 tags">
                                        <q-chip v-for="(tag, index_tag) in historia.tags.slice(0, 4)" :key="index_tag" class="tag" >{{ tag.nome }}</q-chip>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div class="col-12 paginacao mt-5 d-flex justify-content-center">
				<q-pagination
					v-model="page"
					:max="pagesNumber"
					direction-links
					color="grey"
					active-color="primary"
					class="paginacao"
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

	export default {
		data (){
			return {
                projeto_id: this.$route.params.projeto_id,
				autoplay: true,
				historias: [],
                count_historias: 0,
                page: 1,
                pagesNumber: 0,
                limit: 30,
				darkmode: false,
				i18n: {},
				user: {},
				projeto: {},
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
                path_cover_parceiro: `${environment.host}parceiros/parceiro-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
			}
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
                    console.log(this.projeto)
                    this.historias = projeto.data.historias.historias
                    this.count_historias = this.historias.length
                    this.pagesNumber = Math.ceil(this.count_historias / this.limit)
                } catch (error) {
                    console.log(error)
                }
            },
            cutDescricao(value, tam){
                let tamanho_max = tam;

                if(value != undefined && value != null) {
                    if(value.length > tamanho_max) {
                        return value.substring(0, tamanho_max) + '...'
                    }
                    return value
                }

            }
		},
	};
</script>
<style lang="scss" scoped>
   @import 'src/css/projetos/projeto/historias-participantes.scss';
   @import 'src/css/darkMode/projetos/projeto/historias-participantes.scss';

</style>