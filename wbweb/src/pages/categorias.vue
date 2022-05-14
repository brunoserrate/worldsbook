<template>
    <q-page>
        <div class="row" style="margin: 90px 0 0 324px;">
            <div class="col-4" v-for="(categoria, i) in categorias" :key="i">
                <q-card class="card-categorias" >
                    <div class="row">
                        <div class="col-6">
                            <p>{{categoria.genero}}</p>
                        </div>
                    </div>
                </q-card>
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
            }
        },

        mounted(){
            this.getCategorias()
            this.getUser()
        },

        methods: {
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