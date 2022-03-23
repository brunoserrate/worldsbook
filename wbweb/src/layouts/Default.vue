<template>
  <q-layout view="hHh lpR fFf">

    <q-header class="header">
      <q-toolbar>
        <q-toolbar-title>
          <div class="row">
            <div  class="col">
              <q-avatar size="100px" square class="avatar-inicio">
                <img src="~/assets/logo.png" class="icon">
              </q-avatar>
            </div>
            <div class="col" style="margin: 41px 546px 0 0px;">
              <q-input outlined rounded bottom-slots v-model="text" label="Procurar" :dense="true" class="input_search">
                <template v-slot:append>
                    <q-icon v-if="text !== ''" name="close" @click="text = ''" class="cursor-pointer" />
                    <q-icon name="search" />
                </template>
              </q-input>
            </div>
            <div class="col">
              <q-btn flat style="color: #7A22A7" label="Iniciar Sessão" class="iniciar-sessao" @click="prompt = true" />
            </div>
          </div>
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>
<style lang="scss" scoped>

</style>
<script>
import { ref } from 'vue'
export default {
  name: 'MainLayout',
  data () {
    return {
      text: '',
      dense: true,
      prompt:ref(false),
      address: ref('')
    }
  }
}
</script>
