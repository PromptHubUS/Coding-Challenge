<script setup>
import {reactive, ref} from "vue";
import axios from 'axios';
import PromptItem from "@/Components/Prompt/PromptItem.vue";

defineProps({
    models: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['created']);

const initialForm = {
    step: 1,
    model_name: '',
    content: '',
};

const form = reactive({...initialForm});

const prompt = ref(null);

const errors = ref([]);

const busy = ref(false);

const steps = [
    {id: 'Step 1', name: 'Question', value: 1},
    {id: 'Step 2', name: 'Modifier', value: 2},
    {id: 'Step 3', name: 'Preview', value: 3},
]

const submitForm = () => {
    busy.value = true;

    axios.post(route('prompts.chain.create'), {
        ...form,
        prompt_id: prompt.value?.id,
    })
        .then(({data}) => {
            if (form.step < 3) {
                form.step++;

                form.content = '';
            }

            if (form.step === 3) {
                emit('created', data.data);
            }

            prompt.value = data.data;

            busy.value = false;
        })
        .catch(({response}) => {
            errors.value = response.data.errors;
            busy.value = false;
        });
};

const clearForm = () => {
    errors.value = [];

    Object.assign(form, initialForm);

    prompt.value = null;
};
</script>

<template>
    <form @submit.prevent="submitForm">
        <nav aria-label="Progress">
            <ol role="list" class="space-y-4 md:flex md:space-y-0 md:space-x-8">
                <li v-for="step in steps" :key="step.name" class="md:flex-1">
                    <span v-if="step.value < form.step" class="group pl-4 py-2 flex flex-col border-l-4 border-indigo-600 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4">
                        <span class="text-xs text-indigo-600 font-semibold tracking-wide uppercase">{{ step.id }}</span>
                        <span class="text-sm font-medium">{{ step.name }}</span>
                    </span>
                    <span v-else-if="step.value === form.step" class="pl-4 py-2 flex flex-col border-l-4 border-indigo-600 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4" aria-current="step">
                        <span class="text-xs text-indigo-600 font-semibold tracking-wide uppercase">{{ step.id }}</span>
                        <span class="text-sm font-medium">{{ step.name }}</span>
                    </span>
                    <span v-else class="group pl-4 py-2 flex flex-col border-l-4 border-gray-200 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4">
                        <span class="text-xs text-gray-500 font-semibold tracking-wide uppercase">{{ step.id }}</span>
                        <span class="text-sm font-medium">{{ step.name }}</span>
                    </span>
                </li>
            </ol>
        </nav>

        <div v-if="form.step < 3" class="grid grid-cols-1 gap-6 mt-6 bg-gray-50 p-6 rounded-md">
            <div>
                <label for="model_name" class="block text-sm font-medium text-gray-700">
                    Model Name
                </label>

                <select
                    name="model_name"
                    id="model_name"
                    v-model="form.model_name"
                    class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"
                >
                    <option value="">Select a model</option>
                    <option v-for="model in models" :value="model" :key="model">
                        {{ model }}
                    </option>
                </select>

                <div v-if="form.errors?.model_name" class="text-red-500 mt-1 text-sm">{{ form.errors.model_name }}</div>
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">
                    Content
                </label>
                <textarea
                    name="content"
                    id="content"
                    v-model="form.content"
                    class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"
                ></textarea>

                <div v-if="form.errors?.content" class="text-red-500 mt-1 text-sm">{{ form.errors.content }}</div>
            </div>

            <div>
                <button
                    :disabled="busy"
                    type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ busy ? 'Processing...' : 'Process' }}
                </button>
            </div>
        </div>

        <PromptItem class="mt-6" :prompt="prompt" v-if="prompt?.id"/>

        <div v-if="form.step === 3" class="mt-6">
            <button
                @click="clearForm()"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Restart
            </button>
        </div>
    </form>
</template>
