<template>
  <div class="c-calendar--small" :class="{'left': start_flg}">
    <div class="c-calendar__header--input">
      <i class="fa-solid fa-caret-left c-icon--forward" @click="prevMonth"></i>
      <span>{{ currentMonth }}</span>
      <i class="fa-solid fa-caret-right c-icon--forward" @click="nextMonth"></i>
    </div>
    <ul class="c-calendar__dotw--input">
      <li v-for="n in 7" :key="n" class="c-calendar__dotw-name c-calendar__dotw-name--small">{{ youbi(n - 1) }}</li>
    </ul>
    <div v-for="(week, index) in calendars" :key="index" class="c-calendar__row--input">
      <div v-for="(day, index) in week" :key="index" class="c-calendar__day--input" @click="sendDate(day.date)">
        <div class="c-calendar__day--inner"
        :class="[{'c-calendar__outer-month': currentDate.month() !== day.month}, 
        {'active': (start_flg && start === day.date) || (end_flg && end === day.date)}]">{{ day.day }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import dayjs from 'dayjs'
export default {
  props: {
    start_flg: {
      Boolean,
      default: false
    },
    end_flg: {
      Boolean,
      default: false
    },
    start: null,
    end: null,
    clickDate: {
      String
    }
  },
  data () {
    return {
      calendar: [],
      currentDate: null,
    }
  },
  computed: {
    calendars () {
      return this.getCalendar();
    },
    currentMonth () {
      return this.currentDate.format('YYYY年MM月')
    },
  },
  methods: {
    getCalendar () {
      let startDate = this.getStartDate() // カレンダーの一番最初の日
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
    getStartDate () { // カレンダーの最初の日付を取得する。
      let date = dayjs(this.currentDate) // dayjsオブジェクト
      const dayOfTheWeek = date.startOf('month').day() // 当月の初日の曜日を取得
      return date.startOf('month').subtract(dayOfTheWeek, 'day')
    },
    getEndDate () {
      let date =dayjs(this.currentDate)
      const dayOfTheWeek = date.endOf('month').day()
      return date.endOf('month').add(6 - dayOfTheWeek, 'day')
    },
    youbi(dayIndex) {
      const week = ['日', '月', '火', '水', '木', '金', '土']
      return week[dayIndex]
    },
    prevMonth () {
      this.currentDate = dayjs(this.currentDate).subtract(1, 'month')
    },
    nextMonth () {
      this.currentDate = dayjs(this.currentDate).add(1, 'month')
    },
    sendDate (date) {
      this.$emit('get-date', date)
      this.closeCalendar()
    },
    closeCalendar () {
      this.$emit('close-calendar')
    }
  },
  created () {
    this.currentDate = dayjs(this.clickDate)
  }
}
</script>