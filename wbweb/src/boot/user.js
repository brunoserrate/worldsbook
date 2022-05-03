import Vue from 'vue'

Vue.mixin({
    methods: {
        getUser(){
            this.user = JSON.parse( this.$q.sessionStorage.getItem('auth') )
        }
    }
})