import { Notify } from 'quasar'
import eventBus from '../boot/eventBus'
import { format } from 'date-fns';

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
		formatDate(date) {
			if (!date || isNaN(new Date(date).getTime())) return ''

			return format(new Date(date), 'dd/MM/yyyy HH:mm:ss')
		},
		formatDateAmerica(date) {
			console.log(date)
			try {
				if (!date || isNaN(new Date(date).getTime())) return ''
	
				return format(new Date(date), 'yyyy-MM-dd')

			} catch (error) {
				console.log(error)
			}
		},
		formatDateForAmerica(date) {
			try {
				if (!date || isNaN(new Date(date).getTime())) return ''
				
				let data = date.split('/')
				return format(new Date(data[2], (data[1] - 1), data[0]), "yyyy-MM-dd")
			} catch (error) {
				console.log(error)
			}
		},
		
		cutDescricao(value, tam){
			let tamanho_max = tam;

			if(value != undefined && value != null) {
				if(value.length > tamanho_max) {
					return value.substring(0, tamanho_max) + '...'
				}
				return value
			}

		}
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
