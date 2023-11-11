<template>
    <q-page :class="{'dark-editar_perfil': darkmode, 'editar_perfil': !darkmode}">
        <div class="row row-dark-perfil">
            <div class="col-10 offset-2">
                <p class="p-altere-informacoes">{{ i18n.titulo }}</p>
            </div>
        </div>
        <div class="row inputs_row pb-5">
            <div class="col-12 col-md-7 alinhar_inputs">
                <div class="row align-form">
                    <div class="col-9 col-md-4 mt-md-2 mt-lg-2 mt-xl-0 offset-md-0 alinhar_label_utilizador">
                        <span>{{ i18n.nome }}</span>
                    </div>
                    <div class="col-9 col-md-7">
                        <q-input square outlined v-model="user.name" :dense="dense" class="input_form"/>
                    </div> 
                    <div class="col-9 col-md-4 mt-md-2 mt-lg-2 mt-xl-0 offset-md-0 alinhar_label_utilizador">
                        <span>{{ i18n.apelido }}</span>
                    </div>
                    <div class="col-9 col-md-7">
                        <q-input square outlined v-model="user.apelido" :dense="dense" class="input_form"/>
                    </div>
                    <div class="col-9 col-md-4 mt-md-2 mt-lg-2 mt-xl-0 offset-md-0 alinhar_label_utilizador">
                        <span>{{ i18n.email }}</span>
                    </div>
                    <div class="col-9 col-md-7">
                        <q-input square outlined type="email" v-model="user.email" :dense="dense" class="input_form"/>
                    </div>
                    <div class="col-9 col-md-4 mt-md-2 mt-lg-2 mt-xl-0 offset-md-0 alinhar_label_utilizador">
                        <span>{{ i18n.senha }}</span>
                    </div>
                    <div class="col-8 col-md-6">
                        <q-input square outlined type="password" disable v-model="user.password" :dense="dense" class="input_form"/>
                    </div>
                    <div class="col-1 align_icon">
                        <q-icon name="edit" class="icone_edit_password"></q-icon>
                    </div>
                    <div class="col-9 col-md-4 mt-md-2 mt-lg-2 mt-xl-0 offset-md-0 alinhar_label_utilizador">
                        <span>{{ i18n.descricao_usuario }}</span>
                    </div>
                    <div class="col-9 col-md-7">
                        <q-input v-model="user.sobre" outlined type="textarea" style="border-radius: 0"/>
                    </div>
                    <div class="col-9 col-md-7 offset-4 btn-query">
                        <q-btn :label="i18n.salvar" flat @click="setPerfil" class="btn-salvar">
                            <q-inner-loading
                                :showing="visible"
                                label-class="text-teal"
                                label-style="font-size: 1.1em"
                            ></q-inner-loading>
                        </q-btn>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-5">
                <div class="row row_foto">
                    <div v-if="user.foto_perfil === '' " class="col-6 col-md-auto">
                        <q-uploader
                            auto-upload
                            :factory="uploadFiles"
                            @finish="finishedUpload"
                            :loading="uploadPercent"
                            :url="getUrl()"
                            :label="i18n.imagem + '(max 2MB)'"
                            ref="uploader"
                            bordered
                            batch
                            accept=".png, .jpeg, .jpg"
                            :max-file-size="2048000"
                            class="upload_image"
                            color="transparent"
                            text-color="black"
                            flat
                        />
                    </div>
                    <div v-else class="col-5 col-md-6">
                        <q-img :src="user.foto_perfil" alt="" class="foto_perfil">
                            <q-btn-dropdown
                                dropdown-icon="info"
                                flat
                                class="dropdown_remove"
                                @click="onMainClick"
                                >
                                <q-list>
                                    <q-item clickable v-close-popup @click="onItemClick">
                                        <q-item-section>
                                            <q-item-label>{{ i18n.remover_foto }}</q-item-label>
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon name="delete" color="primary" />
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-btn-dropdown>
                        </q-img>
                    </div>
                </div>
            </div>
            <div class="col-8 offset-2 btn-query-desk">
                <q-btn :label="i18n.salvar" flat @click="setPerfil" class="btn-salvar">
                    <q-inner-loading
                        :showing="visible"
                        label-class="text-teal"
                        label-style="font-size: 1.1em"
                    ></q-inner-loading>
                </q-btn>
            </div>
        </div>
        <q-dialog v-model="confirm" persistent>
            <q-card :class="{'dark-card-remover-foto': darkmode, 'card-remover-foto': !darkmode}">
                <q-card-section class="row items-center">
                    <q-avatar icon="delete" color="primary" text-color="white" />
                    <span class="q-ml-sm">{{ i18n.dialog.confirmacao }}</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat :label="i18n.dialog.sim" color="primary" @click="removerFoto" v-close-popup />
                    <q-btn flat :label="i18n.dialog.cancelar" @click="confirm = false" color="primary" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </q-page>
</template>
<script>
    import eventBus from '../boot/eventBus'
    export default {
        name: 'editar-perfil',
        data(){
            return {
                dense: true,
                uploadPercentage: 0,
                uploadPercent:null,
                confirm: false,
                i18n: {},
                avisos: {},
                user: {
                    name: '',
                    sobre: '',
                    avatar: '',
                    foto_perfil: '',
                    apelido: '',
                    email: '',
                },
                visible: false,
                showSimulatedReturnData: false,
                users: [],
                visible: false,
                showSimulatedReturnData: false,
                darkmode: false
            }
        },
        created() {
            this.i18n = this.$i18n.editar_perfil
            this.avisos = this.$i18n.avisos
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
                    this.i18n = this.$i18n.editar_perfil
                    this.avisos = this.$i18n.avisos
                }, 500)
            });
        },
        mounted(){
            this.getUser()
            // console.log(this.user)
        },
        methods: {
            setPerfil(){
                let that = this

                that.visible = true
                that.showSimulatedReturnData = false

                let params = {
                    name: that.user.nome,
                    sobre: that.user.sobre,
                    foto_perfil: that.user.foto_perfil,
                    apelido: that.user.apelido,
                    email: that.user.email,
                }

                that.$axios.patch(that.$pathAPI + `/user/perfil/${this.user.user_id}`, params)
                .then((res) => {
                    console.log("res: ", res)

                    let storage_user = JSON.parse( this.$q.sessionStorage.getItem('auth') )
                    let token = storage_user.token
                    // Alterar os dados necessários
                    storage_user = res.data.data // Nome, apelido, avatar,
                    storage_user.token = token
                    // Sobrepor a chave auth do session storage
                    this.$q.sessionStorage.set('auth', JSON.stringify( storage_user ))

                    that.visible = false
                    that.showSimulatedReturnData = true
                    this.perfilEditado(this.avisos.perfil_editado)

                })
                .catch((err) => {
                    console.log("err: ", err)
                    that.visible = false
                    that.showSimulatedReturnData = true
                    this.erroEditar(err, this.avisos.erro_editar)
                })
            },
            uploadFiles(file){
                this.uploadPercentage = true
                let data = new FormData()
                data.append(`file`, file[0])

                return new Promise((resolve, reject) => {
                this.$axios.post(this.$pathAPI + '/user/upload/foto', data, {
                    headers: { 'content-type': 'multipart/form-data' },
                    processData: false,  contentType: false
                })
                    .then(res => {
                        resolve(null)
                        this.user.foto_perfil = res.data.data.full_path
                        this.uploadPercentage = false
                        this.sucesso()
                    })
                    .catch(err => {
                        reject(err)
                        this.uploadPercentage = false
                        this.falha()
                    })
                })
            },
            finishedUpload () {
                this.$refs.uploader.reset()
            },
            getUrl(){
                return this.$pathAPI + '/uploads'
            },
            onMainClick () {
                // console.log('Clicked on main button')
            },
            onItemClick () {
                this.confirm = true
            },
            removerFoto(){
                this.user.foto_perfil = ''
            },
            buscarString(string, busca) {
                if(string != null || string != undefined){
                    return string.indexOf(busca)
                }
                return 1
            }
        },
    }
</script>
<style lang="scss" scoped>
    @import '../css/editar-perfil.scss';
    @import '../css/darkMode/editar-perfil-dark.scss';
</style>