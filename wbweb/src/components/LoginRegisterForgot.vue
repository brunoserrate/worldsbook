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
                <q-input v-model="formLogin.email" :label="i18n.dialogs.login.inputs.email" type="email" outlined class="input_cadastro" autocorrect="off" autocapitalize="off" autocomplete="off" spellcheck="false"/>
              </div>
              <div class="col-12">
                <q-input
                    v-model="formLogin.senha"
                    :label="i18n.dialogs.login.inputs.senha"
                    :type="isPwdLogin ? 'password' : 'text'"
                    autocorrect="off"
                    autocapitalize="off"
                    autocomplete="off"
                    spellcheck="false"
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
            <div class="row wb-form-dark-outlined">
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
                <!-- <q-input
                  v-model="$v.formRegister.data_nascimento.$model"
                  type="date"
                  outlined
                  class="input_cadastro"
                  :label="i18n.dialogs.cadastro.inputs.data_nascimento+' *'"
                  stack-label
                  :error="$v.formRegister.data_nascimento.$error"
                  :error-message="i18n.dialogs.campo_obrigatorio"
                /> -->
                <q-input outlined v-model="$v.formRegister.data_nascimento.$model" :label="i18n.dialogs.cadastro.inputs.data_nascimento+' *'" mask="##/##/####" stack-label class="dark-input input_cadastro">
                  <template v-slot:append>
                    <q-icon name="event" class="cursor-pointer">
                      <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                        <q-date v-model="$v.formRegister.data_nascimento.$model" mask="DD-MM-YYYY" minimal :options="optionsFn">
                          <div class="row items-center justify-end">
                            <q-btn v-close-popup label="Close" color="primary" flat />
                          </div>
                        </q-date>
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
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
  import eventBus from 'src/boot/eventBus';
  import { format } from 'date-fns';

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
      // computed: {
      //   optionsFn (date) {
      //     console.log("date: ", date)
      //     return date <= '2019/02/15'
      //   },
      // },
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
          async login(){
              let params = {
                  email: this.formLogin.email,
                  password: this.formLogin.senha
              }

              await this.$api.post('/auth/login', params)
              .then((res) => {
                  localStorage.setItem("token", JSON.stringify(res.data))
                  this.$q.sessionStorage.set('auth', res.data)
                  this.user = res.data.usuario
                  this.logado = true

                  this.sessao = false
                  this.logar = false
                  this.esqueciSenhaModal = false

                  this.$router.push({path: '/iniciar_leitura'})
                  this.$emit('usuarioLogado', this.user)
                  // this.sucesso()

              })
              .catch((err) => {
                  console.log(err.response)
                  this.falha(this.i18n.dialogs.falhas.falha_operacao)
              })
          },
          logout(){
              this.user = null
              this.logado = false
              this.$q.sessionStorage.remove('auth')

              this.$router.push({path: '/'})
              // this.$axios.post(this.$pathWeb + '/logout', this.user)
              // .then((res) => {
              //     // console.log(res)
              // })
              // .catch((err) => {
              //     // console.log(err.response)
              // })
          },
          async cadastrarUsuario(){

              let params = {
                  name: this.formRegister.nome,
                  apelido: this.formRegister.apelido,
                  email: this.formRegister.email,
                  password: this.formRegister.senha,
                  password_confirmation: this.formRegister.repita_senha,
                  data_nascimento: this.formatDateForAmerica(this.formRegister.data_nascimento)
              }

              console.log(params)

              if(!this.validarCadastro()) return false

              await this.$api.post('usuarios', params)
              .then(async (res) => {
                  this.sessao = false
                  this.logar = true

                  this.$set(this,'formRegister', {
                      nome: '',
                      apelido: '',
                      email: '',
                      senha: '',
                      repita_senha: '',
                      data_nascimento: '',
                  })

                  await this.sucesso(this.i18n.dialogs.sucesso.cadastro_sucesso)

                  this.$v.$reset()
              })
              .catch((err) => {
                  this.falha(this.i18n.dialogs.falhas.falha_cadastro, 10000)
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
          optionsFn (date) {
            let dataAtual = format(new Date(), 'yyyy/MM/dd')
            return date <= dataAtual
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