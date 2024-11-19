import Vue from 'vue'
import eventBus from './eventBus';

let i18n = ''

Vue.mixin({
    methods: {
        async getComentarios(){
			await this.$api.get(`comentarios`)
			.then((res) => {
				this.comment = res.data
			})
			.catch((err) => {
				console.log(err.response)
			})
		},
		
        async getCategorias(){
			await this.$api.get(`categorias`)
			.then((res) => {
				this.categorias = res.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },

        async getPublicoAlvo(){
			await this.$api.get(`publicos-alvos`)
			.then((res) => {
				this.publicos_alvo = res.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },

        async getIdiomas(){
			await this.$api.get(`idiomas`)
			.then((res) => {
				this.idiomas = res.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },

        async getDireitoAutorais(){
			await this.$api.get(`direitos-autorais`)
			.then((res) => {
				this.direitos_autorais = res.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },

        async getTipos(grupo){
			try {
				let tipos = await this.$api.get(`tipos?grupo=${grupo ? grupo : ''}`)
				return tipos.data
			} catch (error) {
				console.log(error)
			}
			
        },
    }
})