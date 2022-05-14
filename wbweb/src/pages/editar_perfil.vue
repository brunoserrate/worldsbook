<template>
    <q-page>
        <div class="row">
            <div class="col-10 offset-2" style="margin-top: 20px;">
                <p class="p-altere-informacoes">Altere as informações da sua conta</p>
                <q-btn label="Salvar" @click="setPerfil"></q-btn>
            </div>
        </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-7 alinhar_inputs">
                <div class="row">
                    <div class="col-4 alinhar_label_utilizador">
                        <span>Nome</span>
                    </div>
                    <div class="col-7">
                        <q-input square outlined v-model="user.name" :dense="dense" class="input_form"/>
                    </div> 
                    <div class="col-4 alinhar_label_utilizador">
                        <span>Apelido</span>
                    </div>
                    <div class="col-7">
                        <q-input square outlined v-model="user.apelido" :dense="dense" class="input_form"/>
                    </div>
                    <div class="col-4 alinhar_label_utilizador">
                        <span>E-mail</span>
                    </div>
                    <div class="col-7">
                        <q-input square outlined type="email" v-model="user.email" :dense="dense" class="input_form"/>
                    </div>
                    <div class="col-4 alinhar_label_utilizador">
                        <span>Senha</span>
                    </div>
                    <div class="col-7">
                        <q-input square outlined type="password" disable v-model="user.password" :dense="dense" class="input_form"/>
                    </div>
                    <div class="col-4 alinhar_label_utilizador">
                        <span>Descrição do usuário</span>
                    </div>
                    <div class="col-7">
                        <q-input v-model="user.sobre" outlined type="textarea" style="border-radius: 0"/>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="row">
                    <div v-if="user.avatar === '' " class="col-6 col-md-auto">
                        <q-uploader
                            :factory="uploadFiles"
                            @finish="finishedUpload"
                            :loading="uploadPercent"
                            :url="getUrl()"
                            label="Carregar imagem (max 2MB)"
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
                    <div v-else class="col-6">
                        <q-img :src="user.avatar" alt="" class="foto_perfil">
                            <q-btn-dropdown
                                dropdown-icon="info"
                                push
                                split
                                @click="onMainClick"
                                >
                                <q-list>
                                    <q-item clickable v-close-popup @click="onItemClick">
                                        <q-item-section>
                                            <q-item-label>Remover foto</q-item-label>
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon name="delete" color="primary" />
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-btn-dropdown>
                            <!-- <q-icon class="absolute all-pointer-events" size="32px" name="info" color="white" style="top: 8px; left: 8px">
                                <q-tooltip>
                                    Tooltip
                                </q-tooltip>
                            </q-icon> -->
                        </q-img>
                    </div>
                </div>
            </div>
        </div>
        <q-dialog v-model="confirm" persistent>
            <q-card>
                <q-card-section class="row items-center">
                <q-avatar icon="delete" color="primary" text-color="white" />
                <span class="q-ml-sm">Deseja remover a foto de perfil?</span>
                </q-card-section>

                <q-card-actions align="right">
                <q-btn flat label="Sim" color="primary" @click="removerFoto" v-close-popup />
                <q-btn flat label="Cancelar" @click="confirm = false" color="primary" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </q-page>
</template>
<script>
export default {
    name: 'editar-perfil',
    data(){
        return {
            dense: true,
            uploadPercentage: 0,
            uploadPercent:null,
            confirm: false,
            user: {
                name: '',
                sobre: '',
                data_nascimento: '',
                avatar: '',
                foto_perfil: '',
                apelido: '',
                email: '',
                password: '',
            },
            visible: false,
            showSimulatedReturnData: false,
            users: []
        }
    },

    mounted(){
        this.getUser()
        console.log(this.user)
    },

    methods: {
        setPerfil(){
            let that = this

            that.visible = true
            that.showSimulatedReturnData = false
            
            let params = {
                name: that.user.name,
                sobre: that.user.sobre,
                foto_perfil: that.user.avatar,
                apelido: that.user.apelido,
                email: that.user.email,
            }

            that.$axios.patch(that.$pathAPI + `/user/perfil/${this.user.user_id}`, params)
            .then((res) => {
                console.log("res: ", res)
                that.users = res.data.data
                console.log(that.users)
                that.visible = false
                that.showSimulatedReturnData = true
                this.capituloCriadoSucesso()

            })
            .catch((err) => {
                console.log(err.response)
                this.erroCriacaoCapitulo()
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
					this.historia.caminho_capa = res.data.data.full_path
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
            this.user.avatar = ''
        }
    },
}
</script>
<style lang="scss" scoped>
    @import '../css/editar-perfil.scss';
</style>