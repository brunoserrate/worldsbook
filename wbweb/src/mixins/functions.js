import { Notify } from 'quasar'
import eventBus from '../boot/eventBus'

let avisos = {}

export default {
	methods: {
	    sucesso(msg = '', timer = 7000) {
	      Notify.create({
	        position: 'top',
	        color: 'positive',
            textColor: 'white',
	        message:  msg == '' || msg == undefined || msg == null ? avisos.sucesso : msg,
	        icon: 'fas fa-check',
	        timeout: timer,
            actions: [
                { label: avisos.labels.fechar, color: 'white', handler: () => {} }
            ]
	      })
	    },
	    falha(msg = '', timer = 7000) {
	       this.$q.notify({
                color: 'negative',
                position: 'top',
                textColor: 'white',
                message: msg == '' || msg == undefined || msg == null ? avisos.falha : msg,
                icon: 'report_problem',
                timeout: timer,
                actions: [
                    { label: avisos.labels.fechar, color: 'white', handler: () => {} }
                ]
	        })
	    },
		aviso(msg = '', timer = 7000) {
			this.$q.notify({
				position: 'top',
				color: 'warning',
				textColor: 'black',
				message: msg == '' || msg == undefined || msg == null ? avisos.aviso : msg,
				icon: 'report_problem',
				timeout: timer,
				actions: [
					{ label: avisos.labels.fechar, color: 'black', handler: () => {} }
				]
			})
		},
	},
	created() {
        avisos = this.$i18n.avisos
		eventBus.$on('att-idioma', async(option) => {
            this.selectedOption = option;
            setTimeout(() => {
                avisos = this.$i18n.avisos
            }, 500)
        });
	}
}
