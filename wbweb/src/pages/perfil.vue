<template>
    <q-page>
        <q-inner-loading
            :showing="visible"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            label="Carregando perfil..."
        ></q-inner-loading>
        <div class="fit row justify-center items-center content-center user-cover background-cover">
            <div class="col-12">
                <q-img src="https://img.freepik.com/fotos-gratis/fundo-aquarela-pintado-a-mao-com-forma-de-ceu-e-nuvens_24972-1095.jpg?w=2000" class="cover"></q-img>
            </div>
            <div class="col-12 col_perfil_cover">
                <q-avatar size="80px" class="avatar" style="margin: 61px 0 0 0;">
                    <img :src="usuario.foto_perfil" />
                </q-avatar>
            </div>
            <div class="col-12 col_perfil_cover">
                <p class="cover_apelido">{{usuario.name}}</p>
            </div>
            <div class="col-12 col_perfil_cover">
                <p class="cover_nome">@{{usuario.apelido}}</p>
            </div>
        </div>
        <div class="row">
            <div cols="12" style="width: 100%;">
                <q-card class="card_barra">
                    <p class="a-seguir">A Seguir</p>
                    <q-btn flat style="primary" label="Editar Perfil" icon="settings" class="btn-editar-perfil" @click="goEditPerfil"/>
                </q-card>
            </div>
        </div>
        <div class="row">
            <div class="col-4 offset-1">
                <q-card class="card_desc_user">
                    <div class="row">
                        <div class="col-11 offset-1">
                            <h3 class="p-descricao-usuario">Descrição do usuário</h3>
                        </div>
                        <div class="col-12">
                            <q-separator color="gray" inset />
                        </div>
                        <div class="col-11 offset-1">
                            <p class="p-descricao">{{ descricao }}</p>
                        </div>
                    </div>
                </q-card>
            </div>
            <div class="col-6">
                <q-card class="card_historias">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="historias-de-usuario">Historias de {{ usuario.apelido }}</h4>
                        </div>
                        <div class="col-12">
                            <p class="qtd_historias">{{usuario.qtd_historias}} histórias publicadas</p>
                        </div>
                        <div class="row row_livros" v-for="(livro, i) in usuario.historias" :key="i" @click="goLivro(livro)">
                            <div class="col-3">
                                <img :src="livro.caminho_capa" class="livro_cover"/>
                            </div>
                            <div class="col-8" style="margin: 0 0px 0 13px;">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="livro_titulo">{{livro.titulo}}</p>
                                    </div>
                                    <div class="row" style="width: 100%; margin: 0 0px 0 -17px;">
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
                                    <div class="col-12">
                                        <p class="p_descricao"> {{livro.descricao | cutDescricao}} </p>
                                    </div>
                                    <div class="col-12">
                                        <q-chip class="historia_finalizada">{{getHistoriaFinalizada(livro.historia_finalizada)}}</q-chip>
                                    </div>
                                    <div class="col-12">
                                        <p class="p_data">Data de atualização: {{ livro.data_atualizacao | formatDateTime }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
    </q-page>
</template>
<script>
export default {
    name: 'criar-historia',
    data(){
        return {
            user: {},
            usuario: {
                apelido: '',
                avatar: '',
                email: '',
                nome: '',
                token: '',
                usar_apelido: '',
                capa: '',
                historias: []
            },
            descricao: 'Siga-me nas redes sociais! :D',
            visible: false,
            showSimulatedReturnData: false
        }
    },

    mounted(){
        this.getUser()
        this.getUserEndpoint()
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
        goLivro(livro){
            console.log(livro)
            this.$router.push({path: `../livro/` + livro.id})
        },
        goEditPerfil(){
            this.$router.push({path: `../editar_perfil/` + this.user.user_id})
        },
        getUserEndpoint(){
            let that = this

            that.visible = true
            that.showSimulatedReturnData = false

			that.$axios.get(that.$pathAPI + `/user/pesquisa?pesquisa=${this.user.user_id}`)
			.then((res) => {
				this.usuario = res.data.data
                console.log(that.usuario)

                that.visible = false
                that.showSimulatedReturnData = true
			})
			.catch((err) => {
				console.log(err.response)
                that.visible = false
                that.showSimulatedReturnData = true
                this.erroCarregar()
			})
        },
        getHistoriaFinalizada(historia_finalizada){
            if(historia_finalizada == 0){ return "Em andamento" }
            return "Concluída"
        },
    }
}
</script>
<style lang="scss" scoped>
    @import '../css/perfil.scss';
</style>