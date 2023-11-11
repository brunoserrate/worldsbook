import Vue from 'vue';
import BR from '../locale/br.json';
import ES from '../locale/es.json';
import US from '../locale/us.json';
import JP from '../locale/jp.json';
import KO from '../locale/ko.json';
import IT from '../locale/it.json';
import FR from '../locale/fr.json';

Vue.prototype.$locale = 'br';
Vue.prototype.$i18n = BR;

Vue.mixin({
    methods: {
        async changeLanguage(locale) {
            Vue.prototype.$locale = locale
            if (Vue.prototype.$locale == 'us') {
                return this.toUSA()
            }
            else if (Vue.prototype.$locale == 'es') {
                return this.toSpanish()
            }
            else if (Vue.prototype.$locale == 'jp') {
                return this.toJaponese()
            }
            else if (Vue.prototype.$locale == 'ko') {
                return this.toKorean()
            }
            else if (Vue.prototype.$locale == 'it') {
                return this.toItalian()
            }
            else if (Vue.prototype.$locale == 'fr') {
                return this.toFrench()
            }
            else {
                return this.toBrazil()
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
        async toJaponese() {
            Vue.prototype.$i18n = JP
        },
        async toItalian() {
            Vue.prototype.$i18n = IT
        },
        async toKorean() {
            Vue.prototype.$i18n = KO
        },
        async toFrench() {
            Vue.prototype.$i18n = FR
        },
    }
})