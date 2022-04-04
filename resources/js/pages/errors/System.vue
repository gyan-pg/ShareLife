<template>
  <section class="p-wrapper--auth">
    <p class="c-text--gomen">大変申し訳ありません。</p>
    <p>システムエラーが発生しました。</p>
    <a :href="home_url" class="c-btn c-btn--500">戻る</a>
    <div class="u-mt-l u-tc">
      <img class="c-image c-image--500" src="/storage/images/500.png">
    </div>
  </section>
</template>

<script>
export default {
  data () {
    return {
      home_url: null
    }
  },
  mounted () {
    this.home_url = window.location.protocol + '//' + window.location.host
  }
}
</script>