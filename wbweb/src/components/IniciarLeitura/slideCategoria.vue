<template>
    <div>
        <vueper-slides
            class="no-shadow slides_style"
            :visible-slides="6"
            :arrows="true"
            :slide-ratio="1 / 5"
            slide-multiple
            :gap="3"
            :bullets="false"
            :dragging-distance="70"
            >
            <vueper-slide
                v-for="(livro, i) in livros" :key="i"
                :image="livro.caminho_capa" class="slides_historias"
                @click.native="click(livro)"
            />
        </vueper-slides>
    </div>
</template>
<script>
import { VueperSlides, VueperSlide } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'
export default {
	// props:['breadcrumbs'],
    name: 'slideCategoriaVue',
    props: ['categoriaID'],
	data (){
		return {
			sessao: false,
			
			livros:[],
			slide: 1,
			slides: [
				{
					title: 'Slide #1',
					content: 'Slide content.'
				}
			]
			
		}
	},
	mounted(){
		this.buscarLivros()
        console.log(this.categoriaID)
	},
	components: { VueperSlides, VueperSlide },
	methods:{
        click(livro){
            this.$router.push({path: `livro/` + livro.id})
        },
		buscarLivros(){
			let that = this

            let url = '/historia?limit=15';

            if(this.categoriaID != ''){
                url += '&categoria_id=' + this.categoriaID
            }

			that.$axios.get(that.$pathAPI + url)
			.then((res) => {
				that.livros = res.data.data
				console.log(that.livros)
			})
			.catch((err) => {
				console.log(err.response)
			})
		},
		cutSinopse(){
			for(let i=0; i < this.livros.length; i++){
				this.livros[i].attributes.sinopse = this.livros[i].attributes.sinopse.substring(0, 200);
				// console.log(this.livros[i].attributes.sinopse)
			}
		},
	},
};
</script>
<style lang="scss" scoped>
    @import '../../css/iniciar-leitura.scss';
</style>