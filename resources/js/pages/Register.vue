<template>
  <div class="p-wrapper--auth">
    <ul class="c-auth-nav">
      <li class="c-auth-nav__item"><router-link to="/login">ログイン</router-link></li>
      <li class="c-auth-nav__item"><router-link to="/register">ユーザー登録</router-link></li>
    </ul>
    <form class="c-form c-form--login" @submit.prevent="register" novalidate="novalidate">
      <label for="name" class="c-form__label">お名前</label>
      <div class="p-container--form-input">
        <input id="name" class="c-form__input" type="text" v-model="registerForm.name">
        <!-- エラー表示 -->
        <div v-if="registerErrors">
          <ul v-if="registerErrors.name">
            <li v-for="msg in registerErrors.name" :key="msg" class="c-text--error">{{ msg }}</li>
          </ul>
        </div>
      </div>

      <label for="email" class="c-form__label">Eメール</label>
      <div class="p-container--form-input">
        <input id="email" class="c-form__input" type="email" v-model="registerForm.email">
        <!-- エラー表示 -->
        <div v-if="registerErrors">
          <ul v-if="registerErrors.email">
            <li v-for="msg in registerErrors.email" :key="msg" class="c-text--error">{{ msg }}</li>
          </ul>
        </div>
      </div>
      
      <label for="password" class="c-form__label">パスワード</label>
      <div class="p-container--form-input">
        <input id="password" class="c-form__input" type="password" v-model="registerForm.password">
        <!-- エラー表示 -->
        <div v-if="registerErrors">
          <ul v-if="registerErrors.password">
            <li v-for="msg in registerErrors.password" :key="msg" class="c-text--error">{{ msg }}</li>
          </ul>
        </div>
      </div>
      
      <label for="password-confirmation" class="c-form__label">パスワード(再入力)</label>
      <div class="p-container--form-input">
        <input id="password-confirmation" class="c-form__input" type="password" v-model="registerForm.password_confirmation">
      </div>
      <div class="p-container-btn">
        <button type="submit" class="c-btn c-btn--submit">送信</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      registerForm: {
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  computed: {
    registerErrors () {
      return this.$store.state.auth.registerErrorMessages
    },
    apiStatus () {
      return this.$store.state.auth.apiStatus
    }
  },
  methods: {
    async register () {
      console.log(this.registerForm);
      await this.$store.dispatch('auth/register', this.registerForm)
      if (this.apiStatus){
        this.$router.push('/mypage')
      }
    },
    clearError () {
      this.$store.commit('auth/setRegisterErrorMessages', null)
    }
  },
  created () {
    this.clearError()
  }
}
</script>