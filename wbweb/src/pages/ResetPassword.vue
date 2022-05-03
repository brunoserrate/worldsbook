<template>
    <q-page style="margin: .8rem" >
		<div class="row text-center">
			<div class="col-4">
			</div>
			<div class="col q-mt-xl">
				<q-card class="cadastrar">

					<q-card class="card_titulo">
						<q-card-section>
							<div class="titulo_cadastrar">Redefina sua senha</div>
							<p class="p_cadastrar">Digite novamente sua seu e-mail e sua nova senha</p>
						</q-card-section>
					</q-card>

					<q-card-section>
						<div class="row">
							<div class="col">
								<q-input
									v-model="$v.form.email.$model"
									label="E-mail *"
									type="email"
									outlined
									class="input_cadastro"
									:error="$v.form.email.$error"
									:error-message="
									$v.form.email.email ? 'Campo obrigatório': 'Digite um e-mail válido'
									"
								/>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<q-input
									v-model="$v.form.senha.$model"
									label="Senha *"
									:type="isPwd ? 'password' : 'text'"
									outlined
									class="input_cadastro"
									:error="$v.form.senha.$error"
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
						</div>
						<div class="row">
							<div class="col">
								<q-input
									v-model="$v.form.repita_senha.$model"
									label="Confirmar a senha *"
									:type="isPwdConf ? 'password' : 'text'"
									outlined
									class="input_cadastro"
									:error="$v.form.repita_senha.$error"
									:error-message="
									$v.form.repita_senha.sameAsPassword ? 'Campo obrigatório': 'Senhas não coincidem'
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
						</div>
					</q-card-section>

					<q-card-actions vertical >
						<q-btn flat label="Redefinir senha" @click="redefinirSenha()" class="btn_cadastrar" style="margin: 0 80px" />
					</q-card-actions>
				</q-card>
			</div>
		</div>
    </q-page>
</template>
<script>
import { required, sameAs, email } from 'vuelidate/lib/validators'

export default {
	name: 'ResetPassword',
	data (){
		return {
			token: this.$route.params.token,
			// Is password?
			isPwd: true,
			isPwdConf: true,
			form: {
				email: '',
				senha: '',
				repita_senha: ''
			}
		}
	},
	validations: {
		form: {
			email: { required, email },
			senha: { required },
			repita_senha: { required, sameAsPassword: sameAs('senha') },
		},
	},
	watch:{
		'$route' (to,from){
			this.$set(this,'token', this.$route.params.token)
		},
	},
	mounted(){
		this.$v.$reset()
	},
	methods:{
		redefinirSenha(){
			let that = this

			let params = {
				email: that.form.email,
				password: that.form.senha,
				password_confirmation: that.form.repita_senha,
				token: that.token
			}

			that.$axios.post(that.$pathAPI + '/reset-password', params)
			.then((res) => {
				// console.log(res)
				that.sucesso('Senha alterada com sucesso! Siga com o login')
			})
			.catch((err) => {
				// console.log(err.response)
				that.falha('Falha na operação. Por favor verifique o formulário e tente novamente')
			})
		},
		validarRedefinirSenha() {
			this.$v.form.$touch()

			if (this.$v.form.$anyError) {

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
	},
};
</script>

<style lang="scss" scoped>
  @import '../css/tela-inicial.scss';
  @import '../css/dialogs.scss'
</style>