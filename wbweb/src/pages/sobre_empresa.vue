<template>
    <q-page :class="{'dark-sobre_empresa': darkmode, 'sobre_empresa': !darkmode}">
        <div class="row">
            <div class="col-12">
                <h1 class="somos_netdev"><b>Somos a NetDev</b></h1>
            </div>
            <div class="col-10 offset-1">
                <p class="p_about1" v-html="netdev.somos"></p>
            </div>
            <div class="col-12 align_logo mt-5">
                <q-img src="~assets/netdev_logo.png" style="width: 36%;"></q-img>
            </div>
            <!-- <hr  style="margin: 0 0 70px 0;"/> -->
            <div class="col-12 mt-5">
                <h1 class="valores_netdev"><b>Os nossos valores</b></h1>
            </div>
            <div class="col-10 offset-1">
                <p class="p_about1" v-html="netdev.valores"></p>
            </div>
            <!-- <hr  style="margin: 0 0 70px 0;"/> -->
            <div class="col-12">
                <h1 class="valores_netdev"><b>A Nossa missão</b></h1>
            </div>
            <div class="col-10 offset-1">
                <p class="p_about1">{{ netdev.missao }}</p>
            </div>
            <!-- <hr  style="margin: 0 0 70px 0;"/> -->
            <div class="col-12">
                <h1 class="valores_netdev"><b>A Nossa visão</b></h1>
            </div>
            <div class="col-10 offset-1">
                <p class="p_about1">{{ netdev.visao }}</p>
            </div>
        </div>
    </q-page>
</template>
<script>
    import eventBus from '../boot/eventBus'
    export default {
        name: 'sobre-empresa',
        data(){
            return {
                darkmode: false,
                netdev: {
                    somos: '<p>Somos uma empresa especializada em assistência técnica, reparo, manutenção preventiva e corretiva, configuração e instalação de equipamentos de informática, redes e periféricos. Estamos em atividade desde 2006, colaborando com os nossos clientes e parceiros na obtenção de melhores resultados, tornando-os mais competitivos.</p><p>Serviços de informática ao seu alcance, para pessoa física, pequenas e médias empresas. Oferecemos serviços a domicílio de assistência técnica, com técnico de notebook para prestar suporte em sua casa ambiente WINDOWS e MAC APPLE. Manutenção e implantação de rede sem fio Wi-Fi, doméstico e empresarial para grandes ambientes.</p><p>Fazemos a diferença, pois fazemos diferente. Buscamos soluções de qualidade com baixo custo, para satisfazer as necessidades mais especificas de nossos clientes. Nosso serviço de atendimento é personalizado e entende o que você precisa, para atender suas expectativas. Assim você e sua empresa podem focar no que realmente importa para o negócio.</p><p>Nosso objetivo é oferecer soluções criativas e eficientes com qualidade, flexibilidade e rapidez para nossos clientes. Para isto contamos com uma equipe técnica, qualificada em diferentes plataformas tecnológicas, em constante atualização e aprimoramento. Trabalhamos para garantir a estabilidade de sistemas e tecnologias.<p>',
                    missao: 'Superar as expectativas de cada cliente através da excelência na prestação de serviços inovadores, a fim de torná-los ainda mais competitivos em seus negócios, fornecendo, assim, soluções tecnológicas de alta qualidade.',
                    visao: 'Ser vista como principal ponto de contato na área de tecnologia de ponta, oferecendo serviços de qualidade, com respeito, responsabilidade e transparência, visando à satisfação plena de nossos clientes e colaboradores.',
                    valores: '<p class="text-center">Qualidade;</p><p class="text-center">Pontualidade;</p><p class="text-center">Ética;</p><p class="text-center">Inovação.</p>'
                }
            }
        },
        created() {
            setTimeout(() => {
                let dark = this.$q.localStorage.getItem('darkmode')
                this.darkmode = dark == 'true' ? true : false
            }, 500)
            eventBus.$on('att-darkmode', async (option) => {
                setTimeout(async() => {
                    this.darkmode = option
                }, 500);
            });
        }
    }
</script>
<style lang="scss" scoped>
	@import '../css/sobre_empresa.scss';
	@import '../css/darkMode/sobre_empresa-dark.scss';
</style>