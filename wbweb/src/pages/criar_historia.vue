<template>
    <q-page :class="{'dark-criar_historia': darkmode, 'criar_historia': !darkmode}">
        <div class="row justify-center">
            <div class="col-12">
                <div class="row justify-center" style="margin: 60px 0px 36px 0px">
                    <div v-if="historia.caminho_capa === '' " class="col-12 col-md-auto col_upload_image mb-5">
                        <q-uploader
                            auto-upload
                            :factory="uploadFiles"
                            @finish="finishedUpload"
                            :loading="uploadPercent"
                            :url="getUrl()"
                            :label="i18n.imagem + ' (max 2MB)'"
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
                    <div v-else class="col-12 col-md-auto image_upload mb-5">
                        <q-btn-dropdown
                            dropdown-icon="info"
                            flat
                            @click="onMainClick"
                            class="dropdown_remove"
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
                        <img :src="historia.caminho_capa" alt="" class="historia_caminho_capa">
                    </div>
                    <div class="row">
                        <div class="col-12 col-card">
                            <q-card class="card_form_historia">
                                <div class="col-12 col-sm-6">
                                    <h3 class="title_historia">{{ i18n.titulo }}</h3>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="col-12">
                                    <p class="label_input">{{ i18n.detalhes_historia.titulo.label }}</p>
                                    <q-input filled v-model="historia.titulo" :placeholder="i18n.detalhes_historia.titulo.placeholder" :dense="dense" class="inputs_form_historia"/>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="col-12">
                                    <p class="label_input">{{ i18n.detalhes_historia.descricao.label }}</p>
                                    <q-input filled v-model="historia.descricao" type="textarea" :placeholder="i18n.detalhes_historia.descricao.placeholder" :dense="dense" class="inputs_form_historia_descricao"/>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="row">
                                    <div class="col-4 col-select">
                                        <p class="label_select">{{i18n.detalhes_historia.categoria.label}}</p>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <q-select
                                            filled
                                            v-model="historia.categoria_id"
                                            :options="categorias"
                                            type="number"
                                            :label="i18n.detalhes_historia.categoria.placeholder"
                                            :dense="dense"
                                            class="inputs_form_historia_descricao"
                                            option-value="id"
                                            option-label="genero"
                                            emit-value
                                            map-options
                                            />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p class="label_input">{{i18n.detalhes_historia.etiquetas.label}}</p>
                                        <q-select
                                            filled
                                            v-model="historia.tags"
                                            class="inputs_form_historia"
                                            color="white"
                                            :placeholder="i18n.detalhes_historia.etiquetas.placeholder"
                                            :dense="dense"
                                            use-input
                                            use-chips
                                            multiple
                                            hide-dropdown-icon
                                            input-debounce="0"
                                            new-value-mode="add-unique"
                                            emit-value
                                            map-options
                                            >
                                            <template v-slot:selected>
                                                <q-chip
                                                    v-for="(chip, i) in historia.tags" :key="i"
                                                    dense
                                                    square
                                                    color="accent"
                                                    text-color="white"
                                                    >
                                                    {{ chip }}
                                                </q-chip>
                                            </template>
                                        </q-select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="row">
                                    <div class="col-4 col-select">
                                        <p class="label_select">{{i18n.detalhes_historia.publico_alvo.label}}</p>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <q-select
                                            filled
                                            v-model="historia.publico_alvo_id"
                                            :options="publicos_alvo"
                                            type="number"
                                            :label="i18n.detalhes_historia.publico_alvo.placeholder"
                                            :dense="dense"
                                            class="inputs_form_historia_descricao"
                                            option-value="id"
                                            option-label="publico"
                                            emit-value
                                            map-options
                                            />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="row">
                                    <div class="col-4 col-select">
                                        <p class="label_select">{{i18n.detalhes_historia.idioma.label}}</p>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <q-select
                                            filled
                                            v-model="historia.idioma_id"
                                            :options="idiomas"
                                            type="number"
                                            :label="i18n.detalhes_historia.idioma.placeholder"
                                            :dense="dense"
                                            class="inputs_form_historia_descricao"
                                            option-value="id"
                                            option-label="idioma"
                                            emit-value
                                            map-options
                                            />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="row">
                                    <div class="col-4 col-select">
                                        <p class="label_select">{{i18n.detalhes_historia.direitos_autorais.label}}</p>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <q-select
                                            filled
                                            v-model="historia.direitos_autorais_id"
                                            :options="direitos_autorais"
                                            :label="i18n.detalhes_historia.direitos_autorais.placeholder"
                                            :dense="dense"
                                            class="inputs_form_historia_descricao"
                                            option-value="id"
                                            option-label="tipo_autoral"
                                            emit-value
                                            map-options
                                            />
                                    </div>
                                    <div class="col-12" v-if="historia.direitos_autorais == 1">
                                        <p class="p_direitos" >i18n.detalhes_historia.direitos_autorais.aviso</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="row">
                                    <div class="col-4 col-select">
                                        <p class="label_select">{{i18n.detalhes_historia.conteudo_adulto.label}}</p>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <q-select
                                            filled
                                            v-model="historia.conteudo_adulto"
                                            :options="classificacoes"
                                            :label="i18n.detalhes_historia.conteudo_adulto.placeholder"
                                            :dense="dense"
                                            class="inputs_form_historia_descricao"
                                            option-value="value"
                                            option-label="label"
                                            emit-value
                                            map-options
                                            />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="row">
                                    <div class="col-6 d-flex justify-content-center">
                                        <q-btn flat :label="i18n.botoes.seguinte" class="btn_seguinte" @click="setLivro">
                                            <q-inner-loading
                                                :showing="visible"
                                                label-class="text-teal"
                                                label-style="font-size: 1.1em"
                                            >
                                            </q-inner-loading>
                                        </q-btn>
                                    </div>
                                    <div class="col-6 d-flex justify-content-center">
                                        <q-btn flat :label="i18n.botoes.cancelar" class="btn_cancelar" @click="cancel"></q-btn>
                                    </div>
                                </div>
                            </q-card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <q-dialog v-model="confirm" persistent>
            <q-card :class="{'dark-card-remover-foto': darkmode, 'card-remover-foto': !darkmode}">
                <q-card-section class="row items-center">
                <q-avatar icon="delete" color="primary" text-color="white" />
                <span class="q-ml-sm">{{i18n.dialogs.confirmacao}}</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat :label="i18n.dialogs.sim" color="primary" @click="removerFoto" v-close-popup />
                    <q-btn flat :label="i18n.dialogs.cancelar" @click="confirm = false" color="primary" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </q-page>
</template>
<script>
    import UploadHelper from 'components/utils/UploadHelper.vue'
  	import eventBus from '../boot/eventBus'

    export default {
        name: 'criar-historia',
        data(){
            return {
                // Uploader
                uploadPercentage: 0,
                uploadPercent:null,
                files:null,
                errors: null,
                data: null,
                confirm: false,
                darkmode: false,
                // Uploader
                dense: true,
                historia: {
                    titulo: '',
                    descricao: '',
                    personagens_principais: [],
                    categoria_id: '',
                    publico_alvo_id: '',
                    idioma_id: '',
                    direitos_autorais_id: '',
                    conteudo_adulto: '',
                    caminho_capa: '',
                    tags: [],
                    historia_finalizada: '',
                    data_atualizacao: '',
                    data_criacao: '',
                    usuario_id: ''
                },
                classificacoes: [
                    {
                        label: '',
                        value: true,
                    },
                    {
                        label: '',
                        value: false,
                    },

                ],
                categorias: [],
                publicos_alvo: [],
                idiomas: [],
                historias: [],
                direitos_autorais: [],
                user: {},
                i18n: {},
                avisos: {},
                visible: false,
                showSimulatedReturnData: false
            }
        },
        mounted(){
            this.getCategorias()
            this.getPublicoAlvo()
            this.getIdiomas()
            this.getDireitoAutorais()
            this.getUser()
            // console.log(this.user)
        },
        watch: {

        },
        components:{
            UploadHelper
        },
        created() {
            this.i18n = this.$i18n.criar_historia
            this.avisos = this.$i18n.avisos
            this.classificacoes[0].label = this.$i18n.criar_historia.sim
            this.classificacoes[1].label = this.$i18n.criar_historia.nao
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
                    this.i18n = this.$i18n.criar_historia
                    this.avisos = this.$i18n.avisos
                    this.classificacoes[0].label = this.$i18n.criar_historia.sim
                    this.classificacoes[1].label = this.$i18n.criar_historia.nao
                }, 500)
            });
        },
        methods: {
            setLivro(){
                let that = this

                // console.log(this.validarHistoria())

                if(!this.validarHistoria()) return false

                that.historia.usuario_id = that.user.user_id
                // that.historia.data_atualizacao = Date.now()
                // that.historia.data_criacao = Date.now()
                that.historia.historia_finalizada = 0
                // console.log(that.historia)

                that.visible = true
                that.showSimulatedReturnData = false

                that.$axios.post(that.$pathAPI + '/historia', that.historia)
                .then((res) => {
                    // console.log("res: ", res)
                    that.historias = res.data.data
                    that.visible = false
                    that.showSimulatedReturnData = true

                    this.historiaCriadaSucesso(this.avisos.historia_criada)
                    // console.log("A: ", that.historias)
                    this.$router.push({path: `criar_historia/` + that.historias.historia_id})
                })
                .catch((err) => {
                    console.log(err.response)
                    this.erroCriacao(this.avisos.erro_criacao_historia)
                    that.visible = false
                    that.showSimulatedReturnData = true
                })
            },
            cancel(){
                this.$router.push({path: '/iniciar_leitura' })
            },

            // Uploader
            uploadFiles(file){
                this.uploadPercentage = true
                let data = new FormData()
                data.append(`file`, file[0])

                return new Promise((resolve, reject) => {
                this.$axios.post(this.$pathAPI + '/historia/upload/capa', data, {
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
            validarHistoria() {
                if (this.valEmpty(this.historia.titulo) ) {
                    this.aviso(this.i18n.avisos.preencha_titulo)
                    return false
                }

                if (this.valEmpty(this.historia.descricao) ) {
                    this.aviso(this.i18n.avisos.preencha_descricao)
                    return false
                }

                if (this.valEmpty(this.historia.categoria_id) ) {
                    this.aviso(this.i18n.avisos.preencha_categoria)
                    return false
                }

                if (this.valEmpty(this.historia.publico_alvo_id) ) {
                    this.aviso(this.i18n.avisos.preencha_publico_alvo)
                    return false
                }

                if (this.valEmpty(this.historia.idioma_id) ) {
                    this.aviso(this.i18n.avisos.preencha_idioma)
                    return false
                }

                if (this.valEmpty(this.historia.direitos_autorais_id) ) {
                    this.aviso(this.i18n.avisos.preencha_direitos)
                    return false
                }

                if (this.valEmpty(this.historia.caminho_capa) ) {
                    this.aviso(this.i18n.avisos.preencha_capa)
                    return false
                }

                return true

            },
            getUrl(){
                return this.$pathAPI + '/uploads'
            },
            finishedUpload () {
                this.$refs.uploader.reset()
            },
            onMainClick () {
                // console.log('Clicked on main button')
            },
            onItemClick () {
                this.confirm = true
            },
            removerFoto(){
                this.historia.caminho_capa = ''
            },
            valEmpty(val){

                if(val === null || val === undefined || val === '' || val === 0){
                    return true
                }

                return false
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import '../css/criar-historia.scss';
    @import '../css/darkMode/criar-historia-dark.scss';

    .q-chip--dense {
        border-radius: 12px;
        padding: 0 0.4em;
        height: 1.5em;
        background-color: #7A22A7;
    }

</style>