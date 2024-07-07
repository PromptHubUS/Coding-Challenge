<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import PromptForm from "@/Components/Prompt/PromptForm.vue";
import {ref} from "vue";
import PromptItem from "@/Components/Prompt/PromptItem.vue";

const props = defineProps({
    prompts: {
        type: Array,
        required: true,
    },
    models: {
        type: Array,
        required: true,
    },
});

const items = ref(props.prompts);

const pushItem = (item) => {
    if (items.value.findIndex(i => i.id === item.id) === -1) {
        items.value.push(item);
    }
};
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Prompts
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <PromptForm
                        :models="models"
                        @created="pushItem"
                    />
                </div>

                <div class="mt-10 p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 leading-tight mb-6">
                        Prompts
                    </h3>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <PromptItem
                            v-for="prompt in items"
                            :key="prompt.id"
                            :prompt="prompt"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
