<template>
    <q-page :class="{ 'dark-categorias': darkmode, 'categorias': !darkmode }">
        <div class="row row_categorias" >
            <div class="col-12 col-md-4">
                <q-card style="height: 100%;" class="card_search">
                    <div class="row">
                        <div class="col-12">
                            <p class="p_search">{{ i18n.titulo }}</p>
                        </div>
                        <div class="col-12">
                            <p class="p_text">{{ i18n.subtitulo }}</p>
                        </div>
                        <div class="col-12">
                            <p class="p_search_label">{{ i18n.nao_encontrou }}</p>
                        </div>
                        <div class="col-12">
                            <q-input rounded outlined v-model="search.pesquisa" @keyup.enter="pesquisar" dense class="input_search_categoria">
                                <template v-slot:append>
                                    <q-icon name="search" @click="pesquisar" class="cursor-pointer" />
                                </template>
                            </q-input>
                        </div>
                    </div>
                </q-card>
            </div>
            <div class="col-12 col-md-7">
                <div class="row">
                    <div class="col-10 offset-1 offset-sm-0 col-sm-6 col-sm-4" v-for="(categoria, i) in categorias" :key="i">
                        <q-card class="card-categorias" @click="goCategoria(categoria)">
                            <div class="row">
                                <div class="col-6">
                                    <p>{{ categoria.nome }}</p>
                                </div>
                            </div>
                        </q-card>
                    </div>
                </div>
            </div>
        </div>
    </q-page>
</template>
<script>
    import eventBus from '../boot/eventBus'
    export default {
        data (){
            return {
                categorias: [],
                darkmode: false,
                selectedOption: '',
                user: {},
                i18n: {},
                search: {
                    categoria: '',
                    pesquisa: ''
                },
				currentUser: this.$q.sessionStorage.getItem('auth')
            }
        },

        mounted(){
            this.getUser()
            this.getCategorias()
        },
        created() {
            this.i18n = this.$i18n.categorias
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
                setTimeout(() => {
                    this.selectedOption = option;
                    this.i18n = this.$i18n.categorias
                }, 500)
            });
        },
        methods: {
            goCategoria(categoria){
                this.$router.push({path: `/livro_categorias/` + categoria._id + '/' + categoria.nome})
            },
            pesquisar(){
                this.$router.push({ path: `/historia/${this.search.pesquisa}` })
            },
            async getCategorias(){
                await this.$api.get(`categorias?lang=${this.selectedOption}`)
                .then((res) => {
                    this.categorias = res.data
                })
                .catch((err) => {
                    console.log(err.response)
                })
            },
        }
    }
</script>
<style lang="scss" scoped>
    @import '../css/categorias.scss';
    @import '../css/darkMode/categorias-dark.scss';
</style>