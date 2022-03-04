<template>
<!-- event detail -->
  <section class="p-wrapper--modal" @click.self="closeDetail">
    <div class="p-wrapper--event-form">
      <form class="c-form" onsubmit="return false;">
        <p v-if="error" class="c-text--error">{{ error }}</p>
        <div>
          <div><div class="c-form__date" :class="{'active': start_flg }" @click="openStartForm">{{formatDayStr(schedule.start)}}</div><div class="c-form__date" :class="{'active': end_flg }" @click="openCloseForm">{{formatDayStr(schedule.end)}}</div></div>
          <div>image</div>
        </div>
        
        <!-- 日時選択用カレンダー -->
        <div class="p-wrapper--small-calendar">
          <transition name="fade">
            <SmallCalendar v-if="open_calendar" :end_flg="end_flg" :start_flg="start_flg" 
            :start="schedule.start" :end="schedule.end" @close-calendar="closeCalendar" @get-date="getDate"/>
          </transition>
        </div>
        
        <!-- title -->
        <div>タイトル<i v-if="edit_flg_title" @click="editTitle" class="fa-solid fa-circle-check c-icon"></i><i v-if="!edit_flg_title" @click="editTitle" class="fa-regular fa-plus c-icon"></i></div>
        <p v-if="!edit_flg_title" @click="editTitle">{{ schedule.title }}</p>
        <input v-if="edit_flg_title" class="c-form__input" v-model="schedule.title">
        
        <!-- detail -->
        <div>詳細<i v-if="edit_flg_detail" @click="editDetail" class="fa-solid fa-circle-check c-icon"></i><i v-if="!edit_flg_detail" @click="editDetail" class="fa-regular fa-plus c-icon"></i></div>
        <p v-if="!edit_flg_detail" @click="editDetail">{{ schedule.detail }}</p>
        <textarea v-if="edit_flg_detail" class="c-form__textarea" v-model="schedule.detail"></textarea>
        
        <!-- color -->
        
        <div>color<i v-if="edit_flg_color" @click="editColor" class="fa-solid fa-circle-check c-icon"></i><i v-if="!edit_flg_color" @click="editColor" class="fa-regular fa-plus c-icon"></i></div>
        <div v-if="edit_flg_color" class="p-container--form-radio">
          <label for="radio-red" class="c-form__radio-label c-form__radio-label--red" :class="[schedule.color === '#ff9100' ? 'c-form__radio-label--checked' : '']" ></label> 
          <input id="radio-red" type="radio" value="#ff9100" class="c-form__radio" v-model="schedule.color">
          <label for="radio-blue" class="c-form__radio-label c-form__radio-label--blue" :class="[schedule.color === '#46aef3' ? 'c-form__radio-label--checked' : '']"></label>
          <input id="radio-blue" type="radio" value="#46aef3" class="c-form__radio" v-model="schedule.color">
          <label for="radio-green" class="c-form__radio-label c-form__radio-label--green" :class="[schedule.color === '#06f406' ? 'c-form__radio-label--checked' : '']"></label>
          <input id="radio-green" type="radio" value="#06f406" class="c-form__radio" v-model="schedule.color">
          <label for="radio-yellow" class="c-form__radio-label c-form__radio-label--yellow" :class="[schedule.color === '#dceb0e' ? 'c-form__radio-label--checked' : '']"></label>
          <input id="radio-yellow" type="radio" value="#dceb0e" class="c-form__radio" v-model="schedule.color">
          <label for="radio-gray" class="c-form__radio-label c-form__radio-label--gray" :class="[schedule.color === '#afafaf' ? 'c-form__radio-label--checked' : '']"></label>
          <input id="radio-gray" type="radio" value="#afafaf" class="c-form__radio" v-model="schedule.color">
        </div>

        <!-- 更新・削除・閉じる -->
        <button class="c-form__detail-update" type="button" :class="{'active': edit_flg}" :disabled="!edit_flg" @click="changeSchedule">更新</button>
        <button class="c-form__detail-update" type="button" @click="deleteSchedule">削除</button>
        <button class="c-form__detail-update" type="button" @click="closeDetail">閉じる</button>

        <div class="c-form__mask" :class="{'active': start_flg || end_flg}" @click="closeCalendar"></div>

      </form>
      <i class="fa-solid fa-xmark c-icon c-icon--modal-close" @click="closeDetail"></i>
    </div>
  </section>
</template>

<script>
import SmallCalendar from './SmallCalendar.vue'
import dayjs from 'dayjs'
import { OK, UNPROCESSABLE_ENTITY } from '../util'
export default {
  props: {
    clickEvent: {
      Object
    }
  },
  data () {
    return {
      schedule: {},
      start_flg: false,
      end_flg: false,
      edit_flg: false,
      edit_flg_title: false,
      edit_flg_detail: false,
      edit_flg_color: false,
      open_calendar: false,
      error: null
    }
  },
  components: {
    SmallCalendar
  },
  methods: {
    closeDetail () {
      this.$emit('close-detail')
    },
    closeCalendar () {
      this.open_calendar = false
      this.end_flg = false
      this.start_flg = false
    },
    getDate (date) {
      this.edit_flg = true
      if (this.start_flg) {
        this.schedule.start = date
        if (dayjs(this.schedule.end).diff(this.schedule.start) < 0) {
          this.schedule.end = date
        }
      } else {
        this.schedule.end = date
      }
    },
    openStartForm () {
      this.start_flg = !this.start_flg
      this.end_flg = false
      if (this.start_flg || this.end_flg) {
        this.open_calendar = true
      } else {
        this.open_calendar = false
      }
    },
    openCloseForm () { 
      this.end_flg = !this.end_flg
      this.start_flg = false
      if (this.start_flg || this.end_flg) {
        this.open_calendar = true
      } else {
        this.open_calendar = false
      }
    },
    formatDayStr (str) {
      const result = str.substr(0,4)+'年'+str.substr(5,2)+'月'+str.substr(8,2)+'日'
      return result
    },
    editTitle () {
      this.edit_flg = true
      this.edit_flg_title = !this.edit_flg_title
    },
    editDetail () {
      this.edit_flg = true
      this.edit_flg_detail = !this.edit_flg_detail
    },
    editColor () {
      this.edit_flg = true
      this.edit_flg_color = !this.edit_flg_color
    },
    async deleteSchedule () {
      this.error = null
      const response = await axios.delete(`/api/schedule/delete/${this.schedule.id}`)

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.error = response.data.error
        return false
      }
      this.$store.dispatch('events/getScheduleList')
      this.closeDetail()
      this.$store.commit('messages/setMessage', '予定を削除しました。')
    },
    async changeSchedule () {
      this.error = null
      const response = await axios.put('/api/schedule/changeSchedule', this.schedule)
      
      if (response.status === OK) {
        this.$store.dispatch('events/getScheduleList')
        this.closeDetail()
        this.$store.commit('messages/setMessage', '予定を更新しました。')
        return false
      }

    }
  },
  created () {
     this.schedule = this.clickEvent
  }
}
</script>