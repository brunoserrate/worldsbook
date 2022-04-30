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
        <div class="row">
            <div class="col-12 offset-2">
                <q-avatar size="40px" style="background-color: #ddd;">
                    <img :src="livro.foto_perfil" />
                </q-avatar>
                <span class="apelido_usuario">{{livro.apelido_usuario}}</span>
            </div>
            <div class="col-12 offset-2">
                <q-chip class="historia_finalizada">{{getHistoriaFinalizada(livro.historia_finalizada)}}</q-chip>
                <q-chip class="historia_finalizada">{{getConteudoAdulto(livro.conteudo_adulto)}}</q-chip>
            </div>
            <div class="col-12 offset-2 col-sm-8">
                <p class="descricao_p">{{livro.descricao}}</p>
            </div>
            <div class="col-12 offset-2">
                <p style="font-weight: bold;"><q-icon name="copyright"></q-icon> {{livro.direito_autoral}}</p>
            </div>
            <div class="col-12">
                <q-separator class="separator_livro" />
            </div>
        </div>
        <div class="row">
            <div class="col-12 offset-2  col-sm-8">
                <q-chip v-for="(tag, i) in livro.tags" :key="i" >{{tag}}</q-chip>
            </div>
        </div>
        <div class="row">
            <div class="col-12 offset-2  col-sm-8">
                <q-card class="card_indice">
                    <h3 class="title_indice_card">Índice</h3>
                    <q-list>
                        <q-item v-if="livro.capitulos.length == 0">Essa história ainda não tem capítulos!</q-item>
                        <q-item clickable v-for="(capitulo, i) in livro.capitulos" :key="i" class="item_list" @click="goChapter(capitulo)">
                            {{capitulo.titulo}}
                        </q-item>
                    </q-list>
                </q-card>
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
                apelido_usuario: '',
                caminho_capa: '',
                capitulos: [],
                categoria_id: '',
                conteudo_adulto: '',
                data_atualizacao: '',
                data_criacao: '',
                descricao: '',
                direito_autoral: '',
                direitos_autorais_id: '',
                foto_perfil: '',
                historia_finalizada: '',
                id: '',
                idioma_id: '',
                nome_usuario: '',
                publico_alvo_id: '',
                tags: [],
                titulo: '',
                total_capitulos: '',
                total_visualizacoes: '',
                total_votos: '',
                usar_apelido: '',
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
                this.getHistoriaFinalizada()
            })
            .catch((err) => {
                console.log(err.response)
            })
        },
        goChapter(capitulo){
            this.$router.push({path: `capitulo/` + capitulo.id})
        },
        getAvatar(){
            this.user = JSON.parse( this.$q.sessionStorage.getItem('auth') )
        },
        getHistoriaFinalizada(historia_finalizada){
            if(historia_finalizada == 0){ return "Em andamento" }
            return "Concluída"
        },
        getConteudoAdulto(conteudo_adulto){
            if (conteudo_adulto){return "Conteúdo Adulto"}
            return "Conteúdo Livre"
        }
    }

};
</script>
<style lang="scss" scoped>
    @import '../css/livro.scss';
</style>