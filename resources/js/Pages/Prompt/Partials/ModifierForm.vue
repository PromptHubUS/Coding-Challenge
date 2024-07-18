<script setup>
import InputError from '@/Components/InputError.vue';
import { usePage } from '@inertiajs/vue3';

const prompts = usePage().props.prompt;

</script>

<template>
    <section>
        <div v-for="prompt in prompts" :key="prompt.id">
            <p><strong>Question:</strong> {{ prompt.question }}</p>
            <p><strong>First Output:</strong> {{ prompt.first_prompt_output }}</p>
            <p><strong>Final Output:</strong> {{ prompt.final_output }}</p>
        </div>
    </section>
</template>