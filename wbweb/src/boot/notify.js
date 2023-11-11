import Vue from 'vue'

Vue.mixin({
    methods: {
        historiaCriadaSucesso (message) {
            this.$q.notify({
              type: 'positive',
              message: message
            })
        },
        capituloCriadoSucesso (message) {
            this.$q.notify({
              type: 'positive',
              message: message
            })
        },
        perfilEditado (message) {
            this.$q.notify({
              type: 'positive',
              message: message
            })
        },
        erroCriacao (err, message) {
            this.$q.notify({
                message: message,
                color: 'negative',
                icon: 'error_outline',
                multiLine: true,
                actions: [
                    { label: 'OK', color: 'white', handler: () => { /* ... */ } }
                ]
            })
        },
        erroCriacaoCapitulo (err, message) {
            this.$q.notify({
                message: message,
                color: 'negative',
                icon: 'error_outline',
                multiLine: true,
                actions: [
                    { label: 'OK', color: 'white', handler: () => { /* ... */ } }
                ]
            })
        },
        erroCarregar(err, message) {
            this.$q.notify({
                message: message, err,
                color: 'negative',
                icon: 'error_outline',
                multiLine: true,
                actions: [
                    { label: 'OK', color: 'white', handler: () => { /* ... */ } }
                ]
            })
        },
        erroEditar(err, message) {
            this.$q.notify({
                message: message, err,
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