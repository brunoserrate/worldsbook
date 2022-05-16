import Vue from 'vue'

Vue.mixin({
    methods: {
        getUser(){

            this.user = {
                user_id: 0,
                nome: '',
                apelido: '',
                foto_perfil: 'https://avatars.dicebear.com/api/initials/v.svg',
            }

            let user = JSON.parse( this.$q.sessionStorage.getItem('auth') )

            if(user !== null) {
                this.user = user
            }

        }
    }
})