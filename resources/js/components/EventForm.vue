<template>
<!-- event form -->
  <section class="p-wrapper--modal" @click.self="closeEventForm">
    <div class="p-wrapper--event-form">
      <p v-if="errors.server_error" class="c-text--error u-tc">{{ errors.server_error }}</p>
      <form class="c-form u-pr" @submit.prevent="setSchedule">
        <!-- 日時を選択する -->
        <div class="p-container--form-select-date">
          <div class="u-tc">
            <div class="c-form__date" :class="{'active': start_flg }" @click="openStartForm">{{ formatDayStr(schedule.start) }}</div><span class="u-mrl-s">〜</span><div class="c-form__date" :class="{'active': end_flg }" @click="openCloseForm">{{ formatDayStr(schedule.end) }}</div>
          </div>
            <!-- <button class="c-btn" :class="{'active': start_flg }" type="button" @click="openStartForm">編集する</button>
            <button class="c-btn" :class="{'active': end_flg }" type="button" @click="openCloseForm">編集する</button> -->
          <!-- エラー表示 -->
          <p v-if="errors.schedule" class="c-text--error">{{ errors.schedule }}</p>

          <!-- 日時選択用カレンダー -->
          <div class="p-wrapper--small-calendar">
            <transition name="fade">
              <SmallCalendar v-if="open_calendar" :end_flg="end_flg" :start_flg="start_flg" 
              :start="schedule.start" :end="schedule.end" @close-calendar="closeCalendar" @get-date="getDate"/>
            </transition>
          </div>
        </div>

        <!-- タイトル -->
        <label for="agreement-title">タイトル</label>
        <div class="p-container--form-input">
          <input id="agreement-title" class="c-form__input" v-model="schedule.title">
          <div class="p-container--input-notice">
            <p v-if="errors.title" class="c-text--error">{{ errors.title }}</p>
            <p class="c-text--counter">{{ schedule.title.length }}/40</p>
          </div>
        </div>

        <label for="agreement-detail">詳細</label>
        <div class="p-container--form-input">
          <textarea id="agreement-detail" class="c-form__textarea" v-model="schedule.detail"></textarea>
          <div class="p-container--input-notice">
            <p v-if="errors.detail" class="c-text--error">{{ errors.detail }}</p>
            <p class="c-text--counter">{{ schedule.detail.length }}/400</p>
          </div>
        </div>

        <!-- 色選択 -->
        <div class="p-container--form-radio">
          <label>color</label>
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

        <button class="c-btn c-btn--submit" type="submit">submit</button>
        <p v-if="errors" class="c-text--error">{{ errors.server }}</p>

        <div class="c-form__mask" :class="{'active': start_flg || end_flg}" @click="closeCalendar"></div>

      </form>
      <i class="fa-solid fa-xmark c-icon c-icon--modal-close" @click="closeEventForm"></i>
    </div>
  </section>
</template>

<script>
import dayjs from 'dayjs'
import {OK, UNPROCESSABLE_ENTITY, INTERNAL_SERVER_ERROR} from '../util'
import SmallCalendar from './SmallCalendar.vue'
export default {
  props: {
    clickDate: {
      String
    }
  },
  data () {
    return {
      schedule: {
        title: '',
        detail: '',
        start: null,
        end: null,
        color: '#ff9100',
      },
      errors: {
        title: null,
        detail: null,
        schedule: null,
        color: null,
        server_error: ''
      },
      start_flg: false,
      end_flg: false,
      open_calendar: false,
      error_flg: false,
    }
  },
  components: {
    SmallCalendar
  },
  computed: {
    detail_length () {
      return this.schedule.detail.length
    },
    title_length () {
      return this.schedule.title.length
    }
  },
  methods: {
    async setSchedule () {
      this.cleanErrorMessages()
      this.error_flg = false
      this.scheduleValidation()
      this.titleValidation()
      this.detailValidation()
      if (!this.error_flg) {
        const response = await axios.post('/api/schedule/register', this.schedule)

        if (response.status !== OK) {
          this.errors.server_error = 'サーバーでエラーが発生しました。'
          return false;
        }
        this.$store.commit('messages/setMessage', 'スケジュールを登録しました。')
        this.$store.dispatch('events/getScheduleList')
        this.closeEventForm()
      }
    },
    //開始日と終了日のバリデーション
    scheduleValidation () {
      if (dayjs(this.schedule.end).diff(this.schedule.start) < 0) {
        this.errors.schedule = '予定の終了日は開始日より前に設定できません。'
        this.schedule.end = this.schedule.start
        this.error_flg = true
      }
    },
    // 予定のタイトルのバリデーション
    titleValidation () {
      if (this.title_length === 0) {
        this.errors.title = 'タイトルは入力必須です。'
        this.error_flg = true
      }
      if (this.title_length > 40) {
        this.errors.title = 'タイトルは40文字以内で入力してください。'
        this.error_flg = true
      } 
    },
    // 予定の本文のバリデーション
    detailValidation () {
      if (this.detail_length >= 400) {
        this.errors.detail = '予定の詳細は400文字以内で入力してください。'
        this.error_flg = true
      }
    },
    // エラーメッセージの初期化
    cleanErrorMessages () {
      for (let key in this.errors) {
        this.errors[key] = ''
      }
    },
    // 入力用カレンダー関係
    getStartDate () { // カレンダーの最初の日付を取得する。
      let date = dayjs(this.currentDate) // dayjsオブジェクト
      const dayOfTheWeek = date.startOf('month').day() // 当月の初日の曜日を取得
      return date.startOf('month').subtract(dayOfTheWeek, 'day')
    },
    getEndDate () {
      let date = dayjs(this.currentDate)
      const dayOfTheWeek = date.endOf('month').day()
      return date.endOf('month').add(6 - dayOfTheWeek, 'day')
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
    closeCalendar () {
      this.open_calendar = false
      this.end_flg = false
      this.start_flg = false
    },
    formatDayStr (str) {
      const result = str.substr(0,4)+'年'+str.substr(5,2)+'月'+str.substr(8,2)+'日'
      return result
    },
    closeEventForm () {
      this.$emit('close-form')
    },
    getDate (date) {
      console.log(date)
      if (this.start_flg) {
        this.schedule.start = date
        if (dayjs(this.schedule.end).diff(this.schedule.start) < 0) {
          this.schedule.end = date
        }
      } else {
        this.schedule.end = date
      }
    }
  },
  created () {
    if(this.clickDate) {
      this.schedule.start = this.clickDate
      this.schedule.end = this.clickDate
    } else {
      this.schedule.start = dayjs().format('YYYY-MM-DD')
      this.schedule.end = dayjs().format('YYYY-MM-DD')
    }
  }
}

</script>