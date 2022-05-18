<template>
    <q-page class="bgindex">
        <div class="fit row justify-center items-center content-center help-cover background-cover">
            <div class="col-12">
                <q-img src="~assets/cover_help.png" class="cover"></q-img>
                <p class="help_p">AJUDA</p>
            </div> 
            <!-- <div class="col-12 col_help_cover">
                <p class="help_p">AJUDA</p>
            </div> -->
        </div>
        <div class="row" style="margin: 40px 0 0 0;">
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
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                                    commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                                    eveniet doloribus ullam aliquid.
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
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                                    commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                                    eveniet doloribus ullam aliquid.
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
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                                    commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                                    eveniet doloribus ullam aliquid.
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
                            label="Como adicionar uma capa a minha história?"
                            >
                            <q-card class="textos">
                                <q-card-section>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                                    commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                                    eveniet doloribus ullam aliquid.
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
export default {
    name: 'sobre-empresa',
    data(){
        return {

        }

    }

}
</script>
<style lang="scss" scoped>
	@import '../css/faq.scss';
</style>