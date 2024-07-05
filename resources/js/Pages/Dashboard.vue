<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import FormComponent from "@/Components/FormComponent.vue";

defineProps({
    promptResponses: {
        type: Array,
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Prompt Converter!</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mt-6">
                    <FormComponent />
                </div>
                <div v-for="response in promptResponses" :key="response.id" class="bg-white shadow-sm rounded-lg p-6 my-2">
                    <ul class="space-y-2">
                        <li>
                            <p><strong class="font-semibold">Prompt:</strong> {{ response.prompt }}</p>
                        </li>
                        <li>
                            <p><strong class="font-semibold">Modifier:</strong> {{ response.modifier }}</p>
                        </li>
                        <li>
                            <p><strong class="font-semibold">Intermediate Result:</strong> {{ response.intermediate_result }}</p>
                        </li>
                        <li>
                            <p><strong class="font-semibold">Final Result:</strong> {{ response.final_result }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
