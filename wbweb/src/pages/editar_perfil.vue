<template>
    <q-page>
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-6">
                        <p>Nome do utilizador</p>
                    </div>
                    <div class="col-6">
                        <q-input square outlined v-model="usuario.nome" label="Square outlined" />
                    </div>
                </div>
            </div>
            <div class="col-6">
            </div>
        </div>
    </q-page>
</template>
<script>
export default {
    name: 'editar-perfil',
    data(){
        return {
            usuario: {
                nome: 'Laís de Assis Requena',
                descricao: '',
                data_nascimento: '',
                avatar: '',
                apelido: '',
                email: '',
                password: '',
            }
        }
    }
}
</script>
<style lang="scss" scoped>
    @import '../css/editar-perfil.scss';
</style>