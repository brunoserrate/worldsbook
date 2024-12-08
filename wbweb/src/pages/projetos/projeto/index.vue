<template>
    <q-page :class="{'projeto-dark': darkmode}" class="projeto" :style="{ position: 'relative' }" id="projeto">
		<q-inner-loading
            :showing="visible"
            label-class="text-teal"
            label-style="font-size: 1.1em"
        ></q-inner-loading>
		<div 
			class="background-cover" 
			:style="{
				backgroundImage: `url(${projeto.capa ? `${path_cover_projeto}/${projeto.capa}` : ``})`
			}">
		</div>
        <div class="row row-projeto m-0 py-5">
            <div class="col-12 px-2">
                <h1 class="mb-0 pb-0">{{ melhor_literatura.titulo }} "{{ projeto.nome }}"</h1>
				<h3 class="m-0 p-0 text-center mt-2" v-if="projeto.parceiro">{{ melhor_literatura.subtitulo }} {{ projeto.parceiro && projeto.parceiro.name }} </h3>
            </div>
            <div class="col-12 mt-5 content" v-if="projeto.parceiro">
				<div class="row m-0">
					<div class="col-12 col-sm-5 col-md-5 col-lg-3 d-flex align-items-center justify-content-center justify-sm-content-start mb-5 mb-sm-0">
						<img alt="Cover" :src="projeto.parceiro && projeto.parceiro.foto_perfil ? `${path_photo}/${projeto.parceiro.foto_perfil}` : `${path_photo}/default.jpg`" class="img-instituto"/>
					</div>
					<div class="col-12 col-sm-7 col-md-7 col-lg-9 d-flex align-items-center content-paragrafos">
						<span v-html="projeto.parceiro && projeto.parceiro.sobre" ></span>
					</div>
				</div>
            </div>
			<div class="col-12 mt-5 content">
				<div class="row m-0">
					<div class="col-12">
						<span v-html="projeto.sobre" ></span>
					</div>
				</div>
			</div>
			<div class="col-12 content mt-5" v-if="projeto._id">
				<p class="organizado-por">Este projeto está sendo organizado por 
					<b @click="$router.push({ path: `/perfil/${projeto.gestor._id}` })">{{ projeto.gestor && projeto.gestor.name }}</b>
				</p>
			</div>
			<div class="col-12 mt-5 content status-projeto">
				<div class="row m-0 row-status-projeto">
					<div class="col-12">
						<p class="m-0 p-0"><q-icon name="circle" class="pulsating-border me-2"></q-icon> {{ projeto.status && projeto.status.nome }} </p>
					</div>
					<div class="col-12" v-if="(projeto.tipo && projeto.tipo._id == '67246115d7ee7f7570218e6b') && (projeto.status && projeto.status._id == '672462dad7ee7f7570218e79')">
						<p class="m-0 p-0 p-link" @click="$router.push({ path: `/projetos/ranking/${projeto._id}` })">
							Clique aqui para ver os resultados!
						</p>
					</div>
					<div class="col-12" v-if="(projeto.tipo && projeto.tipo._id == '67246115d7ee7f7570218e6b') && (projeto.status && projeto.status._id == '672462b7d7ee7f7570218e71')">
						<p class="m-0 p-0 p-link" @click="$router.push({ path: `/projetos/votacao/${projeto._id}` })">
							Ir para a votação
						</p>
					</div>
					<div class="col-12" v-if="projeto.status && projeto.status._id == '67246270d7ee7f7570218e6d' && projeto.aberto_publico && currentUser != '' && currentUser._id != projeto.gestor._id">
						<p class="m-0 p-0 p-link" @click="dialog_pedir_para_participar = true">Pedir para participar!</p>
					</div>
					<div class="col-12" v-if="currentUser == '' ">
						<p class="m-0 p-0 p-link">Você precisa estar logado para participar</p>
					</div>
				</div>
			</div>
			<div class="col-12 mt-5 mb-3 content">
				<div class="row m-0">
					<div class="col-12 d-flex justify-content-center">
						<q-btn 
							unelevated 
							color="primary" 
							:label="melhor_literatura.content.btn_historias_participantes" 
							class="btn-veja-participantes"
							@click="$router.push({ path: `/projetos/historias-participantes/${projeto_id}` })"
						/>
					</div>
				</div>
			</div>
        </div>
		<div class="row row-projeto m-0 pb-5">
			<div class="col-12 content"> <!-- projetos -->
				<h3>Veja o que o pessoal está comentando</h3>
			</div>
			<div class="col-12 content" v-if="projeto._id"> <!-- projetos -->
				<comentarios-projeto type="projetos" :id="projeto._id" />
			</div>
			<div class="col-12" v-else>
				Sem comentários
			</div>
		</div>
		<q-dialog v-model="dialog_pedir_para_participar" >
			<q-card class="dialog-pedir-para-participar p-3" :class="{ 'dialog-pedir-para-participar-dark': darkmode }" >
				<div class="row m-0 h-100 p-5" v-if="projeto_limite_max == true">
					<div class="col-12 mt-3">
						<h3 class="p-0 m-0 text-center">Infelizmente, esse projeto chegou ao limite máximo de histórias participantes!</h3>
					</div>
				</div>
				<div class="row m-0 h-100" v-else>
					<div class="col-12 mt-3">
						<h3 class="p-0 m-0 text-center">Escolha a história que deseja inscrever!</h3>
					</div>
					<div class="col-12 mt-4 h-100">
						<q-list class="lista-historias h-100" separator v-if="count_historias > 0">
							<q-item clickable v-for="(historia, i) in historias" :key="i" class="item-historia">
								<q-item-section avatar>
									<div class="avatar-capa">
										<img :src="historia.caminho_capa ? (historia.caminho_capa ? `${path_cover}/${historia.caminho_capa}` : ``) : `${path_cover}/default.png`" class="capa" />
									</div>
								</q-item-section>
								<q-item-section avatar>
									<h3>{{ historia.titulo }}</h3>
								</q-item-section>
								<q-popup-proxy transition-show="flip-up" transition-hide="flip-down" :content-class="`popup-remove ${darkmode ? 'popup-remove-dark' : ''}`">
									<q-card class="h-100 p-4 card-remove">
										<div class="row">
											<div class="col-12">
												<p class="m-0 p-0">Você deseja inscrever <strong>"{{ historia.titulo }}"</strong> ao projeto?</p>
											</div>
											<div class="col-12 mt-3">
												<q-btn 
													class="btn button-remover-historia" 
													:class="{ 'type-1': !darkmode, 'type-2': darkmode }" 
													label="Solicitar inscrição"
													@click="solicitarInscricao(historia._id)"
												></q-btn>
											</div>
										</div>
									</q-card>
								</q-popup-proxy>
							</q-item>
						</q-list>
					</div>
				</div>
			</q-card>
		</q-dialog>
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
  	import eventBus from 'src/boot/eventBus'
    import { environment } from 'src/helpers/environment';
	import Comentarios from 'src/components/Comentarios/Projetos.vue'

	export default {
		data (){
			return {
                visible: false,
                projeto_id: this.$route.params.projeto_id,
				dialog_pedir_para_participar: false,
				dialog_check: false,
				sessao: false,
				autoplay: true,
				livros: [],
				livro_dialog: false,
				darkmode: false,
				projeto_limite_max: false,
				slide: 1,
                page: 1,
                pagesNumber: 0,
                limit: 10,
				i18n: {},
				user: {},
				projeto: {},
				melhor_literatura: {
					titulo: 'Conheça o projeto',
					subtitulo: "Em parceria com",
					content: {
						paragrafo1: "O Instituto Viver Melhor tem como foco o desenvolvimento de ações socioeducativas que incentivem o indivíduo a assumir responsabilidade pelo seu próprio crescimento, em especial no aspecto moral, ajudando-o a alcançar seu pleno potencial físico, intelectual e social, tornando-se um cidadão participativo e útil em sua comunidade.",
						paragrafo2: "Entre suas iniciativas, destaca-se o projeto de competição Viver a Literatura, que visa incentivar a expressão literária e criativa dos jovens membros da ONG. A competição oferece um espaço para que esses jovens compartilhem suas histórias, pensamentos e perspectivas com a comunidade, ajudando-os a ganhar confiança e a exercitar a escrita de forma significativa. As histórias submetidas são disponibilizadas aqui mesmo no WorldBooks, onde os demais usuários, podem ler e votar naquelas que mais os impactaram. Essa dinâmica de votação fortalece o engajamento da comunidade, incentivando o apoio mútuo e o reconhecimento entre os participantes.",
						paragrafo3: "Você pode clicar no botão a seguir para visualizar todas as histórias que estarão participando da competição. Escolha dentre as várias obras a melhor e vote na que mais te agradar.",
						btn_historias_participantes: "CLIQUE E VEJA AS HISTÓRIAS PARTICIPANTES"
					}
				},
				dialog_check_content: {
                    icon: '',
                    aviso1: '',
                    aviso2: ''
                },
				historias: [],
				count_historias: 0,
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
                path_cover_projeto: `${environment.host}projetos/capa-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
			}
		},
		mounted(){
			this.currentUser = this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario : '') : ''
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
		components: {
			ComentariosProjeto: Comentarios
		},
		methods:{
			async getProjeto() {
				try {
					this.visible = true
					let projeto = await this.$api.get(`projetos/${this.projeto_id}`)
					this.projeto = projeto.data
					this.visible = false
					console.log(this.projeto)
				} catch (error) {
					this.visible = false
					console.log(error)
				}
			},
			defineLabelStatus(status) {
				try {
					
				} catch (error) {
					console.log(error)
				}
			},
			
            async verificaNumeroMax() {
                try {
                    if (!this.projeto._id) return ''

                    let verificaNumeroMax = await this.$api.get(`projetos/verifica-limite-participantes/${this.projeto._id}`)
                    
                    if (verificaNumeroMax.data == true) {
                        this.projeto_limite_max = true
                    } else {
                        this.projeto_limite_max = false
						await this.getHistorias()
                    }

                } catch (error) {
                    console.log(error)
                }
            },

			async getHistorias() {
				try {
					let historias = await this.$api.get(`historias?usuario=${this.currentUser._id}&limit=200`)
					this.historias = historias.data.historias
					this.count_historias = historias.data.count
				} catch (error) {
					console.log(error)
				}
			},
			async solicitarInscricao(historiaId) {
				try {
                    let body = {
                        historia: historiaId,
                        projeto: this.projeto._id,
						destinatario: this.projeto.gestor._id,
                        tipo: '6732b726f5941d1308fd4029' // Pedido de participação para um projeto
                    }
                    let notificacao = await this.$api.post(`notificacoes/enviar-notificacao`, body)
					.then((res) => {
						console.log(res)
						this.dialog_check_content = {
							icon: 'check',
							aviso1: 'Pedido enviado',
							aviso2: 'O seu pedido para participar foi enviado, aguarde até que o gestor do projeto o aceite!'
						}
						this.dialog_check = true
						this.dialog_pedir_para_participar = false

					}).catch((err) => {
						console.log(err.response)
						this.dialog_check_content = {
							icon: 'close',
							aviso1: 'Algo deu errado!',
							aviso2: 'Algo deu errado ao votar, tente mais tarde!',
						}
						this.dialog_check = true
					})

				} catch (error) {
					console.log(error)
				}
			}
		},
        watch: {
            page() {
                // this.changePage();
                if (!this.isInitialLoad) {
                } else {
                    this.isInitialLoad = false
                }
            },

			dialog_pedir_para_participar() {
				if (this.dialog_pedir_para_participar) {
					this.verificaNumeroMax()
				}
			}
        }
	};
</script>
<style lang="scss" scoped>
   @import 'src/css/projetos/projeto/index.scss';
   @import 'src/css/darkMode/projetos/projeto/index.scss';
   @import 'src/css/dialogs.scss';
   @import 'src/css/darkMode/dialogs-dark.scss';

</style>