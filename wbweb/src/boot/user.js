import Vue from 'vue'

Vue.mixin({
    methods: {
        getUser(){
            let user = this.$q.sessionStorage.getItem('auth')

            if(user !== null) {
                this.user = user.usuario
            } else {
                this.user = {
                    user_id: 0,
                    nome: '',
                    apelido: '',
                    // foto_perfil: 'https://avatars.dicebear.com/api/initials/v.svg',
                }
            }

        },
        async getUsuario(){

            this.user = {
                nome: '',
                apelido: '',
            }

            let user = await this.$api.get(`usuarios/${this.$q.sessionStorage.getItem('auth').usuario._id}`);
            let userStorage = this.$q.sessionStorage.getItem('auth')
            userStorage.usuario = user.data
            this.$q.sessionStorage.set('auth', userStorage)

            user = user.data
            this.user = user ? user : this.user
        }
    }
})