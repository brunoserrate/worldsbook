<template>
    <q-page class="bgindex">
        <div class="row">
            <div class="col-12 offset-1 col-md-4">
                <div class="row">
                    <div class="col-11 offset-1">
                        <h1 class="h1">Contato</h1>
                    </div>
                    <div class="col-11 offset-1">
                        <p class="p">Você pode entrar em contato conosco <br>pelos nossos canais de atendimento!</p>
                    </div>
                    <div class="col-11 offset-1">
                        <p class="email-suporte"><q-icon name="email" class="email-icone"></q-icon>suport@netdev.com</p>
                    </div>
                    <div class="col-11 offset-1">
                        <p class="email-suporte"><q-icon name="whatsapp" class="email-icone"></q-icon>(11) 99999-9999</p>
                    </div>
                    <div class="col-11 offset-1">
                        <p class="email-suporte"><q-icon name="call" class="email-icone"></q-icon>(11) 3333-3333</p>
                    </div>
                </div>
            </div> 
            <div class="col-1 separator_moboile">
                <q-separator vertical class="separator_format" />
            </div>
            <div class="col-12 offset-1 offset-md-0 col-md-6">
                <div class="row">
                    <div class="col-11 offset-1">
                        <h1 class="h1">Redes Sociais</h1>
                    </div>
                    <div class="col-11 offset-1">
                        <p class="p">Siga-nos nas redes sociais! E fique <br>por dentro de todas as novidades</p>
                    </div>
                    <div class="col-11 offset-1">
                        <p class="email-suporte">
                            <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
                                Facebook <q-icon name="link"/>
                            </q-tooltip>
                            <q-img src="~assets/facebook.svg" class="icone_svg"></q-img>NetDev Oficial
                        </p>
                    </div>
                    <div class="col-11 offset-1">
                        <p class="email-suporte">
                            <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
                                Twitter <q-icon name="link"/>
                            </q-tooltip>
                            <q-img src="~assets/twitter.svg" class="icone_svg"></q-img>@NetDevOfc
                        </p>
                    </div>
                    <div class="col-11 offset-1">
                        <p class="email-suporte">
                            <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
                                Instagram <q-icon name="link"/>
                            </q-tooltip>
                            <q-img src="~assets/instagram.svg" class="icone_svg"></q-img>@NetDev
                        </p>
                    </div>
                </div>
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
	@import '../css/contato.scss';
</style>