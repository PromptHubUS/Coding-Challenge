<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import {ChevronRightIcon} from '@heroicons/vue/20/solid'

defineProps({
    prompt: {
        type: Object,
        required: true,
    },
});
</script>
<template>
    <div v-if="prompt?.id" class="bg-gray-100 rounded-lg shadow-sm">
        <div class="p-6">
            <div class="text-sm font-semibold text-gray-500">
                ID: {{ prompt.id }}
            </div>
            <div class="text-sm font-semibold text-gray-500">
                Model: {{ prompt.model_name }}
            </div>

            <div class="mt-3 text-gray-700 font-bold">
                Output
            </div>

            <div class="p-2 bg-white rounded-md my-3">
                <div class="text-sm font-semibold text-gray-800 leading-tight whitespace-pre-wrap">
                    {{ prompt.content }}
                </div>

                <div class="text-sm text-gray-700 pt-2 mt-2 border-t border-t-gray-200 whitespace-pre-wrap">
                    {{ prompt.output }}
                </div>
            </div>

            <div class="text-gray-700 font-bold">
                Chains
            </div>

            <Disclosure v-slot="{ open }" v-for="(chain, index) in prompt.chains" as="div" class="mt-2 p-2 bg-white rounded-md">
                <DisclosureButton class="w-full">
                    <div class="flex items-center justify-between">
                        <div class="text-left text-sm font-semibold whitespace-pre-wrap">
                            #{{ index + 1 }} {{ chain.content }}

                            <span class="text-sm font-semibold text-gray-500">
                                ({{ prompt.model_name }})
                            </span>
                        </div>

                        <div>
                            <ChevronRightIcon class="w-6 h-6" :class="open && 'rotate-90 transform'"/>
                        </div>
                    </div>
                </DisclosureButton>

                <DisclosurePanel class="text-gray-700 text-sm pt-2 mt-2 border-t border-t-gray-200 whitespace-pre-wrap">
                    {{ chain.output }}
                </DisclosurePanel>
            </Disclosure>
        </div>
    </div>
</template>
