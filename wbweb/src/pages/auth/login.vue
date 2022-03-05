<template>
<q-page class="bg-grey-3 flex flex-center">
	<q-card color="white" text-color="primary">
		<q-card-section>
		    <div class="text-h6 text-center text-primary">Worlds Book</div>
	    </q-card-section>
		<q-card class="my-card">
	      <q-card-section>
			<div class="row gutter-lg a-field">
				<div class="col-12">
					<q-input :error="$v.form.username.$error" v-model.trim="form.username" label="Usuário" @keyup.enter="autenticar">
						<template v-slot:prepend>
							<q-icon name="fas fa-user" />
						</template>
					</q-input>
				</div>
			</div>
			<div class="row gutter-lg a-field">
				<div class="col-12">
					<q-input :error="$v.form.password.$error" v-model.trim="form.password" :type="visible ? 'password' : 'text'" label="Senha" @keyup.enter="autenticar">
            <template v-slot:prepend>
              <q-icon name="fas fa-lock" />
            </template>
            <template v-slot:append>
              <q-icon
                :name="visible ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="visible = !visible"
              />
            </template>
					</q-input>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-center a-field">
					<q-btn
            :loading="autenticando"
            color="primary"
            icon="fas fa-check"
            label="Acessar"
            @click.native="autenticar"
          >
						<span slot="loading">
							<q-spinner-dots color="white" :size="30"/>
						</span>
					</q-btn>
				</div>
			</div>
	      </q-card-section>
	    </q-card>
	</q-card>
</q-page>
</template>

<script>
import { required } from 'vuelidate/lib/validators'

export default {
  data (){
    return {
    	form:{
    		username:'',
    		password:'',
    	},
      visible: true,
      autenticando:false,
    }
  },
  validations:{
  	form:{
	  	username: { required },
	  	password: { required }
  	}
  },
  methods:{
  	validarCampos(){
      this.$v.form.$touch()
      if (this.$v.form.$error) {
        this.$q.notify({
          position: 'top',
          color: 'warning',
          message: 'Preencha todos os campos',
          icon: 'report_problem'
        })
        return false
      }
      return true
    },
  	autenticar(){
  		let that=this
  		that.$set(that,'autenticando',true)
  		if(that.form.username == 'admin' && that.form.password == '1234'){
  			sessionStorage.setItem('autenticado', true)
        sessionStorage.setItem('nome', 'Admin')
        sessionStorage.setItem('email', 'admin@app.com.br')
  			// that.$router.push('/sistema/')
  		} else {
  			this.$q.notify({
  				position: 'top',
  				color: 'warning',
  				message: 'Login e senha incorretas!',
  				icon: 'report_problem'
  			})
  		}

  		that.$set(that,'autenticando', false)
  	},
  },
};
</script>

<style>
body {
  font-size: 15px;
/*  background-color: #e0e0e0*/
}

.logotipo {
 height: 5em;
 padding:0;
 margin:0;
}

.a-field {
  padding:12px;
}
</style>
