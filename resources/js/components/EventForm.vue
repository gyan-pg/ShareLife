<template>
<!-- event form -->
  <section class="p-wrapper--modal" @click.self="closeEventForm">
    <div class="p-wrapper--event-form">
      <p v-if="errors.server_error" class="c-text--error u-tc">{{ errors.server_error }}</p>
      <form class="c-form c-form--event" @submit.prevent="setSchedule">
        
        <!-- タイトル -->
        <div class="p-container--form-input">
          <input id="agreement-title" class="c-form__input--title" v-model="schedule.title" placeholder="タイトル">
          <div class="p-container--input-notice">
            <p v-if="errors.title" class="c-text--error">{{ errors.title }}</p>
          </div>
        </div>

        <!-- 日時を選択する -->
        <div class="p-container--form-input">
          <div class="p-container--form-row">
            <div class="p-container--form-left">
              <label class="c-form__label--event"><span class="material-icons c-icon--form-item">schedule</span></label>
            </div>
            <div class="p-container--form-right">
              <div class="u-tc">
                <div class="c-form__date" :class="{'active': start_flg }" @click="openStartForm">{{ formatDayStr(schedule.start) }}</div><span class="u-mrl-s">〜</span><div class="c-form__date" :class="{'active': end_flg }" @click="openCloseForm">{{ formatDayStr(schedule.end) }}</div>
              </div>
            </div>
          </div>
          <!-- エラー表示 -->
          <div class="p-container--input-notice">
            <p v-if="errors.schedule" class="c-text--error">{{ errors.schedule }}</p>
          </div>

          <!-- 日時選択用カレンダー -->
          <div class="p-wrapper--small-calendar">
            <transition name="fade">
              <SmallCalendar v-if="open_calendar" :end_flg="end_flg" :start_flg="start_flg" 
              :start="schedule.start" :end="schedule.end" @close-calendar="closeCalendar" @get-date="getDate"/>
            </transition>
          </div>
        </div>

        <!-- コメント -->
        <div class="p-container--form-input">
          <div class="p-container--form-row">
            <div class="p-container--form-left">
              <label for="agreement-detail" class="c-form__label--event"><span class="material-icons c-icon--form-item">notes</span></label>
            </div>
            <div class="p-container--form-right">
              <input id="agreement-detail" class="c-form__input c-form__input--event" v-model="schedule.detail">
            </div>
          </div>
          <div class="p-container--input-notice">
            <p v-if="errors.detail" class="c-text--error">{{ errors.detail }}</p>
          </div>
        </div>

        <!-- 色選択 -->
        <div class="p-container--form-input">
          <div class="p-container--form-event-input">
            <div class="p-container--form-row">
              <div class="p-container--form-left">
                <label class="c-form__label--event"><span class="material-icons c-icon--form-item">label</span></label>
              </div>
              <div class="p-container--form-right">
                <div class="p-container--form-radio">
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
              </div>
            </div>
          </div>
        </div>

        <div class="p-container--btn-right">
          <button v-if="edit_flg" class="c-btn c-btn--submit" type="button" @click="changeSchedule" :disabled="sending">更新</button>
          <button v-if="!edit_flg" class="c-btn c-btn--submit" type="submit" :disabled="sending">保存</button>
          <button class="c-btn c-btn--submit" type="button" @click="closeEventForm">閉じる</button>
        </div>
        <p v-if="errors" class="c-text--error">{{ errors.server }}</p>

        <div class="c-form__mask" :class="{'active': start_flg || end_flg}" @click="closeCalendar"></div>

      </form>
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
    },
    editEvent: {
      type: [Object, null],
      default: null
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
      edit_flg: false,
      sending: false
    }
  },
  components: {
    SmallCalendar
  },
  computed: {
    detail_length () {
      if (this.schedule.detail === null) {
        return 0
      } else {
        return this.schedule.detail.length
      }
    },
    title_length () {
      if (this.schedule.title === null) {
        return 0
      } else {
        return this.schedule.title.length
      }
    }
  },
  methods: {
    async setSchedule () {
      this.sending = true
      this.cleanErrorMessages()
      this.error_flg = false
      this.scheduleValidation()
      this.titleValidation()
      this.detailValidation()
      if (!this.error_flg) {
        const response = await axios.post('/api/schedule/register', this.schedule)

        if (response.status !== OK) {
          this.errors.server_error = 'サーバーでエラーが発生しました。'
          this.sending = false
          return false;
        }
        this.$store.commit('messages/setMessage', 'スケジュールを登録しました。')
        this.$store.dispatch('events/getScheduleList')
        this.closeEventForm()
      }
      this.sending = false
    },
    async changeSchedule () {
      this.sending = true
      this.cleanErrorMessages()
      this.error_flg = false
      this.scheduleValidation()
      this.titleValidation()
      this.detailValidation()
      if (!this.error_flg){
        const response = await axios.put('/api/schedule/changeSchedule', this.schedule)
        if (response.status === OK) {
          this.$store.dispatch('events/getScheduleList')
          this.closeEventForm()
          this.$store.commit('messages/setMessage', '予定を更新しました。')
          return false
        }
      }
      this.sending = false
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
    // カレンダーの日付マスを押した時
    if (this.clickDate) {
      this.schedule.start = this.clickDate
      this.schedule.end = this.clickDate
    // 新規作成の時
    } else {
      this.schedule.start = dayjs().format('YYYY-MM-DD')
      this.schedule.end = dayjs().format('YYYY-MM-DD')
    }
    // 詳細から編集を押した時
    if (this.editEvent) {
      this.edit_flg = true
      this.schedule = this.editEvent
    }
  }
}

</script>