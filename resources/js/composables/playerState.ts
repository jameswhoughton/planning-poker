import dayjs from "dayjs"
import { ref, Ref } from "vue"
import { Player } from "@/pages/Room.vue"

export default (function() {
    const currentTime: Ref<Date> = ref<Date>(new Date)

    return () => ({
        playerIsActive: (p: Player): boolean => dayjs(currentTime.value).diff(dayjs(p.updated_at), 'minute') < 5
    })
})()
