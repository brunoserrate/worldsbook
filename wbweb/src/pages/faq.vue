<template>
    <q-page :class="{'dark-faq': darkmode, 'faq': !darkmode}">
        <div class="fit row justify-center items-center content-center help-cover background-cover">
            <div class="col-12">
                <q-img src="~assets/cover_help.png" class="cover"></q-img>
                <p class="help_p">AJUDA</p>
            </div> 
            <!-- <div class="col-12 col_help_cover">
                <p class="help_p">AJUDA</p>
            </div> -->
        </div>
        <div class="row row-perguntas" style="padding: 50px 0px 50px 0px; height: 46vh;">
            <div class="col-10 offset-1">
                <q-card class="card_list">
                    <q-list padding class="rounded-borders lista">
                        <q-expansion-item
                            icon="add_circle_outline"
                            label="Como o World Books funciona?"
                            expand-separator
                            >
                            <q-card class="textos">
                                <q-card-section>
                                    O WorldBoks é uma plataforma gratuita de livros online. Aonde você pode postar suas próprias obras e ler as de outros usuários!
                                </q-card-section>
                            </q-card>
                        </q-expansion-item>
                    </q-list>
                </q-card>
                <q-card class="card_list">
                    <q-list padding class="rounded-borders lista">
                        <q-expansion-item
                            expand-separator
                            icon="add_circle_outline"
                            label="Eu posso publicar qualquer obra minha?"
                            >
                            <q-card class="textos">
                                <q-card-section>
                                    Sim! Você pode publicar qualquer obra sua desde que não viole as regras.
                                </q-card-section>
                            </q-card>
                        </q-expansion-item>
                    </q-list>
                </q-card>
                <q-card class="card_list">
                    <q-list padding class="rounded-borders lista">
                        <q-expansion-item
                            expand-separator
                            icon="add_circle_outline"
                            label="Eu posso ler qualquer obra publicada por outros usuários?"
                            >
                            <q-card class="textos">
                                <q-card-section>
                                    Sim, você pode ler qualquer obra postada por outro usuário!
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
	@import '../css/faq.scss';
	@import '../css/darkMode/faq-dark.scss';
</style>