<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>


Here's a formatted version of your form, using Tailwind CSS for styling to ensure it looks clean and centered:

vue
Copiar código
<template>
    <Head title="Prompt" />
    <AuthenticatedLayout>
        <div class="flex justify-center items-center min-h-screen bg-gray-100">
            <form @submit.prevent="submitForm" class="bg-white p-6 rounded shadow-lg max-w-md w-full">
                <div class="mb-4">
                    <label for="question" class="block text-gray-700 font-semibold">Question</label>
                    <input v-model="question" id="question" type="text" required maxlength="100"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>

                <div class="mb-4">
                    <label for="modifier" class="block text-gray-700 font-semibold">Modifier</label>
                    <input v-model="modifier" id="modifier" type="text" required maxlength="100"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white font-bold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Submit
                </button>
            </form>

            <div v-if="result" class="mt-6 max-w-md w-full bg-white p-4 rounded shadow-lg">
                <h3 class="text-lg font-semibold">Last Prompt Output:</h3>
                <p class="mt-2 text-gray-900">{{ result }}</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    data() {
        return {
            question: '',
            modifier: '',
            result: '',
        };
    },
    methods: {
        async submitForm() {
            this.result = '';
            try {
                const response = await fetch('/prompts', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        question: this.question,
                        modifier: this.modifier,
                    }),
                });

                const reader = response.body.getReader();
                const decoder = new TextDecoder();

                let buffer = '';

                const processChunk = async ({ done, value }) => {
                    if (done) {
                        console.log('Stream finished');
                        return;
                    }

                    buffer += decoder.decode(value, { stream: true });

                    let lines = buffer.split('\n');
                    buffer = lines.pop();

                    for (let line of lines) {
                        console.log('Line:', line);
                        if (line.startsWith('data: ')) {
                            if(line.includes('[DONE]'))
                            {
                                console.log('Stream finished');
                                return;
                            }
                            try {
                                let json = JSON.parse(line.substring(6));
                                if (json?.content) {
                                    this.result = json.content;
                                }
                            } catch (e) {
                                console.error('Error parsing JSON:', e);
                            }
                        }
                    }

                    return reader.read().then(processChunk);
                };

                await reader.read().then(processChunk);
            } catch (error) {
                console.error('Fetch error:', error);
            }
        }
    },
    mounted() {
        fetch('/prompts/show')
            .then(response => response.json())
            .then(data => {
                if (data.prompt_output) {
                    this.result = data.prompt_output;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }
};
</script>
