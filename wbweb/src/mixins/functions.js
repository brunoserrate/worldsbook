import { Notify } from 'quasar'

export default {
	methods: {
	    sucesso(msg = '', timer = 7000) {
	      Notify.create({
	        position: 'top',
	        color: 'positive',
            textColor: 'white',
	        message:  msg == '' || msg == undefined || msg == null ? 'Operação realizada com sucesso' : msg,
	        icon: 'fas fa-check',
	        timeout: timer,
            actions: [
                { label: 'Fechar', color: 'white', handler: () => {} }
            ]
	      })
	    },
	    falha(msg = '', timer = 7000) {
	       this.$q.notify({
                color: 'negative',
                position: 'top',
                textColor: 'white',
                message: msg == '' || msg == undefined || msg == null ? 'Falha na operação' : msg,
                icon: 'report_problem',
                timeout: timer,
                actions: [
                    { label: 'Fechar', color: 'white', handler: () => {} }
                ]
	        })
	    },
	},
}
