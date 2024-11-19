<template>
    <q-page :class="{'ranking-dark': darkmode}" class="ranking" >
        <div class="row m-0 py-5">
            <div class="col-12 m-0 w-100 mb-5 titulo">
                <div class="row m-0">
                    <div class="col-12 px-4 mt-0 d-flex justify-content-center flex-direction-column align-items-center">
                        <h1 class="m-0 p-0 h1-confira-o-ranking">Confira o ranking das</h1>
                        <div class="ribbon-6 ribbon mt-3 d-none d-sm-block" :class="{'ribbon-6-dark ribbon': darkmode}" >Melhores histórias</div>
                        <h1 class="m-0 p-0 mt-2 h1-melhores-historias d-block d-sm-none">Melhores histórias</h1>
                        <h1 class="m-0 p-0 h1-projeto mt-2">Projeto <strong>{{ projeto.nome }}</strong></h1>
                    </div>
                </div>
            </div>
            <div class="col-12 px-4 mb-5 ranking-tres-primeiros d-flex justify-content-center">
                <div v-for="(historia, i) in colocacoesTresPrimeiros" :key="i" class="div-capa mx-2" :class="{ 'primeiro': historia.colocacao == 1, 'segundo': historia.colocacao == 2, 'terceiro': historia.colocacao == 3 }">
                    <p>{{ historia.colocacao }}</p>
                    <div class="div-capa-historia">
                        <img 
                            alt="Cover" 
                            :src="historia.historia ? (historia.historia.caminho_capa ? `${path_cover}/${historia.historia.caminho_capa}` : ``) : `${path_cover}/default.png`" 
                            class="cover-historia"
                        />
                    </div>
                </div>
			</div>
            <div class="col-12 col-restante-colocacoes">
                <div class="row m-0">
                    <div class="col-12 textos mb-5">
                        <h1 class="m-0 p-0 h1-projeto mt-2 text-center">Projeto <strong>{{ projeto.nome }}</strong></h1>
                        <p class="text-center p-organizado-por m-0 p-0">organizado por <strong @click="$router.push({ path: `/perfil/${projeto.gestor._id}` })">{{ projeto.gestor && projeto.gestor.name }}</strong></p>
                        <p class="text-center p-organizado-por m-0 p-0" v-if="projeto.parceiro" @click="$router.push({ path: `/perfil/${projeto.parceiro._id}` })">em parceria com <strong>{{ projeto.parceiro && projeto.parceiro.name }}</strong></p>
                    </div>
                    <div class="col-12 col-historias-ranking">
                        <div class="row">
                            <div class="col-12 col-historia mb-3" v-for="(historia, i) in restante" :key="i">
                                <div class="d-flex align-items-center div-text-historia">
                                    <h1 class="p-0 m-0 me-2">#{{ historia.colocacao }}</h1> <p class="p-0 m-0 p-historia">{{ historia.historia && historia.historia.titulo }}</p>
                                    <span>&nbsp;-&nbsp; {{ historia.total_votos }} {{ historia.total_votos == 1 ? "voto" : "votos" }}</span>
                                </div>
                                <span class="ms-5 span-autor cursor-pointer" @click="$router.push({ path: `/perfil/${historia.historia.usuario._id}` })">por {{ historia.historia.usuario && historia.historia.usuario.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
            <div class="col-12 my-5 d-flex justify-content-center">
                <button class="btn" :class="{'type-2': darkmode, 'type-1': !darkmode}" @click="$router.push({ path: `/iniciar_leitura` })">Voltar para o início</button>
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
				historias: [],
				darkmode: false,
				i18n: {},
				user: {},
				projeto: {},
                colocacoesTresPrimeiros: [],
                restante: [],
                votoExistente: null,
                mouseHistory: '',
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
			}
		},
		async mounted(){
			this.currentUser = this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario : '') : ''
			await this.getProjeto()
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
		methods: {
            async getProjeto() {
                try {
                    let projeto = await this.$api.get(`projetos/${this.projeto_id}`)
                    this.projeto = projeto.data
                    console.log(this.projeto)

                    this.colocacoesTresPrimeiros = [
                        this.projeto.ranking[1],
                        this.projeto.ranking[0],
                        this.projeto.ranking[2],
                    ]
                    
                    this.restante = this.projeto.ranking
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
   @import 'src/css/projetos/projeto/ranking.scss';
   @import 'src/css/darkMode/projetos/projeto/ranking.scss';

    .slide-fade-enter-active, .slide-fade-leave-active {
        transition: all 0.3s ease;
    }
    .slide-fade-enter, .slide-fade-leave-to {
        transform: translateY(-10px);
        opacity: 0;
    }

</style>