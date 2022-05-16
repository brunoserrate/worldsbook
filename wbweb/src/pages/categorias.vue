<template>
    <q-page>
        <div class="row row_categorias" >
            <div class="col-4">
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
                            <q-input rounded outlined v-model="search.categoria" dense class="input_search_categoria">
                                <template v-slot:append>
                                    <q-icon v-if="search.categoria !== ''" name="close" @click="search.categoria = ''" class="cursor-pointer" />
                                    <q-icon name="search" />
                                </template>
                            </q-input>
                        </div>
                    </div>
                </q-card>
            </div>
            <div class="col-7">
                <div class="row">
                    <div class="col-4" v-for="(categoria, i) in categorias" :key="i">
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
                this.$router.push({path: `/livro_categorias/` + categoria.id})
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