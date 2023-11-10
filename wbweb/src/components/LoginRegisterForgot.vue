<template>
<div>
    <!-- Login -->
    <q-dialog v-model="logar" class="navbar_classe" @before-hide="hideLogin()">
      <q-card :class="{'dark-cadastrar': darkmode, 'cadastrar': !darkmode}">
        <q-card class="card_titulo">
          <q-card-section>
            <div class="titulo_cadastrar">{{ i18n.dialogs.login.titulo }}</div>
            <p class="p_cadastrar">{{ i18n.dialogs.login.texto }}</p>
          </q-card-section>
        </q-card>

        <q-card-section class="q-pt-none">
          <div class="row">
            <div class="col-12">
              <q-input v-model="formLogin.email" :label="i18n.dialogs.login.inputs.email" type="email" outlined class="input_cadastro"/>
            </div>
            <div class="col-12">
              <q-input
                v-model="formLogin.senha"
                :label="i18n.dialogs.login.inputs.senha"
                :type="isPwdLogin ? 'password' : 'text'"
                outlined
                class="input_cadastro"
                >
                <template v-slot:append>
                  <q-icon
                    :name="isPwdLogin ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer icone"
                    @click="isPwdLogin = !isPwdLogin"
                  />
                </template>
              </q-input>
            </div>
          </div>
        </q-card-section>

        <q-card-actions align="center" class="text-primary" style="padding: 0px 0 26px 0;">
          <q-btn flat :label="i18n.dialogs.login.botoes.logar" @click="login()" class="btn_cadastrar"/>
          <q-btn flat :label="i18n.dialogs.login.botoes.fechar" @click="limparModal()" class="btn_cancelar"/>
        </q-card-actions>
        <p class="p_criar-conta">{{ i18n.dialogs.login.nao_possui_conta }} <a href="#" style="text-decoration: none;"><span :style="darkmode ? `color: #a472bd;` : `color: #7a22a7;`" @click="section()">{{ i18n.dialogs.login.cadastrese }}</span></a></p>
        <p class="p_criar-conta">{{ i18n.dialogs.login.esqueceu_senha }} <a href="#" style="text-decoration: none;"><span :style="darkmode ? `color: #a472bd;` : `color: #7a22a7;`" @click="esqueciSenha()">{{ i18n.dialogs.login.recuperar_senha }}</span></a></p>
      </q-card>
    </q-dialog>

    <!-- Login -->

    <!-- Cadastro -->
    <q-dialog v-model="sessao" class="navbar_classe" @before-hide="hideCadastro()">
      <q-card :class="{'dark-cadastrar': darkmode, 'cadastrar': !darkmode}">
        <q-card class="card_titulo">
          <q-card-section>
            <div class="titulo_cadastrar">{{ i18n.dialogs.cadastro.titulo }}</div>
            <p class="p_cadastrar">{{ i18n.dialogs.cadastro.texto }}</p>
          </q-card-section>
        </q-card>

        <!-- Form -->
        <q-card-section class="q-pt-none">
          <div class="row">
            <div class="col-12">
              <q-input
                v-model="$v.formRegister.nome.$model"
                :label="i18n.dialogs.cadastro.inputs.nome+' *'"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.nome.$error"
                :error-message="i18n.dialogs.campo_obrigatorio"
              />
            </div>
            <div class="col-12">
              <q-input
                v-model="$v.formRegister.apelido.$model"
                :label="i18n.dialogs.cadastro.inputs.apelido+' *'"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.apelido.$error"
                :error-message="i18n.dialogs.campo_obrigatorio"
              />
            </div>
            <div class="col-12">
              <q-input
                v-model="$v.formRegister.email.$model"
                :label="i18n.dialogs.cadastro.inputs.email+' *'"
                type="email"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.email.$error"
                :error-message="
                  $v.formRegister.email.email ? i18n.dialogs.campo_obrigatorio : i18n.dialogs.digite_email_valido
                "
              />
            </div>
            <div class="col-12">
              <q-input
                v-model="$v.formRegister.senha.$model"
                :label="i18n.dialogs.cadastro.inputs.senha+' *'"
                :type="isPwd ? 'password' : 'text'"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.senha.$error"
                :error-message="i18n.dialogs.campo_obrigatorio"
              >
                <template v-slot:append>
                  <q-icon
                    :name="isPwd ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer icone"
                    @click="isPwd = !isPwd"
                  />
                </template>
              </q-input>
            </div>
            <div class="col-12">
              <q-input
                v-model="$v.formRegister.repita_senha.$model"
                :label="i18n.dialogs.cadastro.inputs.confirma_senha+' *'"
                :type="isPwdConf ? 'password' : 'text'"
                outlined
                class="input_cadastro"
                :error="$v.formRegister.repita_senha.$error"
                :error-message="
                  $v.formRegister.repita_senha.sameAsPassword ? i18n.dialogs.campo_obrigatorio : i18n.dialogs.senhas_nao_coincidem
                "
              >
                <template v-slot:append>
                  <q-icon
                    :name="isPwdConf ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer icone"
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
                :label="i18n.dialogs.cadastro.inputs.data_nascimento+' *'"
                stack-label
                :error="$v.formRegister.data_nascimento.$error"
                :error-message="i18n.dialogs.campo_obrigatorio"
              />
            </div>
          </div>
        </q-card-section>
        <!-- Form -->

        <q-card-actions align="center" class="text-primary" style="padding: 0px 0 26px 0;">
          <q-btn flat :label="i18n.dialogs.cadastro.botoes.cadastrar" @click="cadastrarUsuario()" class="btn_cadastrar"/>
          <q-btn flat :label="i18n.dialogs.cadastro.botoes.cancelar" @click="limparModal()" class="btn_cancelar"/>
        </q-card-actions>
          <p class="p_criar-conta">{{ i18n.dialogs.cadastro.ja_possui_conta }} <a href="#" style="text-decoration: none;"><span :style="darkmode ? `color: #a472bd;` : `color: #7a22a7;`" @click="logar_conta()">{{ i18n.dialogs.cadastro.faca_login }}</span></a></p>
      </q-card>
    </q-dialog>
    <!-- Cadastro -->

    <!-- esqueci Senha -->
    <q-dialog v-model="esqueciSenhaModal" class="navbar_classe" @before-hide="hideForgot()">
      <q-card :class="{'dark-cadastrar': darkmode, 'cadastrar': !darkmode}">
        <q-card class="card_titulo">
          <q-card-section>
            <div class="titulo_cadastrar">{{ i18n.dialogs.esqueci_senha.titulo }}</div>
            <p class="p_cadastrar">{{ i18n.dialogs.esqueci_senha.texto }}</p>
          </q-card-section>
        </q-card>

        <!-- Form -->
        <q-card-section class="q-pt-none">
          <div class="row">
            <div class="col-12">
              <q-input
                v-model="$v.formEsqueciSenha.email.$model"
                :label="i18n.dialogs.esqueci_senha.inputs.email+' *'"
                type="email"
                outlined
                class="input_cadastro"
                :error="$v.formEsqueciSenha.email.$error"
                :error-message="
                  $v.formEsqueciSenha.email.email ? i18n.dialogs.campo_obrigatorio : i18n.dialogs.digite_email_valido
                "
              />
            </div>
            <div class="col-12">
              <q-input
                v-model="$v.formEsqueciSenha.confirma_email.$model"
                :label="i18n.dialogs.esqueci_senha.inputs.confirma_email+' *'"
                type="email"
                outlined
                class="input_cadastro"
                :error="$v.formEsqueciSenha.confirma_email.$error"
                :error-message="
                  $v.formEsqueciSenha.confirma_email.sameAsEmail ? i18n.dialogs.campo_obrigatorio : i18n.dialogs.senhas_nao_coincidem
                "
              />
            </div>
          </div>
        </q-card-section>
        <!-- Form -->

        <q-card-actions align="center" class="text-primary" style="padding: 0px 0 26px 0;">
          <q-btn flat :label="i18n.dialogs.esqueci_senha.botoes.enviar" @click="enviarRedefinirSenha()" class="btn_cadastrar"/>
          <q-btn flat :label="i18n.dialogs.esqueci_senha.botoes.cancelar" @click="limparModal()" class="btn_cancelar"/>
        </q-card-actions>
        <p class="p_criar-conta">{{ i18n.dialogs.esqueci_senha.ja_possui_conta }} <a href="#" style="text-decoration: none;"><span :style="darkmode ? `color: #a472bd;` : `color: #7a22a7;`" @click="logar_conta()">{{ i18n.dialogs.esqueci_senha.faca_login }}</span></a></p>
      </q-card>
    </q-dialog>
    <!-- esqueci Senha -->

</div>
</template>

<script>
  import { required, sameAs, email } from 'vuelidate/lib/validators'
  import eventBus from '../boot/eventBus'

  export default {
      name: 'LoginRegisterForgot',
      props: [ 'loginModal','cadastrarModal','forgotModal' ],
      data () {
        return {
          dense: true,
          sessao: this.cadastrarModal,
          esqueciSenhaModal: this.forgotModal,
          logar: this.loginModal,
          text: '',
          // Is password?
          isPwd: true,
          isPwdConf: true,
          isPwdLogin: true,
          isIndex: true,
          logado: false,
          darkmode: false,
          user: null,
          i18n: {},
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
      watch:{
          'loginModal' (to,from){
              this.logar = to
          },
          'cadastrarModal' (to,from){
              this.sessao = to
          },
          'forgotModal' (to,from){
              this.esqueciSenhaModal = to
          },
      },
      mounted(){
          this.$v.$reset()
      },
      created() {
        this.i18n = this.$i18n.index
        setTimeout(() => {
            let dark = this.$q.localStorage.getItem('darkmode')
            this.darkmode = dark == 'true' ? true : false
        }, 500)
        eventBus.$on('att-darkmode', async (option) => {
            setTimeout(async() => {
                this.darkmode = option
            }, 500);
        });
        eventBus.$on('att-idioma', async(option) => {
            this.selectedOption = option;
            setTimeout(() => {
                this.i18n = this.$i18n.index
            }, 500)
        });
      },
      methods: {
          hideLogin(){
              this.$emit('hideLogin', this.logar)
          },
          hideCadastro(){
              this.$emit('hideCadastro', this.sessao)
          },
          hideForgot(){
              this.$emit('hideForgot', this.esqueciSenhaModal)
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
                      this.$router.push({path: `/`})
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

                  this.sessao=false
                  this.logar=false
                  this.esqueciSenhaModal = false

                  this.$router.push({path: '/iniciar_leitura'})
                  this.$emit('usuarioLogado', this.user)
                  // that.sucesso()

              })
              .catch((err) => {
                  // console.log(err.response)
                  that.falha(this.i18n.dialogs.falhas.falha_operacao)
              })
          },
          logout(){
              let that = this

              that.$axios.post(that.$pathWeb + '/logout', this.user)
              .then((res) => {
                  // console.log(res)
                  this.user = null
                  this.logado = false
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

                  that.sucesso(this.i18n.dialogs.sucesso.cadastro_sucesso)

                  this.$v.$reset()
              })
              .catch((err) => {
                  that.falha(this.i18n.dialogs.falhas.falha_cadastro, 10000)
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

              that.$axios.post(that.$pathAPI + '/forgot-password', params)
              .then((res) => {
                  this.$set(this,'formEsqueciSenha', {
                      email: '',
                      confirma_email: '',
                  })

                  this.sessao=false
                  this.logar=false
                  this.esqueciSenhaModal = false

                  that.sucesso(this.i18n.dialogs.sucesso.cadastro_sucesso)

                  this.$v.$reset()
              })
              .catch((err) => {
                  // console.log(err.response)
                  that.falha(this.i18n.dialogs.falhas.falha_cadastro, 10000)

              })
          },
          validarCadastro() {
              this.$v.formRegister.$touch()

              if (this.$v.formRegister.$anyError) {

                  this.$q.notify({
                      position: 'top',
                      color: 'warning',
                      textColor: 'black',
                      message: this.i18n.dialogs.falhas.preecha_forms,
                      icon: 'report_problem',
                      timeout: 10000,
                      actions: [
                          { label: this.i18n.dialogs.botoes.fechar, color: 'black', handler: () => {} }
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
                      message: this.i18n.dialogs.falhas.preecha_forms,
                      icon: 'report_problem',
                      timeout: 10000,
                      actions: [
                          { label: this.i18n.dialogs.botoes.fechar, color: 'black', handler: () => {} }
                      ]
                  })

                  return false

              }

              return true

          },
          esqueciSenha(){
              this.sessao = false
              this.logar = false
              this.esqueciSenhaModal = true


              this.$set(this,'formEsqueciSenha', {
                  email: '',
                  confirma_email: ''
              })
          },
          limparModal(){
              this.sessao = false
              this.logar = false
              this.esqueciSenhaModal = false

              this.$set(this,'formEsqueciSenha', {
                  email: '',
                  confirma_email: ''
              })

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

              this.hideLogin()
          },

      }

  }
</script>

<style lang="scss" scoped>
  // $
  @import '../css/tela-inicial.scss';
  @import '../css/footer.scss';
  @import '../css/dialogs.scss';

  @import '../css/darkMode/tela-inicial-dark.scss';
  @import '../css/darkMode/footer-dark.scss';
  @import '../css/darkMode/dialogs-dark.scss';
</style>