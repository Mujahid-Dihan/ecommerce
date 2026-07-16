<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
const props = defineProps({ customer: Object, stats: Object });
const formatPrice = (p) => new Intl.NumberFormat('en-IN').format(p || 0);
</script>

<template>
    <Head :title="customer.name + ' | Customer'" />
    <AdminLayout>
        <template #header>
            <Link :href="route('admin.customers.index')" class="text-gray-500 hover:text-ryans transition font-medium">Customers</Link>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-800 font-bold">{{ customer.name }}</span>
        </template>

        <div class="flex items-center justify-between mb-6">
            <div><h1 class="text-2xl font-bold text-[#111]">{{ customer.name }}</h1><p class="text-[13px] text-gray-500 mt-1">{{ customer.email }}</p></div>
            <Link :href="route('admin.customers.index')" class="text-[13px] text-gray-500 hover:text-ryans font-bold"><i class="fas fa-arrow-left mr-1"></i> Back</Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <p class="text-[12px] text-gray-500 font-bold uppercase">Total Orders</p>
                <p class="text-2xl font-bold text-[#111] mt-1">{{ stats.total_orders }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <p class="text-[12px] text-gray-500 font-bold uppercase">Total Spent</p>
                <p class="text-2xl font-bold text-[#111] mt-1">৳{{ formatPrice(stats.total_spent) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <p class="text-[12px] text-gray-500 font-bold uppercase">Member Since</p>
                <p class="text-2xl font-bold text-[#111] mt-1">{{ new Date(customer.created_at).toLocaleDateString() }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-5 py-3 bg-[#f8f9fa] border-b border-gray-200"><h3 class="text-[14px] font-bold text-[#111]">Recent Orders</h3></div>
            <table class="w-full text-[13px]">
                <thead><tr><th class="text-left px-4 py-2 text-[11px] text-gray-500 uppercase font-bold">Order</th><th class="text-left px-4 py-2 text-[11px] text-gray-500 uppercase font-bold">Total</th><th class="text-left px-4 py-2 text-[11px] text-gray-500 uppercase font-bold">Payment</th><th class="text-left px-4 py-2 text-[11px] text-gray-500 uppercase font-bold">Date</th></tr></thead>
                <tbody>
                    <tr v-for="o in customer.orders" :key="o.id" class="border-t border-gray-50">
                        <td class="px-4 py-3"><Link :href="route('admin.orders.show', o.id)" class="font-bold text-ryans hover:underline">#{{ o.id }}</Link></td>
                        <td class="px-4 py-3 font-bold">৳{{ formatPrice(o.grand_total) }}</td>
                        <td class="px-4 py-3 capitalize">{{ o.payment_status }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ new Date(o.created_at).toLocaleDateString() }}</td>
                    </tr>
                    <tr v-if="!customer.orders?.length"><td colspan="4" class="px-4 py-8 text-center text-gray-400">No orders yet.</td></tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
