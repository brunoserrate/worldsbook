<template>
    <q-page :class="{'dark-criar_capitulo': darkmode, 'criar_capitulo': !darkmode}">
        <div class="row justify-end items-center content-center navbar_capitulo">
            <div class="col-4 col-sm-2 col_btn">
                <q-inner-loading
                    :showing="visible_page"
                    label-class="text-teal"
                    label-style="font-size: 1.1em"
                    :label="i18n.carregando_capitulo"
                >
                </q-inner-loading>
                <q-btn flat :label="i18n.botoes.publicar" class="btn_seguinte" @click="setCapitulo"></q-btn>
            </div>
            <div class="col-4 col-sm-2">
                <q-btn flat :label="i18n.botoes.cancelar" class="btn_cancelar" @click="cancelar"></q-btn>
            </div>
        </div>
        <q-separator></q-separator>
        <div class="fit row justify-center items-center content-center row_icones">
            <div class="col-6" style="text-align: right;">
                <q-icon name="perm_media" color="black" size="25px" class="row_icone"/> 
            </div>
            <div class="col-6">
                <q-icon name="videocam" color="black" size="25px" class="row_icone"/>
            </div>
            <div class="capa_img_2" v-if="capitulo.caminho_capa"> </div>
        </div>
        <div class="fit row justify-center items-center content-center">
            <div class="col-9 col-sm-6">
                <q-input v-model="capitulo.titulo" class="title_capitulo" :placeholder="i18n.capitulo_sem_nome"/>
            </div>
            <div class="col-10 col-sm-8 offset-0" style="margin: 53px 0 0 0;">
                <q-editor
                    class="editor-text"
                    :placeholder="i18n.comece_escrever"
                    toolbar-toggle-color="primary"
                    :toolbar-text-color="darkmode ? 'grey-6' : ''"
                    v-model="capitulo.capitulo"
                    :toolbar="toolbar"
                    :fonts="fonts"
                    ref="editor"
                    @paste="handlePaste"
                />
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
                    titulo: '',
                    capitulo: '',
                    historia: this.$route.params.historia_id,
                    caminho_capa: null,
                    votacao: 0,
                    quantidade_visualizacao: 0
                },
                toolbar: [
                    [
                        {
                            label: this.$q.lang.editor.align,
                            icon: this.$q.iconSet.editor.align,
                            fixedLabel: true,
                            list: 'only-icons',
                            options: ['left', 'center', 'right', 'justify']
                        },
                        {
                            label: this.$q.lang.editor.align,
                            icon: this.$q.iconSet.editor.align,
                            fixedLabel: true,
                            options: ['left', 'center', 'right', 'justify']
                        }
                    ],
                    ['bold', 'italic', 'strike', 'underline', 'subscript', 'superscript'],
                    ['token', 'hr', 'link', 'custom_btn'],
                    ['print', 'fullscreen'],
                    [
                        {
                            label: this.$q.lang.editor.formatting,
                            icon: this.$q.iconSet.editor.formatting,
                            list: 'no-icons',
                            options: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'code']
                        },
                        {
                            label: this.$q.lang.editor.fontSize,
                            icon: this.$q.iconSet.editor.fontSize,
                            fixedLabel: true,
                            fixedIcon: true,
                            list: 'no-icons',
                            options: ['size-1', 'size-2', 'size-3', 'size-4', 'size-5', 'size-6', 'size-7' ]
                        },
                        {
                            label: this.$q.lang.editor.defaultFont,
                            icon: this.$q.iconSet.editor.font,
                            fixedIcon: true,
                            list: 'no-icons',
                            options: ['default_font', 'arial', 'arial_black', 'comic_sans', 'courier_new', 'impact', 'lucida_grande', 'times_new_roman', 'verdana', 'raleway' ]
                        },
                        'removeFormat'
                    ],
                    ['quote', 'unordered', 'ordered', 'outdent', 'indent'],
                    ['undo', 'redo'],
                    ['viewsource']
                ],
                fonts: {
                    arial: 'Arial',
                    arial_black: 'Arial Black',
                    comic_sans: 'Comic Sans MS',
                    courier_new: 'Courier New',
                    impact: 'Impact',
                    lucida_grande: 'Lucida Grande',
                    times_new_roman: 'Times New Roman',
                    verdana: 'Verdana',
                    raleway: 'Raleway',
                },
                capitulos: [],
                i18n: {},
                avisos: {},
                darkmode: false,
                visible: false,
                visible_page: false,
                showSimulatedReturnData: false,
				currentUser: this.$q.sessionStorage.getItem('auth')
            }
        },
        async mounted(){
            // console.log(this.capitulo_id)
            await this.getCapitulo()
            this.currentUser = this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario : '') : ''
            
            if (this.currentUser._id != this.capitulo.usuario._id) {
                this.$router.push({ path: `/perfil` })
            }
        },
        created() {
            this.i18n = this.$i18n.criar_capitulo
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
                    this.i18n = this.$i18n.criar_capitulo
                    this.avisos = this.$i18n.avisos
                }, 500)
            });
        },
        methods: {
            async getCapitulo(){
                this.visible_page = true
                this.showSimulatedReturnData = false

                await this.$api.get(`capitulos/${this.capitulo_id}`)
                .then((res) => {
                    this.capitulo = res.data

                    this.visible_page = false
                    this.showSimulatedReturnData = true
                })
                .catch((err) => {
                    console.log(err.response)
                    this.erroCarregar(err, this.avisos.erro_carregar)
                    this.falha()
                    this.visible_page = false
                    this.showSimulatedReturnData = true
                })


            },
            async setCapitulo(){
                this.visible = true
                this.showSimulatedReturnData = false

                await this.$api.patch(`capitulos/${ this.capitulo_id }`, this.capitulo)
                .then((res) => {
                    this.capitulos = res.data
                    this.visible = false
                    this.showSimulatedReturnData = true
                    this.capituloCriadoSucesso(this.avisos.capitulo_criado)
                    this.sucesso()

                    this.$router.push({ path: `../livro/capitulo/${res.data._id}` })
                })
                .catch((err) => {
                    console.log(err.response)
                    this.falha()
                })
            },
            cancelar(){
                this.$router.push({path: `../livro/` + this.capitulo.historia._id})
            },
            
            /**
             * @param {string}
             * @param {number}
             * @returns {string}
            */
            truncateToMaxSize(content, maxSizeBytes) {
                let truncatedContent = content;

                while (new Blob([truncatedContent]).size > maxSizeBytes) {
                    truncatedContent = truncatedContent.slice(0, -1);
                }

                return truncatedContent;
            },
            handlePaste(event) {
                event.preventDefault();
                const plainText = event.clipboardData.getData('text/plain');

                const editor = this.$refs.editor;
                const selection = window.getSelection();

                if (selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);
                    range.deleteContents();
                    range.insertNode(document.createTextNode(plainText));
                }
            },
        },
        watch: {
            "capitulo.capitulo"(newValue) {
                let currentSize = new Blob([newValue]).size;
                let maxSizeMB = 5

                const maxSizeBytes = maxSizeMB * 1024 * 1024;

                if (currentSize > maxSizeBytes) {
                    alert(`O conteúdo excede o limite de ${this.maxSizeMB} MB! Reduza o tamanho.`);
                    this.capitulo.capitulo = this.truncateToMaxSize(newValue, maxSizeBytes);
                }
            },
        }
    }
</script>
<style lang="scss" scoped>
    @import '../css/criar_capitulo.scss';
    @import '../css/darkMode/criar_capitulo-dark.scss';

    
    :deep(.q-editor__content > p) {
        font-family: 'Raleway' !important;
        font-size: 15px !important;
    }
</style>