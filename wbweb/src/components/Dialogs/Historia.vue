<template>
    <q-dialog v-model="$store.state.dialog.dialog_historia" @hide="closeDialog">
        <q-card class="dialog-historia" :class="{ 'dark-dialog-historia': darkmode }">
            <div class="row" style="height: 100%;">
                <div class="col-12 col-sm-5 col-cover ">
                    <div class="w-100 h-100">
                        <img alt="Cover" :src="$store.state.dialog.livro_detail.caminho_capa ? ($store.state.dialog.livro_detail.caminho_capa ? `${path_cover}/${$store.state.dialog.livro_detail.caminho_capa}` : ``) : `${path_cover}/default.png`" class="cover_detail_historia"/>
                    </div>
                </div>
                <div class="col-12 col-sm-7 col-content">
                    <div class="row">
                        <div class="col-12 py-2">
                            <h1 class="title_dialog_historia_desktop px-3">{{ $store.state.dialog.livro_detail.titulo }}</h1>
                        </div>
                        <div class="col-12">
                            <q-separator class="separador" :dark="darkmode"></q-separator>
                        </div>
                        <div class="col-12 px-3">
                            <div class="row m-0 mt-3">
                                <div class="col-10 col_btn_detail pe-2">
                                    <q-btn 
                                        unelevated 
                                        :label="i18n.dialogs.iniciar_leitura" 
                                        class="btn_detail_iniciar_leitura btn" 
                                        :class="{ 'type-1': !darkmode, 'type-2': darkmode }" 
                                        @click="$router.push({ path: `/livro/${$store.state.dialog.livro_detail._id }` })"
                                    />
                                </div>
                                <div class="col-2 col_btn_detail d-flex justify-content-end">
                                    <q-btn 
                                        unelevated 
                                        label="+" 
                                        class="btn_detail_mais btn"
                                        :class="{ 'type-1': !darkmode, 'type-2': darkmode }" 
                                    />
                                </div>
                                <div class="col-12 col_btn_detail_desktop mt-3">
                                    <p>{{ cutDescricao($store.state.dialog.livro_detail.descricao, 200) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <template q-slot="footer">
                        <div class="row footer">
                            <div class="col-12">
                                <q-separator class="separador" :dark="darkmode"></q-separator>
                            </div>
                            <div class="col-12 col_btn_detail py-4 px-3">
                                <p class="m-0 p-0"><span>{{ i18n.dialogs.data_atualizacao }}: </span>{{ formatDate($store.state.dialog.livro_detail.updatedAt) }}</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </q-card>
    </q-dialog>
</template>
<script>
    import eventBus from 'src/boot/eventBus'
    import { environment } from 'src/helpers/environment';

    export default {
        name: 'livro-dialog',
        props: ['index', 'id'],
        data (){
            return {
                i18n: {},
                livro: {
                    apelido_usuario: '',
                    caminho_capa: '',
                    capitulos: '',
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
                    tags: '',
                    titulo: '',
                    total_capitulos: '',
                    total_visualizacoes: '',
                    total_votos: '',
                    usar_apelido: '',
                    usuario_id: '',
                },
                livro_detail: this.$store.state.dialog.livro_detail,
                darkmode: false,
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
            }
        },        
        created() {
            this.i18n = this.$i18n.livro_categorias
            this.avisos = this.$i18n.avisos
            setTimeout(() => {
				let dark = this.$q.localStorage.getItem('darkmode')
				this.darkmode = dark == 'true' ? true : false
			}, 500)
			eventBus.$on('att-darkmode', async (option) => {
				setTimeout(async() => {
					this.darkmode = option
				}, 500);
			});
            eventBus.$on('att-idioma', async(option) => {
                this.selectedOption = option;
                setTimeout(() => {
                    this.i18n = this.$i18n.livro_categorias
                    this.avisos = this.$i18n.avisos
                }, 500)
            });
        },
        methods: {
            closeDialog() {
                this.$store.commit("dialog/CLOSE_DIALOG");
            },
        },
        mounted() {
            this.livro_detail = this.$store.state.dialog.livro_detail
        }
    }
</script>
<style lang="scss" scoped>
    @import 'src/css/dialogs/historia.scss';
    @import 'src/css/darkMode/dialogs/historia.scss';
</style>