<template>
    <q-scroll-area
        :thumb-style="thumbStyle"
        :bar-style="barStyle"
        style="height: 300px; max-width: 100%;"
    >
        <q-list class="lista-notificacoes" :class="{'lista-notificacoes-dark': darkmode }" separator v-if="notificacoes.length > 0">
            <q-item clickable v-for="(notificacao, i) in notificacoes" :key="i" class="item-notificacao" :class="{ 'notificacao-lida': !notificacao.lido }" @click="lerNotificacao(notificacao)">
            <!-- convite -->
                <q-item-section avatar>
                    <q-avatar>
                        <img :src="notificacao.remetente && notificacao.remetente.foto_perfil ? `${path_photo}/${notificacao.remetente.foto_perfil}` : `${path_photo}/default.jpg`" 
                              @click="$router.push({ path: `/perfil/${notificacao.remetente._id}` })"/>
                    </q-avatar>
                </q-item-section>

                <!-- convite -->
                <q-item-section v-if="notificacao.tipo && notificacao.tipo._id == '67294ed209990319bb0b4c1e'">
                    <div class="div-notificacao">
                        <span>{{ formatDate(notificacao.createdAt) }}</span>
                        <p>A sua história <b class="b-link" @click="$router.push({ path: `/livro/${notificacao.historia._id}` })" >{{ notificacao.historia.titulo }}</b> 
                            foi convidado(a) à participar do projeto 
                            <strong class="b-link" @click="$router.push({ path: `/projetos/${notificacao.projeto._id}` })">{{ notificacao.projeto.nome }}</strong> 
                        </p>
                        <div>
                            <button class="btn-aceitar" @click="acceptConvite(notificacao)">Aceitar</button>
                        </div>
                    </div>
                </q-item-section>

                <!-- Pedido de participação para um projeto -->
                <q-item-section v-if="notificacao.tipo && notificacao.tipo._id == '6732b726f5941d1308fd4029'">
                    <div class="div-notificacao">
                        <span>{{ formatDate(notificacao.createdAt) }}</span>
                        <p>
                            <b class="b-link" @click="$router.push({ path: `/perfil/${notificacao.remetente._id}` })">{{ notificacao.remetente && notificacao.remetente.name }}</b> 
                            enviou um pedido de participação para o projeto  <strong class="b-link" @click="$router.push({ path: `/projetos/${notificacao.projeto._id}` })">{{ notificacao.projeto.nome }}</strong> 
                            com a sua história <b class="b-link" @click="$router.push({ path: `/livro/${notificacao.historia._id}` })">{{ notificacao.historia.titulo }}</b>                            
                        </p>
                        <div>
                            <button class="btn-aceitar" @click="acceptConvite(notificacao)">Aceitar</button>
                        </div>
                    </div>
                </q-item-section>

                <!-- Pedido/Convite aceito -->
                <q-item-section v-if="notificacao.tipo && (notificacao.tipo._id == '6733b72911c6cd33882cb282' || notificacao.tipo._id == '6733b71f11c6cd33882cb281')">
                    <div class="div-notificacao">
                        <span>{{ formatDate(notificacao.createdAt) }}</span>
                        <p class="" v-if="notificacao.tipo && (notificacao.tipo._id == '6733b72911c6cd33882cb282')">
                            A sua solicitação para participar do projeto <strong class="b-link" @click="$router.push({ path: `/projetos/${notificacao.projeto._id}` })">{{ notificacao.projeto.nome }}</strong> foi aceita
                        </p>
                        <p class="" v-if="notificacao.tipo && (notificacao.tipo._id == '6733b71f11c6cd33882cb281')">
                            O convite para <b class="b-link" @click="$router.push({ path: `/livro/${notificacao.historia._id}` })">{{ notificacao.historia.titulo }}</b>
                            participar de <strong class="b-link" @click="$router.push({ path: `/projetos/${notificacao.projeto._id}` })">{{ notificacao.projeto.nome }}</strong> 
                            foi aceito
                        </p>
                    </div>
                </q-item-section>

                <q-item-section avatar class="icones">
                    <q-icon name="delete" @click="removeNotificacao(notificacao._id)"></q-icon>
                </q-item-section>
            </q-item>
        </q-list>
        <div v-else>
            Nenhuma notificação
        </div>
    </q-scroll-area>
</template>
<script>
  	import eventBus from 'src/boot/eventBus'
    import { environment } from 'src/helpers/environment';

	export default {
        nome: 'Notificacoes',
		data (){
			return {
				sessao: false,
                convite: {
                    idHistoria: '',
                    projeto: ''
                },
                thumbStyle: {
                    right: '4px',
                    borderRadius: '5px',
                    backgroundColor: '#7A22A7',
                    width: '5px',
                    opacity: 0.75
                },

                barStyle: {
                    right: '2px',
                    borderRadius: '9px',
                    backgroundColor: '#7A22A7',
                    width: '9px',
                    opacity: 0.2
                },
				darkmode: false,
                logado: false,
				i18n: {},
				user: {},
                notificacoes: [],
                notificacoes_nao_lidas: 0,
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
			}
		},
		async mounted(){
            this.currentUser = this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario : '') : ''

            if(this.currentUser !== null || this.currentUser !== '' || !this.currentUser !== null) {
                this.logado = true
                await this.buscarNotificacoes()
            }
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
            async buscarNotificacoes() {
                try {
                    if (this.currentUser) {
                        let notificacao = await this.$api.get(`notificacoes?destinatario=${this.currentUser._id}`)
                        this.notificacoes = notificacao.data.notificacoes
                        this.notificacoes_nao_lidas = notificacao.data.countNaoLidas
                        this.$emit('notificacoesNaoLidas', this.notificacoes_nao_lidas)
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async lerNotificacao(notificacao) {
                try {
                    if (!notificacao.lido) {
                        let lerNotificacao = await this.$api.patch(`notificacoes/ler-notificacao/${notificacao._id}`)
                        await this.buscarNotificacoes()
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async acceptConvite(notificacao) {
                try {
                    let aceitarConvite = await this.$api.patch(`notificacoes/aceitar-convite/${notificacao._id}`)
                    await this.buscarNotificacoes()
                    await this.sucesso('Convite aceito!')
                
                } catch (error) {
                    console.log(error)
                }
            },
            async removeNotificacao(_id) {
                try {
                    let deletNotificacao = await this.$api.delete(`notificacoes/${_id}`)
                    await this.buscarNotificacoes()
                
                } catch (error) {
                    console.log(error)
                }
            }
		},
	};
</script>
<style lang="scss" scoped>
    @import 'src/css/notificacoes/index.scss';
    @import 'src/css/darkMode/notificacoes/index.scss';

    
    .badge-notifications {
        position: absolute;
        top: 4.7vh;
        z-index: 2;
        border-radius: 50px;
        padding: 2px 4px;
    }
</style>