<template>
  <section class="p-wrapper--modal" @click.self="closeProfileEdit">
    <div class="p-wrapper--agreement-form">
      <form class="c-form c-form--event" @submit.prevent="submit">

        <!-- 名前 -->
        <div class="p-container--form-row">
          <div class="p-container--form-left">
            <label for="profile-name" class="c-form__label--event"><span class="material-icons c-icon--form-item">face</span></label>
          </div>
          <div class="p-container--form-right">
            <input id="profile-name" class="c-form__input c-form__input--underline" :class="{'c-form__input--disable': guest}" placeholder="お名前を入力してください。" v-model="userInfo.name" :disabled="guest">
          </div>
        </div>
        <div class="p-container--input-notice">
          <!-- このコンポーネントでのバリデーション結果 -->
          <span v-if="error_flg && errors_js.name" class="c-text--error">{{ errors_js.name }}</span>
          <!-- サーバー側からのエラーの表示 -->
          <div v-if="error_flg && errors.name"><span class="c-text--error" v-for="(error, index) in errors.name" :key="index">{{ error }}</span></div>
        </div>

        <!-- Eメール -->
        <div class="p-container--form-row">
          <div class="p-container--form-left">
            <label for="profile-email" class="c-form__label--event"><span class="material-icons c-icon--form-item">email</span></label>
          </div>
          <div class="p-container--form-right">
            <input id="profile-email" class="c-form__input c-form__input--underline" :class="{'c-form__input--disable': guest}" placeholder="Eメールアドレスを入力してください。" v-model="userInfo.email" :disabled="guest">
          </div>
        </div>
        <div class="p-container--input-notice">
          <!-- このコンポーネントでのバリデーション結果 -->
          <span v-if="error_flg && errors_js.email" class="c-text--error">{{ errors_js.email }}</span>
          <!-- サーバー側からのエラーの表示 -->
          <div v-if="error_flg && errors.email">
            <span class="c-text--error" v-for="(error, index) in errors.email" :key="index">{{ error }}</span>
          </div>
        </div>

        <!-- 画像 -->
        <div class="p-container--form-row">
          <div class="p-container--form-left">
            <label for="profile-image"><span class="material-icons c-icon--form-item">image</span></label>
          </div>
          <div class="p-container--form-right">
            <button type="button" class="c-btn c-btn--clear" @click="reset">画像をクリア</button>
          </div>
        </div>
         <div class="p-container--input-notice u-mb-s u-d-f-jc">
            <!-- このコンポーネントでのバリデーション結果 -->
            <span v-if="error_flg && errors_js.image" class="c-text--error">{{ errors_js.image }}</span>
            <!-- サーバー側からのエラーの表示 -->
            <div v-if="error_flg && errors.image"><span class="c-text--error" v-for="(error, index) in errors.image" :key="index">{{ error }}</span></div>
          </div>

        <div class="p-container--form-input">
          <div class="p-container--image-profile-edit">
            <input id="profile-image" type="file" class="c-form__image" @change="onFileChange">
            <output>
              <img class="c-image c-image--profile-edit" :src="preview ? preview : userInfo.image">
            </output>
          </div>
        </div>
        <div class="p-container--btn-right">
          <button class="c-btn c-btn--submit" type="submit" :disabled="sending">保存する</button>
          <button class="c-btn c-btn--submit" type="button" @click="closeProfileEdit">閉じる</button>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
import { OK, EMAIL_PATTERN, UNPROCESSABLE_ENTITY, INTERNAL_SERVER_ERROR, UNAUTHORIZED } from '../util'
export default {
  data () {
    return {
      userInfo: {
        name: null,
        email: null,
        image: null,
      },
      file: null,
      preview: null,
      errors: {
        name: null,
        email: null,
        image: null
      },
      errors_js: {
        name: null,
        email: null,
        image: null
      },
      error_flg: false,
      reset_flg: false,
      sending: false,
    }
  },
  computed: {
    guest () {
      return this.$store.getters['auth/guest']
    }
  },
  methods: {
    closeProfileEdit () {
      this.$emit('closeProfileEdit')
    },
    onFileChange (event) {
      this.error_flg = false
      this.reset_flg = false
      // 何も選択されていなかったら処理中断

      if (event.target.files.length === 0) {
        this.reset()
        return false
      }

      // ファイルが画像ではなかったら処理中断
      if (! event.target.files[0].type.match('image.*')) {
        this.error_flg = true
        this.reset()
        this.errors_js.image = '.gif, .jpeg, .pngファイルのみ登録可能です。'
        return false
      }

      // ファイルサイズは1MB以内
      if (event.target.files[0].size > 1024 * 1024) {
        this.error_flg = true
        this.reset()
        this.errors_js.image = 'ファイルサイズは1MB以内でお願いします。'
        return false
      }

      // FileReaderクラスのインスタンスを取得
      const reader = new FileReader()

      // ファイルを読み込む
      // 読み込まれたファイルはデータURL形式で受け取れる
      reader.readAsDataURL(event.target.files[0])

      // ファイルを読み込み終わったタイミングで実行する処理
      // FileReader().onloadはファイルの読み込みが正常に完了した場合に発火するイベント。
      reader.onload = e => {
        // previewに読み込み結果（データURL）を代入する
        // previewに値が入ると<output>につけたv-ifがtrueと判定される
        // また<output>内部の<img>のsrc属性はpreviewの値を参照しているので
        // 結果として画像が表示される
        this.preview = e.target.result
      }
      this.file = event.target.files[0]
    },
    reset () {
      this.userInfo.image = '/storage/images/noimage.png'
      this.file = null
      this.preview = null
      // resetされたことを検知
      this.reset_flg = true
      this.errors_js.image = null
      this.$el.querySelector('input[type="file"]').value = null
    },

    async submit () {
      this.error_flg = false
      this.sending = true

      // js側でのバリデーション
      this.validName()
      this.validEmail()

      if(this.error_flg) {
        this.sending = false
        return false
      }

      // 画像がある時
      if (this.file) {
        const formData = new FormData()
        // 画像ファイルを追加
        formData.append('image', this.file)
        const res = await axios.post('/api/profile/image', formData)

        if (res === undefined) {
          this.closeProfileEdit()
          return false
        }

        if (res.status === UNPROCESSABLE_ENTITY) {
          this.error_flg = true
          this.errors = res.data.errors
          this.sending = false
        }
      // resetボタンが押された時
      } else if (this.reset_flg) {
        const res_reset = await axios.put('/api/profile/image/reset')
        
        // 問題があった時
        if (res_reset === undefined) {
          this.closeProfileEdit()
          return false
        }
      }

      // 画像の登録処理が終わった後に問題がなければ次の処理へすすむ
      if (this.error_flg) {
        this.sending = false
        return false
      }

      const response = await axios.put('/api/profile/update', this.userInfo)

      // 認証切れや500エラーの時はフォームを閉じる。
      if (response === undefined) {
        this.closeProfileEdit()
        return false
      }

      if (response.status === OK) {
        this.$store.commit('messages/setMessage', 'プロフィール変更しました。')
        this.$store.dispatch('auth/currentUser')
        this.closeProfileEdit()
      } else if (response.status === UNPROCESSABLE_ENTITY) {
        this.error_flg = true
        this.sending = false
        this.errors = response.data.errors
      }
    },
    validName () {
      // バリデーションメッセージの初期化
      this.errors_js.name = null
      this.errors.name = null
      // 名前のバリデーション
      // 最大文字数
      if (this.userInfo.name.length > 20) {
        this.errors_js.name = '名前は20文字以内で入力してください。'
        this.error_flg = true
        return false
      }
      // 空欄禁止
      if (this.userInfo.name.length === 0) {
        this.errors_js.name = '名前は入力必須項目です。'
        this.error_flg = true
        return false
      }
    },
    validEmail () {
      // バリデーションメッセージの初期化
      this.errors_js.email = null
      this.errors.email = null
      // Eメールのバリデーション
      // 最大文字数
      if (this.userInfo.email.length > 255) {
        this.errors_js.email = 'Eメールアドレスは255文字以内で入力してください。'
        this.error_flg = true
        return false
      }
      // 空欄禁止
      if (this.userInfo.email.length === 0) {
        this.errors_js.email = 'Eメールアドレスは入力必須項目です。'
        this.error_flg = true
        return false
      }
      // Eメール形式の確認
      const result = EMAIL_PATTERN.test(this.userInfo.email)
      if (!result) {
        this.errors_js.email = 'Eメールアドレスの形式で入力してください。'
        this.error_flg = true
        return false
      }
    }
  },
  created () {
    this.userInfo.name = this.$store.state.auth.user.name
    this.userInfo.email = this.$store.state.auth.user.email
    this.userInfo.image = this.$store.state.auth.user.image
  }
}
</script>