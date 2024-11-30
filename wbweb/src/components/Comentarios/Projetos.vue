<template>
    <div class="comentarios" :class="{ 'dark-comentarios': darkmode }">
        <q-inner-loading :showing="visible" label-class="text-teal" label-style="font-size: 1.1em" class="loading"></q-inner-loading>
        <div class="row justify-center" :class="{'wb-form-dark-outlined': darkmode, 'wb-form': !darkmode}">
            <div class="col-2 offset-0 col-md-1 d-none d-sm-block">
                <q-avatar size="60px" class="avatar_comentario">
                    <img :src="currentUser ? (currentUser.foto_perfil ? `${path_photo}/${currentUser.foto_perfil}` : ``) : `${path_photo}/default.jpg`" />
                </q-avatar>
            </div>
            <div class="col-12 col-sm-10 col-md-11">
                <q-input v-model="comment.comentario" outlined type="textarea" class="comment_textarea">
                    <q-inner-loading :showing="visible" :label="i18n.aguarde" class="loading" label-class="text-teal" label-style="font-size: 1.1em" />
                </q-input>
            </div>
            <div class="col-12 col-sm-8 col-md-10 offset-0 offset-sm-0 offset-md-0 mt-3 row_btn">
                <q-btn unelevated :label="i18n.enviar" class="btn_enviar_comentario btn type-3"  v-if="comment.comentario && currentUser" @click="setComentario"/>
                <q-btn unelevated :label="i18n.enviar" class="btn_enviar_comentario btn type-3" disabled v-if="!comment.comentario && currentUser"/>
                <q-btn unelevated :label="'Você precisa estar logado para comentar'" class="btn_enviar_comentario btn type-3" disabled v-if="!currentUser"/>
            </div>
            <div class="col-12 mt-5 mb-4">
                <!-- <q-separator class="separador" v-if="comentarios.count > 0" :dark="darkmode"></q-separator> -->
            </div>
            <div class="col-12 offset-0 col-sm-11 offset-sm-1 mt-3 mb-4">
                <div class="row" v-for="(comentario, i) in comentarios.comentarios" :key="i">
                    <div class="col-2 offset-0 col-md-1 py-3 col-avatar">
                        <q-avatar size="50px" class="avatar_comentario" @click="$router.push({ path: `/perfil/${comentario.usuario._id}` })" >
                            <img :src="comentario ? (comentario.usuario.foto_perfil ? `${path_photo}/${comentario.usuario.foto_perfil}` : ``) : `${path_photo}/default.jpg`"/>
                        </q-avatar>
                    </div>
                    <div class="col-10 col-md-11 col-comentario py-3">
                        <div class="row">
                            <div class="col-12">
                                <strong class="nome_usuario_comentario p-0 m-0" @click="$router.push({ path: `/perfil/${comentario.usuario._id}` })" >{{ comentario.usuario ? comentario.usuario.name : '' }}</strong>
                            </div>
                            <div class="col-12">
                                <p class="data_comentario p-0 m-0">{{ formatDate(comentario.createdAt) }}</p>
                            </div>
                            <div class="col-12 mt-3">
                                <p class="comentario_corpo p-0 m-0">{{ comentario.comentario }}</p>
                            </div>
                            <div class="col-12 mt-3">
                                <p class="responder_comentario p-0 m-0 mb-2" @click="reply = { id: i, comentario: '' }" v-if="i != reply.id && currentUser">responder</p>
                                <p class="responder_comentario p-0 m-0 mb-2" @click="reply = { id: null, comentario: '' }" v-if="i == reply.id && currentUser">cancelar</p>
                                <div class="row row-responder-comentario" v-if="i == reply.id">
                                    <div class="col-2 offset-0 col-md-1 d-none d-sm-block">
                                        <q-avatar class="avatar_comentario_resposta">
                                            <img :src="currentUser ? (currentUser.foto_perfil ? `${path_photo}/${currentUser.foto_perfil}` : ``) : `${path_photo}/default.jpg`" />
                                        </q-avatar>
                                    </div>
                                    <div class="col-12 col-sm-10 col-md-11">
                                        <q-input v-model="reply.comentario" outlined type="textarea" class="comment_textarea">
                                            <q-inner-loading :showing="visible" :label="i18n.aguarde" label-class="text-teal" class="loading" label-style="font-size: 1.1em" />
                                        </q-input>
                                    </div>
                                    <div class="col-12 offset-0 offset-sm-2 offset-md-1 mt-3 row_btn">
                                        <q-btn unelevated :label="i18n.enviar" class="btn_enviar_comentario btn type-3"  v-if="reply.comentario" @click="replyComentario(comentario, reply)"/>
                                        <q-btn unelevated :label="i18n.enviar" class="btn_enviar_comentario btn type-3" disabled v-if="!reply.comentario"/>
                                    </div>
                                </div>
                                <div class="row row-respostas mt-4" v-if="comentario.respostas && comentario.respostas.length > 0">
                                    <div class="col-12" v-for="(resposta, index) in comentario.respostas" :key="index">
                                        <div class="row">
                                            <div class="col-2 offset-0 col-md-1 d-none d-sm-block">
                                                <q-avatar class="avatar_resposta" @click="$router.push({ path: `/perfil/${resposta.usuario._id}` })" >
                                                    <img :src="resposta ? (resposta.usuario.foto_perfil ? `${path_photo}/${resposta.usuario.foto_perfil}` : ``) : `${path_photo}/default.jpg`"/>
                                                </q-avatar>
                                            </div>
                                            <div class="col-10 col-md-11 col-comentario">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <strong class="nome_usuario_comentario p-0 m-0" @click="$router.push({ path: `/perfil/${resposta.usuario._id}` })" >{{ resposta.usuario ? resposta.usuario.name : '' }}</strong>
                                                    </div>
                                                    <div class="col-12">
                                                        <p class="data_comentario p-0 m-0">{{ formatDate(resposta.createdAt) }}</p>
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <p class="comentario_corpo p-0 m-0">{{ resposta.comentario }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 offset-0 my-3" v-if="comentario.respostas && comentario.respostas.length != (index + 1)">
                                                <q-separator class="separador" v-if="comentarios.count > 0" :dark="darkmode"></q-separator>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 offset-0 my-2" v-if="comentarios.comentarios && comentarios.comentarios.length != (i + 1)">
                        <q-separator class="separador" v-if="comentarios.count > 0" :dark="darkmode"></q-separator>
                    </div>
                </div>
                <div class="row" v-if="comentarios.count == 0">
                    <div class="col-12">
                        <p>Por enquanto nenhum comentário, seja o primeiro a comentar!</p>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</template>
<script>
    import eventBus from 'src/boot/eventBus'
    import { environment } from 'src/helpers/environment';
    
    export default {
        name: "Comentarios",
        props: ['id', 'type'],
        data (){
            return {
                user: {
                    apelido: '',
                    avatar: '',
                    email: '',
                    nome: '',
                    token: '',
                    usar_apelido: ''
                },
                comment: {
                    comentario: '',
                },
                reply: {
                    id: null,
                    comentario: ''
                },
                i18n: {},
                comentarios: [],
                visible: false,
                darkmode: false,
				currentUser: this.$q.sessionStorage.getItem('auth'),
                path_photo: `${environment.host}usuarios/profile-image`,
            }
        },
        async mounted(){
            await this.getComentarios()
            await this.getUser()

        },
        created() {
			this.currentUser = this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario : '') : ''
            this.i18n = this.$i18n.capitulo
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
                }, 500)
            });
        },
        methods: {
            async getComentarios() {
                try {
                    let query = ''
                    if (this.type == 'projetos') query = `projeto=${this.id ? this.id : ''}`
                    else query = `capitulo=${this.id ? this.id : ''}`

                    let comentarios = await this.$api.get(`comentarios?${query}`)
                    this.comentarios = comentarios.data

                    console.log("comentarios: ", this.comentarios)
                } catch (error) {
                    console.log(error)
                }
            },
            
            async setComentario(){
                if(this.currentUser === null || this.currentUser === ''){
                    this.falha(this.i18n.falha_comentar)
                    return false
                }

                try{
                    this.visible = true

                    let params = {
                        comentario: this.comment.comentario
                    }

                    console.log("Comment: ", this.comment)

                    await this.$api.post(`${this.type}/comentar/${this.id}`, params)
                    .then(async (res) => {
                        await this.getComentarios()
                        this.visible = false
                        this.comment.comentario = ''
                    })
                    .catch((err) => {
                        console.log(err.response)
                    })
                }catch (error){
                    console.log(error)
                }
            },

            async replyComentario(comentario, reply) {
                try{
                    this.visible = true

                    let params = {
                        comentario: this.reply.comentario,
                        comentarioId: comentario._id
                    }

                    await this.$api.post(`${this.type}/responder-comentario/${this.id}`, params)
                    .then(async (res) => {
                        await this.getComentarios()
                        this.visible = false
                        this.reply = {
                            id: null, 
                            comentario: ''
                        }
                    })
                    .catch((err) => {
                        console.log(err.response)
                    })

                }catch (error){
                    console.log(error)
                }
            }
        },
    }
</script>
<style lang="scss" scoped>
    @import 'src/css/comentarios/projetos.scss';
    @import 'src/css/darkMode/comentarios/projetos.scss';
</style>