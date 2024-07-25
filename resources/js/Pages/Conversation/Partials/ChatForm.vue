<template>
    <div class="basis-4/6 mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form class="m-6 space-y-6" @submit.prevent="submit()">
                <div v-if="!current_chat">
                    <div>
                        <InputLabel for="question" value="Question" />

                        <TextInput id="question" v-model="form.question" autocomplete="question" autofocus
                            class="mt-1 block w-full" required type="text" :disabled="current_chat" />

                        <InputError :message="form.errors.question" class="mt-2" />
                    </div>

                    <div class="py-5">
                        <InputLabel for="modifier" value="Modifier" />

                        <TextInput id="modifier" v-model="form.modifier" autocomplete="modifier" autofocus
                            class="mt-1 block w-full" required type="text" :disabled="current_chat" />

                        <InputError :message="form.errors.modifier" class="mt-2" />
                    </div>
                </div>

                <p v-for="message in current_chat?.messages" :key="message.id"
                    :class="message.role === 'user' ? ['bg-green-100', 'flex-row-reverse'] : ['bg-gray-100', 'flex-row']"
                    class="my-4 p-2 rounded-md flex">
                <div v-if="message.role === 'assistant'">
                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z">
                        </path>
                    </svg>
                </div>
                <div v-if="message.role === 'user'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <p class="text-sm mx-2">{{ message.message }}</p>
                </p>

                <div class="flex items-center gap-4" v-if="!current_chat || current_chat.messages.length < 4">
                    <PrimaryButton v-if="!form.processing && !current_chat?.messages?.length">Send</PrimaryButton>
                    <PrimaryButton :disabled="true"
                        v-if="form.processing || (current_chat && current_chat.messages.length < 4)">Processing...
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const current_chat = ref(page.props.current_chat);

const props = defineProps({
    addConversation: Function,
});

const form = useForm({
    question: '',
    modifier: '',
});

const submitMessages = async (messages) => {
    if (messages.length === 0) {
        form.reset();
        return;
    }

    const message = messages.shift();
    const messageData = { message };
    const isNewChat = !current_chat.value;

    form.transform(() => messageData).post(route('chat.send', { chatId: current_chat.value?.id }), {
        preserveScroll: true,
        onSuccess: (data) => {
            if (isNewChat) {
                props.addConversation(data.props.current_chat);
            }
            current_chat.value = data.props.current_chat;
            submitMessages(messages);
        },
    });
};

const submit = async () => {
    const prompts = [form.question, form.modifier].filter(Boolean);
    submitMessages(prompts);
};
</script>
