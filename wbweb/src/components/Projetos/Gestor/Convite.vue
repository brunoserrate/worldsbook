<template>
    <q-page :class="{ 'convite-dark': darkmode }" class="convite" >
        <div class="row m-0 py-5" v-if="count_projetos > 0">
            <div class="col-12 px-5">
                <q-card class="card-convite p-5">
                    <div class="row m-0">
                        <div class="col-12 col-md-5 col-id">
                            <h1 class="p-0 m-0">Convite para o projeto</h1>
                            <p class="p-0 m-0">Insira o código de identificação da história para enviar um convite ao autor!</p>
                            <q-input
                                v-model="convite.idHistoria"
                                autocorrect="off"
                                autocapitalize="off"
                                autocomplete="off"
                                spellcheck="false"
                                rounded standout
                                @blur="getHistoria"
                                class="input-id-historia mt-3"
                            ></q-input>
                            <p class="m-0 p-0 mt-4">Selecione o projeto</p>
                            <q-select 
                                rounded standout 
                                v-model="convite.projeto" 
                                :options="projetos" 
                                emit-value
                                option-value="_id"
                                option-label="nome"
                                map-options
                            />
                        </div>
                        <div class="col-12 col-md-1 d-flex justify-content-center">
                            <q-separator vertical inset class="p-0 m-0 h-100 d-none d-md-block" />
                            <q-separator inset class="my-4 m-0 w-100 d-block d-md-none" />
                        </div>
                        <div class="col-12 col-md-6 historia">
                            <div class="row" v-if="Object.keys(historia).length > 0">
                                <div class="col-12 col-sm-4 col-md-5 col-lg-4 d-flex justify-content-center justify-sm-content-start">
                                    <img :src="historia.caminho_capa ? (historia.caminho_capa ? `${path_cover}/${historia.caminho_capa}` : ``) : `${path_cover}/default.png`" class="historia-capa">
                                </div>
                                <div class="col-12 col-sm-8 col-md-7 col-lg-8 p-0 m-0">
                                    <div class="row m-0 p-0">
                                        <div class="col-12 m-0 p-0">
                                            <h1 class="text-center text-sm-start">{{ historia.titulo }}</h1>
                                        </div>
                                        <div class="col-12 m-0 p-0">
                                            <p>{{ historia.descricao | cutDescricao }}</p>
                                        </div>
                                        <div class="col-12 m-0 p-0 d-flex justify-content-center justify-sm-content-start">
                                            <q-btn 
                                                unelevated rounded 
                                                color="primary" 
                                                label="Enviar convite" 
                                                class="w-100" 
                                                :disable="convite.idHistoria == '' && convite.projeto == ''"
                                                @click="enviarConvite"  
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
        <div class="row m-0 sem-projetos mt-5" v-else>
            <div class="col-12 px-5 mb-3">
                <p>Você não tem nenhum projeto</p>
                <p class="p-link" @click="$router.push({ path: `/projetos/criar_projeto` })">Clique aqui para criar um!</p>
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
        nome: 'ProjetoConvite',
		data (){
			return {
				sessao: false,
                convite: {
                    idHistoria: '',
                    projeto: ''
                },
                historia: {},
				darkmode: false,
				i18n: {},
				user: {},
                projetos: [],
                count_projetos: 0,
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
			}
		},
		mounted(){
            this.currentUser = this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario : '') : ''
            this.getProjetos()
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
		watch: {
			
		},
		components: {
            
		},
        computed: {
            // disableEnvio() {
            //     if ()
                
            // }
        },
		methods:{
            async getProjetos() {
                try {
                    let gestor = await this.$api.get(`projetos?gestor=${this.currentUser._id}`)
                    this.projetos = gestor.data.projetos
                    this.count_projetos = gestor.data.count
                } catch (error) {
                    console.log(error)
                }
            },

            async getHistoria() {
                try {
                    if (this.convite.idHistoria != '') {
                        console.log('entrou')
                        let historia = await this.$api.get(`historias/${this.convite.idHistoria}`)
                        this.historia = historia.data
                    }
                } catch (error) {
                    console.log(error)
                }
            },

            async enviarConvite() {
                try {
                    let body = {
                        historia: this.historia._id,
                        projeto: this.convite.projeto,
                        destinatario: this.historia.usuario._id,
                        tipo: '67294ed209990319bb0b4c1e' // convite
                    }
                    let notificacao = await this.$api.post(`notificacoes/enviar-notificacao`, body)
                    this.$q.dialog({
                        dark: this.darkmode ? true : false,
                        title: 'Convite enviado',
                        message: 'O convite foi enviada, aguarde até o destinatário confirme',
                        cancel: false,
                        persistent: true
                    })
                } catch (error) {
                    console.log(error)
                }
            }
		},
        filters: {
            cutDescricao(value){
                let tamanho_max = 200;

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
    @import 'src/css/projetos/projeto/convite.scss';
    @import 'src/css/darkMode/projetos/projeto/convite.scss';
</style>