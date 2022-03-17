<template>
  <div class="c-calendar">
    <div class="c-calendar__head-container">
      <h2 class="c-calendar__current-date">{{ currentMonth }}</h2>
      <i @click="prevMonth" class="fa-solid fa-caret-left c-icon--forward"></i>
      <i @click="nextMonth" class="fa-solid fa-caret-right c-icon--forward"></i>
      <i @click="openForm()" class="fa-solid fa-plus c-icon--forward"></i>
    </div>
    <!-- カレンダーの曜日 -->
    <ul class="c-calendar__dotw">
      <li v-for="n in 7" :key="n" class="c-calendar__dotw-name">{{ youbi(n - 1) }}</li>
    </ul>
    <!-- カレンダー本体 -->
    <div class="c-calendar__body">
      <div v-for="(week, index) in calendars" :key="index" class="c-calendar__row" :style="{height: rowHeight+'%'}">
        <!-- 日付 -->
        <div v-for="(day, index) in week" :key="index" class="c-calendar__date-container"
        :class="{'c-calendar__outer-month': currentDate.month() !== day.month}"
        @drop="dragEnd($event, day.date)" @dragover.prevent @click.self="openForm(day.date)">
          <div class="c-calendar__date">
            <span class="c-calendar__day c-text--calendar" :class="{'today': today === day.date}">{{ day.day }}</span>
            <span v-if="holidays[day.date]" class="c-calendar__holiday c-text--calendar">{{holidays[day.date]}}</span>
            <div style="width: 26px; height: 26px"></div>
          </div>
          <!-- 予定の表示 -->
          <div v-for="dayEvent in day.dayEvents" :key="dayEvent.id">
            <div v-if="dayEvent.width" class="c-calendar__event"
            :style="`width:${dayEvent.width}%;background-color: ${dayEvent.color}`"
            draggable="true" @dragstart="dragStart($event, dayEvent.id)" @click="eventDetail(dayEvent)"><!-- $eventはDOMイベントと呼ばれる -->
              {{ formatTitle(dayEvent) }}
            </div>
            <div v-else style="height:23px;"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- イベント入力フォーム -->
    <transition name="fade">
      <EventForm v-if="form_flg" @close-form="closeEventForm" :clickDate="clickDate"/>
    </transition>
    <!-- イベント詳細 -->
    <transition name="fade">
      <EventDetail v-if="detail_flg" @close-detail="closeDetail" :clickEvent="clickEvent"/>
    </transition>
  </div>
</template>

<script>
import dayjs from 'dayjs'
import EventForm from './EventForm.vue'
import EventDetail from './EventDetail.vue'
export default {
  data () {
    return {
      currentDate: dayjs(),
      currentYear: null, // apiで取得できる祝日の年数に限りがあるため、当日の年を記録しておく。
      changeYear: null, // カレンダーを進めていって、年が変更になった場合に代入
      today: null,
      clickDate: null,
      clickEvent: null,
      form_flg: false,
      detail_flg: false,
      calendar_row_num: null,
    }
  },
  components: {
    EventForm,
    EventDetail
  },
  computed: {
    calendars () {
      return this.getCalendar();
    },
    currentMonth () {
      return this.currentDate.format('YYYY[年]M[月]')
    },
    events () {
      return this.$store.state.events.event
    },
    holidays () {
      return this.$store.state.events.holiday
    },
    rowHeight () {
      return 100 / this.calendar_row_num
    }
  },
  methods: {
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
      this.calendar_row_num = weekNum
      let calendars = []
      // カレンダーの情報を作るループ
      // 1週間の配列
      for (let week = 0; week < weekNum; week++) {
        let weekRow = []
        // 各日の情報を詰める
        for (let day = 0; day < 7; day++) { // 0が日曜日、6が金曜日
          let dayEvents = this.getDayEvents(startDate, day) // startDateは処理する日、dayは曜日
          weekRow.push({
            date: startDate.format('YYYY-MM-DD'),
            day: startDate.date(), // 日を取得
            month: startDate.month(),
            dayEvents: dayEvents
          })
          startDate = startDate.add(1, 'day') // 次の日の情報を詰める
        }
        calendars.push(weekRow);
      }
      return calendars
    },
    prevMonth () {
      this.currentDate = dayjs(this.currentDate).subtract(1, 'month')
    },
    nextMonth () {
      this.currentDate = dayjs(this.currentDate).add(1, 'month')
    },
    youbi(dayIndex) {
      const week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
      return week[dayIndex]
    },
    // カレンダーの各日にイベントをセット。
    // dateはイベントをセットする日。
    // dateが一日更新される毎に全てのイベントの中身をチェックしている。
    // イベントの開始と終了の期間にdateが含まれている場合、
    // そのイベントをその日のイベント情報に含める。
    getDayEvents(date, day) {
      let stackIndex = 0 // その日のイベントの番号
      let dayEvents = [] // その日のイベント情報を詰める変数
      let startedEvents = [] // すでに開始されているイベントを保持
      this.events.forEach(event => {
        let startDate = dayjs(event.start).format('YYYY-MM-DD') // イベントがスタートする日
        let endDate = dayjs(event.end).format('YYYY-MM-DD') // イベントが終わる日
        let Date = date.format('YYYY-MM-DD') // イベントの情報を確認する日
        // イベント情報を確認する日が、イベントの開始日から終了日の間の場合
        if(startDate <= Date && endDate >= Date) {
          if (startDate === Date) {
            [stackIndex, dayEvents] = this.getStackEvents(event, day, stackIndex, dayEvents, startedEvents, event.start)
          } else if (day === 0) {
            [stackIndex, dayEvents] = this.getStackEvents(event, day, stackIndex, dayEvents, startedEvents, Date)
          } else {
            startedEvents.push(event)
          }
        }
      })
      return dayEvents
    },
    getStackEvents(event, day, stackIndex, dayEvents, startedEvents, start){
      [stackIndex, dayEvents] = this.getStartedEvents(stackIndex, startedEvents, dayEvents)
      let width = this.getEventWidth(start, event.end, day)
      // event Object にstackindexを追加している
      // stackIndexはただの数値だが、{}で囲うことでstackIndexというプロパティで中の数値が追加される。
      Object.assign(event, { stackIndex })
      dayEvents.push({...event, width})
      stackIndex++;
      return [stackIndex,dayEvents];
    },
    getStartedEvents(stackIndex, startedEvents, dayEvents){
      let startedEvent
        do{
          startedEvent = startedEvents.find(event => event.stackIndex === stackIndex)
          if(startedEvent) {
            dayEvents.push(startedEvent) // ダミー領域として利用するため
            stackIndex++;
          }
        }while(typeof startedEvent !== 'undefined')
      return [stackIndex, dayEvents]
    },
    getEventWidth (start, end, day) {
      let betweenDays = dayjs(end).diff(dayjs(start), 'day') // startとendの差分の日数
      // 1週間以上の予定の場合、カレンダーの土曜日までラインを伸ばす
      if (betweenDays > 6){
        return (6 - day) * 100 + 98
      }
      if (betweenDays > 6 - day) {
        return (6 - day) * 100 + 98
      } else {
        return betweenDays * 100 + 98
      }
    },
    // ドラッグイベント
    // html側の$eventでDOMイベント、第二引数でdayEventのidを送っている
    dragStart (event, eventId) {
      event.dataTransfer.effectAllowed = 'move'
      event.dataTransfer.dropEffect = 'move'
      event.dataTransfer.setData('eventId', eventId)
    },
    // dateは移動先の日付
    dragEnd (event, date) {
      let eventId = event.dataTransfer.getData('eventId')
      let changeData = {'id': eventId, 'start': date}
      this.$store.dispatch('events/changeScheduleList', changeData)

      // let eventId = event.dataTransfer.getData('eventId')
      // let dragEvent = this.events.find(event => event.id === parseInt(eventId))
      // let betweenDays = dayjs(dragEvent.end).diff(dayjs(dragEvent.start), 'day')
      // dragEvent.start = day
      // dragEvent.end = dayjs(dragEvent.start).add(betweenDays, 'day').format('YYYY-MM-DD')
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
    openForm (date = dayjs().format('YYYY-MM-DD')) {
      this.form_flg = true
      this.clickDate = date
    },
    closeEventForm () {
      this.form_flg = false
    },
    closeDetail () {
      this.detail_flg = false
    },
    getHoliday () {
      this.$store.dispatch('events/getHolidayList', this.currentDate.year())
    }
  },
  created () {
    this.$store.dispatch('events/getScheduleList')
    this.today = this.currentDate.format('YYYY-MM-DD')
    this.currentYear = this.changeYear = dayjs().year()
    this.getHoliday()
  },
  watch: {
    currentDate (newValue) {
      if (newValue.year() !== this.changeYear) {
        if (Math.abs(newValue.year() - this.currentYear) > 1) {// apiでは翌年、去年の祝日までしか取得できないので、それ以上の期間の場合apiからデータを取得しない。
          return false
        }
        this.getHoliday()
        this.changeYear = newValue.year()
      }
    }
  }
}
/*
day.jsの簡単な使い方
dayjs()でdayjsを使用できる。dayjs()のみだと、現在日時を返す。
以下はdayjs()につなぐメソッド。
.day() : 曜日を取得
.startOf('month or year or week') : 当月や今年、今週の最初の日付を取得できる。なお、日曜日が始まりです。
.endOf('month or year or week') : 上記と同様。最後の日付を取得できる。
.subtract('num', 'day or week or month or year') : num(day/week/month/year)だけ日付を遡った値を返す。
日付などの差分(diff)の使い方
dateFrom.diff(dateTo, 'day') : dateFromからdateToまで何日だったか取得できる。monthやhour、dayも可能。
*/
/*
カレンダー中の変数
date: カレンダーの日付データ[yyyy-mm-dd]
day: カレンダーの日データ[d]
month: 月[m] 月は実際の月からマイナス１された値が入っている。
*/
</script>