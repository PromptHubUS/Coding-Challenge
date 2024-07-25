<script>
import { ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConversationsList from './Conversation/Partials/ConversationsList.vue';
import ChatForm from './Conversation/Partials/ChatForm.vue';

export default {
  components: {
    AuthenticatedLayout,
    ConversationsList,
    ChatForm,
    Head,
  },
  setup() {
    const page = usePage();
    const conversations = ref(page.props.conversations);

    const addConversation = (conversation) => {
      conversations.value.unshift(conversation);
    };

    return {
      conversations,
      addConversation,
    };
  },
};
</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chat</h2>
    </template>

    <div class="max-w-7xl py-12 flex flex-row mx-auto">
      <ConversationsList :conversations="conversations" />
      <ChatForm :add-conversation="addConversation" />
    </div>
  </AuthenticatedLayout>
</template>
