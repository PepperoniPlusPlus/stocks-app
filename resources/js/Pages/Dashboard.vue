<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const stockPrices = ref([]);

onMounted(async () => {
    try {
        const response = await fetch('api/stocks');
        const data = await response.json();
        stockPrices.value = data.data;
    } catch (error) {
        console.error('Error:', error);
    }
});
</script>

<template>
    <div>
        <Head title="Dashboard" />

        <AuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
            </template>


            <div class="py-4 flex items-center justify-center">
                <table class="min-w-56 bg-white border border-gray-300">
                    <thead>
                    <tr>
                        <th class="border-b">Ticker</th>
                        <th class="border-b">Evolution</th>
                        <th class="border-b">Trend</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="price in stockPrices" :key="price.symbol">
                        <td class="border-b">{{ price.ticker }}</td>
                        <td class="border-b">{{ price.price_evolution }}</td>
                        <td class="py-2 px-4 border-b">
                          <span :class="{ 'text-green-500': price.price_evolution >= 0, 'text-red-500': price.price_evolution < 0 }">
                            {{ price.price_evolution > 0 ? '▲' : price.price_evolution < 0 ? '▼' : '-' }}
                          </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </AuthenticatedLayout>
    </div>
</template>
