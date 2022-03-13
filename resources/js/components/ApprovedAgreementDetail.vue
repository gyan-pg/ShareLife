<template>
  <div>
    <span>No.{{ index+1 }}</span>
    <span>{{ agree.title }}</span>
    <i v-if="agree.content" class="fa-solid fa-arrow-up-right-from-square" @click="openContent"></i>
    <transition name="fade">
      <div v-if="open_flg">{{ agree.content }}</div>
    </transition>
  </div>
</template>

<script>
export default {
  props: {
    index: {
      type: Number
    },
    agree: {
      type: Object
    }
  },
  data () {
    return {
      open_flg: false
    }
  },
  methods: {
    openContent () {
      this.open_flg = !this.open_flg
    }
  }
}
</script>