<template>
    <q-page :class="{'dark-criar_historia': darkmode, 'criar_historia': !darkmode}">
        <div class="row justify-center">
            <div class="col-12">
                <div class="row justify-center" style="margin: 60px 0px 36px 0px">
                    <div class="col-12 d-block d-md-none mb-4">
                        <h3 class="title_historia text-center m-0">{{ i18n.titulo }}</h3>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3 d-flex col_upload_image mb-5 me-0 me-md-5">
                        <!-- <div class="avatar-profile" @mouseover="mouseover = true" @mouseout="mouseover = false"> -->
                        <div class="row_foto">
                            <div class="avatar-profile">
                                <label for='selecao-arquivo'>
                                    <!-- <q-icon name="photo_camera" class="icon-photo" :style="`display: ${mouseover ? 'block' : 'none'};`" /> -->
                                    <q-icon name="photo_camera" class="icon-photo m-3" />
                                </label>
                                <q-img :src="historia.caminho_capa ? `${path_photo}/${historia.caminho_capa}` : `${path_photo}/default.png`" class="img-avatar" ></q-img>
                                <q-inner-loading :showing="loading_photo">
                                    <q-spinner size="50px" color="primary" :thickness="7" />
                                </q-inner-loading>
                            </div>
                            <input
                                type="file"
                                accept="image/png, image/jpeg"
                                placeholder="a"
                                id="selecao-arquivo"
                                name="selecao-arquivo"
                                ref="fileInput"
                                @change="handleFileChange"
                            />
                            <div v-if="submitted && !$v.historia.caminho_capa.required" class="invalid-feedback text-center">Insira uma capa</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-12 col-card d-flex justify-content-center">
                                <q-card class="card_form_historia">
                                    <div class="col-12 col-sm-6 d-none d-md-block">
                                        <h3 class="title_historia">{{ i18n.titulo }}</h3>
                                    </div>
                                    <div class="col-12 my-4 my-md-0">
                                        <q-separator class="d-none d-md-block separator_card"/>
                                    </div>
                                    <div class="col-12">
                                        <p class="label_input">{{ i18n.detalhes_historia.titulo.label }}</p>
                                        <q-input filled v-model="historia.titulo" :placeholder="i18n.detalhes_historia.titulo.placeholder" :error="submitted && !$v.historia.titulo.required" :dense="dense" class="inputs_form_historia p-0" />
                                        <div v-if="submitted && !$v.historia.titulo.required" class="invalid-feedback">{{ i18n.avisos.preecha_titulo }}</div>
                                    </div>
                                    <div class="col-12 my-4 my-md-0">
                                        <q-separator class="d-none d-md-block separator_card"/>
                                    </div>
                                    <div class="col-12">
                                        <p class="label_input">{{ i18n.detalhes_historia.descricao.label }}</p>
                                        <q-input filled v-model="historia.descricao" type="textarea" :placeholder="i18n.detalhes_historia.descricao.placeholder" :error="submitted && !$v.historia.descricao.required" :dense="dense" class="inputs_form_historia_descricao p-0" />
                                        <div v-if="submitted && !$v.historia.descricao.required" class="invalid-feedback">{{ i18n.avisos.preecha_descricao }}</div>
                                    </div>
                                    <div class="col-12 my-4 my-md-0">
                                        <q-separator class="d-none d-md-block separator_card"/>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 col-select">
                                            <p class="label_select">{{i18n.detalhes_historia.categoria.label}}</p>
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <q-select
                                                filled
                                                v-model="historia.categoria"
                                                :options="categorias"
                                                type="number"
                                                :label="i18n.detalhes_historia.categoria.placeholder"
                                                :error="submitted && !$v.historia.categoria.required"
                                                :dense="dense"
                                                class="inputs_form_historia_descricao p-0"
                                                option-value="_id"
                                                option-label="nome"
                                                emit-value
                                                map-options
                                            />
                                            <div v-if="submitted && !$v.historia.categoria.required" class="invalid-feedback">{{ i18n.avisos.preecha_categoria }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 my-4 my-md-0">
                                        <q-separator class="d-none d-md-block separator_card"/>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="label_input">{{ i18n.detalhes_historia.etiquetas.label }}</p>
                                            <q-select
                                                filled
                                                v-model="historia.tags"
                                                class="inputs_form_historia p-0"
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
                                    <div class="col-12 my-4 my-md-0">
                                        <q-separator class="d-none d-md-block separator_card"/>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 col-select">
                                            <p class="label_select">{{ i18n.detalhes_historia.publico_alvo.label }}</p>
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <q-select
                                                filled
                                                v-model="historia.publico_alvo"
                                                :options="publicos_alvo"
                                                type="number"
                                                :label="i18n.detalhes_historia.publico_alvo.placeholder"
                                                :error="submitted && !$v.historia.publico_alvo.required"
                                                :dense="dense"
                                                class="inputs_form_historia_descricao p-0"
                                                option-value="_id"
                                                option-label="publico"
                                                emit-value
                                                map-options
                                            />
                                            <div v-if="submitted && !$v.historia.publico_alvo.required" class="invalid-feedback">{{ i18n.avisos.preecha_publico_alvo }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 my-4 my-md-0">
                                        <q-separator class="d-none d-md-block separator_card"/>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 col-select">
                                            <p class="label_select">{{ i18n.detalhes_historia.idioma.label }}</p>
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <q-select
                                                filled
                                                v-model="historia.idioma"
                                                :options="idiomas"
                                                type="number"
                                                :label="i18n.detalhes_historia.idioma.placeholder"
                                                :error="submitted && !$v.historia.idioma.required"
                                                :dense="dense"
                                                class="inputs_form_historia_descricao p-0"
                                                option-value="_id"
                                                option-label="nome"
                                                emit-value
                                                map-options
                                            />
                                            <div v-if="submitted && !$v.historia.idioma.required" class="invalid-feedback">{{ i18n.avisos.preecha_idioma }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 my-4 my-md-0">
                                        <q-separator class="d-none d-md-block separator_card"/>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 col-select">
                                            <p class="label_select">{{ i18n.detalhes_historia.direitos_autorais.label }}</p>
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <q-select
                                                filled
                                                v-model="historia.direitos_autorais"
                                                :options="direitos_autorais"
                                                :label="i18n.detalhes_historia.direitos_autorais.placeholder"
                                                :error="submitted && !$v.historia.direitos_autorais.required"
                                                :dense="dense"
                                                class="inputs_form_historia_descricao p-0"
                                                option-value="_id"
                                                option-label="tipo_autoral"
                                                emit-value
                                                map-options
                                            />
                                            <div v-if="submitted && !$v.historia.direitos_autorais.required" class="invalid-feedback">{{ i18n.avisos.preecha_direitos }}</div>
                                        </div>
                                        <div class="col-12" v-if="historia.direitos_autorais == 1">
                                            <p class="p_direitos" >i18n.detalhes_historia.direitos_autorais.aviso</p>
                                        </div>
                                    </div>
                                    <div class="col-12 my-4 my-md-0">
                                        <q-separator class="d-none d-md-block separator_card"/>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 col-select d-block">
                                            <p class="label_select">{{ i18n.detalhes_historia.conteudo_adulto.label }}</p>
                                        </div>
                                        <div class="col-8">
                                            <q-toggle v-model="historia.conteudo_adulto" color="#8e1cac" />
                                            <div v-if="submitted && !$v.historia.conteudo_adulto.required" class="invalid-feedback">{{ i18n.avisos.preecha_conteudo_adulto }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 my-4 my-md-0">
                                        <q-separator class="d-none d-md-block separator_card"/>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6 d-flex justify-content-center">
                                            <q-btn flat :label="i18n.botoes.seguinte" class="btn_seguinte" @click="setLivro">
                                                <q-inner-loading
                                                    :showing="visible"
                                                    label-class="text-teal"
                                                    label-style="font-size: 1.1em"
                                                >
                                                </q-inner-loading>
                                            </q-btn>
                                        </div>
                                        <div class="col-12 col-sm-6 d-flex justify-content-center my-3 my-sm-0">
                                            <q-btn flat :label="i18n.botoes.cancelar" class="btn_cancelar" @click="cancel"></q-btn>
                                        </div>
                                    </div>
                                </q-card>
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
    import { required, minLength } from 'vuelidate/lib/validators'
    import { environment } from 'src/helpers/environment';

    export default {
        name: 'criar-historia',
        data(){
            return {
                // Uploader
                uploadPercentage: 0,
                uploadPercent: null,
                files: null,
                errors: null,
                data: null,
                confirm: false,
                darkmode: false,
                submitted: false,
                loading_photo: false,
                // Uploader
                dense: true,
                historia: {
                    titulo: '',
                    descricao: '',
                    personagens_principais: [],
                    categoria: '',
                    publico_alvo: '',
                    idioma: '',
                    direitos_autorais: '',
                    conteudo_adulto: false,
                    caminho_capa: '',
                    tags: [],
                    historia_finalizada: '',
                    data_atualizacao: '',
                    data_criacao: '',
                    usuario: ''
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
                selectedFile: {},
                categorias: [],
                publicos_alvo: [],
                idiomas: [],
                historias: [],
                direitos_autorais: [],
                user: {},
                i18n: {},
                avisos: {},
                visible: false,
                showSimulatedReturnData: false,
                path_photo: `${environment.host}historias/capa-image`,
            }
        },
        validations() {
            return {
                historia: {
                    titulo: { required },
                    descricao: { required },
                    categoria: { required },
                    publico_alvo: { required },
                    idioma: { required },
                    direitos_autorais: { required },
                    conteudo_adulto: { required },
                    caminho_capa: { required }
                },
            }
        },
        async mounted(){
            await this.setLivroRascunho()
            await this.getCategorias()
            await this.getPublicoAlvo()
            await this.getIdiomas()
            await this.getDireitoAutorais()
            await this.getUser()
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
            async setLivroRascunho() {
                await this.$api.post(`historias/rascunho`)
                .then((res) => {
                    this.historia = { 
                        ... this.historia,
                        _id: res.data._id,
                        status: res.data.status,
                        usuario: res.data.usuario,
                    }
                })
                .catch((err) => {
                    console.log(err.response)
                    this.erroCriacao(this.avisos.erro_criacao_historia)
                })
            },
            async setLivro(){
                this.submitted = true;
                this.$v.historia.$touch()
                
                if (!this.$v.historia.$invalid) {
                    this.historia.historia_finalizada = false
    
                    this.visible = true
                    this.showSimulatedReturnData = false
    
                    await this.$api.patch(`historias/finalizar-criacao/${this.historia._id}`, this.historia)
                    .then((res) => {
                        this.visible = false
                        this.showSimulatedReturnData = true
    
                        this.historiaCriadaSucesso(this.avisos.historia_criada)
                        this.$router.push({path: `criar_historia/` + res.data._id})
                    })
                    .catch((err) => {
                        console.log(err.response)
                        this.erroCriacao(this.avisos.erro_criacao_historia)
                        this.visible = false
                        this.showSimulatedReturnData = true
                    })
                }

            },
            cancel(){
                this.$router.push({path: '/iniciar_leitura' })
            },

            // Uploader
            
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
                    await this.$api.post(`historias/upload/capa/${this.historia._id}`, data, {
                        headers: {
                            'content-type': 'multipart/form-data',
                        },
                        processData: false,
                        contentType: false
                    })
                    .then(res => {
                        this.historia.caminho_capa = res.data.caminho_capa
                        this.sucesso()
                    })
                    .catch(err => {
                        console.log(err)
                        this.falha()
                    })

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
                this.historia.caminho_capa = ''
            },
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