import Vue from 'vue';
import BR from '../locale/br.json';
import ES from '../locale/es.json';
import US from '../locale/us.json';

Vue.prototype.$locale = 'br';
Vue.prototype.$i18n = BR;

Vue.mixin({
    methods: {
        async changeLanguage(locale) {
            Vue.prototype.$locale = locale
            if (Vue.prototype.$locale == 'br') {
                return this.toBrazil()
            }
            else if (Vue.prototype.$locale == 'es') {
                return this.toSpanish()
            }
            else {
                return this.toUSA()
            }
        },
        async toBrazil() {
            Vue.prototype.$i18n = BR
        },
        async toUSA() {
            Vue.prototype.$i18n = US
        },
        async toSpanish() {
            Vue.prototype.$i18n = ES
        },
    }
})