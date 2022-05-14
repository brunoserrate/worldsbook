import Vue from 'vue'

Vue.mixin({
    methods: {
        historiaCriadaSucesso () {
            this.$q.notify({
              type: 'positive',
              message: `História criada!`
            })
        },
        capituloCriadoSucesso () {
            this.$q.notify({
              type: 'positive',
              message: `Capítulo criado!`
            })
        },
        perfilEditado () {
            this.$q.notify({
              type: 'positive',
              message: `Perfil editado com sucesso!`
            })
        },
        erroCriacao (err) {
            this.$q.notify({
                message: 'Erro na criação da história!',
                color: 'negative',
                icon: 'error_outline',
                multiLine: true,
                actions: [
                    { label: 'OK', color: 'white', handler: () => { /* ... */ } }
                ]
            })
        },
        erroCriacaoCapitulo (err) {
            this.$q.notify({
                message: 'Erro na criação do capítulo!',
                color: 'negative',
                icon: 'error_outline',
                multiLine: true,
                actions: [
                    { label: 'OK', color: 'white', handler: () => { /* ... */ } }
                ]
            })
        },
        erroCarregar(err) {
            this.$q.notify({
                message: 'Erro ao carregar!', err,
                color: 'negative',
                icon: 'error_outline',
                multiLine: true,
                actions: [
                    { label: 'OK', color: 'white', handler: () => { /* ... */ } }
                ]
            })
        },
        erroEditar(err) {
            this.$q.notify({
                message: 'Houve um erro ao editar!', err,
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