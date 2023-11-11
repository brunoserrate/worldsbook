<template>
    <q-page :class="{'dark-faq': darkmode, 'faq': !darkmode}">
        <div class="fit row justify-center items-center content-center help-cover background-cover">
            <div class="col-12">
                <q-img src="~assets/cover_help.png" class="cover"></q-img>
                <p class="help_p">{{ i18n.ajuda }}</p>
            </div> 
            <!-- <div class="col-12 col_help_cover">
                <p class="help_p">AJUDA</p>
            </div> -->
        </div>
        <div class="row row-perguntas" style="padding: 50px 0px 50px 0px; height: 46vh;">
            <div class="col-10 offset-1">
                <q-card class="card_list" v-for="(pergunta, i) in i18n.perguntas" :key="i">
                    <q-list padding class="rounded-borders lista">
                        <q-expansion-item
                            icon="add_circle_outline"
                            :label="pergunta.pergunta"
                            expand-separator
                            >
                            <q-card class="textos">
                                <q-card-section>
                                    {{ pergunta.resposta }}
                                </q-card-section>
                            </q-card>
                        </q-expansion-item>
                    </q-list>
                </q-card>
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
                i18n: {}
            }
        },
        created() {
            this.i18n = this.$i18n.faq
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
                    this.i18n = this.$i18n.faq
                }, 500)
            });
        }
    }
</script>
<style lang="scss" scoped>
	@import '../css/faq.scss';
	@import '../css/darkMode/faq-dark.scss';
</style>