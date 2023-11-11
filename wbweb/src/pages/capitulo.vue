<template>
    <q-page :class="{'dark-capitulo': darkmode, 'capitulo': !darkmode}">
        <div class="row">
            <div class="col-12">
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
                    <q-avatar size="70px" @click="goToPerfil(capitulo.usuario_id)">
                        <img :src="capitulo.foto_perfil" />
                        <q-tooltip anchor="top right" self="top start" class="bg-transparent text-body2" :offset="[10, 10]">
                            <p class="nome_usuario">{{capitulo.nome_usuario}}</p>
                        </q-tooltip>
                    </q-avatar>
                </div>
                <div class="col-12 foto_perfil_usuario" style="margin: 0px 0 17px 0;">
                    <p  class="p_nome_usuario">de <span class="nome_usuario" @click="goToPerfil(capitulo.usuario_id)">{{capitulo.apelido_usuario}}</span></p>
                </div>
                <!-- STATUS DO CAPÍTULO -->
                <div class="row justify-center row_status">
                    <div class="col-1 offset-md-2">
                        <q-icon name="visibility" class="icons_card"/>
                        {{capitulo.quantidade_visualizacao}}
                    </div>
                    <div class="col-1">
                        <q-icon name="star_border" class="icons_card"/>
                        {{capitulo.votacao}}
                    </div>
                    <div class="col-1">
                        <q-icon name="question_answer" class="icons_card"/>
                        {{capitulo.comentarios.length}}
                    </div>
                </div>
                <!-- AVATAR -->
                <div class="row justify-center">
                    <div class="col-2 row_avatar">
                        <div class="row">
                            <div class="col-12">
                                <q-avatar size="70px" class="avatar_corpo_capitulo" @click="goToPerfil(capitulo.usuario_id)">
                                    <img :src="capitulo.foto_perfil" />
                                    <q-tooltip anchor="top right" self="top start" class="bg-transparent text-body2" :offset="[10, 10]">
                                        <p class="nome_usuario">{{capitulo.nome_usuario}}</p>
                                    </q-tooltip>
                                </q-avatar>
                            </div>
                            <div class="col-12">
                                <p  class="p_nome_usuario">{{i18n.de}} <span class="nome_usuario" @click="goToPerfil(capitulo.usuario_id)">{{capitulo.apelido_usuario}}</span></p>
                            </div>
                            <div class="col-12">
                                <p class="historia-titulo" @click="goToHistoria()">{{historia.titulo}}</p>
                            </div>
                        </div>
                    </div>
                    <!-- CORPO DO CAPÍTULO -->
                    <div class="col-8 sm-6">
                        <q-card flat class="card-capitulo">
                            <q-card-section class="corpo_capitulo" v-html="capitulo.capitulo" />
                        </q-card>
                        <!-- <p class="corpo_capitulo">{{capitulo.capitulo}}</p> -->
                        <div class="row justify-center">
                            <div v-if="capitulo.proximo_capitulo != null" class="col-12 row_status">
                                <q-btn unelevated :label="i18n.proximo_capitulo + ' >'" class="btn_proximo_capitulo" @click="nextChapter"/>
                            </div>
                            <div v-else class="col-12 row_status">
                                <q-btn unelevated :label="i18n.sem_capitulos" disable class="btn_proximo_capitulo" @click="nextChapter"/>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BOTÃO -->
                <q-separator class="separador"></q-separator>
                <div class="row justify-center">
                    <div class="col-8 col-md-4">
                        <q-icon :name="votar.icone_name" class="icon_votar" @click="vote"/>
                        <span class="p_votar">{{ votar.span }}</span>
                    </div>
                </div>
                <q-separator class="separador"></q-separator>
                <div class="row justify-center">
                    <div class="col-2 offset-0 col-md-1 offset-md-1">
                        <q-avatar size="60px" class="avatar_comentario">
                            <img :src="user.foto_perfil" />
                        </q-avatar>
                    </div>
                    <div class="col-6">
                        <q-input v-model="comment.comentario" outlined type="textarea" class="comment_textarea">
                            <q-inner-loading
                                :showing="visible"
                                :label="i18n.aguarde"
                                label-class="text-teal"
                                label-style="font-size: 1.1em"
                            />
                        </q-input>
                    </div>
                    <div class="col-6 offset-2 row_btn">
                        <q-btn unelevated :label="i18n.enviar" class="btn_enviar_comentario" v-if="comment.comentario" @click="setComentario"/>
                        <q-btn unelevated :label="i18n.enviar" class="btn_enviar_comentario" disabled v-if="!comment.comentario"/>
                    </div>
                </div>
                <q-separator class="separador"></q-separator>
                <!-- COMENTÁRIOS -->
                <div class="row justify-center" v-for="(comentario, i) in capitulo.comentarios" :key="i">
                    <div class="col-2 offset-0 col-md-1 offset-md-1">
                        <q-avatar size="50px" class="avatar_comentario" @click="goToPerfil(comment.usuario_id)">
                            <img :src="comentario.foto_perfil" />
                        </q-avatar>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-12">
                                <p class="nome_usuario_comentario">{{comentario.nome_usuario}}</p>
                            </div>
                            <div class="col-12">
                                <p class="data_comentario">{{comentario.data_criacao | formatDateTime}}</p>
                            </div>
                            <div class="col-12">
                                <p class="comentario_corpo">{{comentario.comentario}}</p>
                            </div>
                        </div>
                        <q-separator class="separador"></q-separator>
                    </div>
                </div>

            </div>
        </div>
    </q-page>
</template>
<script>
    import eventBus from '../boot/eventBus'
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
                    historia_id: '',
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
                timerVisualizacao: null
            }
        },
        mounted(){
            let that = this

            this.limparCapitulo()
            this.getCapitulo(this.capitulo_id)
            this.getUser()

	  		this.timerVisualizacao = setTimeout(function() {
                that.visualizarCapitulo()
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

                this.timerVisualizacao = setTimeout(function() {
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
                this.$router.push({path: `/livro/` + this.capitulo.historia_id})
            },
            goToPerfil(usuario_id){
                this.$router.push({path: `/perfil/` + usuario_id})
            },
            getCapitulo(capitulo_id){
                let that = this
                that.$axios.get(that.$pathAPI + '/capitulo/' + capitulo_id)
                .then((res) => {
                    that.capitulo = res.data.data
                    // console.log("capitulo: ", that.capitulo)
                    this.getHistoria(that.capitulo)
                    if(that.capitulo.votado){
                        that.votar = {
                            icone_name: 'star',
                            span: this.i18n.voto.votado,
                            votado: true
                        }
                    }
                    else {
                        that.votar = {
                            icone_name: 'star_border',
                            span: this.i18n.voto.votar,
                            votado: false
                        }
                    }


                    // console.log(that.capitulo)
                })
                .catch((err) => {
                    console.log(err)
                })
            },
            getHistoria(){
                let that = this
                // console.log(this.capitulo.historia_id)
                that.$axios.get(that.$pathAPI + '/historia/' + this.capitulo.historia_id)
                .then((res) => {
                    that.historia = res.data.data
                    // console.log("historia: ", that.historia)
                })
                .catch((err) => {
                    console.log(err.response)
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
            setComentario(){
                let user = JSON.parse( this.$q.sessionStorage.getItem('auth') )

                if(user === null){
                    this.falha(this.i18n.falha_comentar)

                    return false
                }

                try{
                    this.visible = true
                    this.showSimulatedReturnData = false

                    let that = this
                    // that.comment.usuario_id = 2
                    // that.comment.data_atualizacao = Date.now()
                    // that.comment.data_criacao = Date.now()
                    // that.comment.capitulo_id = that.capitulo.id

                    let params = {
                        capitulo_id: that.capitulo_id,
                        comentario: that.comment.comentario
                    }

                    // console.log("Comment: ", that.comment)

                    that.$axios.post(that.$pathAPI + '/comentario', params)
                    .then((res) => {
                        // console.log("RES: ", res)
                        that.capitulo.comentarios.unshift(res.data.data)
                        // console.log(that.capitulo.comentarios)
                        this.visible = false
                        this.showSimulatedReturnData = true
                        that.comment.comentario = ''

                    })
                    .catch((err) => {
                        console.log(err.response)
                    })
                }catch (error){
                    console.log(error)
                }
            },
            vote(){
                let that = this

                let user = JSON.parse( that.$q.sessionStorage.getItem('auth') )

                let params = {
                    votado: that.votar.votado
                }

                // Usuário logado
                if(user !== null){

                    that.$axios.post(that.$pathAPI + '/capitulo/votado/' + that.capitulo_id, params)
                    .then((res) => {
                        // Se votar for verdadeiro, o capítulo já foi votado
                        // Então a rotina irá remover o voto que foi realizado pelo usuário
                        if(that.votar.votado){
                            that.sucesso(this.i18n.voto.voto_removido)

                            that.votar = {
                                icone_name: 'star_border',
                                span: this.i18n.voto.votar,
                                votado: false
                            }

                        }
                        // Se for falso, o capítulo ainda não foi votado
                        // Então a rotina irá adicioanr o voto feito pelo usuário
                        else {
                            that.sucesso(this.i18n.voto.capitulo_votado)

                            that.votar = {
                                icone_name: 'star',
                                span: this.i18n.voto.votado,
                                votado: true
                            }

                        }
                    })
                    .catch((err) => {
                    })

                }
                // Visitante
                else{
                    this.falha(this.i18n.voto.falha_conexao)
                }

            },
            setVoto(){

            },
            async visualizarCapitulo(){
                let that = this

                that.$axios.post(that.$pathAPI + '/capitulo/visualizado/' + this.capitulo_id)
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
                    historia_id: '',
                    id: '',
                    nome_usuario: '',
                    quantidade_visualizacao: '',
                    titulo: '',
                    usar_apelido: '',
                    votacao: '',
                }
            }
        },
    }
</script>
<style lang="scss" scoped>
    @import '../css/capitulo.scss';
    @import '../css/darkMode/capitulo-dark.scss';
</style>