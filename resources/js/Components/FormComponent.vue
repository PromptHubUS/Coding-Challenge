<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { CheckCircleIcon, XCircleIcon } from "@heroicons/vue/24/solid";

defineProps({
    models: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    prompt: "",
    modifier: "",
    model_id: 1,
});
const messageType = ref(null);

const submitForm = () => {
    messageType.value = null;
    form.post("/prompt/process", {
        preserveState: true,
        onSuccess: () => {
            form.reset();
            messageType.value = "success";
        },
        onError: () => {
            messageType.value = "error";
        },
    });
};
</script>

<template>
    <div class="p-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">AI Model Selector</h2>
        <p class="text-gray-600 mb-6">Choose your AI model and provide a prompt with a modifier to see the magic!</p>
        <form @submit.prevent="submitForm">
            <div class="mb-6">
                <label for="model_id" class="block text-sm font-semibold text-gray-700">Model</label>
                <select v-model="form.model_id" id="model_id" class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" required>
                    <option v-for="model in models" :key="model.id" :value="model.id">{{ model.name }}</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="prompt" class="block text-sm font-semibold text-gray-700">Prompt</label>
                <input type="text" v-model="form.prompt" id="prompt" class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" required />
            </div>
            <div class="mb-6">
                <label for="modifier" class="block text-sm font-semibold text-gray-700">Modifier</label>
                <input type="text" v-model="form.modifier" id="modifier" class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" required />
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 transition">Submit</button>
        </form>
        <transition name="fade">
            <div v-if="messageType" class="mt-4 flex items-center justify-center">
                <CheckCircleIcon v-if="messageType === 'success'" class="h-6 w-6 text-green-500 mr-2" />
                <XCircleIcon v-if="messageType === 'error'" class="h-6 w-6 text-red-500 mr-2" />
                <p v-if="messageType === 'success'" class="text-green-500">Submission successful!</p>
                <p v-if="messageType === 'error'" class="text-red-500">There was an error with your submission.</p>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
