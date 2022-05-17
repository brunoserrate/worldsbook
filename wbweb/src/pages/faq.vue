<template>
    <q-page class="bgindex">
        <div class="fit row justify-center items-center content-center row_icones">
            <div class="col-12">
                <q-img src="~assets/cover_help.png"><p class="help_p">AJUDA</p></q-img>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <q-list bordered class="rounded-borders">
                    <q-expansion-item
                        expand-separator
                        icon="perm_identity"
                        label="Account settings"
                        caption="John Doe"
                        >
                        <q-card>
                            <q-card-section>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                                commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                                eveniet doloribus ullam aliquid.
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>

                    <q-expansion-item
                        expand-separator
                        icon="signal_wifi_off"
                        label="Wifi settings"
                        >
                        <q-card>
                            <q-card-section>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                                commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                                eveniet doloribus ullam aliquid.
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>

                    <q-expansion-item
                        expand-separator
                        icon="drafts"
                        label="Drafts"
                        header-class="text-purple"
                        >
                        <q-card>
                            <q-card-section>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                                commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                                eveniet doloribus ullam aliquid.
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>

                    <q-expansion-item icon="assessment" label="Disabled" disable>
                        <q-card>
                            <q-card-section>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                                commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                                eveniet doloribus ullam aliquid.
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>
                </q-list>
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