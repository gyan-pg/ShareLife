<template>
<!-- event form -->
  <section class="p-wrapper--modal">
    <div class="p-wrapper--auth">
      <p v-if="errors.server_error" class="c-text--error u-tc">{{ errors.server_error }}</p>
      <form class="c-form" @submit.prevent="setSchedule">
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
        <!-- 日時を選択する -->
        <div class="p-container--form-select-date">
          <div>
            <span>開始日</span><span>{{ formatDayStr(schedule.start) }}</span>
            <button class="c-btn" :class="{'active': start_flg }" type="button" @click="openStartForm">編集する</button>
          </div>
          <div>
            <span>終了日</span><span>{{ formatDayStr(schedule.end) }}</span>
            <button class="c-btn" :class="{'active': end_flg }" type="button" @click="openCloseForm">編集する</button>
          </div>
          <!-- エラー表示 -->
          <p v-if="errors.schedule" class="c-text--error">{{ errors.schedule }}</p>

          <!-- 日時選択用カレンダー -->
          <div class="c-calendar--input" v-if="open_calendar">
            <div class="c-calendar__header--input">
              <i class="fa-solid fa-arrow-left-long" @click="prevMonth"></i>
              <span>{{ currentMonth }}</span>
              <i class="fa-solid fa-arrow-right-long" @click="nextMonth"></i>
            </div>
            <ul class="c-calendar__dotw--input">
              <li v-for="n in 7" :key="n" class="c-calendar__dotw-name">{{ youbi(n - 1) }}</li>
            </ul>
            <div v-for="(week, index) in calendars" :key="index" class="c-calendar__row--input">
              <div v-for="(day, index) in week" :key="index" class="c-calendar__day--input"
              :class="[{'c-calendar__outer-month': currentDate.month() !== day.month}, 
              {'active': (start_flg && schedule.start === day.date) || (end_flg && schedule.end === day.date)}]" 
              @click="getDate(day.date)">
                {{ day.day }}
              </div>
            </div>
            <button class="c-btn" type="button" @click="closeCalendar">閉じる</button>
          </div>
        </div>

        <!-- 色選択 -->
        <label for="agreement-detail">color</label>
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

        <button class="c-btn c-btn--submit" type="submit">submit</button>
        <p v-if="errors" class="c-text--error">{{ errors.server }}</p>
      </form>
      <i class="fa-solid fa-xmark c-icon c-icon--modal-close" @click="closeEventForm"></i>
    </div>
  </section>
</template>

<script>
import dayjs from 'dayjs'
import {OK, UNPROCESSABLE_ENTITY, INTERNAL_SERVER_ERROR} from '../util'
export default {
  data () {
    return {
      schedule: {
        title: '',
        detail: '',
        start: dayjs().format('YYYY-MM-DD'),
        end: dayjs().format('YYYY-MM-DD'),
        color: '#ff9100',
      },
      errors: {
        title: null,
        detail: null,
        schedule: null,
        color: null,
        server_error: ''
      },
      calendar: [],
      currentDate: dayjs(),
      start_flg: false,
      end_flg: false,
      open_calendar: false,
      error_flg: false,
    }
  },
  computed: {
    calendars () {
      return this.getCalendar();
    },
    currentMonth () {
      return this.currentDate.format('YYYY年MM月')
    },
    startSchedule () {
      return this.start.format('YYYY年MM月DD日')
    },
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
    getCalendar () {
      let startDate = this.getStartDate(this.currentDate) // カレンダーの一番最初の日
      const endDate = this.getEndDate() // カレンダーの最後の日
      const weekNum = Math.ceil(endDate.diff(startDate, 'day') / 7) // カレンダーの行数
      let calendars = []
      // カレンダーの情報を作るループ
      // 1週間の配列
      for (let week = 0; week < weekNum; week++) {
        let weekRow = []
        // 各日の情報を詰める
        for (let day = 0; day < 7; day++) { // 0が日曜日、6が金曜日
          weekRow.push({
            date: startDate.format('YYYY-MM-DD'),
            day: startDate.date(), // 日を取得
            month: startDate.month(),
          })
          startDate = startDate.add(1, 'day') // 次の日の情報を詰める
        }
        calendars.push(weekRow);
      }
      console.log(calendars)
      return calendars
    },
    youbi(dayIndex) {
      const week = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
      return week[dayIndex]
    },
    prevMonth () {
      this.currentDate = dayjs(this.currentDate).subtract(1, 'month')
    },
    nextMonth () {
      this.currentDate = dayjs(this.currentDate).add(1, 'month')
    },
    getDate (date) {
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
    }
  }
}

</script>