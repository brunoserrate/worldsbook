<template>
    <div class="fullscreen background-cover text-white text-center q-pa-md flex flex-center">
      <div class="row">
        <div class="col-5" style="">
          <div class="row">
            <div class="col-12 offset-2">
              <h1 style="font-size: 80px; font-weight: bold; font-family: Raleway; margin: 144px 0px 0px 0; color: #000;">404 not found</h1>
            </div>
            <div class="col-12 offset-2">
              <h4 style="margin-top: 0; margin-bottom: 0; color: #000; font-size: 25px;">A página que você deseja não foi encontrada</h4>
            </div>
            <div class="col-12 offset-2">
              <q-btn
                class="q-mt-xl botao"
                unelevated
                to="/"
                label="Clique para voltar ao início"
                no-caps
              />
            </div>
          </div>
        </div>
        <div class="col-7">
          <img src="~assets/mascote404.png" style="width: 39%; margin: 0 190px 0 0;"/>
        </div>
      </div>
    </div>
</template>

<script>
export default {
  name: 'Error404'
}
</script>
<style lang="scss" scoped>
  .background-cover {
      background: url('~assets/background.svg');
      background-size: 27%;
      // position: absolute;
      background-repeat: no-repeat;
      background-position: right center;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      // z-index: 10000;
  }

  .botao {
    margin: 51px 0 0 0;
    color: #7a22a7;
    text-transform: uppercase;
    font-size: 17px;
    &:hover {
      background-color: #7b22a734;
    }
  }
</style>