<template>
    <q-page>
        <div class="row row_categorias" >
            <div class="col-12">
                <q-card style="height: 100%;" class="card_search">
                    <div class="row">
                        <div class="col-12">
                            <p class="p_search">Aqui estão os melhores livro na categoria de {{categoria_id}}</p>
                        </div>
                        <div class="col-12">
                            <p class="p_text">Diversos livro para todos os gostos!</p>
                        </div>
                    </div>
                </q-card>
            </div>
            <div class="col-12" style="margin: 75px 0; padding: 0 199px;">
                <div class="row">
                    <div class="col-12"><p class="total_historias">{{livros.length}} histórias</p></div>
                    <div class="col-6" v-for="(livro, i) in livros" :key="i">
                        <q-card class="card-categorias" @click="goLivro(livro)">
                            <div class="row">
                                <div class="col-5">
						            <img alt="Cover" :src="livro.caminho_capa" class="cover_historia"/>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="livro_titulo">{{livro.titulo}} </p>
                                        </div>
                                        <div class="col-12">
                                            <p class="livro_autor">de {{livro.usuario_id}} </p>
                                        </div>
                                        <div class="col-12">
                                            <div class="row  row_icones">
                                                <div class="col-1 align_icone">
                                                    <q-icon name="grade" class="icons_card" />
                                                </div>
                                                <div class="col-1 align_result"> {{livro.total_votos}} </div>
                                                <div class="col-1 align_icone">
                                                    <q-icon name="visibility" class="icons_card" />
                                                </div>
                                                <div class="col-1 align_result"> {{livro.total_visualizacoes}} </div>
                                                <div class="col-1 align_icone">
                                                    <q-icon name="list" class="icons_card" />
                                                </div>
                                                <div class="col-1 align_result"> {{livro.total_capitulos}} </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <p class="livro_descricao">{{livro.descricao | cutDescricao}}</p>
                                        </div>
                                    </div>
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
    name:'livro-categoria',
	data (){
		return {
			categoria_id: this.$route.params.categoria_id,
            categoria: {},
			livros:[],
			livro: {
				caminho_capa: '', 
				categoria_id: '',
				conteudo_adulto: '',
				data_atualizacao: '',
				data_criacao: '',
				descricao: '',
				direitos_autorais_id: '',
				idioma_id: '',
				publico_alvo_id: '',
				titulo: '',
				usuario_id: '',
			},
        }
    },
    mounted(){
        this.getLivros()
        this.getCategoria()
    },
	filters: {
		cutDescricao(value){
			let tamanho_max = 150;

			if(value != undefined && value != null) {
				if(value.length > tamanho_max) {
					return value.substring(0, tamanho_max) + '...'
				}
				return value
			}

		}
	},
    methods: {
		getLivros(){
			let that = this
            let url = '/historia?';

            if(this.categoria_id != ''){
                url += 'categoria_id=' + this.categoria_id
            }

			that.$axios.get(that.$pathAPI + url)
			.then((res) => {
				that.livros = res.data.data
				console.log(that.livros)
			})
			.catch((err) => {
				console.log(err.response)
			})
		},
        getCategoria(){
            let that = this

			that.$axios.get(that.$pathAPI + `/categoria/${this.categoria_id}`)
			.then((res) => {
				that.categoria = res.data.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },
        goLivro(livro){

        }
    }
}
</script>
<style lang="scss" scoped>
    @import '../css/livro_categorias.scss';
</style>