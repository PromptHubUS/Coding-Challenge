<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import FormComponent from "@/Components/FormComponent.vue";
import { ChatBubbleLeftRightIcon, PencilIcon, CogIcon } from "@heroicons/vue/24/solid";
defineProps({
    models: {
        type: Array,
        required: true,
    },
    promptResponses: {
        type: Array,
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Prompt Converter!</h2>
        </template>

        <div class="py-12 bg-gradient-to-r from-gray-100 to-gray-200 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md rounded-lg p-8 mb-6">
                    <FormComponent :models="models" />
                </div>
                <div class="space-y-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Previous Responses</h3>
                    <div v-for="response in promptResponses" :key="response.id" class="bg-white shadow-md rounded-lg p-6 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                        <ul class="divide-y divide-gray-200">
                            <li class="py-4 flex">
                                <CogIcon class="h-6 w-6 text-gray-500 mr-2" />
                                <p><strong class="font-semibold">Model Used:</strong> {{ response.model.name }}</p>
                            </li>
                            <li class="py-4 flex">
                                <ChatBubbleLeftRightIcon class="h-6 w-6 text-gray-500 mr-2" />
                                <p><strong class="font-semibold">Prompt:</strong> {{ response.prompt }}</p>
                            </li>
                            <li class="py-4 flex">
                                <PencilIcon class="h-6 w-6 text-gray-500 mr-2" />
                                <p><strong class="font-semibold">Modifier:</strong> {{ response.modifier }}</p>
                            </li>
                            <li class="py-4 flex">
                                <p><strong class="font-semibold">Intermediate Result:</strong> {{ response.intermediate_result }}</p>
                            </li>
                            <li class="py-4 flex">
                                <p><strong class="font-semibold">Final Result:</strong> {{ response.final_result }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
