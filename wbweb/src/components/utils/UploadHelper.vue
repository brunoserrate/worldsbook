<template>
<div>
    <q-btn round color="primary" size="sm" @click="help_modal = !help_modal">
        <q-icon size="md" name="help" />
    </q-btn>

    <q-dialog v-model="help_modal">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Carregando imagem</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
            Para carregar a imagem basta clicar no ícone de "mais" ("+") conforme indica a imagem: <br>
            <div class="text-center">
                <!-- <img src="../../assets/t_00.png" alt=""><br><br> -->
            </div>
            Selecione a imagem desejada e depois clique no ícone de "upload" indicado na imagem: <br>
            <div class="text-center">
                <!-- <img src="../../assets/t_01.png" alt=""><br><br> -->
            </div>
            Em seguida o sistema irá carregar a imagem. Caso queira carregar outra imagem, será necessário
             remover a imagem anterior, para isso, basta clicar no ícone conforme a imagem: <br>
            <div class="text-center">
                <!-- <img src="../../assets/t_02.png" alt=""><br><br> -->
            </div>
            Clicar em "Remover foto": <br>
            <div class="text-center">
                <!-- <img src="../../assets/t_03.png" alt=""><br><br> -->
            </div>
            E confirmar em seguida.
        </q-card-section>
      </q-card>
    </q-dialog>
</div>
</template>

<script>
export default {
    name: 'UploadHelper',
    data(){
        return {
            help_modal: false
        }
    },
}
</script>

<style>

</style>