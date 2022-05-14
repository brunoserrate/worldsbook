import Vue from 'vue'

Vue.mixin({
    methods: {
        getComentarios(){
			let that = this

			that.$axios.get(that.$pathAPI + '/comentario')
			.then((res) => {
				that.comment = res.data.data
				console.log(that.comentarios)
			})
			.catch((err) => {
				console.log(err.response)
			})
		},
		
        getCategorias(){
            let that = this

			that.$axios.get(that.$pathAPI + '/categoria')
			.then((res) => {
				that.categorias = res.data.data
				// console.log("cat: ", this.categorias)
			})
			.catch((err) => {
				console.log(err.response)
			})
        },

        getPublicoAlvo(){
            let that = this

			that.$axios.get(that.$pathAPI + '/publico_alvo')
			.then((res) => {
				that.publicos_alvo = res.data.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },

        getIdiomas(){
            let that = this

			that.$axios.get(that.$pathAPI + '/idioma')
			.then((res) => {
				that.idiomas = res.data.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },

        getDireitoAutorais(){
            let that = this

			that.$axios.get(that.$pathAPI + '/direitos_autorais')
			.then((res) => {
				that.direitos_autorais = res.data.data
			})
			.catch((err) => {
				console.log(err.response)
			})
        },
    }
})