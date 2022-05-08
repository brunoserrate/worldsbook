<template>
    <q-page>
        <div class="row">
            <div class="col-12">
                <div class="capa_img" v-if="!capitulo.caminho_capa">
                    <h3 class="title_capa">capa</h3>
                </div>
                <!-- CAPA  -->
                <div class="capa_img_2" v-if="capitulo.caminho_capa">

                </div>
                <!-- TÍTULO DO CAPÍTULO -->
                <div class="col-12">
                    <h3 class="title_capitulo">{{capitulo.titulo}}</h3>
                </div>
                <!-- STATUS DO CAPÍTULO -->
                <div class="row justify-center row_status">
                    <div class="col-1">
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
                                <q-avatar size="70px" class="avatar_corpo_capitulo">
                                    <img :src="capitulo.foto_perfil" />
                                    <q-tooltip anchor="top right" self="top start" class="bg-transparent text-body2" :offset="[10, 10]">
                                        <p class="nome_usuario">{{capitulo.nome_usuario}}</p>
                                    </q-tooltip>
                                </q-avatar>
                            </div>
                            <div class="col-12">
                                <p  class="p_nome_usuario">de <span class="nome_usuario">{{capitulo.apelido_usuario}}</span></p>
                            </div>
                        </div>
                    </div>
                    <!-- CORPO DO CAPÍTULO -->
                    <div class="col-6">
                        <h3 class="corpo_capitulo">{{capitulo.capitulo}}</h3>
                        <div class="row justify-center">
                            <div class="col-12 row_status">
                                <q-btn unelevated label="Ir para o próximo capítulo >" class="btn_proximo_capitulo" @click="nextChapter"/>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BOTÃO -->
                <q-separator class="separador"></q-separator>
                <div class="row justify-center">
                    <div class="col-4">
                        <q-icon :name="votar.icone_name" class="icon_votar" @click="vote"/>
                        <span class="p_votar">{{ votar.span }}</span>
                    </div>
                </div>
                <q-separator class="separador"></q-separator>
                <div class="row justify-center">
                    <div class="col-1 offset-1">
                        <q-avatar size="60px" class="avatar_comentario">
                            <img :src="user.avatar" />
                        </q-avatar>
                    </div>
                    <div class="col-6">
                        <q-input v-model="comment.comentario" outlined type="textarea" class="comment_textarea">
                            <q-inner-loading
                                :showing="visible"
                                label="Please wait..."
                                label-class="text-teal"
                                label-style="font-size: 1.1em"
                            />
                        </q-input>
                    </div>
                    <div class="col-6 offset-2 row_btn">
                        <q-btn unelevated label="enviar" class="btn_enviar_comentario" v-if="comment.comentario" @click="setComentario"/>
                        <q-btn unelevated label="enviar" class="btn_enviar_comentario" disabled v-if="!comment.comentario"/>
                    </div>
                </div>
                <q-separator class="separador"></q-separator>
                <!-- COMENTÁRIOS -->
                <div class="row justify-center" v-for="(comentario, i) in capitulo.comentarios" :key="i">
                    <div class="col-1 offset-1">
                        <q-avatar size="50px" class="avatar_comentario">
                            <img :src="comentario.foto_perfil" />
                        </q-avatar>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-12">
                                <p class="nome_usuario_comentario">{{comentario.nome_usuario}}</p>
                            </div>
                            <div class="col-12">
                                <p class="data_comentario">{{comentario.data_criacao}}</p>
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
                comentarios: [],
                visible: false,
                showSimulatedReturnData: false,
                votar: {
                    icone_name: 'star_border' , 
                    span: 'votar'
                }
            }
        },
        mounted(){
            this.getCapitulo(this.capitulo_id)
            this.getUser()
        },
        methods: {
            getCapitulo(capitulo_id){
                let that = this
                that.$axios.get(that.$pathAPI + '/capitulo/' + capitulo_id)
                .then((res) => {
                    that.capitulo = res.data.data
                    console.log(that.capitulo)
                })
                .catch((err) => {
                    console.log(err.response)
                })
            },
            nextChapter(){
                console.log("next")
            },
            setComentario(){
                try{
                    this.visible = true
                    this.showSimulatedReturnData = false

                    let that = this
                    that.comment.usuario_id = 2
                    that.comment.data_atualizacao = Date.now()
                    that.comment.data_criacao = Date.now()
                    that.comment.capitulo_id = that.capitulo.id

                    console.log("Comment: ", that.comment)
    
                    that.$axios.post(that.$pathAPI + '/comentario', that.comment)
                    .then((res) => {
                        console.log("RES: ", res)
                        that.capitulo.comentarios.unshift(res.data.data)
                        console.log(that.capitulo.comentarios)
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
                if(this.votar.icone_name == 'star') {
                    this.votar.icone_name = 'star_border'
                    this.votar.span = 'Votar!'

                } else {
                    this.votar.icone_name = 'star'
                    this.votar.span = 'Votado!'
                }
            },

            setVoto(){

            }
        },
    }
</script>
<style lang="scss" scoped>
    @import '../css/capitulo.scss';
</style>