<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PromptForm from '@/Pages/Prompt/PromptForm.vue';
import Logger from '@/Pages/Prompt/Logger.vue';


defineProps({ lastQuestion: String, lastModifier: String });


const logs = ref([]);

const handleOnLoad = (data) => {
    logs.value.push(data);
};

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">LLM Prompt</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <PromptForm @onLoad="handleOnLoad" :question="lastQuestion" :modifier="lastModifier"/>
                    </div>
                    <div class="p-6 text-gray-900">
                        <Logger :logs="logs"/>
                    </div>
                </div>
            </div>
        </div>


    </AuthenticatedLayout>
</template>
