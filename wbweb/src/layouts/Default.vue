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
              <q-btn flat style="color: #7A22A7" label="Iniciar Sessão" class="iniciar-sessao" @click="logar_conta()"/>
            </div>
          </div>
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <!-- Header -->
    <!-- Footer -->
    <q-footer elevated class="text-white footer_pages">
      <div class="row">
        <div class="col-5 logo_footer">
          <img alt="logo" src="~assets/logo-footer.png"  />
        </div>
        <div class="col-2">
          <h3 class="footer_titles">Inicio</h3>
          <p class="footer_subs">Home</p>
          <p class="footer_subs">Iniciar Leitura</p>
          <p class="footer_subs">Começar a Escrever</p>
        </div>
        <div class="col-2">
          <h3 class="footer_titles">Sobre nós</h3>
          <p class="footer_subs">Sobre a empresa</p>
          <p class="footer_subs">Contato</p>
          <p class="footer_subs">Redes Sociais</p>
          <p class="footer_subs">Desenvolvedores</p>
        </div>
        <div class="col-2">
          <h3 class="footer_titles">Suporte</h3>
          <p class="footer_subs">FAQ</p>
          <p class="footer_subs">GitHub</p>
          <p class="footer_subs">Chat</p>
        </div>
      </div>
    </q-footer>
    <!-- Footer -->
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
              <q-input v-model="formLogin.email" label="E-mail" type="email" outlined class="input_cadastro"/>
            </div>
            <div class="col-12">
              <q-input
                v-model="formLogin.senha"
                label="Senha"
                :type="isPwdLogin ? 'password' : 'text'"
                outlined
                class="input_cadastro"
              >
                <template v-slot:append>
                  <q-icon
                    :name="isPwdLogin ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer"
                    @click="isPwdLogin = !isPwdLogin"
                  />
                </template>
              </q-input>
            </div>
          </div>
        </q-card-section>

        <q-card-actions align="center" class="text-primary" style="padding: 0px 0 26px 0;">
          <q-btn flat label="Logar" @click="login()" class="btn_cadastrar"/>
          <q-btn flat label="Fechar" @click="logar = false" class="btn_cancelar"/>
        </q-card-actions>
          <p class="p_criar-conta">Não possui uma conta? <a href="#" style="text-decoration: none;"><span style="color: #7a22a7;" @click="section()">Cadastre-se</span></a></p>
          <p class="p_criar-conta">Esqueceu a senha? <a href="#" style="text-decoration: none;"><span style="color: #7a22a7;" @click="limparEsqueciSenha(true)">Recuperar senha</span></a></p>
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

        <!-- Form -->
        <q-card-section class="q-pt-none">
          <div class="row">
            <div class="col-12">
              <q-input
                v-model="$v.formRegister.nome.$model"
                label="Nome *"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.nome.$error"
                error-message="Campo obrigatório"
              />
            </div>
            <div class="col-12">
              <q-input
                v-model="$v.formRegister.apelido.$model"
                label="Apelido *"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.apelido.$error"
                error-message="Campo obrigatório"
              />
            </div>
            <div class="col-12">
              <q-input
                v-model="$v.formRegister.email.$model"
                label="E-mail *"
                type="email"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.email.$error"
                :error-message="
                  $v.formRegister.email.email ? 'Campo obrigatório': 'Digite um e-mail válido'
                "
              />
            </div>
            <div class="col-12">
              <q-input
                v-model="$v.formRegister.senha.$model"
                label="Senha *"
                :type="isPwd ? 'password' : 'text'"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.senha.$error"
                error-message="Campo obrigatório"
              >
                <template v-slot:append>
                  <q-icon
                    :name="isPwd ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer"
                    @click="isPwd = !isPwd"
                  />
                </template>
              </q-input>
            </div>
            <div class="col-12">
              <q-input
                v-model="$v.formRegister.repita_senha.$model"
                label="Confirmar a senha *"
                :type="isPwdConf ? 'password' : 'text'"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.repita_senha.$error"
                :error-message="
                  $v.formRegister.repita_senha.sameAsPassword ? 'Campo obrigatório': 'Senhas não coincidem'
                "
              >
                <template v-slot:append>
                  <q-icon
                    :name="isPwdConf ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer"
                    @click="isPwdConf = !isPwdConf"
                  />
                </template>
              </q-input>
            </div>
            <div class="col-12">
                <!-- label="Data de nascimento *" -->
              <q-input
                v-model="$v.formRegister.data_nascimento.$model"
                type="date"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.data_nascimento.$error"
                error-message="Campo obrigatório"
              />
            </div>
          </div>
        </q-card-section>
        <!-- Form -->

        <q-card-actions align="center" class="text-primary" style="padding: 0px 0 26px 0;">
          <q-btn flat label="Cadastrar" @click="cadastrarUsuario()" class="btn_cadastrar"/>
          <q-btn flat label="Cancelar" v-close-popup class="btn_cancelar"/>
        </q-card-actions>
          <p class="p_criar-conta">Já possui uma conta? <a href="#" style="text-decoration: none;"><span style="color: #7a22a7;" @click="logar_conta()">Faça login!</span></a></p>
      </q-card>
    </q-dialog>
    <!-- Cadastro -->

    <!-- esqueci Senha -->
    <q-dialog v-model="esqueciSenhaModal" class="navbar_classe">
      <q-card class="cadastrar">
        <q-card class="card_titulo">
          <q-card-section>
            <div class="titulo_cadastrar">Esqueceu a senha?</div>
            <p class="p_cadastrar">Digite e confirme o e-mail da sua conta</p>
          </q-card-section>
        </q-card>

        <!-- Form -->
        <q-card-section class="q-pt-none">
          <div class="row">
            <div class="col-12">
              <q-input
                v-model="$v.formEsqueciSenha.email.$model"
                label="E-mail *"
                type="email"
                outlined
                class="input_cadastro"
                :error="$v.formEsqueciSenha.email.$error"
                :error-message="
                  $v.formEsqueciSenha.email.email ? 'Campo obrigatório': 'Digite um e-mail válido'
                "
              />
            </div>
            <div class="col-12">
              <q-input
                v-model="$v.formEsqueciSenha.confirma_email.$model"
                label="Confirmar e-mail *"
                type="email"
                outlined
                class="input_cadastro"
                :error="$v.formEsqueciSenha.confirma_email.$error"
                :error-message="
                  $v.formEsqueciSenha.confirma_email.sameAsEmail ? 'Campo obrigatório': 'E-mails não coincidem'
                "
              />
            </div>
          </div>
        </q-card-section>
        <!-- Form -->

        <q-card-actions align="center" class="text-primary" style="padding: 0px 0 26px 0;">
          <q-btn flat label="Enviar" @click="enviarRedefinirSenha()" class="btn_cadastrar"/>
          <q-btn flat label="Cancelar"  @click="limparEsqueciSenha(false)" class="btn_cancelar"/>
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!-- esqueci Senha -->

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { required, sameAs, email } from 'vuelidate/lib/validators'

export default {
  name: 'MainLayout',
  data () {
    return {
      dense: true,
      sessao: false,
      esqueciSenhaModal: false,
      logar: false,
      text: '',
      // Is password?
      isPwd: true,
      isPwdConf: true,
      isPwdLogin: true,
      isIndex: true,
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
      }
    }
  },
  watch:{
    '$route' (to,from){
      this.$set(this,'isIndex', this.$route.params.token)
    },
  },
  validations: {
      formRegister: {
        nome: { required },
        apelido: { required },
        email: { required, email },
        senha: { required },
        repita_senha: { required, sameAsPassword: sameAs('senha') },
        data_nascimento: { required },
      },
      formLogin: {
        email: { required },
        senha: { required }
      },
      formEsqueciSenha: {
        email: { required },
        confirma_email: { required, sameAsEmail: sameAs('email') }
      }
  },
  mounted(){
    this.$v.$reset()
  },
  methods: {
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
      this.$router.push({path: `/`})
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
        that.sucesso()
      })
      .catch((err) => {
        // console.log(err.response)
        that.falha('Falha na operação. Por favor verifique o formulário e tente novamente')
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
        // this.$set(this,'formEsqueciSenha', {
        //   email: '',
        //   confirma_email: '',
        // })

        console.log(res)

        // that.sucesso('Cadastrado com sucesso! Conecte-se na plataforma.')

        this.$v.$reset()
      })
      .catch((err) => {
        console.log(err.response)
        // that.falha('Falha no cadastro! Verifique as informações do formulário ou contate o nosso suporte.', 10000)

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
  @import '../css/footer.scss';
  @import '../css/dialogs.scss';

</style>
