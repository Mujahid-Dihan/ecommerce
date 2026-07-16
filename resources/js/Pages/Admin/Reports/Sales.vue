<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
const props = defineProps({ stats: Object, recentOrders: Array, filters: Object });
const formatPrice = (p) => new Intl.NumberFormat('en-IN').format(p || 0);
</script>
<template>
    <Head title="Sales Report | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Sales Report</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Sales Report</h1></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-5"><p class="text-[12px] text-gray-500 font-bold uppercase">Total Orders</p><p class="text-2xl font-bold text-[#111] mt-1">{{ stats.total_orders }}</p></div>
            <div class="bg-white border border-gray-200 rounded-lg p-5"><p class="text-[12px] text-gray-500 font-bold uppercase">Total Revenue</p><p class="text-2xl font-bold text-ryans mt-1">৳{{ formatPrice(stats.total_revenue) }}</p></div>
            <div class="bg-white border border-gray-200 rounded-lg p-5"><p class="text-[12px] text-gray-500 font-bold uppercase">Avg Order Value</p><p class="text-2xl font-bold text-[#111] mt-1">৳{{ formatPrice(stats.avg_order_value) }}</p></div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-5 py-3 bg-[#f8f9fa] border-b border-gray-200"><h3 class="text-[14px] font-bold text-[#111]">Recent Orders</h3></div>
            <table class="w-full text-[13px]"><thead><tr><th class="text-left px-4 py-2 text-[11px] text-gray-500 uppercase font-bold">Order</th><th class="text-left px-4 py-2 text-[11px] text-gray-500 uppercase font-bold">Customer</th><th class="text-left px-4 py-2 text-[11px] text-gray-500 uppercase font-bold">Total</th><th class="text-left px-4 py-2 text-[11px] text-gray-500 uppercase font-bold">Date</th></tr></thead>
                <tbody><tr v-for="o in recentOrders" :key="o.id" class="border-t border-gray-50"><td class="px-4 py-3 font-bold text-ryans">#{{ o.id }}</td><td class="px-4 py-3 text-gray-700">{{ o.user?.name || 'Guest' }}</td><td class="px-4 py-3 font-bold">৳{{ formatPrice(o.grand_total) }}</td><td class="px-4 py-3 text-gray-500">{{ new Date(o.created_at).toLocaleDateString() }}</td></tr></tbody>
            </table>
        </div>
    </AdminLayout>
</template>
