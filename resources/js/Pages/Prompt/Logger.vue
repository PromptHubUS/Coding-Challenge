<script setup>
import { ref } from 'vue';

const props = defineProps(['logs']);

</script>

<template>
    <div>
        <h2 class="text-lg font-medium text-gray-900">Output Logs</h2>
        <div v-if="logs && logs.length"  class="flex flex-col space-y-4">
            <div v-for="row in logs"  class="flex flex-col space-y-4">
                <div v-for="key in Object.keys(row)">
                    <div>{{ key }}: {{ row[key] }}</div>
                </div>
            </div>
        </div>
    </div>
</template>