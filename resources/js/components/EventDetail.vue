<template>
<!-- event detail -->
  <section class="p-wrapper--modal" @click.self="closeDetail">
    <div class="p-wrapper--event-form">
      <form class="c-form" @submit.prevent="submitForm">
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
        <div>タイトル<i v-if="edit_flg_title" @click="editTitle" class="fa-solid fa-circle-check c-icon"></i></div>
        <p v-if="!edit_flg_title" @click="editTitle">{{ schedule.title }}</p>
        <input v-if="edit_flg_title" class="c-form__input" v-model="schedule.title">
        <!-- detail -->
        <div>詳細</div>
        <p v-if="!edit_flg_detail" @click="editDetail">{{ schedule.detail }}</p>
        <input v-if="edit_flg_detail" class="c-form__textarea-detail" v-model="schedule.detail">
        <button class="c-form__detail-update" type="button" :class="{'active': edit_flg}" :disabled="!edit_flg">更新</button>
        <button class="c-form__detail-update" type="button" @click="deleteSchedule">削除</button>
        <button type="button" @click="closeDetail">閉じる</button>

        <div class="c-form__mask" :class="{'active': start_flg || end_flg}" @click="closeCalendar"></div>

      </form>
      <i class="fa-solid fa-xmark c-icon c-icon--modal-close" @click="closeDetail"></i>
    </div>
  </section>
</template>

<script>
import SmallCalendar from './SmallCalendar.vue'
import dayjs from 'dayjs'
import { UNPROCESSABLE_ENTITY } from '../util'
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
      this.edit_flg_title = !this.edit_flg_title
    },
    editDetail () {
      this.edit_flg_detail = !this.edit_flg_detail
    },
    submitForm () {

    },
    async deleteSchedule () {
      const response = await axios.delete(`/api/schedule/delete/${this.schedule.id}`)
      console.log(response)
      if (response.status === UNPROCESSABLE_ENTITY) {
        console.log(response)
      }
    }
  },
  created () {
     this.schedule = this.clickEvent
  }
}
</script>