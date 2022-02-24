<template>
  <div>
    <p>calendar component</p>
    <div class="c-calendar">
      <h2>{{ currentMonth }}</h2>
      <button @click="prevMonth" type="button">前の月</button>
      <button @click="nextMonth" type="button">次の月</button>
      <!-- カレンダーの曜日 -->
      <ul class="c-calendar__dotw">
        <li v-for="n in 7" :key="n" class="c-calendar__dotw-name">{{ youbi(n - 1) }}</li>
      </ul>
      <!-- カレンダー本体 -->
      <div v-for="(week, index) in calendars" :key="index" class="c-calendar__row">
        <!-- 日付 -->
        <div v-for="(day, index) in week" :key="index" class="c-calendar__day"
        :class="{'c-calendar__outer-month': currentDate.month() !== day.month}"
        @drop="dragEnd($event, day.day)" @dragover.prevent>
          <div>{{ day.date }}</div>
          <!-- 予定 -->
          <div v-for="dayEvent in day.dayEvents" :key="dayEvent.id">
            <div v-if="dayEvent.width" class="c-calendar__event"
            :style="`width:${dayEvent.width}%;background-color: ${dayEvent.color}`"
            draggable="true" @dragstart="dragStart($event, dayEvent.id)"><!-- $eventはDOMイベントと呼ばれる -->
              {{ dayEvent.name }}
            </div>
            <div v-else style="height:26px"></div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import dayjs from 'dayjs'
export default {
  data () {
    return {
      currentDate: dayjs(),
      events: [
        { id: 1, name: "ミーティング", start: "2022-02-01", end:"2022-02-01", color:"blue"},
        { id: 2, name: "イベント", start: "2022-02-02", end:"2022-02-03", color:"limegreen"},
        { id: 3, name: "会議", start: "2022-02-06", end:"2022-02-06", color:"deepskyblue"},
        { id: 4, name: "有給", start: "2022-02-08", end:"2022-02-08", color:"dimgray"},
        { id: 5, name: "海外旅行", start: "2022-02-08", end:"2022-02-16", color:"navy"},
        { id: 6, name: "誕生日", start: "2022-02-16", end:"2022-02-16", color:"orange"},
        { id: 7, name: "イベント", start: "2022-02-12", end:"2022-02-15", color:"limegreen"},
        { id: 8, name: "出張", start: "2022-02-12", end:"2022-02-13", color:"teal"},
        { id: 9, name: "客先訪問", start: "2022-02-14", end:"2022-02-14", color:"red"},
        { id: 10, name: "パーティ", start: "2022-02-15", end:"2022-02-15", color:"royalblue"},
        { id: 12, name: "ミーティング", start: "2022-02-18", end:"2022-02-19", color:"blue"},
        { id: 13, name: "イベント", start: "2022-02-21", end:"2022-02-21", color:"limegreen"},
        { id: 14, name: "有給", start: "2022-02-20", end:"2022-02-22", color:"dimgray"},
        { id: 15, name: "イベント", start: "2022-02-25", end:"2022-02-28", color:"limegreen"},
        { id: 16, name: "会議", start: "2022-02-21", end:"2022-02-21", color:"deepskyblue"},
        { id: 17, name: "旅行", start: "2022-02-23", end:"2022-02-24", color:"navy"},
        { id: 18, name: "ミーティング", start: "2022-02-28", end:"2022-02-28", color:"blue"},
        { id: 19, name: "会議", start: "2022-02-12", end:"2022-02-12", color:"deepskyblue"},
      ]
    }
  },
  computed: {
    calendars () {
      return this.getCalendar();
    },
    currentMonth () {
      return this.currentDate.format('YYYY[年]M[月]')
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
      const endDate = this.getEndDate()
      const weekNum = Math.ceil(endDate.diff(startDate, 'day') / 7)
      let calendars = []
      for (let week = 0; week < weekNum; week++) {
        let weekRow = []
        for (let day = 0; day < 7; day++) {
          let dayEvents = this.getDayEvents(startDate, day) // startDateは処理する日、dayは曜日
          weekRow.push({
            day: startDate.format('YYYY-MM-DD'),
            date: startDate.date(), // 日を取得
            month: startDate.month(),
            dayEvents: dayEvents
          })
          startDate = startDate.add(1, 'day')
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
      let dayEvents = []
      let startedEvents = [] // すでに開始されているイベントを保持
      this.sortedEvents().forEach(event => {
        let startDate = dayjs(event.start).format('YYYY-MM-DD')
        let endDate = dayjs(event.end).format('YYYY-MM-DD')
        let Date = date.format('YYYY-MM-DD')
        // イベントの開始日から終了日の間の場合
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
    getEventWidth (start, end, day) {
      let betweenDays = dayjs(end).diff(dayjs(start), 'day')
      if (betweenDays > 6 - day) {
        return (6 - day) * 100 + 95
      } else {
        return betweenDays * 100 + 95
      }
    },

    getStackEvents(event, day, stackIndex, dayEvents, startedEvents, start){
      [stackIndex, dayEvents] = this.getStartedEvents(stackIndex, startedEvents, dayEvents)
      let width = this.getEventWidth(start, event.end, day)
      Object.assign(event,{
        stackIndex
      })
      dayEvents.push({...event, width})
      stackIndex++;
      return [stackIndex,dayEvents];
    },

    getStartedEvents(stackIndex, startedEvents, dayEvents){
      let startedEvent
        do{
          startedEvent = startedEvents.find(event => event.stackIndex === stackIndex)
          if(startedEvent) {
            dayEvents.push(startedEvent) //ダミー領域として利用するため
            stackIndex++;
          }
        }while(typeof startedEvent !== 'undefined')
      return [stackIndex, dayEvents]
    },
    // 昇順に並び替え。DBでソートする場合はいらない。
    sortedEvents () {
      return this.events.slice().sort(function (a,b) {
        return (a.start < b.start) ? -1 : 1
      })
    },
    // ドラッグイベント
    // html側の$eventでDOMイベント、第二引数でdayEventのidを送っている
    dragStart (event, eventId) {
      event.dataTransfer.effectAllowed = 'move'
      event.dataTransfer.dropEffect = 'move'
      event.dataTransfer.setData('eventId', eventId)
    },
    dragEnd (event, day) {
      let eventId = event.dataTransfer.getData('eventId')
      let dragEvent = this.events.find(event => event.id === parseInt(eventId))
      let betweenDays = dayjs(dragEvent.end).diff(dayjs(dragEvent.start), 'day')
      dragEvent.start = day
      dragEvent.end = dayjs(dragEvent.start).add(betweenDays, 'day').format('YYYY-MM-DD')
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
</script>