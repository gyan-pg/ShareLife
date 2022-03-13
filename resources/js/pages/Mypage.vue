<template>
  <div>
    <Calendar v-if="checkTeam"/>
    <Invitation v-if="!checkTeam"/>
  </div>
</template>

<script>
import Calendar from '../components/Calendar.vue'
import Invitation from '../components/Invitation.vue'
export default {
  components: {
    Calendar,
    Invitation
  },
  computed: {
    checkTeam () {
      return this.$store.getters['auth/checkTeam']
    }
  }
}
</script>