<template>
<!-- カレンダーの１マスに予定を表示する -->
  <div class="c-calendar__event-container">
    <!-- 隠れている予定の数の表示 -->
    <div v-if="eventNum" class="c-calendar__hidden-container">
      <div v-if="hiddenEventNum" @click="openEventList" v-click-outside="closeEventList" class="c-calendar__hidden-event">{{ hiddenEventNum }}</div>
    </div>
    
    <div v-for="(dayEvent, index) in Events" :key="dayEvent.id">
      <div v-if="dayEvent.width" class="c-calendar__event"
      :class="{'hide': index + 1 > eventNum}"
      :style="`width:${dayEvent.width}%;background-color: ${dayEvent.color}`"
      draggable="true" @dragstart="dragStart($event, dayEvent.id)" @click.self="eventDetail(dayEvent)"><!-- $eventはDOMイベントと呼ばれる -->
        {{ formatTitle(dayEvent) }}
      </div>
      <div v-else style="height:23px;"></div>
    </div>
    <div>
      <transition name="fade">
        <CalendarHiddenEventList v-if="show_hiddenList_flg" :Events="Events" @eventDetail="eventDetail"/>
      </transition>
    </div>
     <!-- イベント入力フォーム -->
    <transition name="fade">
      <EventForm v-if="form_flg" @close-form="closeEventForm" :editEvent="editEvent"/>
    </transition>
    <!-- イベント詳細 -->
    <transition name="fade">
      <EventDetail v-if="detail_flg" @close-detail="closeDetail" @edit-schedule="editSchedule" :clickEvent="clickEvent"/>
    </transition>
  </div>
</template>

<script>
import CalendarHiddenEventList from './CalendarHiddenEventList.vue'
import ClickOutside from 'vue-click-outside'
import EventDetail from './EventDetail.vue'
import EventForm from './EventForm.vue'
export default {
  props: {
    Events: {
      type: Array,
      default: []
    },
    // 1マスに予定を表示できる件数
    eventNum: {
      type: Number
    }
  },
  data () {
    return {
      index: 0,
      hiddenEventNum: 0,// マスからはみ出た予定の数
      clickEvent: null,
      show_hiddenList_flg: false,
      form_flg: false,
      clickEvent: null,
      editEvent: null,
      detail_flg: false,
    }
  },
  components: {
    CalendarHiddenEventList,
    EventDetail,
    EventForm
  },
  methods: {
    dragStart ($event, dayEventId) {
      this.$emit('dragStart',$event, dayEventId)
    },
    // カレンダーに表示するタイトルを加工する
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
    getIndex () {
      this.index = this.Events.length
    },
    showEventNum (value) {
      let hiddenEventNum = this.index - value
      if (hiddenEventNum > 0) {
        this.hiddenEventNum = hiddenEventNum
      } else {
        this.hiddenEventNum = 0
        this.show_hiddenList_flg = false
      }
    },
    openEventList () {
      this.show_hiddenList_flg = !this.show_hiddenList_flg
    },
    closeEventList () {
      this.show_hiddenList_flg = false
    },
    eventDetail (dayEvent) {
      this.detail_flg = true
      // dayEventを直接渡してしまうと参照渡しになってしまうので、
      // ここでは新たにオブジェクトを作成する。
      this.clickEvent = {
        'id': dayEvent.id,
        'title': dayEvent.title,
        'color': dayEvent.color,
        'start': dayEvent.start,
        'end': dayEvent.end,
        'detail': dayEvent.detail,
        'user_id': dayEvent.user_id
      }
    },
    closeDetail () {
      this.detail_flg = false
    },
    editSchedule (event) {
      this.detail_flg = false
      this.form_flg = true
      this.editEvent = event
    },
    closeEventForm () {
      this.form_flg = false
      this.editEvent = null
    }
  },
  updated () {
    this.showEventNum(this.eventNum)
  },
  watch: {
    eventNum (newValue) {
      this.showEventNum(newValue)
    },
    Events () {
      this.getIndex()
    }
  },
  directives: {
    ClickOutside,
  }
}
</script>