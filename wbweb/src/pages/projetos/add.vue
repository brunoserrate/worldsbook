
<template>
    <q-page :class="{'create-projeto-dark': darkmode}" class="create-projeto">
        <q-inner-loading
            :showing="loading"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            class="loading"
            :label="i18n.carregando+'...'"
        ></q-inner-loading>
        <div class="row row-projetos m-0 py-3 py-sm-5 px-2 px-sm-5" >
            <div class="col-12">
                <h1>Criação do projeto</h1>
                <p>Crie seu projeto e compartilhe com os seus amigos para participar!</p>
            </div>
            <div class="col-12 mb-3" v-if="projeto_id">
                <q-card class="h-100 capa-projeto p-2">
                    <div class="row row-capa-projeto">
                        <div class="col-12">
                            <div class="avatar-profile d-flex justify-content-center align-items-center" @mouseover="mouseover = true" @mouseout="mouseover = false">
                                <label for='selecao-arquivo'>
                                    <!-- <q-icon name="photo_camera" class="icon-photo" :style="`display: ${mouseover ? 'block' : 'none'};`" /> -->
                                    <q-icon name="photo_camera" class="icon-photo m-3" />
                                </label>
                                <img :src="projeto.capa ? `${path_cover}/${projeto.capa}` : `${path_cover}/default.png`" class="img-avatar" />
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
                        </div>
                    </div>
                    <span class="mx-3">Recomendação: o ideal seriam capas de resolução 800x300 pixels</span>
                </q-card>
            </div>
            <div class="col-12 mb-3">
                <q-card class="h-100 card-projeto wb-form p-5" :class="{ 'wb-form-dark': darkmode }">
                    <div class="row">
                        <div class="col-12 col-md-8 px-1">
                            <p class="label_input mb-1 p-0 m-0">Nome</p>
                            <q-input filled v-model="projeto.nome" :placeholder="'Insira o nome'" :error="submitted && !$v.projeto.nome.required" :dense="dense" input-class="inputs-form-projeto" class="inputs-form-projeto p-0" />
                            <div v-if="submitted && !$v.projeto.nome.required" class="invalid-feedback">Insira o nome!</div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2 px-1 mt-3 mt-md-0">
                            <p class="label_input mb-1 p-0 m-0">Nº Min. 
                                <q-icon class="cursor-pointer" name="help">
                                    <q-tooltip content-class="bg-purple tooltips" anchor="center right" self="center left" :offset="[10, 10]">
                                        Insira o número mínimo de participantes para o seu projeto (Você deve inserir no mínimo 3)
                                    </q-tooltip>
                                </q-icon>
                            </p>
                            <q-input filled v-model="projeto.numero_min_participantes" type="number" :placeholder="'Nº min'" :error="submitted && !$v.projeto.numero_min_participantes.required" :dense="dense" input-class="inputs-form-projeto" class="inputs-form-projeto p-0" />
                            <div v-if="submitted && !$v.projeto.numero_min_participantes.required" class="invalid-feedback">Insira o número mínimo!</div>
                            <div v-if="submitted && !$v.projeto.numero_min_participantes.minLength" class="invalid-feedback">O mínimo são 3 participantes</div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2 px-1 mt-3 mt-md-0">
                            <p class="label_input mb-1 p-0 m-0">Nº Max. 
                                <q-icon class="cursor-pointer" name="help">
                                    <q-tooltip content-class="bg-purple tooltips" anchor="center right" self="center left" :offset="[10, 10]">
                                        Insira o número máximo de participantes para o seu projeto (Você deve inserir no máximo 30)
                                    </q-tooltip>
                                </q-icon>
                            </p>
                            <q-input filled v-model="projeto.numero_max_participantes" type="number"  :maxlength="30" :placeholder="'Nº máx'" :error="submitted && !$v.projeto.numero_max_participantes.required" :dense="dense" input-class="inputs-form-projeto" class="inputs-form-projeto p-0" />
                            <div v-if="submitted && !$v.projeto.numero_max_participantes.required" class="invalid-feedback">Insira o número máximo!</div>
                            <div v-if="submitted && !$v.projeto.numero_max_participantes.maxLength" class="invalid-feedback">O máximo são 30 participantes</div>
                        </div>
                        <div class="col-12 mt-3 px-1">
                            <p class="label_input mb-1 p-0 m-0">Descrição</p>
                            <q-editor
                                class="editor-text"
                                :placeholder="'Fale sobre o seu projeto...'"
                                :toolbar-text-color="darkmode ? 'grey-6' : ''"
                                v-model="projeto.sobre"
                                :definitions="{
                                    bold: { label: 'Bold', icon: null, tip: 'My bold tooltip' }
                                }"
                            />
                            <div v-if="submitted && !$v.projeto.nome.required" class="invalid-feedback">Escreva um pouco sobre o seu projeto</div>
                        </div>
                        <div class="col-12 col-sm-6 mt-3 px-1">
                            <p class="label_input mb-1 p-0 m-0">Tipo de projeto 
                                <q-icon class="cursor-pointer" name="help">
                                    <q-tooltip content-class="bg-purple tooltips" anchor="center right" self="center left" :offset="[10, 10]">
                                        Por enquanto, o único tipo de projeto são as competições, mas 
                                        em atualizações futuras, teremos mais tipos de projetos para você além das competições! :D
                                    </q-tooltip>
                                </q-icon>
                            </p>
                            <q-select
                                filled
                                v-model="projeto.tipo"
                                :options="tipos"
                                :label="'Selecione o tipo de projeto'"
                                :error="submitted && !$v.projeto.tipo.required"
                                :dense="dense"
                                class="select-form-projeto p-0"
                                option-value="_id"
                                option-label="nome"
                                emit-value
                                map-options
                            />
                            <div v-if="submitted && !$v.projeto.tipo.required" class="invalid-feedback">Especifique o tipo do seu projeto</div>
                        </div>
                        <div class="col-12 col-sm-6 mt-3 px-1">
                            <p class="label_input mb-1 p-0 m-0">Projeto aberto?
                                <q-icon class="cursor-pointer" name="help">
                                    <q-tooltip content-class="bg-purple tooltips" anchor="center right" self="center left" :offset="[10, 10]">
                                        Diga se no seu projeto apenas amigos participarão ou se é aberto ao público!
                                    </q-tooltip>
                                </q-icon>
                            </p>
                            <q-toggle v-model="projeto.aberto_publico" color="#8e1cac" />
                        </div>
                    </div>
                </q-card>
            </div>
            <div class="col-12 mb-3">
                <q-card class="h-100 card-projeto p-5">
                    <div class="row">
                        <div class="col-12">
                            <button 
                                class="btn" 
                                :class="{ 'type-2': darkmode, 'type-3': !darkmode }" 
                                @click="createOrUpdateProjeto"
                            >
                                {{ projeto_id ? 'Salvar' : 'Criar projeto' }}
                            </button>
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
    </q-page>
</template>
<script>
    import eventBus from 'src/boot/eventBus'
    import { environment } from 'src/helpers/environment';
    import { required } from 'vuelidate/lib/validators'

    export default {
        name: 'CreateProjeto',
        data (){
            return {
                projeto_id: this.$route.params.projeto_id,
                loading_photo: false,
                projeto: {
                    nome: '',
                    sobre: '',
                    capa: '',
                    tipo: '',
                    aberto_publico: true,
                    numero_min_participantes: 3,
                    numero_max_participantes: 30,
                },
                selectedFile: {},
                mouseover: false,
                darkmode: false,
                submitted: false,
                loading: false,
                dense: true,
                i18n: {},
                tipos: [],
                path_cover: `${environment.host}projetos/capa-image`,
            }
        },
        validations() {
            return {
                projeto: {
                    nome: { required },
                    sobre: { required },
                    numero_min_participantes: { 
                        minLength: this.minLength(3),
                        required,
                    },
                    numero_max_participantes: { 
                        required,
                        maxLength: this.maxLength(30)
                    },
                    tipo: { required },
                    aberto_publico: { required }
                },
            }
        },
        async mounted(){
            this.tipos = await this.getTipos('67246103d7ee7f7570218e69')
            this.tipos = this.tipos.tipos

            if (this.projeto_id) {
                await this.getProjeto()
            }
        },
        created() {
            this.i18n = this.$i18n.livro_categorias
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
                    this.i18n = this.$i18n.livro_categorias
                    this.avisos = this.$i18n.avisos
                }, 500)
            });
        },
        methods: {
            async getProjeto() {
                try {
                    let projeto = await this.$api.get(`projetos/${this.projeto_id}`)
                    this.projeto = projeto.data
                } catch (error) {
                    console.log(error)
                }
            },

            async createOrUpdateProjeto() {
                try {

                    if (this.projeto_id) {
                        await this.editProjeto()
                    } else {
                        await this.createProjeto()
                    }

                } catch (error) {
                    console.log(error)
                }
            },
            async createProjeto() {
                try {
                    this.submitted = true;
                    this.$v.projeto.$touch()
                    
                    if (!this.$v.projeto.$invalid) {
                        this.loading = true

                        let projeto = await this.$api.post(`projetos`, this.projeto)

                        setTimeout(() => {
                            this.$router.push({ path: `/projetos/${projeto.data._id}` })
                            this.loading = false
                        }, 2000)

                    } else {
                        console.log(this.$v)
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async editProjeto() {
                try {
                    this.submitted = true;
                    this.$v.projeto.$touch()
                    
                    if (!this.$v.projeto.$invalid) {
                        this.loading = true

                        let projeto = await this.$api.patch(`projetos/${this.projeto_id}`, this.projeto)

                        setTimeout(() => {
                            this.$router.push({ path: `/projetos/${projeto.data._id}` })
                            this.loading = false
                        }, 2000)

                    } else {
                        console.log(this.$v)
                    }
                } catch (error) {
                    console.log(error)
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
                    await this.$api.post(`projetos/upload/capa/${this.projeto_id}`, data, {
                        headers: {
                            'content-type': 'multipart/form-data',
                        },
                        processData: false,
                        contentType: false
                    })
                    .then(res => {
                        this.projeto.capa = res.data.capa
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

            // formats
            minLength(number) {
                return function (value) {
                    if (value < number) {
                        return !value
                    }
                    return true; 
                }.bind(this);
            },
            maxLength(number) {
                return function (value) {
                    if (value > number) {
                        return !value
                    }
                    return true; 
                }.bind(this);
            },
            cutDescricao(value, tam){
                let tamanho_max = tam;

                if(value != undefined && value != null) {
                    if(value.length > tamanho_max) {
                        return value.substring(0, tamanho_max) + '...'
                    }
                    return value
                }

            },
        },
        watch: {
            'projeto.numero_max_participantes'() {
                if (this.projeto.numero_max_participantes < 0) {
                    this.projeto.numero_max_participantes = 0
                }
            },
            'projeto.numero_min_participantes'() {
                if (this.projeto.numero_min_participantes < 0) {
                    this.projeto.numero_min_participantes = 0
                }
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import 'src/css/projetos/add.scss';
    /* DARK MODE */
    @import 'src/css/darkMode/projetos/add.scss';
</style>