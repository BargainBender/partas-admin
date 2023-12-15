<template>
    <div class="mt-10">
        <vue-recaptcha v-show="showRecaptcha" sitekey="6LepYjIpAAAAAHcN-IAMQZTw5IVSAMAYazznNHmi" size="normal" theme="dark"
            hl="tr" :loading-timeout="loadingTimeout" @verify="recaptchaVerified" @expire="recaptchaExpired"
            @fail="recaptchaFailed" @error="recaptchaError" ref="vueRecaptcha">
        </vue-recaptcha>
    </div>
</template>
  
<script>
import vueRecaptcha from 'vue3-recaptcha2';
import { store } from '@/recaptcha'

export default {
    name: 'ReCaptcha',
    components: {
        vueRecaptcha
    },
    data() {
        return {
            showRecaptcha: true,
            loadingTimeout: 30000 // 30 seconds
        }
    },
    methods: {
        recaptchaVerified(response) {
            console.log("verified")
            store.recaptchaVerified = true
        },
        recaptchaExpired() {
            this.$refs.vueRecaptcha.reset();
        },
        recaptchaFailed() {
        },
        recaptchaError(reason) {
        }
    }
};
</script>