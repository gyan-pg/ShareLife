<template>
  <div class="c-calendar__container-event-list js-hide-list" :style="{right: offsetX+'px', bottom: offsetY+'px'}">
    <div v-for="event in Events" :key="event.id">
      <div :style="`width:100%;background-color: ${event.color}`" class="c-calendar__event" @click="eventDetail(event)">
        {{ formatTitle(event) }}
      </div>
    </div>
    <div class="p-outside" @click="closeEventList"></div>
  </div>
</template>

<script>
import EventDetail from './EventDetail.vue'
import EventForm from './EventForm.vue'
export default {
  props: {
    Events: {
      type: Array
    },
    windowWidth: {
      type: Number,
      required: true
    }
  },
  data () {
    return {
      offsetX: 0,
      offsetY: 0
    }
  },
  components: {
    EventDetail,
    EventForm
  },
  methods: {
    formatTitle (dayEvent) {
      // スケジュールの日数を確認
      let scheduleDays = Math.ceil(dayEvent.width/100)
      // タイトルは1日あたり7文字までとする。
      if (dayEvent.title.length > scheduleDays * 20) {
        let returnStr = dayEvent.title.slice(0, (scheduleDays * 7 - 1))
        return returnStr + '…'
      }
      return dayEvent.title
    },
    eventDetail (event) {
      this.$emit('eventDetail', event)
      this.closeEventList()
    },
    closeEventList () {
      this.$emit('closeEventList')
    }
  },
  mounted () {
    const elementRect = document.querySelector('.js-hide-list').getBoundingClientRect()
    console.log(this.windowWidth)
    console.log(elementRect)
    if (this.windowWidth < elementRect.right) {
      this.offsetX = elementRect.right - this.windowWidth
    }
  }
}
</script>