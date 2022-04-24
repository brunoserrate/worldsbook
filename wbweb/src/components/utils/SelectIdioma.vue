<template>
<!--
    emit-value => retorna o valor (value) do array de objetos e não mais o objeto todo
    map-options => mapeia o array das opções (necessário para o emit-value)
    use-input => permite o usuário adicionar input
 -->
<q-select
    v-model="selected_id"
    :options="items"
    :label="internalLabel"
    outlined
    map-options
    emit-value
    use-input
    @input="selecionou"
    @filter="filtro"
/>
</template>

<script>
export default {
    name:'SelectIdioma',
    props:['id', 'label', 'desabilitar'],
    data () {
        return {
            items: [],
            filterItems:[],
            selected_id: this.id
        }
    },
    computed : {
        internalLabel (){

            return this.label != '' ? this.label : 'Idioma'

        },
        selectDisabled (){
            return this.desabilitar != '' ? this.desabilitar : false
        }
    },
    watch: {
        id: function (idTo, idFrom){
            if(this.id == ''){
                this.selected_id = ''
            }
            else {
                this.selected_id = idTo
            }
        }
    },
    mounted () {
        this.carregarDados()
    },
    methods: {
        selecionou(selecionado){
            this.$emit('input', selecionado);
        },
        carregarDados(){
            let that = this

            that.$axios.get(that.$pathAPI + '/idioma')
            .then((response) => {
                that.items = [];

                for(let item of response.data.data){
                    that.items.push({label:item.idioma,value:item.id})
                    that.filterItems.push({label:item.idioma,value:item.id})
                }
            })
        },
        // Função para filtrar o select (forma simplificada)
        filtro(val, update, abort) {
            // Caso o valor esteja vazio (status inicial do componente)
            // Retorna todas as opções existentes
            if(val === ''){
                update(() => {
                    this.items = this.filterItems
                })
                return
            }

            // Em caso o usuário comece a escrever algo
            // Deixa o valor em minusculo (lowerCase) e verifica se dentre as opções
            // Existe o(s) valor(es) que o usuário busca
            update(() => {
                const needle = val.toLowerCase()

                this.items = this.filterItems.filter(
                    v => v.label.toLowerCase().includes(needle)
                )
            })
        }
    },
}
</script>

<style lang="scss" scoped>
</style>