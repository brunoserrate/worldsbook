<template>
  <q-layout view="hHh lpR fFf">
    <!-- Header -->
    <q-header class="header">
      <q-toolbar>
        <q-toolbar-title>
          <div class="row">
            <div  class="col">
              <q-avatar size="100px" square class="avatar-inicio">
                <img src="~/assets/logo.png" class="icon" @click="goIndex">
              </q-avatar>
            </div>
            <div class="col" style="margin: 33px 546px 0 0px;">
              <q-input outlined rounded bottom-slots v-model="text" label="Procurar" :dense="true" class="input_search">
                <template v-slot:append>
                    <q-icon v-if="text !== ''" name="close" @click="text = ''" class="cursor-pointer" />
                    <q-icon name="search" />
                </template>
              </q-input>
            </div>
            <div class="col">
              <q-btn flat style="color: #7A22A7" label="Iniciar Sessão" class="iniciar-sessao" @click="logar_conta"/>
            </div>
          </div>
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <!-- Header -->

    <!-- Login -->
    <q-dialog v-model="logar" class="navbar_classe">
      <q-card class="cadastrar">
        <q-card class="card_titulo">
          <q-card-section>
            <div class="titulo_cadastrar">Bem-vindo de volta ao WorldBooks!</div>
            <p class="p_cadastrar">Bem-vindo de volta ao Worldbook! Logue e continue apoiando escritores e mostrando ao mundo os seus universos!</p>
          </q-card-section>
        </q-card>

        <q-card-section class="q-pt-none">
          <div class="row">
            <div class="col-12">
              <q-input v-model="usuario.email" label="E-mail" type="email" outlined class="input_cadastro"/>
            </div>
            <div class="col-12">
              <q-input v-model="usuario.senha" label="Senha" type="password" outlined class="input_cadastro"/>
            </div>
          </div>
        </q-card-section>

        <q-card-actions align="center" class="text-primary" style="padding: 0px 0 26px 0;">
          <q-btn flat label="Logar" v-close-popup @click="sessao=false" class="btn_cadastrar"/>
          <q-btn flat label="Fechar" v-close-popup class="btn_cancelar"/>
        </q-card-actions>
          <p class="p_criar-conta">Não possui uma conta? <a href="#" style="text-decoration: none;"><span style="color: #7a22a7;" @click="section">Cadastre-se</span></a></p>
      </q-card>
    </q-dialog>
    <!-- Login -->

    <!-- Cadastro -->
    <q-dialog v-model="sessao" class="navbar_classe">
      <q-card class="cadastrar">
        <q-card class="card_titulo">
          <q-card-section>
            <div class="titulo_cadastrar">Junte-se ao WorldBooks!</div>
            <p class="p_cadastrar">Faça parte da comunidade global de leitores e escritores, todos conectados através do poder das histórias</p>
          </q-card-section>
        </q-card>

        <q-card-section class="q-pt-none">
          <div class="row">
            <div class="col-12">
              <q-input v-model="usuario.nome" label="Nome" outlined class="input_cadastro" />
            </div>
            <div class="col-12">
              <q-input v-model="usuario.email" label="E-mail" type="email" outlined class="input_cadastro"/>
            </div>
            <div class="col-12">
              <q-input v-model="usuario.senha" label="Senha" type="password" outlined class="input_cadastro"/>
            </div>
            <div class="col-12">
              <q-input v-model="usuario.repita_senha" label="Repita a senha" type="password" outlined class="input_cadastro"/>
            </div>
            <div class="col-12">
              <q-input v-model="usuario.data_nascimento" type="date" outlined class="input_cadastro"/>
            </div>
          </div>
        </q-card-section>

        <q-card-actions align="center" class="text-primary" style="padding: 0px 0 26px 0;">
          <q-btn flat label="Cadastrar" v-close-popup @click="sessao=false" class="btn_cadastrar"/>
          <q-btn flat label="Cancelar" v-close-popup class="btn_cancelar"/>
        </q-card-actions>
          <p class="p_criar-conta">Já possui uma conta? <a href="#" style="text-decoration: none;"><span style="color: #7a22a7;" @click="logar_conta">Faça login!</span></a></p>
      </q-card>
    </q-dialog>
    <!-- Cadastro -->

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
export default {
  name: 'MainLayout',
  data () {
    return {
      dense: true,
      sessao: false,
      logar: false,
      text: '',
      usuario: {
          nome: '',
          email: '',
          senha: '',
          repita_senha: '',
          data_nascimento: '',
      }
    }
  },
  methods: {
    section(){
      this.logar=false
      this.sessao=true
		},
    goIndex(){
      this.$router.push({path: `/`})
    },
    logar_conta(){
      this.sessao=false
      this.logar=true
		}
  }
}
</script>

<style lang="scss" scoped>
  // $
  @import '../css/tela-inicial.scss';
  @import '../css/dialogs.scss'
</style>
