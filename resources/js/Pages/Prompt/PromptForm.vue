<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import Textarea from '@/Components/Textarea.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const emit = defineEmits(['onLoad'])
const props = defineProps(['question', 'modifier']);

const step1Output = ref(null);
const step2Output = ref(null);

const errorMessage = ref(null);

const form = useForm({
    question: props.question || '',
    modifier: props.modifier || '',
});

const sendQuestion = () => {
    errorMessage.value = null;
    let step1Form = new FormData();
    step1Form.append('question', form.question);
    axios.post('/prompt/step1', step1Form)
        .then(response => {
            step1Output.value = response.data.output;

            emit('onLoad', response.data);

            sendStep2();
        })
        .catch(error => {
            console.log(error);
            errorMessage.value = "There was an error processing your request. Please try again.";
        });
    
};

const sendStep2 = () => {
    errorMessage.value = null;
    let step2Form = new FormData();
    step2Form.append('modifier', form.modifier);
    step2Form.append('step1Output', step1Output.value);
    axios.post('/prompt/step2', step2Form)
        .then(response => {
            step2Output.value = response.data.output;

            emit('onLoad', response.data);
        })
        .catch(error => {
            console.log(error);
            errorMessage.value = "There was an error processing your request. Please try again.";
        });
    
};

const closeModal = () => {
    errorMessage.value = null;
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Ask the Question</h2>

            <p class="mt-1 text-sm text-gray-600">
                Type in the question you want to ask the AI.
            </p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="sendQuestion">
            <div>
                <InputLabel for="question" value="question" />
                <Textarea
                    id="question"
                    v-model="form.question"
                    class="mt-1 block w-full"
                    type="text"
                />

                <InputError :message="form.errors.question" class="mt-2" />
            </div>
            <div>
                <InputLabel for="modifier" value="modifier" />
                <Textarea
                    id="modifier"
                    v-model="form.modifier"
                    class="mt-1 block w-full"
                    type="text"
                />

                <InputError :message="form.errors.modifier" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Send</PrimaryButton>
            </div>
        </form>
        {{ error }}
        <Modal :show="errorMessage" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Error
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ errorMessage }}
                </p>

                <div class="mt-6 flex justify-end">
                    <PrimaryButton @click="closeModal"> Close</PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
