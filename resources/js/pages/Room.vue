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
import axios from "axios";
import ButtonShare from "@/components/ButtonShare.vue";
import ButtonReset from "@/components/ButtonReset.vue";
import ButtonDestroyRoom from "@/components/ButtonDestroyRoom.vue";
import ModalRoomDeleted from "@/components/Modals/ModalRoomDeleted.vue";
import ButtonEditName from "@/components/ButtonEditName.vue";
import { toast } from "vue3-toastify";
import ButtonLeave from "@/components/ButtonLeave.vue";
import Logo from "@/components/logo.vue";

type Room = {
    id: string,
    uuid: string,
    showScores: boolean,
    players: Player[],
    playerLimit: number,
}

const props = defineProps<{
    room: Room,
    players: Player[],
    playerId: string | null,
}>()

const me: ComputedRef<Player | undefined> = computed<Player | undefined>(() => playerData.value.find((p: Player) => p.id === props.playerId))


type EventPlayerCreatedUpdated = {
    player: Player,
}

type EventPlayerDeleted = EventPlayerCreatedUpdated

type EventRoomUpdated = {
    room: Room,
    players: Player[],
    message: string,
}

useEchoPublic(
    `room.${props.room.id}`,
    'PlayerCreatedUpdated',
    (e: EventPlayerCreatedUpdated) => {
        const index = playerData.value.findIndex((p: Player) => p.id === e.player.id)

        if (index === -1) {
            playerData.value.push(e.player)

            if (props.playerId && e.player.id !== props.playerId) {
                toast(`${e.player.name} has joined the room`)
            }

            return
        }

        playerData.value[index] = e.player
    }
)

useEchoPublic(
    `room.${props.room.id}`,
    'PlayerDeleted',
    (e: EventPlayerDeleted) => {
        const index = playerData.value.findIndex((p: Player) => p.id === e.player.id)

        if (index === -1) return

        const deleted = playerData.value.splice(index, 1)

        if (props.playerId && deleted[0].id !== props.playerId) {
            toast(`${deleted[0].name} has left`)
        }
    }
)

useEchoPublic(
    `room.${props.room.id}`,
    'RoomUpdated',
    (e: EventRoomUpdated) => {
        roomData.value = e.room
        playerData.value = e.players

        if (e.message !== '') {
            toast(e.message)
        }
    }
)

const roomDeletedChannel = useEchoPublic(
    `room.${props.room.id}`,
    'RoomDeleted',
    () => roomDeleted.value = true
)

const roomData: Ref<Room | null> = ref<Room | null>(null)
const playerData: Ref<Player[]> = ref<Player[]>([])
const roomDeleted: Ref<boolean> = ref<boolean>(false)

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
    {
        label: '8',
        value: 8,
    },
    {
        label: '13',
        value: 13,
    },
    {
        label: '20',
        value: 20,
    },
    {
        label: '40',
        value: 40,
    },
    {
        label: '100',
        value: 100,
    },
]

function toggleScores(): void {
    axios.patch(`/api/room/${props.room.uuid}`, {
        showScores: !roomData.value?.showScores,
    })
}

onMounted(() => {
    roomData.value = props.room
    playerData.value = props.players
})
</script>

<template>
    <ModalRoomDeleted v-if="roomDeleted" />
    <template v-if="roomData !== null">
        <ModalCreatePlayer v-if="playerId === null" :room-uuid="room.uuid"
            :joinable="playerData.length < room.playerLimit" />
        <div class="flex flex-col h-screen justify-between">
            <div
                class="border-b border-purple-600 py-6 px-4 flex items-center justify-between mb-6 bg-slate-200 dark:bg-slate-800">
                <a href="/">
                    <Logo class="w-12" />
                </a>
                <div class="flex items-center gap-4">
                    <ButtonShare />
                    <ButtonEditName v-if="playerId !== null" :room-uuid="room.uuid" :player-id="playerId" />
                    <ButtonLeave v-if="playerId !== null" :room-uuid="room.uuid" :player-id="playerId" />
                    <ButtonDestroyRoom :room-uuid="room.uuid" @click="roomDeletedChannel.leaveChannel" />
                </div>
            </div>
            <div class="flex flex-col items-center gap-6">
                <div class="flex gap-6 justify-center flex-wrap mb-6 px-3">
                    <PlayerCard v-for="(player, i) in playerData" :key="i" :player="player"
                        :show-score="roomData.showScores || player.id === playerId"
                        :class="{ 'border-purple-600': player.id === playerId }" />
                </div>
                <div class="flex gap-3">
                    <VButton type="button" @click="toggleScores">{{ roomData.showScores ? 'Hide' : 'Show' }} Scores
                    </VButton>
                    <ButtonReset :room-uuid="room.uuid" />
                </div>
            </div>
            <PokerCards class="mt-3 flex justify-center mb-6 px-3" v-if="me !== undefined" :cards="cards" :player="me"
                :room-id="room.uuid" />
        </div>
    </template>
</template>
