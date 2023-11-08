<template>
  <q-layout view="hHh lpR fFf">
    <!-- Header -->
    <q-header :class="isIndex ? (darkmode ? 'dark-index-header' : 'header') : (darkmode ? 'dark-header' : 'header')">
      <q-toolbar>
        <q-toolbar-title>
          <div class="row">
            <div  class="col-2"> 
              <q-avatar square class="avatar-inicio">
                <img src="~/assets/logo.png" class="icon" @click="goIndex">
              </q-avatar>
            </div>
            <div class="col-4 col-md-5 col-lg-6 barra-pesquisa" >
              <q-input 
                outlined 
                rounded 
                bottom-slots 
                v-model="search.pesquisa" 
                placeholder="Procurar" 
                :dense="true" 
                class="input_search" 
                :label-color="darkmode ? 'grey-5' : ''"
                @keyup.enter="pesquisar"
                >
                <template v-slot:append>
                    <q-icon v-if="text !== ''" name="close" @click="text = ''" class="cursor-pointer" />
                    <q-icon name="search"  @click="pesquisar" class="icone_search" />
                </template>
              </q-input>
            </div>
            <!-- MOBILE -->
            <div class="col-2 offset-3 col-menu-hamburguer">
              <q-icon name="search" class="lupa">
                <q-popup-edit v-model="label" auto-save v-slot="scope" :content-class="darkmode ? 'dark-popup_pesquisa' : 'popup_pesquisa'">
                  <q-input v-model="search.pesquisa" dense autofocus rounded outlined @keyup.enter="pesquisar" class="popup_search">
                    <template v-slot:append>
                        <q-icon v-if="text !== ''" name="close" @click="text = ''" class="cursor-pointer" />
                        <q-icon name="search"  @click="pesquisar" class="icone_search" />
                    </template>
                  </q-input>
                </q-popup-edit>
              </q-icon>
            </div>
            <div class="col-2 offset-3 col-menu-hamburguer">
              <q-icon name="menu" class="menu-hamburguer" @click="sidebar = !sidebar"></q-icon>
            </div>
            <!-- MOBILE -->
            <q-drawer
              v-model="sidebar"
              :width="240"
              :breakpoint="1000"
              side="right"
              overlay
              bordered
              class="bg-grey-3 sidebar"
              :content-class="{'dark-sidebar-drawer': darkmode, 'sidebar-drawer': !darkmode}"
              >
              <q-scroll-area class="fit">
                <q-list>
                  <div class="col-12">
                    <q-item clickable v-ripple v-if="logado && user" class="avatar_sidebar">
                      <q-avatar size="52px" style="padding: 0 79px;">
                        <img :src="user.foto_perfil" />
                      </q-avatar>
                      <q-item-section>{{user.nome}} </q-item-section>
                    </q-item>
                    <!-- <q-separator /> -->
                    <q-item clickable v-ripple v-if="logado && user" @click="goPerfil">
                      <q-item-section avatar>
                        <q-icon name="account_circle" class="icone-sidebar"/>
                      </q-item-section>
                      <q-item-section class="itens-sidebar" >Perfil</q-item-section>
                    </q-item>
                    <q-item clickable v-ripple v-if="!logado && user == null" @click="logar = !logar" class="avatar_sidebar">
                      <q-item-section avatar>
                        <q-icon name="login" class="icone-sidebar"/>
                      </q-item-section>
                      <q-item-section class="itens-sidebar" >Iniciar Sessão</q-item-section>
                    </q-item>
                    <!-- <q-separator /> -->
                    <q-item clickable v-ripple>
                      <q-item-section avatar>
                        <q-icon name="menu_book" class="icone-sidebar"/>
                      </q-item-section>
                      <q-item-section class="itens-sidebar" @click="goCategoria">Iniciar Leitura</q-item-section>
                    </q-item>
                    <!-- <q-separator /> -->
                    <q-item clickable v-ripple v-if="logado && user">
                      <q-item-section avatar>
                        <q-icon name="border_color" class="icone-sidebar"/>
                      </q-item-section>
                      <q-item-section class="itens-sidebar" @click="getNewHistoria">Começar a escrever</q-item-section>
                    </q-item>
                    <q-separator />
                    <q-item clickable v-ripple>
                      <q-item-section avatar>
                        <q-icon name="dark_mode" class="icone-sidebar"/>
                      </q-item-section>
                      <q-item-section class="itens-sidebar" @click="mobileDarkMode">Modo Dark</q-item-section>
                    </q-item>
                    <q-separator />
                    <q-item clickable v-ripple v-if="logado && user">
                      <q-item-section avatar>
                        <q-icon name="logout" class="icone-sidebar" style="color: red;" />
                      </q-item-section>
                      <q-item-section class="itens-sidebar" style="color: red;" @click="logout()">Logout</q-item-section>
                    </q-item>
                    <!-- <q-separator /> -->
                  </div>
                </q-list>
              </q-scroll-area>
            </q-drawer>
            <!-- INICIAR SESSÃO -->
            <div v-if="!logado && user == null" class="col">
              <div class="row pt-4">
                <div class="col-md-5 col-lg-3 col-xl-2 pe-4 pe-md-0 me-lg-5 me-xl-5">
                  <q-btn flat label="Iniciar Sessão" class="iniciar-sessao" @click="logar = !logar"/>
                </div>
                <div class="col-md-4 col-xl-5 ms-lg-1 ms-xl-4">
                  <q-btn unelevated round :icon="darkmode ? 'dark_mode' : 'light_mode'" class="iniciar-sessao" style="padding: 0px 0px;" @click="mobileDarkMode"/>
                </div>
              </div>
            </div>
            <!-- DESKTOP -->
            <div v-else class="col navegar" style="margin: 25px 277px 7px 0;" >
              <q-btn-dropdown unelevated label="Navegar" class="btn_dropdown_navegar">
                <div :class="{ 'dropdown_navegar_dark': darkmode, 'dropdown_navegar': darkmode }">
                  <q-item clickable v-close-popup>
                    <q-item-section>
                      <q-item-label @click="goCategoria">Categorias</q-item-label>
                    </q-item-section>
                  </q-item>
                </div>
              </q-btn-dropdown>
              <q-btn-dropdown unelevated label="Escrever" class="btn_dropdown_escrever">
                <div  :class="{ 'dropdown_navegar_dark': darkmode, 'dropdown_navegar': darkmode }">
                  <q-item clickable v-close-popup>
                    <q-item-section>
                      <q-item-label @click="getNewHistoria"><q-icon name="post_add" class="icon_criar_historia me-2"></q-icon>Criar História</q-item-label>
                    </q-item-section>
                  </q-item>
                </div>
              </q-btn-dropdown>
              <q-btn round size="lg" class="button-profile">
                <q-menu>
                  <div class="menu-opcoes">
                    <q-item clickable v-close-popup class="profile">
                      <q-item-section @click="goPerfil">
                           Perfil
                      </q-item-section>
                      <q-item-section avatar>
                          <q-icon name="account_circle" color="primary" size="32px" />
                      </q-item-section>
                    </q-item>
                    <q-separator />
                    <q-item tag="label" v-ripple class="apelido">
                      <q-item-section>
                        <q-item-label>Usar apelido?</q-item-label>
                        <q-item-label caption>Utilize o seu apelido</q-item-label>
                      </q-item-section>
                      <q-item-section avatar>
                        <q-toggle color="primary" keep-color v-model="user.usar_apelido" @input="alterarParametro" />
                      </q-item-section>
                    </q-item>
                    <q-separator />
                    <q-item tag="label" v-ripple class="dark-mode">
                      <q-item-section>
                        <q-item-label>Modo Dark</q-item-label>
                        <q-item-label caption>Ative o modo escuro</q-item-label>
                      </q-item-section>
                      <q-item-section avatar>
                        <q-toggle icon="dark_mode" color="dark" keep-color v-model="darkmode" @input="emitSelectDarkMode" />
                      </q-item-section>
                    </q-item>
                    <q-separator />
                    <q-item clickable v-close-popup class="logout">
                      <q-item-section @click="logout()" >Logout</q-item-section>
                    </q-item>
                  </div>
                </q-menu>
                <q-avatar size="52px">
                  <img :src="user.foto_perfil" />
                </q-avatar>
              </q-btn>
            </div>
          </div>
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <!-- Header -->
    <!-- Footer -->
    <!-- (isIndex) ? 'text-white hidden' : 'text-white' -->
    <!-- Caso seja index inclui as classes personalizadas mais o "hidden", caso o contrário, apenas as classes personalizadas -->
    <q-footer elevated :class="{ 'text-white hidden': isIndex, 'text-white': !isIndex, 'dark-footer': darkmode, 'footer': !darkmode }" >
      <div class="row">
        <div class="col-5 logo_footer">
          <img alt="logo" src="~assets/logo-footer.png" class="logo_footer_img" />
        </div>
        <div class="col-6 col-md-2 offset-1 offset-md-0">
          <div class="col-12">
            <h3 class="footer_titles">Inicio</h3>
          </div>
          <div class="col-12">
            <q-btn class="footer_subs" label="Home" flat square to="/" ></q-btn>
          </div>
          <div class="col-12">
            <q-btn class="footer_subs" label="Iniciar Leitura" flat to="/iniciar_leitura" ></q-btn>
          </div>
          <div class="col-12">
            <q-btn class="footer_subs" label="Começar a Escrever" flat to="/criar_historia" ></q-btn>
          </div>
          <div class="col-12">
            <q-btn class="footer_subs" label="Categorias" flat to="/categorias" ></q-btn>
          </div>
        </div>
        <div class="col-4 col-md-2 offset-md-0 offset-1">
          <div class="col-12">
            <h3 class="footer_titles">Sobre nós</h3>
          </div>
          <div class="col-12">
            <q-btn class="footer_subs" label="Sobre a empresa" flat to="/sobre_empresa" ></q-btn>
          </div>
          <div class="col-12">
            <q-btn class="footer_subs" label="Contato" flat to="/contato" ></q-btn>
          </div>
          <div class="col-12">
            <q-btn class="footer_subs" label="Redes Sociais" flat to="/contato" ></q-btn>
          </div>
        </div>
        <div class="col-2 col-md-2 offset-md-0 offset-1">
          <div class="col-12">
            <h3 class="footer_titles">Suporte</h3>
          </div>
          <div class="col-12">
            <q-btn class="footer_subs" label="FAQ" flat to="/faq" ></q-btn>
          </div>
          <div class="col-12">
            <q-btn class="footer_subs" label="GitHub" flat href="https://github.com/brunoserrate/worldsbook" target="_blank"></q-btn>
          </div>
        </div>
      </div>
    </q-footer>
    <!-- Footer -->

    <login-register-forgot
      :loginModal="logar"
      :cadastrarModal="sessao"
      :forgotModal="esqueciSenhaModal"

      @hideLogin="(value) => {
        this.logar = value
      }"

      @hideCadastro="(value) => {
        this.sessao = value
      }"

      @hideForgot="(value) => {
        this.esqueciSenhaModal = value
      }"

      @usuarioLogado="(value) => {
        this.user = value
        this.logar = false
        this.logado = true
      }"
    />

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
  import { required, sameAs, email } from 'vuelidate/lib/validators'
  import LoginRegisterForgot from '../components/LoginRegisterForgot.vue'
  import eventBus from '../boot/eventBus'

  export default {
    name: 'MainLayout',
    data () {
      return {
        dense: true,
        sessao: false,
        esqueciSenhaModal: false,
        logar: false,
        text: '',
        label: '',
        // Is password?
        isPwd: true,
        isPwdConf: true,
        isPwdLogin: true,
        isIndex: true,
        isCriarCapitulo: false,
        logado: false,
        user: null,
        sidebar: false,
        darkmode: null,
        formLogin: {
            email: '',
            senha: '',
        },
        formRegister: {
            nome: '',
            apelido: '',
            email: '',
            senha: '',
            repita_senha: '',
            data_nascimento: '',
        },
        formEsqueciSenha: {
          email: '',
          confirma_email: ''
        },
        search: {
          pesquisa: ''
        }
      }
    },
    components: {
      LoginRegisterForgot: LoginRegisterForgot
    },
    watch:{
      '$route' (to,from){
        if(this.$route.path == '/'){
          this.$set(this,'isIndex', true)
        }
        else if (this.$route.path == '/criar_historia/' + this.$route.params.historia_id){
          this.$set(this,'isIndex', false)
          this.$set(this,'isCriarCapitulo', true)
        }
        else if (!this.$route.path == '/criar_historia/' + this.$route.params.historia_id){
          this.$set(this,'isIndex', false)
          this.$set(this,'isCriarCapitulo', false)
        }
        else {
          this.$set(this,'isIndex', false)
        }
      },
      
    },
    mounted(){
      let user = JSON.parse( this.$q.sessionStorage.getItem('auth') )

      if(user !== null) {
        this.user = user
        this.logado = true
      }

      if(this.$route.path == '/'){
        this.$set(this,'isIndex', true)
      }
      else {
        this.$set(this,'isIndex', false)
      }
    },
    created() {
      setTimeout(() => {
        let dark = this.$q.localStorage.getItem('darkmode')
        this.darkmode = dark == 'true' ? true : false
      }, 500)
    },
    methods: {
      emitSelectDarkMode() {
        localStorage.setItem("darkmode", this.darkmode)
        eventBus.$emit('att-darkmode', this.darkmode);
      },
      mobileDarkMode() {
        this.darkmode = !this.darkmode
        this.emitSelectDarkMode()
      },
      iniciarLeitura(){
        this.$router.push({ path: '/categorias' })
      },
      comeceEscrever(){
        if(this.user != null){
          this.$router.push({ path: '/iniciar_leitura' })
        }
        else {
          this.logar = !this.logar
        }
      },
      pesquisar(){
        // console.log(this.search.pesquisa)
        this.$router.push({ path: `/historia/${this.search.pesquisa}` })
      },
      goCategoria(){
        this.$router.push({ path: '/categorias' })
      },
      goPerfil(){
        this.$router.push({ path: '/perfil' })
      },
      getNewHistoria(){
        this.$router.push({ path: '/criar_historia' })
      },
      section(){
        this.logar=false
        this.sessao=true
        this.esqueciSenhaModal = false

        this.$set(this,'formRegister', {
          nome: '',
          apelido: '',
          email: '',
          senha: '',
          repita_senha: '',
          data_nascimento: '',
        })

      },
      goIndex(){
        if(this.user !== null) {
          this.$router.push({ path: `/iniciar_leitura` })
        }
        else {
          let user = JSON.parse( this.$q.sessionStorage.getItem('auth') )
          if(user !== null) {
            this.$router.push({ path: `/iniciar_leitura` })
          }
          else {
            if(this.$route.path != '/'){
              this.isIndex = true
              this.$router.push({path: '/', replace: true })
            }
          }
        }
      },
      logar_conta(){
        this.sessao=false
        this.logar=true
        this.esqueciSenhaModal = false

        this.$set(this,'formRegister', {
          nome: '',
          apelido: '',
          email: '',
          senha: '',
          repita_senha: '',
          data_nascimento: '',
        })

        this.$set(this,'formLogin', {
          email: '',
          senha: '',
        })
      },
      login(){
        let that = this

        let params = {
          email: that.formLogin.email,
          password: that.formLogin.senha
        }

        that.$axios.post(that.$pathWeb + '/login', params)
        .then((res) => {
          // console.log(res)
          this.$q.sessionStorage.set('auth', JSON.stringify( res.data.data ))
          this.user = res.data.data
          this.logado = true

          this.sessao = false
          this.logar = false
          this.esqueciSenhaModal = false

          // that.sucesso()
          this.$router.push({path: '/iniciar_leitura'})

        })
        .catch((err) => {
          // console.log(err.response)
          that.falha('Falha na operação. Por favor verifique o formulário e tente novamente')
        })
      },
      logout(){
        let that = this

        that.$axios.post(that.$pathWeb + '/logout', this.user)
        .then((res) => {
          // console.log(res)
          this.user = null
          this.logado = false
          this.isIndex = true
          this.$q.sessionStorage.remove('auth')

          this.$router.push({path: '/'})
        })
        .catch((err) => {
          // console.log(err.response)
        })
      },
      cadastrarUsuario(){
        let that = this

        if(!this.validarCadastro()) return false

        let params = {
          name: that.formRegister.nome,
          apelido: that.formRegister.apelido,
          email: that.formRegister.email,
          password: that.formRegister.senha,
          password_confirmation: that.formRegister.repita_senha,
          data_nascimento: that.formRegister.data_nascimento
        }

        that.$axios.post(that.$pathAPI + '/register', params)
        .then((res) => {
          this.sessao = false
          this.logar=true

          this.$set(this,'formRegister', {
            nome: '',
            apelido: '',
            email: '',
            senha: '',
            repita_senha: '',
            data_nascimento: '',
          })

          that.sucesso('Cadastrado com sucesso! Conecte-se na plataforma.')

          this.$v.$reset()
        })
        .catch((err) => {
          that.falha('Falha no cadastro! Verifique as informações do formulário ou contate o nosso suporte.', 10000)
          // console.log(err)
        })
      },
      enviarRedefinirSenha(){
        let that = this

        if(!this.validarRedefinirSenha()) return false

        let params = {
          email: that.formEsqueciSenha.email,
          email_confirmation: that.formEsqueciSenha.confirma_email
        }

        that.$axios.post(that.$pathAPI + '/forgot_password', params)
        .then((res) => {
          this.$set(this,'formEsqueciSenha', {
            email: '',
            confirma_email: '',
          })

          this.sessao=false
          this.logar=false
          this.esqueciSenhaModal = false

          that.sucesso('Redefinição de senha enviada com sucesso! Instrunções foram enviadas por e-mail')

          this.$v.$reset()
        })
        .catch((err) => {
          // console.log(err.response)
          that.falha('Falha no cadastro! Verifique as informações do formulário ou contate o nosso suporte.', 10000)

        })
      },
      alterarParametro(){
        let that = this

        that.$axios.post(that.$pathAPI + '/user/preferencia/apelido')
        .then((res) => {
          this.$q.sessionStorage.set('auth', JSON.stringify( this.user ))
        })
        .catch((err) => {

        })
      },
      validarCadastro() {
        this.$v.formRegister.$touch()

        if (this.$v.formRegister.$anyError) {

          this.$q.notify({
            position: 'top',
            color: 'warning',
            textColor: 'black',
            message: 'Preencha os campos obrigatórios',
            icon: 'report_problem',
            timeout: 10000,
            actions: [
              { label: 'Fechar', color: 'black', handler: () => {} }
            ]
          })

          return false

        }

        return true

      },
      validarRedefinirSenha() {
        this.$v.formEsqueciSenha.$touch()

        if (this.$v.formEsqueciSenha.$anyError) {

          this.$q.notify({
            position: 'top',
            color: 'warning',
            textColor: 'black',
            message: 'Preencha os campos obrigatórios',
            icon: 'report_problem',
            timeout: 10000,
            actions: [
              { label: 'Fechar', color: 'black', handler: () => {} }
            ]
          })

          return false

        }

        return true

      },
      limparEsqueciSenha(modal){
        this.sessao = false
        this.logar = false
        this.esqueciSenhaModal = modal

        this.$set(this,'formEsqueciSenha', {
          email: '',
          confirma_email: ''
        })

      },
    }
  }
</script>

<style lang="scss" scoped>
  // $
  @import '../css/tela-inicial.scss';
  @import '../css/darkMode/tela-inicial-dark.scss';
  @import '../css/darkMode/tela-inicial-index-dark.scss';
  @import '../css/darkMode/footer-dark.scss';
  @import '../css/footer.scss';
  @import '../css/dialogs.scss';

</style>
