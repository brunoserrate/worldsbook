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
    }
})