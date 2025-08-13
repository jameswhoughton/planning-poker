<script lang="ts">
export type Player = {
    id: string,
    name: string,
    score: number | null
    updated_at: Date
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
import VMenu from "@/components/VMenu.vue";

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
        <div class="min-h-screen flex flex-col">
            <div
                class="border-b-2 border-blue-600 py-6 px-4 flex items-center justify-between mb-6 bg-slate-200 dark:bg-slate-800">
                <a href="/">
                    <Logo class="w-12" />
                    <span class="sr-only">Home</span>
                </a>
                <div class="flex gap-6">
                    <ButtonShare />
                    <VMenu>
                        <ButtonEditName v-if="playerId !== null" :room-uuid="room.uuid" :player-id="playerId" />
                        <ButtonLeave v-if="playerId !== null" :room-uuid="room.uuid" :player-id="playerId" />
                        <ButtonDestroyRoom :room-uuid="room.uuid" @click="roomDeletedChannel.leaveChannel" />
                    </VMenu>
                </div>
            </div>
            <div class="flex flex-col justify-between grow px-4">
                <div class="flex gap-3 justify-end mb-6">
                    <VButton type="button" variant="secondary" @click="toggleScores">{{ roomData.showScores ? 'Hide' :
                        'Show' }} Scores
                    </VButton>
                    <ButtonReset :room-uuid="room.uuid" />
                </div>
                <div class="flex flex-col items-center gap-6">
                    <div class="flex gap-6 justify-center flex-wrap px-3">
                        <PlayerCard v-for="(player, i) in playerData" :key="i" :player="player"
                            :show-score="roomData.showScores || player.id === playerId"
                            class="border-slate-200 dark:border-slate-700"
                            :class="{ '!border-blue-600': player.id === playerId }" />
                    </div>
                </div>
                <div class="mt-6 border-t-2 border-blue-600 py-6 px-4 bg-slate-200 dark:bg-slate-800">
                    <PokerCards class="flex justify-center px-3" v-if="me !== undefined" :cards="cards" :player="me"
                        :room-id="room.uuid" />
                </div>
            </div>
        </div>
    </template>
</template>
