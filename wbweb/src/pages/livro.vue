<template>
    <q-page>
        <div class="row">
            <div class="col-12">
                <q-card class="card_livro">
                    <div class="row justify-center">
                        <div class="col-12 col-md-auto">
                            <img alt="Cover" :src="livro.caminho_capa" class="capa_card"/>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="title_card">{{livro.titulo}}</h3>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <q-icon name="visibility" class="icons_card"/>
                                    </div>
                                    <div class="col-6">
                                        <p>Visualizações</p>
                                    </div>
                                    <div class="col-12 align_text_details">
                                        {{livro.total_visualizacoes}}
                                    </div>
                                </div>
                                <q-separator vertical class="separator_card"/>
                                <div class="row">
                                    <div class="col-4">
                                        <q-icon name="star_border" class="icons_card" />
                                    </div>
                                    <div class="col-6">
                                        <p>Votos</p>
                                    </div>
                                    <div class="col-12 align_text_details">
                                        {{livro.total_votos}}
                                    </div>
                                </div>
                                <q-separator vertical class="separator_card"/>
                                <div class="row">
                                    <div class="col-4">
                                        <q-icon name="list" class="icons_card"/>
                                    </div>
                                    <div class="col-6">
                                        <p>Capítulos</p>
                                    </div>
                                    <div class="col-12 align_text_details">
                                        {{livro.total_capitulos}}
                                    </div>
                                </div>
                                <div class="col-10">
                                    <q-btn unelevated label="Iniciar leitura" icon="import_contacts" class="btn_iniciar_leitura_livro"/>
                                </div>
                                <div class="col-2">
                                    <q-btn unelevated icon="add" class="btn_add_lista"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
        <div class="row justify-around">
            <div class="col-12 col-md-auto">
                <q-avatar size="35px">
                    <img :src="user.avatar" />
                </q-avatar>
                {{livro.nome_usuario}}
            </div>
        </div>
    </q-page>
</template>
<script>
export default {
	data (){
		return {
			livro_id: this.$route.params.livro_id,
            livro: {
                caminho_capa: '',
                capitulos: [],
                categoria_id: '',
                conteudo_adulto: '',
                data_atualizacao: '',
                data_criacao: '',
                descricao: '',
                direito_autoral: '',
                direitos_autorais_id: '',
                id: '',
                idioma_id: '',
                nome_usuario: '',
                publico_alvo_id: '',
                tags: [],
                titulo: '',
                total_capitulos: '',
                total_visualizacoes: '',
                total_votos: '',
                usuario_id: '',
            },
            user: {
                apelido: '',
                avatar: '',
                email: '',
                nome: '',
                token: '',
            }
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
                that.livro = res.data.data
                this.getAvatar()
            })
            .catch((err) => {
                console.log(err.response)
            })
        },
        getAvatar(){
            this.user = JSON.parse( this.$q.sessionStorage.getItem('auth') )
            console.log("auth: ", this.user)
        }
    }

};
</script>
<style lang="scss" scoped>
    @import '../css/livro.scss';
</style>