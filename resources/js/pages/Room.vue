<script lang="ts">
export type Player = {
    id: string,
    name: string,
    score: number | null
}
</script>

<script setup lang="ts">
import { useEchoPublic } from "@laravel/echo-vue";
import { Ref, ref, onMounted, ComputedRef, computed } from "vue";
import ModalCreatePlayer from "@/components/Modals/ModalCreatePlayer.vue";
import VButton from "@/components/VButton.vue";
import PokerCards, { type Card } from "@/components/PokerCards.vue";
import PlayerCard from "@/components/PlayerCard.vue";
import { router } from '@inertiajs/vue3';

type Room = {
    id: string,
    uuid: string,
    showScore: boolean,
    players: Player[],
}

const props = defineProps<{
    room: Room,
    playerId: string | null,
}>()

const me: ComputedRef<Player | undefined> = computed<Player | undefined>(() => players.value.find((p: Player) => p.id === props.playerId))


type EventPlayerCreatedUpdated = {
    player: Player,
}

type EventPlayerDeleted = EventPlayerCreatedUpdated

useEchoPublic(
    `room.${props.room.id}`,
    'PlayerCreatedUpdated',
    (e: EventPlayerCreatedUpdated) => {
        const index = players.value.findIndex((p: Player) => p.id === e.player.id)

        if (index === -1) {
            players.value.push(e.player)

            return
        }

        players.value[index] = e.player
    }
)

useEchoPublic(
    `room.${props.room.id}`,
    'PlayerDeleted',
    (e: EventPlayerDeleted) => {
        const index = players.value.findIndex((p: Player) => p.id === e.player.id)

        if (index === -1) return

        const deleted = players.value.splice(index, 1)

        console.log(`player ${deleted[0].name} has left`)
    }
)

const players: Ref<Player[]> = ref<Player[]>([])

const cards: Card[] = [
    {
        label: '1',
        value: 1,
    },
    {
        label: '2',
        value: 2,
    },
    {
        label: '3',
        value: 3,
    },
    {
        label: '5',
        value: 5,
    },
]

onMounted(() => players.value = props.room.players)
</script>

<template>
    <ModalCreatePlayer v-if="playerId === null" :room-uuid="room.uuid" />
    <div class="flex flex-col h-screen justify-between">
        <div class="border-b border-purple-600 py-6 px-4 flex items-center justify-between mb-6">
            <h1 class="text-3xl">Planning Poker</h1>
            <form @submit.prevent="router.delete(`/room/${room.uuid}/player/${playerId}`)">
                <VButton type="submit">Leave</VButton>
            </form>
        </div>
        <div class="flex gap-6 justify-center flex-wrap mb-6 px-3">
            <PlayerCard v-for="(player, i) in players" :key="i" :player="player"
                :show-score="room.showScore || player.id === playerId"
                :class="{ 'border-purple-600': player.id === playerId }" />
        </div>
        <PokerCards class="mt-3 flex justify-center mb-6 px-3" v-if="me !== undefined" :cards="cards" :player="me"
            :room-id="room.uuid" />
    </div>
</template>
