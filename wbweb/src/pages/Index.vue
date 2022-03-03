<template>
<q-page class="q-ma-lg">
  <div class="row gutter-lg">
    <div class="col">
      <img :src="img" width="80%" :alt="category">
    </div>
    <div class="col">
      <q-btn color="primary" icon="refresh" label="resetar" @click="resetar" />
    </div>
  </div>
</q-page>
  <!-- <q-page class="flex flex-center">
  </q-page> -->
</template>

<script>
//https://api.waifu.pics/type/category
const pathApi = 'https://api.waifu.pics/sfw/';

export default {
	// props:['breadcrumbs'],
	data (){
		return {
      img: '',
      category:'cringe',
		}
	},
	mounted(){
    this.resetar()
    //https://api.waifu.pics/type/category
	},
	methods:{
    resetar(){
      let that = this

      that.$axios.get(pathApi + `${that.category}`)
      .then((res) => {
        console.log(res)
        that.img = res.data.url
      })
    }
	}
};
</script>
