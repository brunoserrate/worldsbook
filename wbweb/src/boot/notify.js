import Vue from 'vue'

Vue.mixin({
    methods: {
        historiaCriadaSucesso () {
            this.$q.notify({
              type: 'positive',
              message: `História criada!`
            })
        },
        erroCriacao () {
            this.$q.notify({
                message: 'Erro na criação da história!',
                color: 'negative',
                icon: 'error_outline',
                multiLine: true,
                actions: [
                    { label: 'OK', color: 'white', handler: () => { /* ... */ } }
                ]
            })
        }
    }
})