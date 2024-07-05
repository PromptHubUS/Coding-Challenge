<script setup>
import { useForm, usePage } from "@inertiajs/vue3";

const form = useForm({
    prompt: "",
    modifier: "",
});

const submitForm = () => {
    form.post("/prompt/process", {
        preserveState: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <div class="bg-white shadow-sm rounded-lg p-6">
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label for="prompt" class="block text-sm font-medium text-gray-700">Prompt</label>
                <input type="text" v-model="form.prompt" id="prompt" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
            </div>
            <div class="mb-4">
                <label for="modifier" class="block text-sm font-medium text-gray-700">Modifier</label>
                <input type="text" v-model="form.modifier" id="modifier" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Submit</button>
        </form>
    </div>
</template>
