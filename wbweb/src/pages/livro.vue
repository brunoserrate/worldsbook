<template>
    <q-page>
        <div>{{livro}}</div>
    </q-page>
</template>
<script>
export default {
	data (){
		return {
			livro_id: this.$route.params.livro_id,
            livro: [],
		}
	},
    mounted(){
        this.carregarLivro(this.livro_id)
    },
    methods: {
        carregarLivro(livro_id){
            let that = this
            that.$axios.get(that.$pathAPI + '/historia/' + livro_id)
            .then((res) => {
                console.log(res)
            })
            .catch((err) => {
                console.log(err.response)
            })
        },
    }

};
</script>
<style lang="scss" scoped>

</style>