<template>
    <div :class="{'lista-historias-dark': darkmode}" class="row m-0 w-100 lista-historias" id="lista-historias" >
        <div class="col-12 pt-3" v-if="count_historias > 0">
            <div class="row m-0 p-0">
                <div class="col-12 col-lg-6 px-5 py-3 py-sm-5 col-historias" v-for="(historia, i) in historias" :key="i" @click="openDialog(historia, i)">
                    <div class="row p-0 m-0">
                        <div class="col-12 d-flex d-sm-none justify-content-center mb-3">
                            <h3 class="m-0 p-0">{{ historia.titulo }}</h3>
                        </div>
                        <div class="col-12 col-sm-4 col-md-3 col-lg-4 d-flex justify-content-center justify-sm-content-start">
                            <img alt="Cover" :src="historia.caminho_capa ? (historia.caminho_capa ? `${path_cover}/${historia.caminho_capa}` : ``) : `${path_cover}/default.png`" class="cover-historia"/>
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-8 content-historia">
                            <div class="row p-0 m-0">
                                <div class="col-12 d-flex justify-content-center justify-sm-content-start flex-direction-column">
                                    <h3 class="m-0 p-0 d-none d-sm-flex">{{ historia.titulo }}</h3>
                                    <span class="d-flex justify-content-center justify-sm-content-start mt-2 mt-sm-0">by &nbsp; <strong>{{ historia.usuario && historia.usuario.name }}</strong></span>
                                </div>
                                <div class="col-12 mt-3 descricao">
                                    <p class="text-justify"> {{ cutDescricao(historia.descricao, 200) }} </p>
                                </div>
                                <div class="col-12 mt-3 tags">
                                    <q-chip v-for="(tag, index_tag) in historia.tags.slice(0, 4)" :key="index_tag" class="tag" >{{ tag.nome }}</q-chip>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 paginacao py-5 d-flex justify-content-center" v-if="pagesNumber && pagesNumber > 0">
            <q-pagination
                v-model="pagina"
                :max="pagesNumber"
                direction-links
                color="grey"
                active-color="primary"
                class="paginacao"
            />
        </div>
        <dialog-historia />
    </div>
</template>
<script>
    import eventBus from 'src/boot/eventBus'
    import { environment } from 'src/helpers/environment';
    import DialogHistoria from 'src/components/Dialogs/Historia.vue'
    
    export default {
        name: 'LivrosLista',
        props: ['historias', 'count_historias', 'pagesNumber', 'page'],

        data (){
            return {
                categoria_id: this.$route.params.categoria_id,
                categoria: this.$route.params.categoria_nome,
                livro_dialog: false,
                pagina: this.page,
                livro_detail: {},
                i18n: {},
                avisos: {},
                livro: {
                    caminho_capa: '',
                    capitulos: [],
                    categoria: {},
                    conteudo_adulto: false,
                    descricao: '',
                    direitos_autoriais: [],
                    historia_finalizada: false,
                    idioma: {},
                    projetos: [],
                    publico_alvo: {},
                    quantidade_comentarios: 0,
                    status: {},
                    tags: [],
                    titulo: '',
                    total_capitulos: 0,
                    total_visualizacoes: 0,
                    total_votos: 0,
                    usuario: {},
                    _id: '',
                    createdAt: new Date(),
                    updatedAt: new Date()
                },
                count: 0,
                visible: false,
                showSimulatedReturnData: false,
                darkmode: false,
                isInitialLoad: false,
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
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
        components: {
            DialogHistoria: DialogHistoria
        },             
        methods: {
            goBackPageCategorias(){
                this.$router.push({ path: `/categorias` })
            },
            openDialog(livro){
				this.$store.commit("dialog/OPEN_DIALOG", livro);
            },
            goLivro(livro_detail){
                this.$router.push({path: `/livro/` + livro_detail.id})
            }
        },

        watch: {            
            'pagina' (to,from){
                this.$emit('alterPage', this.pagina)
            },
        }
    }
</script>
<style lang="scss" scoped>
    @import 'src/css/livros/lista.scss';
    @import 'src/css/darkMode/livros/lista.scss';

    @import 'src/css/dialogs.scss';
    @import 'src/css/darkMode/dialogs-dark.scss';
</style>