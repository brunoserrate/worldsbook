<template>
    <q-page :class="{'votacoes-dark': darkmode}" class="votacoes" >
        <q-inner-loading
            :showing="loading"
            label-class="text-teal"
            label-style="font-size: 1.1em"
        ></q-inner-loading>
        <div class="aviso" v-if="projeto.status && projeto.status._id != '672462b7d7ee7f7570218e71'">
            <h1 v-if="projeto.status && projeto.status._id == '67246270d7ee7f7570218e6d' || projeto.status._id == '67246274d7ee7f7570218e6f'">No momento, as votações ainda não estão abertas</h1>
            <h1 v-if="projeto.status && projeto.status._id == '672462c3d7ee7f7570218e73'">No momento, as votações já foram encerradas</h1>
            <h1 v-if="projeto.status && projeto.status._id == '672462cbd7ee7f7570218e75'">No momento, os votos estão sendo apurados</h1>
            <h1 v-if="projeto.status && projeto.status._id == '672462cbd7ee7f7570218e75'">No momento, os votos estão sendo apurados</h1>
            <h1 v-if="projeto.status && projeto.status._id == '672462d3d7ee7f7570218e77' || projeto.status._id == '672462dad7ee7f7570218e79'">O projeto está encerrado</h1>
            
            <p 
                class="m-0 p-0 p-link" 
                v-if="(projeto.status && projeto.status._id == '672462d3d7ee7f7570218e77') 
                || (projeto.status && projeto.status._id == '672462dad7ee7f7570218e79')"
                @click="$router.push({ path: `/projetos/ranking/${projeto._id}` })"
            >
                Clique aqui para ver os resultados!
            </p>
        </div>
        <div class="row m-0 py-5">
            <div class="col-12 px-4 mb-4 fitas d-flex justify-content-center">
                <div class="ribbon-2 ribbon mt-5">2</div>
                <div class="ribbon-1 ribbon mb-5">1</div>
                <div class="ribbon-3 ribbon mt-5">3</div>
			</div>
            <div class="row m-0 w-100 mb-5">
                <div class="col-12">
                    <div class="row m-0">
                        <div class="col-12 px-4 mt-5 titulo">
                            <h1 class="mb-0 pb-0">Vote na melhor <strong>história</strong></h1>
                            <p class="m-0 p-0 text-center p-link" @click="$router.push({ path: `/projetos/${projeto._id}` })">{{ projeto.nome }}</p>
                        </div>
                        <div class="col-12 mt-5 radios d-flex flex-direction-column">
                            <!-- <div v-for="(historia, i) in historias" :key="i" @mouseover="mouseHistory = historia._id" @mouseout="mouseHistory = null" class="div-radio"> -->
                            <div v-for="(historia, i) in historias" :key="i" class="div-radio">
                                <div clas="div-radio-span">
                                    <q-radio 
                                        v-model="historia_selecionada" 
                                        color="purple"
                                        :val="historia._id" 
                                        :label="historia.titulo" 
                                        class="radio-historia"
                                    />
                                    <span class="ms-1">by {{ historia.usuario && historia.usuario.name }}</span>
                                </div>
                                <transition name="slide-fade">
                                    <p class="ms-4 sinopse" v-show="historia_selecionada === historia._id">{{ cutDescricao(historia.descricao, 100) }}</p>
                                </transition>
                            </div>
                            <div class="buttons">
                                <button unelevated rounded class="btn type-3 w-100 mt-5" :disabled="!historia_selecionada" v-if="currentUser && projeto.status && projeto.status._id == '672462b7d7ee7f7570218e71'" @click="votar">Votar</button>
                                <button unelevated rounded class="btn type-3 w-100 mt-5" disabled v-if="!currentUser && projeto.status && projeto.status._id != '672462b7d7ee7f7570218e71'">Você precisa estar logado</button>
                                <button unelevated rounded class="btn type-5 w-100 mt-2" v-if="votoExistente && projeto.status && projeto.status._id == '672462b7d7ee7f7570218e71'" @click="removerVoto">Remover voto</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <q-dialog v-model="dialog_check" persistent transition-show="flip-down" transition-hide="flip-up" content-class="dialog-check">
            <q-card class="text-white card-check">      
              <q-card-section class="d-flex justify-content-center">
                <q-icon :name="dialog_check_content.icon || 'exclamation'" class="icon-check"></q-icon>
              </q-card-section>
      
              <q-card-section class="q-pt-none">
                <p class="p-0 m-0 p-aviso">{{ dialog_check_content.aviso1 || '' }}</p>
                <p class="p-0 m-0 p-aviso-2">{{ dialog_check_content.aviso2 || '' }}</p>
              </q-card-section>
      
              <q-card-section class="q-pt-none d-flex justify-content-center">
                <q-btn label="OK" class="btn type-4" @click="dialog_check = false" ></q-btn>
              </q-card-section>
            </q-card>
        </q-dialog>
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
                loading: false,
				autoplay: true,
                dialog_check: false,
				historias: [],
                historia_selecionada: '',
				darkmode: false,
				i18n: {},
				user: {},
				projeto: {},
                dialog_check_content: {
                    icon: '',
                    aviso1: '',
                    aviso2: ''
                },
                votoExistente: null,
                mouseHistory: '',
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
                path_cover_parceiro: `${environment.host}parceiros/parceiro-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
			}
		},
		async mounted(){
			this.currentUser = this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario : '') : ''
			await this.getProjeto()
            await this.verificarVotoExistente()
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
                    this.loading = true
                    let projeto = await this.$api.get(`projetos/${this.projeto_id}`)
                    this.projeto = projeto.data
                    this.historias = projeto.data.historias.historias
                    this.loading = false
                } catch (error) {
                    console.log(error)
                    this.loading = false
                }
            },
            async verificarVotoExistente() {
                try {
                    let votoExistente = await this.$api.get(`competicao-votos?projeto=${this.projeto_id}&usuario=${this.currentUser._id}`)
                    console.log(votoExistente)
                    if (votoExistente.data.count > 0) {
                        this.votoExistente = votoExistente.data.competicao_votos[0]._id
                        this.historia_selecionada = votoExistente.data.competicao_votos[0].historia_votada._id
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async votar() {
                try {
                    let voto = {
                        historia_votada: this.historia_selecionada,
                        projeto: this.projeto_id
                    } 

                    let projeto = await this.$api.post(`competicao-votos/votar`, voto)
                    console.log(projeto.data)
                    
                    if (projeto.data.code == 2) {
                        this.dialog_check_content = {
                            icon: 'priority_high',
                            aviso1: 'Você já votou nesse projeto',
                            aviso2: 'Você não pode votar mais de uma vez!',
                        }
                        this.dialog_check = true
                        return ''
                    }

                    this.dialog_check_content = {
                        icon: 'check',
                        aviso1: 'O seu voto foi registrado.',
                        aviso2: 'Você pode alterá-lo a qualquer momento',
                    }

                    this.dialog_check = true
                    this.votoExistente = null
                    this.historia_selecionada = ''
                    
                    await this.getProjeto()
                    await this.verificarVotoExistente()

                } catch (error) {
                    console.log(error)
                    
                    this.dialog_check_content = {
                        icon: 'close',
                        aviso1: 'Algo deu errado!',
                        aviso2: 'Algo deu errado ao votar, tente mais tarde!',
                    }
                    this.dialog_check = true
                }
            },

            async removerVoto() {
                try {
                    let projeto = await this.$api.delete(`competicao-votos/${this.votoExistente}`)
                    console.log(projeto.data)
                    
                    this.dialog_check_content = {
                        icon: 'check',
                        aviso1: 'O seu voto foi removido.',
                        aviso2: 'Você pode votar novamente até o perído de votações terminar',
                    }

                    this.dialog_check = true
                    this.votoExistente = null
                    this.historia_selecionada = ''

                    await this.getProjeto()
                    await this.verificarVotoExistente()

                } catch (error) {
                    console.log(error)
                    
                    this.dialog_check_content = {
                        icon: 'close',
                        aviso1: 'Algo deu errado!',
                        aviso2: 'Algo deu errado ao remover o voto, tente mais tarde!',
                    }
                    this.dialog_check = true
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
   @import 'src/css/projetos/projeto/votacao.scss';
   @import 'src/css/darkMode/projetos/projeto/votacao.scss';

    .slide-fade-enter-active, .slide-fade-leave-active {
        transition: all 0.3s ease;
    }
    .slide-fade-enter, .slide-fade-leave-to {
        transform: translateY(-10px);
        opacity: 0;
    }

</style>