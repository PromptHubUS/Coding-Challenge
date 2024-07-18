<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import QuestionForm from './Partials/QuestionForm.vue';
import ModifierForm from './Partials/ModifierForm.vue';


defineProps({
    prompt: {
        type: String,
    }
});
</script>

<template>
    <Head title="Prompt" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Prompt</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <QuestionForm />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <ModifierForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
