import { Notify } from 'quasar'

export default {
	methods: {
	    sucesso(msg = '') {
	      Notify.create({
	        position: 'top',
	        color: 'positive',
	        message:  msg == '' || msg == undefined || msg == null ? 'Operação realizada com sucesso' : msg,
	        icon: 'fas fa-check',
	        timeout: 1500,
	      })
	    },
	    falha(msg) {
	    	console.log(msg)
	       this.$q.notify({
	          color: 'negative',
	          position: 'top',
	          message: 'Falha na operação ' + msg,
	          icon: 'report_problem',
	          timeout: 2500,
	        })
	    },
	},
}
