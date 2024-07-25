<template>
    <div class="basis-2/6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">Conversations</div>

            <div
                v-for="conversation in conversations"
                :key="conversation.id"
                class="p-6 border-b border-gray-200 cursor-pointer hover:bg-gray-100 transition duration-150 ease-in-out"
                @click="() => fetchConversation(conversation.id)"
            >
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path d="M10 12a5 5 0 019.9-1" />
                        <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-800">{{ conversation.question.length > 30 ? `${conversation.question.substring(0, 30)}...` : conversation.question }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
    conversations: {
        type: Array,
    },
});

const fetchConversation = async (id) => {
    router.get(`/chat/${id}`, {
        preserveState: true,
    });
};
</script>
