<script lang="ts">
export type Player = {
    id: string,
    name: string,
    score: number | null
    updated_at: string
}
</script>

<script setup lang="ts">
import { useEchoPublic } from "@laravel/echo-vue";
import { Ref, ref, onMounted, ComputedRef, computed } from "vue";
import ModalCreatePlayer from "@/components/Modals/ModalCreatePlayer.vue";
import PokerCards, { type Card } from "@/components/PokerCards.vue";
import PlayerCard from "@/components/PlayerCard.vue";
import ButtonShare from "@/components/ButtonShare.vue";
import ButtonReset from "@/components/ButtonReset.vue";
import ButtonDestroyRoom from "@/components/ButtonDestroyRoom.vue";
import ModalRoomDeleted from "@/components/Modals/ModalRoomDeleted.vue";
import ButtonEditName from "@/components/ButtonEditName.vue";
import { toast } from "vue3-toastify";
import ButtonLeave from "@/components/ButtonLeave.vue";
import Logo from "@/components/logo.vue";
import VMenu from "@/components/VMenu.vue";
import ButtonScores from "@/components/ButtonScores.vue";
import ModalScores from "@/components/Modals/ModalScores.vue";
import VButton from "@/components/VButton.vue";
import usePlayerState from "@/composables/playerState";
import ButtonHelp from "@/components/ButtonHelp.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faGithub } from "@fortawesome/free-brands-svg-icons";

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

const me: ComputedRef<Player | undefined> = computed<Player | undefined>(() => playersLocal.value.find((p: Player) => p.id === props.playerId))


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
        const index = playersLocal.value.findIndex((p: Player) => p.id === e.player.id)

        if (index === -1) {
            playersLocal.value.push(e.player)

            if (props.playerId && e.player.id !== props.playerId) {
                toast(`${e.player.name} has joined the room`)
            }

            return
        }

        playersLocal.value[index] = e.player
    }
)

useEchoPublic(
    `room.${props.room.id}`,
    'PlayerDeleted',
    (e: EventPlayerDeleted) => {
        const index = playersLocal.value.findIndex((p: Player) => p.id === e.player.id)

        if (index === -1) return

        const deleted = playersLocal.value.splice(index, 1)

        if (props.playerId && deleted[0].id !== props.playerId) {
            toast(`${deleted[0].name} has left`)
        }
    }
)

useEchoPublic(
    `room.${props.room.id}`,
    'RoomUpdated',
    (e: EventRoomUpdated) => {
        roomLocal.value = e.room
        playersLocal.value = e.players

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

const roomLocal: Ref<Room | null> = ref<Room | null>(null)
const playersLocal: Ref<Player[]> = ref<Player[]>([])
const roomDeleted: Ref<boolean> = ref<boolean>(false)
const showResultsModal: Ref<boolean> = ref<boolean>(false)
const hideInactive: Ref<boolean> = ref<boolean>(false)

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

const { playerIsActive } = usePlayerState()

const filteredPlayers: ComputedRef<Player[]> = computed<Player[]>(() => {
    if (!hideInactive.value) return playersLocal.value

    return playersLocal.value.filter((p: Player) => playerIsActive(p))
})

const otherPlayers: ComputedRef<Player[]> = computed<Player[]>(
    () => playersLocal.value.filter((p: Player) => p.id !== me.value?.id)
)

onMounted(() => {
    roomLocal.value = props.room
    playersLocal.value = props.players
})
</script>

<template>
    <ModalRoomDeleted v-if="roomDeleted" />
    <template v-if="roomLocal !== null">
        <ModalCreatePlayer v-if="playerId === null" :room-uuid="room.uuid"
            :joinable="playersLocal.length < room.playerLimit" />
        <div class="min-h-screen flex flex-col">
            <div
                class="border-b-2 border-blue-600 py-6 px-4 flex items-center justify-between mb-6 bg-slate-100 dark:bg-slate-800">
                <a href="/">
                    <Logo class="w-12" />
                    <span class="sr-only">Home</span>
                </a>
                <div class="flex gap-6 items-center">
                    <a href="https://github.com/jameswhoughton/planning-poker/" target="_blank">
                        <FontAwesomeIcon :icon="faGithub" size="2xl" />
                        <span class="sr-only">Project repository</span>
                    </a>
                    <ButtonShare />
                    <VMenu>
                        <ButtonEditName v-if="playerId !== null" :room-uuid="room.uuid" :player-id="playerId" />
                        <ButtonLeave v-if="playerId !== null" :room-uuid="room.uuid" :player-id="playerId" />
                        <ButtonDestroyRoom :room-uuid="room.uuid" @click="roomDeletedChannel.leaveChannel" />
                    </VMenu>
                </div>
            </div>
            <div class="flex flex-col justify-between grow">
                <div class="flex gap-3 justify-end mb-6 flex-wrap px-4">
                    <ButtonScores :room-uuid="room.uuid" :show-scores="roomLocal.showScores" />
                    <VButton v-show="roomLocal.showScores" variant="secondary" @click="showResultsModal = true">Results
                    </VButton>
                    <ButtonReset :room-uuid="room.uuid" />
                    <VButton @click="hideInactive = !hideInactive" variant="secondary">{{ hideInactive ? 'Show' : 'Hide'
                        }} Inactive</VButton>
                    <ButtonHelp />
                    <ModalScores v-model="showResultsModal" :players="filteredPlayers" />
                </div>
                <div class="flex flex-col items-center gap-6 px-4">
                    <div v-if="filteredPlayers.length === 0" class="flex justify-center items-center">
                        <h2 class="text-3xl">No active players</h2>
                    </div>
                    <div v-else class="flex gap-6 justify-center px-3 overflow-x-scroll max-w-full">
                        <PlayerCard v-for="(player, i) in otherPlayers" :key="i" :player="player"
                            :show-score="roomLocal.showScores" class="border-slate-400 dark:border-slate-700" />
                    </div>
                    <div v-if="me !== undefined && filteredPlayers.length > 0" class="flex justify-center px-3">
                        <PlayerCard :player="me" :show-score="true" class="border-blue-600" />
                    </div>
                </div>
                <div class="mt-6 border-t-2 border-blue-600 py-6 px-4 bg-slate-100 dark:bg-slate-800">
                    <PokerCards class="flex justify-center px-3" v-if="me !== undefined" :cards="cards" :player="me"
                        :room-id="room.uuid" />
                </div>
            </div>
        </div>
    </template>
</template>
