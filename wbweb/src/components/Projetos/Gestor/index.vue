<template>
    <div :class="{'dark-visao-gestor': darkmode }" class="visao-gestor py-5">
        <q-inner-loading
            :showing="visible_page"
            label-class="text-teal"
            label-style="font-size: 1.1em; z-index: 1"
            class="loading"
        ></q-inner-loading>
        <div class="row m-0 px-3 px-sm-5" v-if="count_projetos > 0">
            <div class="col-12  mb-3">
                <q-select 
                    standout 
                    v-model="projetoId" 
                    :options="projetos" 
                    emit-value
                    option-value="_id"
                    option-label="nome"
                    map-options
                    bg-color="card-select"
                    class="select-projeto"
                />
            </div>
            <div class="col-12 " v-if="projeto.status && projeto.status._id != '67246270d7ee7f7570218e6d' && projeto.status._id != '67246274d7ee7f7570218e6f'" >
                <q-card class="card-chart-historias-mais-votadas p-1 p-sm-3 p-md-4 pt-4 pt-sm-3 pt-md-4">
                    <h3 class="p-0 m-0 ps-3 ps-sm-0">Histórias mais votadas</h3>
                    <p class="p-0 m-0 ps-3 ps-sm-0 mb-5">Confira o andamento das votações até o momento!</p>
                    <div v-if="projeto.total_votos && projeto.total_votos.length == 0">
                        <p class="text-white">Nenhum projeto selecionado ainda!</p>
                    </div>
                    <div v-else>
                        <canvas id="chartHistoriasMaisVotadas"></canvas>
                    </div>
                </q-card>
            </div>
            <div class="col-12 col-sm-6 col-md-3 pe-sm-1 mt-3">
                <q-card class="card-chart-historias-mais-votadas p-1 p-sm-3 p-md-4 pt-4 pt-sm-3 pt-md-4 h-100">
                    <h3 class="p-0 m-0 ps-3 ps-sm-0">Total de autores</h3>
                    <p class="total-participantes ps-sm-0 text-center"> {{ projeto.numero_participantes || '-' }} </p>
                </q-card>
            </div>
            <div class="col-12 col-sm-6 col-md-3 ps-sm-1 mt-3">
                <q-card class="card-chart-historias-mais-votadas p-1 p-sm-3 p-md-4 pt-4 pt-sm-3 pt-md-4 h-100">
                    <h3 class="p-0 m-0 ps-3 ps-sm-0">Histórias competindo</h3>
                    <p class="total-participantes ps-sm-0 text-center"> {{ projeto.quantidade_historias_participando || '-' }} </p>
                </q-card>
            </div>
            <div class="col-12 col-md-6 ps-md-2 mt-3">
                <q-card class="card-chart-historias-mais-votadas p-1 p-sm-3 p-md-4 pt-4 pt-sm-3 pt-md-4 h-100 d-flex justify-content-center align-items-center flex-direction-column">
                    <q-btn unelevated rounded :label="'Editar projeto'" class="btn-editar-projeto mb-2" @click="$router.push({ path: `/projetos/editar_projeto/${projetoId}` })"></q-btn>
                    <q-btn unelevated rounded :label="labelStatusProjeto(projeto)" class="btn-encerrar-votacoes" @click="updateProjeto(projeto)"></q-btn>
                </q-card>
            </div>
            <div class="col-12 col-md-6 mt-3">
                <q-card class="card-chart-historias-mais-votadas p-1 p-sm-3 p-md-4 pt-4 pt-sm-3 pt-md-4 h-100 d-flex align-items-center flex-direction-column justify-content-start">
                    <div class="row m-0 w-100">
                        <div class="col-12 mb-4 d-flex flex-direction-column justify-content-start">
                            <h3 class="p-0 m-0 ps-3 ps-sm-0">Histórias</h3>
                            <p class="p-0 m-0 ps-3 ps-sm-0">Confira as histórias que estão participando</p>
                        </div>
                        <div class="col-12 mb-3 d-flex align-items-center">
                            <div class="row m-0 w-100">
                                <div class="col-12">
                                    <table class="w-100 tabela">
                                        <tr class="w-100 tr-dados py-3" v-for="(historia, i) in historias" :key="i">
                                            <th class="text-start th-titulo px-2 py-2" >{{ historia.titulo }}</th>
                                            <th class="h-100">
                                                <q-icon name="delete">
                                                    <q-popup-proxy transition-show="flip-up" transition-hide="flip-down" :content-class="`popup-remove ${darkmode ? 'popup-remove-dark' : ''}`">
                                                        <q-card class="h-100 p-4 card-remove">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <p class="m-0 p-0">Você deseja remover a história <strong>"{{ historia.titulo }}"</strong> do projeto?</p>
                                                                </div>
                                                                <div class="col-12 mt-3">
                                                                    <q-btn 
                                                                        class="btn button-remover-historia" 
                                                                        :class="{ 'type-1': !darkmode, 'type-2': darkmode }" 
                                                                        label="Remover"
                                                                        @click="removeHistoria(historia._id)"
                                                                    ></q-btn>
                                                                </div>
                                                            </div>
                                                        </q-card>
                                                    </q-popup-proxy>
                                                </q-icon>
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </q-card>
            </div>
            <!-- <div class="col-12 mt-3">
                <q-card class="card-chart-historias-mais-votadas p-1 p-sm-3 p-md-4 pt-4 pt-sm-3 pt-md-4 h-100 d-flex justify-content-center align-items-center flex-direction-column">
                    <div class="row m-0 w-100">
                        <div class="col-12 mb-3 d-flex align-items-center">
                            <table class="w-100 tabela">
                                <tr class="w-100 tr-dados py-3" v-for="(rank, i) in ranking" :key="i">
                                    <th class="text-start" style="max-width: 40px;">{{ rank.historia && rank.historia.titulo }}</th>
                                    <th class="text-start d-flex align-items-center">
                                        <div class="barra me-2" :style="`width: calc(${rank.total_votos}em + 100px) `"></div>
                                        <span>{{ rank.total_votos }}</span>
                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </q-card>
            </div> -->
        </div>
        <div class="row m-0 sem-projetos" v-else>
            <div class="col-12 px-5 mb-3">
                <p>Você não tem nenhum projeto</p>
                <p class="p-link" @click="$router.push({ path: `/projetos/criar_projeto` })">Clique aqui para criar um!</p>
            </div>
        </div>
    </div>
  </template>
  
<script>
    import eventBus from 'src/boot/eventBus';
    import { environment } from 'src/helpers/environment';
    const Chart = require('chart.js');

    export default {
        name: 'ProjetosVisaoGestor',
        
        data () {
            return {
                logado: false,
                visible_page: false,
                darkmode: false,
                projetoId: '',
                projeto: {
                    labels: [],
                    total_votos: [],
                    quantidade_comentarios: [],
                    total_visualizacoes: [],
                    numero_participantes: 0
                },
                user: null,
                i18n: {},
                projetos: [],
                historias: [],
                ranking: [],
                count_projetos: 0,
                pesquisado: false,
                path_cover: `${environment.host}historias/capa-image`,
                path_photo: `${environment.host}usuarios/profile-image`,
				currentUser: this.$q.sessionStorage.getItem('auth')
            }
        },
        watch: {
            async projetoId() {
                await this.limparProjeto()
                await this.getProjeto()
                await this.renderizarChart()
            }
        },
        async mounted(){
            this.currentUser = this.currentUser ? (this.currentUser.usuario ? this.currentUser.usuario : '') : ''
        },
        async created() {
            this.i18n = this.$i18n.index
            setTimeout(() => {
                let dark = this.$q.localStorage.getItem('darkmode')
                this.darkmode = dark == 'true' ? true : false
            }, 200)
            eventBus.$on('att-darkmode', async (option) => {
                setTimeout(async() => {
                    this.darkmode = option
                }, 500);
            });
            eventBus.$on('att-idioma', async(option) => {
                this.selectedOption = option;
                setTimeout(() => {
                    this.i18n = this.$i18n.index
                }, 500)
            });
            
            setTimeout(async () => {
                await this.getProjetosSelect()
                if (this.count_projetos > 0) {
                    await this.getProjeto(this.projetos[0]._id) 
                    await this.renderizarChart()
                }
            }, 200)
        },
        methods: {
            async getProjeto(projetoId) {
                try {
                    let projeto = await this.$api.get(`projetos/dashboard/historias-mais-votadas/${projetoId ? projetoId : this.projetoId}`)
                    this.projeto = projeto.data

                    console.log(this.projeto)
                    this.projetoId = this.projeto._id
                    this.ranking = this.projeto.ranking
                    this.historias = this.projeto.historias
                } catch (error) {
                    console.log(error)
                }
            },
            async renderizarChart() {
                try {
                    if (this.projeto.total_votos && this.projeto.total_votos.length > 0) {
                        Chart.defaults.global.defaultFontColor = this.darkmode == true ? 'white' : 'black'
                        Chart.defaults.global.defaultFontFamily = 'Raleway'
        
                        var chartHistoriasMaisVotadas = new Chart(document.getElementById("chartHistoriasMaisVotadas"), {
                            type: 'bar',
                            data: {
                                labels: this.projeto.legends,
                                datasets: [{
                                    barPercentage: 0.3,
                                    categoryPercentage: 0.5,
                                    backgroundColor: '#785498',
                                    hoverBackgroundColor: '#a991bd',
                                    borderRadius: 5,
                                    borderSkipped: false,
                                    data: this.projeto.total_votos,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    display: false,
                                },
                                scales: {
                                    yAxes: [{
                                        display: true,
                                        gridLines: {
                                            display: false,
                                        },
                                        stacked: false,
                                        ticks: {
                                            beginAtZero: true,
                                            callback: function(value) {if (value % 1 === 0) {return value;}}
                                        },
                                    }],
                                    xAxes: [{
                                        stacked: false,
                                        gridLines: {
                                            color: 'rgba(0,0,0,0.01)',
                                        },
                                    }],
                                },
                                tooltips: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        title: (context) => {
                                            return `#${context[0].index + 1} ${this.projeto.labels[context[0].index]}`
                                        },
                                        label: (context) => {
                                            return `${(context.yLabel)} votos`
                                        }
                                    }
                                }
                            }
                        });              
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async getProjetosSelect() {
                try {
                    let gestor = await this.$api.get(`projetos?gestor=${this.currentUser._id}`)
                    this.projetos = gestor.data.projetos
                    this.count_projetos = gestor.data.count
                } catch (error) {
                    console.log(error)
                }
            },
            async limparProjeto() {
                this.projeto = {
                    labels: [],
                    total_votos: [],
                    quantidade_comentarios: [],
                    total_visualizacoes: [],
                    numero_participantes: 0
                }
                this.pesquisado = false
            },
            labelStatusProjeto(projeto) {
                try {
                    if (projeto.status) {
                                                    // inscricoes abertas
                        if (projeto.status._id == '67246270d7ee7f7570218e6d') {
                            return `Fechar inscrições`
                                                            // inscrições fechadas
                        } else if (projeto.status._id == '67246274d7ee7f7570218e6f') {
                            return `Abrir fase de votações`
                                                            // fase de votacoes
                        } else if (projeto.status._id == '672462b7d7ee7f7570218e71') {
                            return `Encerrar votações`
                                                        // encerramento das votações
                        } else if (projeto.status._id == '672462c3d7ee7f7570218e73') {
                            return `Fazer apuração dos votos e encerrar projeto`

                        } else {
                            return `Projeto encerrado`
                        }

                    }

                } catch (error) {
                    console.log(error)
                }
            },
            async updateProjeto(projeto) {
                try {
                    if (projeto.status._id != '672462dad7ee7f7570218e79') {
                        this.visible_page = true
                        let altStatus = await this.$api.patch(`projetos/avancar-projeto/${projeto._id}`)
                        console.log(altStatus.data)
    
                        setTimeout(async () => {
                            await this.getProjetosSelect()
                            await this.getProjeto(projeto._id) 
                            await this.renderizarChart()
                            this.visible_page = false
                        }, 1000)
                    }
                } catch (error) {
                    console.error(error)
                }
            },
            async removeHistoria(_id) {
                try {
                    let remove = await this.$api.patch(`historias/remove-projeto/${_id}/${this.projetoId}`)
                    console.log(remove.data)
                    
                    setTimeout(async () => {
                        await this.limparProjeto()
                        await this.getProjeto(this.projetoId)
                        await this.renderizarChart()
                    }, 1000)

                } catch (error) {
                    console.log()
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

    @import 'src/css/projetos/projeto/gestor.scss';
    @import 'src/css/darkMode/projetos/projeto/gestor.scss';
    
  </style>