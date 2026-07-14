<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    stats: Object
});
</script>

<template>
    <Head title="Seller Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Seller Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Welcome back to your Shop!</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-indigo-50 p-4 rounded border border-indigo-100">
                                <p class="text-sm text-indigo-600 font-semibold">Your Total Sales</p>
                                <p class="text-2xl font-bold">${{ stats?.totalSales || 0 }}</p>
                            </div>
                            <div class="bg-teal-50 p-4 rounded border border-teal-100">
                                <p class="text-sm text-teal-600 font-semibold">Your Total Orders</p>
                                <p class="text-2xl font-bold">{{ stats?.totalOrders || 0 }}</p>
                            </div>
                            <div class="bg-orange-50 p-4 rounded border border-orange-100">
                                <p class="text-sm text-orange-600 font-semibold">Active Products</p>
                                <p class="text-2xl font-bold">{{ stats?.activeProducts || 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
