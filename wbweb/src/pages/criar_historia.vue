<template>
    <q-page>
        <div class="row justify-center">
            <div class="col-12">
                <div class="row justify-center" style="margin: 60px 0 0 0;">
                    <div v-if="historia.caminho_capa === '' " class="col-12 col-md-auto">
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
                    <div v-else class="col-12 col-md-auto text-right">
                        <img :src="historia.caminho_capa" alt="" height="50%">
                    </div>
                    <div class="row">
                        <div class="col-12" style="margin: 0 0px 0 58px;">
                            <q-card class="card_form_historia">
                                <div class="col-6">
                                    <h3 class="title_historia">Detalhes das História</h3>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="col-12">
                                    <p class="label_input">Título</p>
                                    <q-input filled v-model="historia.titulo" placeholder="História sem título" :dense="dense" class="inputs_form_historia"/>
                                </div>
                                <div class="col-12">
                                    <p class="label_input">Descrição</p>
                                    <q-input filled v-model="historia.descricao" type="textarea" :dense="dense" class="inputs_form_historia_descricao"/>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <p class="label_select">Categoria</p>
                                    </div>
                                    <div class="col-8">
                                        <q-select
                                            filled
                                            v-model="historia.categoria_id"
                                            :options="categorias"
                                            type="number"
                                            label="Categoria"
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
                                        <p class="label_input">Etiquetas</p>
                                                <q-select
                                                    filled
                                                    v-model="historia.tags"
                                                    class="inputs_form_historia"
                                                    color="white"
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
                                    <div class="col-4">
                                        <p class="label_select">Público Alvo</p>
                                    </div>
                                    <div class="col-8">
                                        <q-select
                                            filled
                                            v-model="historia.publico_alvo_id"
                                            :options="publicos_alvo"
                                            type="number"
                                            label="Qual o teu público alvo principal?"
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
                                    <div class="col-4">
                                        <p class="label_select">Idioma</p>
                                    </div>
                                    <div class="col-8">
                                        <q-select
                                            filled
                                            v-model="historia.idioma_id"
                                            :options="idiomas"
                                            type="number"
                                            label="Qual o idioma?"
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
                                    <div class="col-4">
                                        <p class="label_select">Direitos do Autor</p>
                                    </div>
                                    <div class="col-8">
                                        <q-select
                                            filled
                                            v-model="historia.direitos_autorais_id"
                                            :options="direitos_autorais"
                                            label="Direitos Autorais"
                                            :dense="dense"
                                            class="inputs_form_historia_descricao"
                                            option-value="id"
                                            option-label="tipo_autoral"
                                            emit-value
                                            map-options
                                            />
                                    </div>
                                    <div class="col-12" v-if="historia.direitos_autorais == 1">
                                        <p class="p_direitos" >Isto permite que qualquer pessoa utilize a tua história para qualquer propósito — podem imprimi-la, vendê-la ou transformá-la num filme.</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <q-separator class="separator_card"/>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <p class="label_select">Conteúdo Adulto?</p>
                                    </div>
                                    <div class="col-8">
                                        <q-select
                                            filled
                                            v-model="historia.conteudo_adulto"
                                            :options="classificacoes"
                                            label="Conteúdo Adulto"
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
                                    <div class="col-6">
                                        <q-btn flat label="Seguinte" class="btn_seguinte" @click="setLivro">
                                            <q-inner-loading 
                                                :showing="visible"
                                                label-class="text-teal"
                                                label-style="font-size: 1.1em"
                                            >
                                            </q-inner-loading>
                                        </q-btn>
                                    </div>
                                    <div class="col-6">
                                        <q-btn flat label="Cancelar" class="btn_cancelar" @click="cancel"></q-btn>
                                    </div>
                                </div>
                            </q-card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </q-page>
</template>
<script>
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
                    label: 'Sim',
                    value: true,
                },
                {
                    label: 'Não',
                    value: false,
                },

            ],
            categorias: [],
            publicos_alvo: [],
            idiomas: [],
            historias: [],
            direitos_autorais: [],
            user: {},
            visible: false,
            showSimulatedReturnData: false
        }
    },
    mounted(){
        this.getCategorias()
        this.getPublicoAlvo()
        this.getIdiomas()
        this.getDireitoAutorais()
        this.user = JSON.parse( this.$q.sessionStorage.getItem('auth') )
        console.log(this.user)
    },
    watch: {

    },
    methods: { 
        setLivro(){
            let that = this
            that.historia.usuario_id = that.user.user_id
            that.historia.data_atualizacao = Date.now()
            that.historia.data_criacao = Date.now()
            that.historia.historia_finalizada = 0
            console.log(that.historia)

            that.visible = true
            that.showSimulatedReturnData = false

            that.$axios.post(that.$pathAPI + '/historia', that.historia)
			.then((res) => {
				console.log("res: ", res)
                that.historias = res.data.data
                that.visible = false
                that.showSimulatedReturnData = true
                
                this.historiaCriadaSucesso()
                console.log("A: ", that.historias)
			})
			.catch((err) => {
				console.log(err.response)
                this.erroCriacao()
                that.visible = false
                that.showSimulatedReturnData = true
			})
        },
        cancel(){
            
        },
        goCapitulo(){
            // console.log("A: ", this.historias)
            // this.$router.push({path: `criar_historia/` + livro_detail.id})
        },
        getCategorias(){
            let that = this

			that.$axios.get(that.$pathAPI + '/categoria')
			.then((res) => {
				that.categorias = res.data.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },

        getPublicoAlvo(){
            let that = this

			that.$axios.get(that.$pathAPI + '/publico_alvo')
			.then((res) => {
				that.publicos_alvo = res.data.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },

        getIdiomas(){
            let that = this

			that.$axios.get(that.$pathAPI + '/idioma')
			.then((res) => {
				that.idiomas = res.data.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },

        getDireitoAutorais(){
            let that = this

			that.$axios.get(that.$pathAPI + '/direitos_autorais')
			.then((res) => {
				that.direitos_autorais = res.data.data
			})
			.catch((err) => {
				console.log(err.response)
			})
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
		getUrl(){
			return this.$pathAPI + '/uploads'
		},
		finishedUpload () {
			this.$refs.uploader.reset()
		},
    }
}
</script>
<style lang="scss" scoped>
    @import '../css/criar-historia.scss';

    .q-chip--dense {
        border-radius: 12px;
        padding: 0 0.4em;
        height: 1.5em;
        background-color: #7A22A7;
    }
</style>