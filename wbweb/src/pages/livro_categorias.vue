<template>
    <q-page class="livro_categorias" :class="{ 'dark-livro_categorias': darkmode }" id="livro-categorias">
        <q-inner-loading
            :showing="visible"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            :label="i18n.carregando+'...'"
        ></q-inner-loading>
        <div class="row row-livros pt-5" >
            <div class="col-12">
                <div class="row">
                    <div class="col-12 mt-5">
                        <h1 class="p_search p-0 m-0">{{ i18n.titulo }} <b>{{ categoria | lowercase }}</b></h1>
                    </div>
                    <div class="col-12">
                        <h3 class="p_text">{{i18n.diversos_livros}}</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 px-5 mt-4"><p class="total-historias p-0">{{livros.length}} {{ (livros.length == 1) ? i18n.qtd_historias.singular : i18n.qtd_historias.plural }}</p></div>
            <div class="col-12 align_livros">
                <livros-lista 
                    :historias="livros" 
                    :count_historias="count" 
                    :pagesNumber="pagesNumber" 
                    :page="page" 
                    @alterPage="(value) => {
                        this.page = value
                        this.getLivros()
                    }"
                />
            </div>
        </div>
    </q-page>
</template>
<script>
    import eventBus from 'src/boot/eventBus'
    import { environment } from 'src/helpers/environment';
	import Livros from 'src/components/Livros/Index.vue'

    export default {
        name: 'livro-categoria',
        data (){
            return {
                categoria_id: this.$route.params.categoria_id,
                categoria: this.$route.params.categoria_nome,
                livro_dialog: false,
                livro_dialog_mobile: false,
                livro_detail: {},
                i18n: {},
                avisos: {},
                livros: [],
                livro: {
                    apelido_usuario: '',
                    caminho_capa: '',
                    capitulos: '',
                    categoria_id: '',
                    conteudo_adulto: '',
                    data_atualizacao: '',
                    data_criacao: '',
                    descricao: '',
                    direito_autoral: '',
                    direitos_autorais_id: '',
                    foto_perfil: '',
                    historia_finalizada: '',
                    id: '',
                    idioma_id: '',
                    nome_usuario: '',
                    publico_alvo_id: '',
                    tags: '',
                    titulo: '',
                    total_capitulos: '',
                    total_visualizacoes: '',
                    total_votos: '',
                    usar_apelido: '',
                    usuario_id: '',
                },
                count: 0,
                page: 1,
                limit: 10,
                pagesNumber: 0,
                visible: false,
                showSimulatedReturnData: false,
                darkmode: false,
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
            }
        },
        components: {
            LivrosLista: Livros
        },
        mounted(){
            this.getLivros()
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
            async changePage(e) {
                await this.getLivros()

                let element = document.getElementById('livro-categorias');
                if (element) {
                    let pixels_parar = 50;
                    let offsetTop = element.offsetTop - pixels_parar;
                    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            },
            async getLivros(){
                this.visible = true
                this.showSimulatedReturnData = false

                await this.$api.get(`historias?categoria=${this.categoria_id}&page=${this.page}&limit=${this.limit}&mode=index&sort=total_visualizacoes&ordem=-1`)
                .then((res) => {
                    this.livros = res.data.historias
                    this.count = res.data.count
                    this.pagesNumber = Math.ceil(this.count / this.limit)

                    this.visible = false
                    this.showSimulatedReturnData = true
                })
                .catch((err) => {
                    console.log(err.response)
                    this.visible = false
                    this.showSimulatedReturnData = true
                    this.erroCarregar(err, this.avisos.erro_carregar)
                })
            },
            goBackPageCategorias(){
                this.$router.push({ path: `/categorias` })
            },
        },
        watch: {
            page() {
                this.changePage();
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import 'src/css/livro_categorias.scss';
    @import 'src/css/dialogs.scss';
    @import 'src/css/darkMode/livro_categorias-dark.scss';
    @import 'src/css/darkMode/dialogs-dark.scss';
</style>