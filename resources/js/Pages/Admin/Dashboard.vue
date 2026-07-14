<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    stats: Object
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Welcome back, Admin!</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-blue-50 p-4 rounded border border-blue-100">
                                <p class="text-sm text-blue-600 font-semibold">Total Sales</p>
                                <p class="text-2xl font-bold">${{ stats.totalSales }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded border border-green-100">
                                <p class="text-sm text-green-600 font-semibold">Total Orders</p>
                                <p class="text-2xl font-bold">{{ stats.totalOrders }}</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded border border-purple-100">
                                <p class="text-sm text-purple-600 font-semibold">Active Sellers</p>
                                <p class="text-2xl font-bold">{{ stats.activeSellers }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
