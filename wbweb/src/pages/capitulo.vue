<template>
    <q-page :class="{'dark-capitulo': darkmode, 'capitulo': !darkmode}">
        <div class="row ">
            <div class="col-12 mb-5">
                <div class="capa_img" v-if="!capitulo.caminho_capa">
                    <h3 class="title_capa">{{i18n.capa}}</h3>
                </div>
                <!-- CAPA  -->
                <div class="capa_img_2" v-if="capitulo.caminho_capa"></div>
                <!-- TÍTULO DO CAPÍTULO -->
                <div class="col-12 col-md-10 offset-md-1">
                    <h3 class="title_capitulo">{{capitulo.titulo}}</h3>
                </div>
                <div class="col-12 foto_perfil_usuario">
                    <q-avatar size="70px" @click="goToPerfil(capitulo.usuario._id)">
                        <img :src="capitulo.usuario ? (capitulo.usuario.foto_perfil ? `${path_photo}/${capitulo.usuario.foto_perfil}` : ``) : `${path_photo}/default.jpg`" />
                        <q-tooltip anchor="top right" self="top start" class="bg-transparent text-body2" :offset="[10, 10]">
                            <p class="nome_usuario">{{ capitulo.usuario ? capitulo.usuario.name : '' }}</p>
                        </q-tooltip>
                    </q-avatar>
                </div>
                <div class="col-12 foto_perfil_usuario" style="margin: 0px 0 17px 0;">
                    <p class="p_nome_usuario">{{ i18n.de }} <span class="nome_usuario" @click="goToPerfil(capitulo.usuario._id)">{{ capitulo.usuario ? capitulo.usuario.apelido : '' }}</span></p>
                </div>
                <!-- STATUS DO CAPÍTULO -->
                <div class="row justify-center row_status">
                    <div class="col-1 offset-md-2">
                        <q-icon name="visibility" class="icons_card"/>
                        {{ capitulo.quantidade_visualizacao }}
                    </div>
                    <div class="col-1">
                        <q-icon name="star_border" class="icons_card"/>
                        {{ capitulo.votacao }}
                    </div>
                    <div class="col-1">
                        <q-icon name="question_answer" class="icons_card"/>
                        {{ capitulo.comentarios ? capitulo.comentarios.length : 0 }}
                    </div>
                </div>
                <!-- AVATAR -->
                <div class="row justify-center">
                    <div class="col-2 row_avatar">
                        <div class="row">
                            <div class="col-12">
                                <q-avatar size="70px" class="avatar_corpo_capitulo" @click="goToPerfil(capitulo.usuario._id)">
                                    <img :src="capitulo.usuario ? (capitulo.usuario.foto_perfil ? `${path_photo}/${capitulo.usuario.foto_perfil}` : ``) : `${path_photo}/default.jpg`" />
                                    <q-tooltip anchor="top right" self="top start" class="bg-transparent text-body2" :offset="[10, 10]">
                                        <p class="nome_usuario">{{ capitulo.usuario ? capitulo.usuario.name : '' }}</p>
                                    </q-tooltip>
                                </q-avatar>
                            </div>
                            <div class="col-12">
                                <p  class="p_nome_usuario">{{i18n.de}} <span class="nome_usuario" @click="goToPerfil(capitulo.usuario._id)">{{ capitulo.usuario ? capitulo.usuario.apelido : '' }}</span></p>
                            </div>
                            <div class="col-12">
                                <p class="historia-titulo" @click="goToHistoria()">{{ historia.titulo }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- CORPO DO CAPÍTULO -->
                    <div class="col-11 col-md-8 mt-5 px-3 px-lg-0">
                        <q-card flat class="card-capitulo">
                            <span class="corpo_capitulo" v-html="capitulo.capitulo" />
                        </q-card>
                        <!-- <p class="corpo_capitulo">{{capitulo.capitulo}}</p> -->
                        <div class="row justify-center">
                            <div v-if="capitulo.proximo_capitulo != null" class="col-12 row_status">
                                <q-btn unelevated :label="i18n.proximo_capitulo + ' >'" class="btn_proximo_capitulo" @click="nextChapter"/>
                            </div>
                            <div v-else class="col-12 row_status">
                                <q-btn unelevated :label="i18n.sem_capitulos" disable class="btn_proximo_capitulo" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BOTÃO -->
                <q-separator class="separador"></q-separator>
                <div class="row">
                    <div class="offset-0 col-12 col-lg-8 offset-lg-3 px-5 px-lg-0">
                        <q-icon :name="votar.icone_name" class="icon_votar" @click="vote"/>
                        <span class="p_votar">{{ votar.span }}</span>
                    </div>
                </div>
                <q-separator class="separador"></q-separator>
                
                <div class="row m-0 mt-5 pb-5">
                    <div class="offset-0 col-12 col-lg-8 offset-lg-3 px-5 px-lg-0" v-if="capitulo._id">
                        <comentarios type="capitulos" :id="capitulo._id" />
                    </div>
                    <div class="col-12" v-else>
                        Sem comentários
                    </div>
                </div>

            </div>
        </div>
    </q-page>
</template>
<script>
    import eventBus from '../boot/eventBus'
    import { environment } from 'src/helpers/environment';
	import Comentarios from 'src/components/Comentarios/Projetos.vue'
    
    export default {
        data (){
            return {
                capitulo_id: this.$route.params.capitulo_id,
                capitulo: {
                    apelido_usuario: '',
                    caminho_capa: '',
                    capitulo: '',
                    comentarios: [],
                    data_atualizacao: '',
                    data_criacao: '',
                    foto_perfil: '',
                    historia: '',
                    id: '',
                    nome_usuario: '',
                    quantidade_visualizacao: '',
                    titulo: '',
                    usar_apelido: '',
                    votacao: '',
                },
                historia: {

                },
                user: {
                    apelido: '',
                    avatar: '',
                    email: '',
                    nome: '',
                    token: '',
                    usar_apelido: ''
                },
                comment: {
                    apelido_usuario: '',
                    capitulo_id: '',
                    comentario: '',
                    data_atualizacao: '',
                    data_criacao: '',
                    foto_perfil: '',
                    nome_usuario: '',
                    usar_apelido: '',
                    usuario_id: '',
                },
                i18n: {},
                comentarios: [],
                visible: false,
                showSimulatedReturnData: false,
                darkmode: false, 
                votar: {
                    icone_name: 'star_border' ,
                    span: '',
                    votado: false
                },
                timerVisualizacao: null,
				currentUser: this.$q.sessionStorage.getItem('auth'),
                path_photo: `${environment.host}usuarios/profile-image`,
            }
        },
        async mounted(){
            await this.limparCapitulo()
            await this.getCapitulo(this.capitulo_id)
            await this.getUser()

            
            this.timerVisualizacao = setTimeout(() => {
                this.visualizarCapitulo()
	        }, 10000)
        },
        beforeDestroy(){
            clearTimeout(this.timerVisualizacao);
        },
        watch:{
            '$route' (to,from){
                this.limparCapitulo()
                clearTimeout(this.timerVisualizacao);
                
                this.capitulo_id = this.$route.params.capitulo_id
                this.getCapitulo(this.capitulo_id)
                
                this.getUser()
                
                this.timerVisualizacao = setTimeout(() => {
                    this.visualizarCapitulo()
                }, 10000)
            },
        },
        created() {
            this.i18n = this.$i18n.capitulo
            this.votar.span = this.votar.votado ? this.i18n.voto.votado : this.i18n.voto.votar
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
                    this.i18n = this.$i18n.capitulo
                    this.votar.span = this.votar.votado ? this.i18n.voto.votado : this.i18n.voto.votar
                }, 500)
            });
        },
        methods: {
            goToHistoria(){
                this.$router.push({path: `/livro/${this.capitulo.historia._id}`})
            },
            goToPerfil(usuario_id){
                this.$router.push({path: `/perfil/` + usuario_id})
            },
            async getCapitulo(capitulo_id){
                await this.$api.get(`capitulos/${capitulo_id}?usuario=${this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario._id : '') : ''}`)
                .then((res) => {
                    this.capitulo = res.data

                    this.historia = res.data.historia
                    if(this.capitulo.votado){
                        this.votar = {
                            icone_name: 'star',
                            span: this.i18n.voto.votado,
                            votado: true
                        }
                    }
                    else {
                        this.votar = {
                            icone_name: 'star_border',
                            span: this.i18n.voto.votar,
                            votado: false
                        }
                    }
                })
                .catch((err) => {
                    console.log(err)
                })
            },
            nextChapter(){
                if(this.capitulo.proximo_capitulo != null && this.capitulo.proximo_capitulo != 0){
                    this.$router.push({path: '/livro/capitulo/' + this.capitulo.proximo_capitulo })
                }
                else {
                    this.$q.notify({
                        color: 'warning',
                        position: 'top',
                        textColor: 'black',
                        message: this.i18n.sem_capitulos,
                        icon: 'warning',
                        timeout: 5000,
                        actions: [
                            { label: this.i18n.fechar, color: 'black', handler: () => {} }
                        ]
                    })
                }
            },
            async setComentario(){
                if(this.currentUser === null){
                    this.falha(this.i18n.falha_comentar)
                    return false
                }

                try{
                    this.visible = true
                    this.showSimulatedReturnData = false

                    let params = {
                        comentario: this.comment.comentario
                    }

                    // console.log("Comment: ", this.comment)

                    await this.$api.post(`capitulos/comentar/${this.capitulo_id}`, params)
                    .then((res) => {
                        this.getCapitulo(this.capitulo_id)
                        this.visible = false
                        this.showSimulatedReturnData = true
                        this.comment.comentario = ''
                    })
                    .catch((err) => {
                        console.log(err.response)
                    })
                }catch (error){
                    console.log(error)
                }
            },
            async vote(){

                let params = {
                    votado: this.votar.votado
                }

                // Usuário logado
                if(this.currentUser !== null){

                    await this.$api.post(`capitulos/votar/${this.capitulo_id}`, params)
                    .then((res) => {
                        this.getCapitulo(res.data._id)

                        if(this.votar.votado){
                            this.sucesso(this.i18n.voto.voto_removido)
                        } else {
                            this.sucesso(this.i18n.voto.capitulo_votado)
                        }
                    })
                    .catch((err) => {
                        console.log(err)
                    })

                }
                // Visitante
                else{
                    this.falha(this.i18n.voto.falha_conexao)
                }

            },
            async visualizarCapitulo(){
                await this.$api.patch(`capitulos/visualizador/${this.capitulo_id}`)
                .then((res) => {
                })
                .catch((err) =>{
                })
            },
            limparCapitulo(){
                this.capitulo = {
                    apelido_usuario: '',
                    caminho_capa: '',
                    capitulo: '',
                    comentarios: [],
                    data_atualizacao: '',
                    data_criacao: '',
                    foto_perfil: '',
                    id: '',
                    nome_usuario: '',
                    quantidade_visualizacao: '',
                    titulo: '',
                    usar_apelido: '',
                    votacao: '',
                }
            }
        },
        components: {
            Comentarios: Comentarios
        }
    }
</script>
<style lang="scss" scoped>
    @import '../css/capitulo.scss';
    @import '../css/darkMode/capitulo-dark.scss';
</style>