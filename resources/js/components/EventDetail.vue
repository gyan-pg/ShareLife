<template>
<!-- event detail -->
  <section class="p-wrapper--modal" @click.self="closeDetail">
    <div class="p-wrapper--event-form">
      <form class="c-form c-form--event" onsubmit="return false;">
        <div class="p-container--event-profile">
          <img v-if="image !== '/storage/images/noimage.png'" class="c-image c-image--event-profile" :src="image">
          <span class="c-text--detail-name" v-else>{{ name }}</span>
        </div>

        <!-- title -->
        <div class="p-container--event-title">
          <span class="c-title--event-detail">{{ schedule.title }}</span>
        </div>

        <!-- date -->
        <div class="p-container--form-row u-mb-m">
          <div class="p-container--form-left">
            <span class="material-icons c-icon--form-item">schedule</span>
          </div>
          <div class="p-container--form-right p-container--event-date">
            <span class="c-date">{{formatDayStr(schedule.start)}}</span><span class="material-icons c-icon--date">start</span><span class="c-date">{{formatDayStr(schedule.end)}}</span>
          </div>
        </div>
        
        <!-- detail -->
        <div v-if="schedule.detail" class="p-container--form-row p-container--form-detail u-mb-m">
          <div class="p-container--form-left u-d-t">
            <span class="material-icons c-icon--form-item u-d-tc u-vm">notes</span>
          </div>
          <div class="p-container--form-right">
            <span class="c-text c-text--schedule-detail">{{ schedule.detail }}</span>
          </div>
        </div>

        <!-- 更新・削除・閉じる -->
        <div class="p-container--btn-right">
          <button v-if="my_schedule" class="c-btn c-btn--submit" type="button" @click="editSchedule">編集</button>
          <button v-if="my_schedule" class="c-btn c-btn--submit" type="button" @click="deleteSchedule">削除</button>
          <button class="c-btn c-btn--submit" type="button" @click="closeDetail">閉じる</button>
        </div>
        <div class="c-form__mask" :class="{'active': start_flg || end_flg}" @click="closeCalendar"></div>

      </form>
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
      error: null,
      image: null,
      name: null,
      my_schedule: false
    }
  },
  components: {
    SmallCalendar
  },
  computed: {
    myImage () {
      return this.$store.state.auth.user.image
    },
    partnerImage () {
      return this.$store.getters['auth/partner'].image
    }
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
      const result = str.substr(5,2)+'月'+str.substr(8,2)+'日'
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
    editSchedule () {
      this.$emit('edit-schedule', this.clickEvent)
    }
  },
  created () {
    this.schedule = this.clickEvent
    // 自分のスケジュールか判断する。
    if (this.clickEvent.user_id === this.$store.state.auth.user.id) {
      this.my_schedule = true
      this.image = this.myImage
      this.name = this.$store.state.auth.user.name
    } else {
      this.image = this.partnerImage
      this.name = this.$store.getters["auth/partner"].name
    }
  }
}
</script>