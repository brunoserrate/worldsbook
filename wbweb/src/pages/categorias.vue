<template>
    <q-page>
        <div class="row row_categorias" >
            <div class="col-12 col-md-4">
                <q-card style="height: 100%;" class="card_search">
                    <div class="row">
                        <div class="col-12">
                            <p class="p_search">Busque pelas melhores obras de acordo com a sua categoria favorita!</p>
                        </div>
                        <div class="col-12">
                            <p class="p_text">Diversas categorias para todos os gostos!</p>
                        </div>
                        <div class="col-12">
                            <p class="p_search_label">Não encontrou o que procurava? Pesquise aqui!</p>
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
                                    <p>{{categoria.genero}}</p>
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
    export default {
        data (){
            return {
                categorias: [],
                user: {},
                search: {
                    categoria: '',
                    pesquisa: ''
                }
            }
        },

        mounted(){
            this.getCategorias()
            this.getUser()
        },

        methods: {
            goCategoria(categoria){
                // console.log(categoria)
                this.$router.push({path: `/livro_categorias/` + categoria.id + '/' + categoria.genero})
            },
            pesquisar(){
                // console.log(this.search.pesquisa)
                this.$router.push({ path: `/historia/${this.search.pesquisa}` })
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
        }
    }
</script>
<style lang="scss" scoped>
    @import '../css/categorias.scss';
</style>