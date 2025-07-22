<script setup lang="ts">
import { useEchoPublic } from "@laravel/echo-vue";
import { computed, ComputedRef, Ref, ref, onMounted } from "vue";
import ModalCreatePlayer from "@/components/Modals/ModalCreatePlayer.vue";

type Player = {
    id: string,
    name: string,
    score: number
}

type Room = {
    id: string,
    uuid: string,
    players: Player[],
}

const props = defineProps<{
    room: Room,
    playerId: string | null,
}>()


type EventPlayerCreatedUpdated = {
    player: {
        id: string,
        name: string,
    },
}

useEchoPublic(
    `room.${props.room.id}`,
    'PlayerCreatedUpdated',
    (e: EventPlayerCreatedUpdated) => {
        if (e.player.id === props.playerId) return

        const index = players.value.findIndex((p: Player) => p.id === e.player.id)

        if (index === -1) {
            players.value.push({
                ...e.player,

                score: 0,
            })

            return
        }

        players.value[index].name = e.player.name
    }
)

const players: Ref<Player[]> = ref<Player[]>([])

const me: ComputedRef<Player | undefined> = computed<Player | undefined>(() => props.room.players.find((p: Player) => p.id === props.playerId))

onMounted(() => players.value = props.room.players)
</script>

<template>
    <ModalCreatePlayer v-if="playerId === null" :room-uuid="room.uuid" />
    <ul>
        <li v-for="(player, i) in players" :key="i">{{ player.name }}</li>
    </ul>
</template>
