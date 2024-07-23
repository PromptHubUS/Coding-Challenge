<template>
    <div>
        <form @submit.prevent="submitForm">
            <label for="question">Question</label>
            <input v-model="question" id="question" type="text" required />

            <label for="modifier">Modifier</label>
            <input v-model="modifier" id="modifier" type="text" required />

            <button type="submit">Submit</button>
        </form>

        <div v-if="result">
            <h3>Prompt Output:</h3>
            <p>{{ result }}</p>
        </div>
    </div>
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
                        question: this.question, // Use v-model values
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
                        if (line.startsWith('data: ')) {
                            if(line.includes('[DONE]'))
                            {
                                console.log('Stream finished');
                                return;
                            }
                            try {
                                let json = JSON.parse(line.substring(6));
                                if (json?.choices?.[0]?.delta?.content) {
                                    this.result += json.choices[0].delta.content;
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
};
</script>
