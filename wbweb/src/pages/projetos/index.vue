<template>
    <q-page :class="{'projetos-dark': darkmode}" class="projetos" id="projetos">
        <q-inner-loading
            :showing="visible"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            :label="i18n.carregando+'...'"
        ></q-inner-loading>
        <div class="row row_projetos pt-5 px-md-5 pb-4" >
            <div class="col-12 col-sm-6 px-2" :class="{ 'wb-form-dark-outlined': darkmode }">
                <q-input @keypress.enter="getProjetos" v-model="pesquisa" :placeholder="'Pesquise um projeto'" dense rounded outlined input-class="inputs-form-projeto-outlined" class="inputs-form-projeto-outlined p-0" >
                    <template v-slot:append>
                        <q-icon name="search" class="cursor-pointer" @click="getProjetos" />
                    </template>
                </q-input>
            </div>
            <div class="col-12 col-projetos mt-5">
                <div class="row">
                    <div class="col-12 col-sm-6 mt-4 px-2" v-for="(projeto, i) in projetos" :key="i" @click="$router.push({ path: `/projetos/${projeto._id}` })">
                        <!-- <q-card class="card-projeto p-3" :style="`background-size: cover; background-image: url(${projeto.parceiro && projeto.parceiro.foto ? `${path_cover_parceiro}/${projeto.parceiro.foto}` : `${path_cover_parceiro}/default.jpg`})`"> -->
                        <q-card class="card-projeto h-100 p-3" :style="{ position: 'relative' }" >
                            <div 
                                class="background-cover" 
                                :style="{
                                    backgroundImage: `url(${projeto.capa ? `${path_cover_projeto}/${projeto.capa}` : ``})`
                                }">
                            </div>
                            <div class="row">
                                <div class="col-12 titulo">
                                    <div class="ribbon"> {{ projeto.status && cutDescricao(projeto.status.nome, 15) }} </div>
                                    <h3 class="p-0 m-0"> {{ projeto.nome }} <span>({{ projeto.tipo && projeto.tipo.nome }})</span></h3>
                                    <p v-if="projeto.parceiro" class="p-0 m-0"> {{ projeto.parceiro.nome }} </p>
                                </div>
                                <div class="col-12 mt-3">
                                    <span class="p-0 m-0" v-html="cutDescricao(projeto.sobre, 250)" ></span>
                                </div>
                                <div class="col-12 py-3">
                                    <hr />
                                </div>
                                <div class="col-12">
                                    <p class="p-0 m-0"> 
                                        <q-icon name="trip_origin" ></q-icon>
                                        {{ projeto.numero_participantes }} 
                                        {{ projeto.numero_participantes == 1 ? 'pessoa' : 'pessoas' }} 
                                        {{ projeto.numero_participantes == 1 ? 'está' : 'estão' }} participando! 
                                    </p>
                                </div>
                            </div>
                        </q-card>
                    </div>
                </div>
                <div class="row w-100 my-5 mb-4 mb-sm-2 paginacao">
                    <div class="col-12 d-flex justify-content-center">
                        <q-pagination
                            v-model="page"
                            :max="pagesNumber"
                            direction-links
                            color="grey"
                            active-color="primary"
                            class="paginacao"
                        />
                    </div>
                </div>
            </div>
        </div>
    </q-page>
</template>
<script>
    import eventBus from 'src/boot/eventBus'
    import { environment } from 'src/helpers/environment';

    export default {
        name: 'Projetos',
        data (){
            return {
                pesquisa: this.$route.params.pesquisa,
                categoria: {},
                livro_dialog: false,
                livro_dialog_mobile: false,
                darkmode: false,
                livro_detail: {},
                avisos: {},
                pesquisa: '',
                i18n: {},
                projetos:[],
                visible: false,
                count: 0,
                page: 1,
                pagesNumber: 0,
                limit: 10,
                showSimulatedReturnData: false,
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
                path_cover_projeto: `${environment.host}projetos/capa-image`,
            }
        },
        async mounted(){
            await this.getProjetos()
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
                
                await this.getProjetos()
                
                let element = document.getElementById('projetos');
                if (element) {
                    let pixels_parar = 50;
                    let offsetTop = element.offsetTop - pixels_parar;
                    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            },
            async getProjetos(){
                this.visible = true
                this.showSimulatedReturnData = false
                await this.$api.get(`projetos?nome=${this.pesquisa}&limit=${this.limit}&page=${this.page}`)
                .then((res) => {
                    this.projetos = res.data.projetos
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

            cutDescricao(value, tam){
                let tamanho_max = tam;

                if(value != undefined && value != null) {
                    if(value.length > tamanho_max) {
                        return value.substring(0, tamanho_max) + '...'
                    }
                    return value
                }

            }
        },
        watch: {
            page() {
                this.changePage();
                if (!this.isInitialLoad) {
                } else {
                    this.isInitialLoad = false
                }
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import 'src/css/projetos/index.scss';
    /* DARK MODE */
    @import 'src/css/darkMode/projetos/index.scss';
</style>