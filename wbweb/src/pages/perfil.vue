<template>
    <q-page>
        <div class="fit row justify-center items-center content-center user-cover background-cover">
            <div class="col-12">
                <q-img src="https://img.freepik.com/fotos-gratis/fundo-aquarela-pintado-a-mao-com-forma-de-ceu-e-nuvens_24972-1095.jpg?w=2000" class="cover"></q-img>
            </div>
            <div class="col-12 col_perfil_cover">
                <q-avatar size="80px" class="avatar" style="margin: 61px 0 0 0;">
                    <img :src="user.avatar" />
                </q-avatar>
            </div>
            <div class="col-12 col_perfil_cover">
                <p class="cover_apelido">{{user.nome}}</p>
            </div>
            <div class="col-12 col_perfil_cover">
                <p class="cover_nome">@{{user.apelido}}</p>
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
                            <h4 class="historias-de-usuario">Historias de {{ user.apelido }}</h4>
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
            user: {
                apelido: '',
                avatar: '',
                email: '',
                nome: '',
                token: '',
                usar_apelido: '',
                capa: '',
            },
            descricao: 'Siga-me nas redes sociais! :D' 
        }
    },
    
    mounted(){
        this.getUser()
        console.log(this.user)
    },

    methods: {
        goEditPerfil(){
            this.$router.push({path: `../editar_perfil/` + this.user.user_id})
        }
    }
}
</script>
<style lang="scss" scoped>
    @import '../css/perfil.scss';
</style>