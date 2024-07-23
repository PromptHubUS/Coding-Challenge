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
            <p>{{ result.choices[0].message.content }}</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            question: '',
            modifier: '',
            result: null,
        };
    },
    methods: {
        async submitForm() {
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

            this.result = await response.json();
        },
    },
};
</script>
