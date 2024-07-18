<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    question: '',
    modifier: '',
});

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Submit a Prompt</h2>
            
            <p class="mt-1 text-sm text-gray-600">
                Enter your question and modifier to generate prompts.
            </p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="form.post(route('pormpt.store'))">
            <div>
                <InputLabel for="question" value="Question" />

                <TextInput
                    id="question"
                    ref="questionInput"
                    v-model="form.question"
                    autocomplete="question"
                    class="mt-1 block w-full"
                    type="text"
                />

                <InputError :message="form.errors.question" class="mt-2" />
            </div>

            <div>
                <InputLabel for="modifier" value="Modifier" />

                <TextInput
                    id="modifier"
                    ref="modifierInput"
                    v-model="form.modifier"
                    autocomplete="modifier"
                    class="mt-1 block w-full"
                    type="text"
                />

                <InputError :message="form.errors.modifier" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Submit</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>