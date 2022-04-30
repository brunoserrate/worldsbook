<template>
    <q-page>
        <div class="row">
            <div class="col-12">
                <!-- <img alt="Cover" :src="livro_detail.caminho_capa" class="cover_detail_historia"/> -->
            </div>
        </div>
    </q-page>
</template>
<script>
    export default {
        data (){
            return {
                capitulo_id: this.$route.params.capitulo_id,
                capitulo: {
                    caminho_capa: '',
                    id: '',
                    quantidade_visualizacao:  '',
                    titulo:  '',
                    votacao:  '',
                }
            }
        },
        mounted(){
            this.getCapitulo(this.capitulo_id)
        },
        methods: {
            getCapitulo(capitulo_id){
                let that = this
                that.$axios.get(that.$pathAPI + '/capitulo/' + capitulo_id)
                .then((res) => {
                    that.capitulo = res.data.data
                    console.log(that.capitulo)
                })
                .catch((err) => {
                    console.log(err.response)
                })
            },
        },
    }
</script>
<style lang="scss" scoped>
    @import '../css/capitulo.scss';
</style>