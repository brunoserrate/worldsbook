import Vue from 'vue'

Vue.mixin({
    methods: {
        getUser(){
            let user = JSON.parse( this.$q.sessionStorage.getItem('auth') )

            if(user !== null) {
                this.user = user
            }
            else {
                this.user = {
                    nome: '',
                    apelido: '',
                    foto_perfil: 'https://avatars.dicebear.com/api/initials/v.svg',
                }
            }
        }
    }
})