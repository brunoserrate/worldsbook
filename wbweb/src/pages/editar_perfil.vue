<template>
    <q-page class="editar_perfil" :class="{'dark-editar_perfil': darkmode }">
        <div class="row inputs_row py-5" v-if="alterar_senha == false">
            <div class="col-12 col-md-7 alinhar_inputs">
                <div class="row align-form px-4 px-md-0">
                    
                    <div class="col-12 mb-3">
                        <p class="p-altere-informacoes text-center">{{ i18n.titulo }}</p>
                    </div>

                    <div class="col-12">
                        <div class="row m-0">
                            <div class="col-12 col-md-4 alinhar_label_utilizador">
                                <span class="me-3">{{ i18n.nome }}</span>
                            </div>
                            <div class="col-12 col-md-7">
                                <q-input square outlined v-model="user.name" :dense="dense" class="input_form"/>
                            </div> 
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="row m-0">
                            <div class="col-12 col-md-4 alinhar_label_utilizador">
                                <span class="me-3">{{ i18n.apelido }}</span>
                            </div>
                            <div class="col-12 col-md-7">
                                <q-input square outlined v-model="user.apelido" :dense="dense" class="input_form"/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <div class="row m-0">
                            <div class="col-12 col-md-4 alinhar_label_utilizador">
                                <span class="me-3">{{ i18n.email }}</span>
                            </div>
                            <div class="col-12 col-md-7">
                                <q-input square outlined type="email" v-model="user.email" :dense="dense" class="input_form"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="row m-0">
                            <div class="col-12 col-md-4 alinhar_label_utilizador">
                                <span class="me-3">{{ i18n.descricao_usuario }}</span>
                            </div>
                            <div class="col-12 col-md-7">
                                <q-input v-model="user.sobre" outlined type="textarea" style="border-radius: 0"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 btn-query-desk">
                        <div class="row m-0">
                            <div class="col-12 col-md-7 offset-4 d-none d-md-block mt-3">
                                <q-btn :label="i18n.salvar" flat @click="setPerfil" class="btn-salvar">
                                    <q-inner-loading
                                        :showing="visible"
                                        label-class="text-teal"
                                        label-style="font-size: 1.1em"
                                    ></q-inner-loading>
                                </q-btn>
                                <q-btn :label="'Alterar senha'" flat @click="alterar_senha = true" class="btn-salvar mt-2"></q-btn>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-12 col-md-5 mt-4">
                <div class="row row_foto d-flex justify-content-center justify-md-content-start">
                    <div class="col-5 col-md-6 d-flex justify-content-center">
                        <q-avatar size="17em" class="avatar-profile" @mouseover="mouseover = true" @mouseout="mouseover = false">
                            <label for='selecao-arquivo'>
                                <q-icon name="photo_camera" class="icon-photo" :style="`display: ${mouseover ? 'block' : 'none'};`" />
                            </label>
                            <q-img :src="user.foto_perfil ? `${path_photo}/${user.foto_perfil}` : `${path_photo}/default.jpg`" class="img-avatar" ></q-img>
                            <q-inner-loading :showing="loading_photo">
                                <q-spinner size="50px" color="primary" :thickness="7" />
                            </q-inner-loading>
                        </q-avatar>
                        <input
                            type="file"
                            accept="image/png, image/jpeg"
                            placeholder="a"
                            id="selecao-arquivo"
                            name="selecao-arquivo"
                            ref="fileInput"
                            @change="handleFileChange"
                        />
                    </div>
                </div>
            </div>
            <div class="col-8 offset-2 mt-5 btn-query-desk d-block d-md-none">
                <q-btn :label="i18n.salvar" flat @click="setPerfil" class="btn-salvar">
                    <q-inner-loading
                        :showing="visible"
                        label-class="text-teal"
                        label-style="font-size: 1.1em"
                    ></q-inner-loading>
                </q-btn>
                <q-btn :label="'Alterar senha'" flat @click="alterar_senha = true" class="btn-salvar mt-2"></q-btn>
            </div>
        </div>
        <div class="row py-5 inputs_row" v-else>
            <div class="col-12 col-md-7 alinhar_inputs">
                <div class="row align-form px-4 px-md-0">
                    
                    <div class="col-12 mb-3">
                        <p class="p-altere-informacoes text-center">{{ 'Alterar senha' }}</p>
                    </div>

                    <div class="col-12">
                        <div class="row m-0">
                            <div class="col-12 col-md-4 alinhar_label_utilizador">
                                <span class="me-3">{{ 'Nova senha' }}</span>
                            </div>
                            <div class="col-12 col-md-7">
                                <q-input 
                                square 
                                outlined 
                                v-model="altSenha.password" 
                                type="password" 
                                :dense="dense" 
                                class="input_form mb-0 pb-0"
                                :error="submittedPassword && !$v.altSenha.password.required"
                            />
                            <div v-if="submittedPassword && !$v.altSenha.password.required" class="invalid-feedback">{{ 'Digite a senha' }}</div>
                            </div> 
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="row m-0">
                            <div class="col-12 col-md-4 alinhar_label_utilizador">
                                <span class="me-3">{{ 'Repita a senha' }}</span>
                            </div>
                            <div class="col-12 col-md-7">
                                <q-input 
                                    square 
                                    outlined 
                                    v-model="altSenha.repeat_password" 
                                    type="password" 
                                    :dense="dense" 
                                    class="input_form mb-0 pb-0"
                                    :error="submittedPassword && !$v.altSenha.repeat_password.required || !$v.altSenha.repeat_password.sameAsPassword"
                                />
                                <div v-if="submittedPassword && !$v.altSenha.repeat_password.required" class="invalid-feedback">{{ 'Digite a senha' }}</div>
                                <div v-if="submittedPassword && !$v.altSenha.repeat_password.sameAsPassword" class="invalid-feedback">{{ 'Senhas não coincidem' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 btn-query-desk">
                        <div class="row m-0">
                            <div class="col-12 col-md-7 offset-4 d-none d-md-block mt-3">
                                <q-btn :label="i18n.salvar" flat @click="alterarSenha" class="btn-salvar">
                                    <q-inner-loading
                                        :showing="visible"
                                        label-class="text-teal"
                                        label-style="font-size: 1.1em"
                                    ></q-inner-loading>
                                </q-btn>
                                <q-btn :label="'Cancelar'" flat variant="secondary" @click="alterar_senha = false" class="btn-salvar mt-2"></q-btn>
                            </div>
                        </div>
                    </div>
                </div>
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
    import { environment } from 'src/helpers/environment';
    import { required, sameAs, email } from 'vuelidate/lib/validators'

    export default {
        name: 'editar-perfil',
        data(){
            return {
                mouseover: false,
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
                altSenha: {
                    password: '',
                    repeat_password: ''
                },
                alterar_senha: false,
                selectedFile: {},
                loading_photo: false,
                visible: false,
                showSimulatedReturnData: false,
                users: [],
                visible: false,
                showSimulatedReturnData: false,
                submittedPassword: false,
                darkmode: false,
                path_photo: `${environment.host}usuarios/profile-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
            }
        },
        validations: {
            altSenha: {
                password: { required },
                repeat_password: { required, sameAsPassword: sameAs('password') }
            },
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
        async mounted(){
            await this.getUsuario()
            this.currentUser = this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario : '') : ''
        },
        methods: {
            async setPerfil(){
                this.visible = true
                this.showSimulatedReturnData = false

                await this.$api.patch(`/usuarios/${this.currentUser._id}`, this.user)
                .then((res) => {

                    let storage_user = this.$q.sessionStorage.getItem('auth')
                    storage_user.usuario = res.data
                    
                    this.$q.sessionStorage.set('auth', storage_user)

                    this.visible = false
                    this.showSimulatedReturnData = true
                    this.perfilEditado(this.avisos.perfil_editado)
                })
                .catch((err) => {
                    console.log("err: ", err)
                    this.visible = false
                    this.showSimulatedReturnData = true
                    this.erroEditar(err, this.avisos.erro_editar)
                })
            },
            async alterarSenha() {
                try {
                    this.submittedPassword = true
                    if (!this.$v.altSenha.$invalid) {
                        this.visible = true
                        let alterar_senha = await this.$api.patch(`usuarios/alterar-senha/${this.currentUser._id}`, this.altSenha)
                        console.log(alterar_senha.data)
                        
                        setTimeout(() => {
                            this.$q.sessionStorage.remove('auth')
                            this.$router.go({ path: '/' })
                            this.visible = false
                        }, 2000)

                    } else {
                        console.log(this.$v)
                    }

                } catch (error) {
                    console.log(error)
                    this.falha('Houve um erro ao tentar alterar a senha, tente novamente!')
                    this.visible = false
                }
            },
            async handleFileChange() {
                this.loading_photo = true
                const fileInput = this.$refs.fileInput;
                if (fileInput.files.length > 0) {
                    this.selectedFile = fileInput.files[0];
                    await this.uploadFiles(this.selectedFile)
                } else {
                    this.selectedFile = null;
                    this.loading_photo = false
                }
            },
            async uploadFiles(file){
                if (!this.selectedFile) {
                    console.error('Nenhum arquivo selecionado.');
                    return;
                }

                let data = new FormData();
                data.append('file', this.selectedFile);

                try {
                    await this.$api.post('usuarios/upload/foto', data, {
                        headers: {
                            'content-type': 'multipart/form-data',
                        },
                        processData: false,
                        contentType: false
                    });

                    await this.getUsuario()
                    this.$router.go()

                    setTimeout(() => {
                        this.loading_photo = false
                    }, 1000)
                    console.log('Upload concluído com sucesso.');
                } catch (error) {
                    console.error('Erro ao fazer upload do arquivo:', error);
                    this.loading_photo = false
                }
            },
            removerFoto(){
                this.user.foto_perfil = ''
            },
        },
    }
</script>
<style lang="scss" scoped>
    @import '../css/editar-perfil.scss';
    @import '../css/darkMode/editar-perfil-dark.scss';
</style>