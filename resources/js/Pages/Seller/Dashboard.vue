<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentOrders: Array,
    hasShop: Boolean,
});

const formatPrice = (price) => {
    if (!price) return '0.00';
    return new Intl.NumberFormat('en-IN').format(price);
};

const statusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-700',
        confirmed: 'bg-blue-100 text-blue-700',
        shipped: 'bg-indigo-100 text-indigo-700',
        delivered: 'bg-green-100 text-green-700',
        cancelled: 'bg-red-100 text-red-700',
    };
    return colors[status] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head title="Seller Dashboard | Electronics Dokan" />

    <SellerLayout>
        <template #header>
            <span class="text-gray-800 font-bold">Dashboard</span>
        </template>

        <!-- No Shop Warning -->
        <div v-if="!hasShop" class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-amber-500 text-2xl mr-4"></i>
                <div>
                    <h3 class="text-[15px] font-bold text-amber-800">Shop Not Set Up</h3>
                    <p class="text-[13px] text-amber-700 mt-1">You need to create a shop before you can start selling. Go to Shop Settings to get started.</p>
                </div>
            </div>
        </div>

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#111]">Seller Dashboard</h1>
            <p class="text-[13px] text-gray-500 mt-1 font-medium">Welcome back! Here's your store performance overview.</p>
        </div>

        <!-- Stats Cards Row 1 -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-ryans/10 flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-ryans text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded">Revenue</span>
                </div>
                <p class="text-2xl font-black text-[#111]">৳{{ stats.totalSales }}</p>
                <p class="text-[12px] text-gray-500 font-medium mt-1">Total Sales</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-blue-500 text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Orders</span>
                </div>
                <p class="text-2xl font-black text-[#111]">{{ stats.totalOrders }}</p>
                <p class="text-[12px] text-gray-500 font-medium mt-1">Total Orders</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                        <i class="fas fa-box-open text-purple-500 text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">Active</span>
                </div>
                <p class="text-2xl font-black text-[#111]">{{ stats.activeProducts }}</p>
                <p class="text-[12px] text-gray-500 font-medium mt-1">Active Products</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                        <i class="fas fa-wallet text-orange-500 text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded">Balance</span>
                </div>
                <p class="text-2xl font-black text-[#111]">৳{{ stats.walletBalance }}</p>
                <p class="text-[12px] text-gray-500 font-medium mt-1">Wallet Balance</p>
            </div>
        </div>

        <!-- Order Status Breakdown -->
        <div class="grid grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition">
                <p class="text-xl font-black text-yellow-600">{{ stats.pendingOrders }}</p>
                <p class="text-[11px] text-gray-500 font-bold mt-1 uppercase tracking-wider">Pending</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition">
                <p class="text-xl font-black text-blue-600">{{ stats.confirmedOrders }}</p>
                <p class="text-[11px] text-gray-500 font-bold mt-1 uppercase tracking-wider">Confirmed</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition">
                <p class="text-xl font-black text-indigo-600">{{ stats.shippedOrders }}</p>
                <p class="text-[11px] text-gray-500 font-bold mt-1 uppercase tracking-wider">Shipped</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition">
                <p class="text-xl font-black text-green-600">{{ stats.deliveredOrders }}</p>
                <p class="text-[11px] text-gray-500 font-bold mt-1 uppercase tracking-wider">Delivered</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition">
                <p class="text-xl font-black text-red-600">{{ stats.cancelledOrders }}</p>
                <p class="text-[11px] text-gray-500 font-bold mt-1 uppercase tracking-wider">Cancelled</p>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-[15px] font-bold text-[#111] flex items-center">
                    <i class="fas fa-receipt mr-2 text-ryans"></i> Recent Orders
                </h3>
                <span class="text-[12px] text-gray-400 font-medium">Last 10 orders</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead class="bg-[#f8f9fa]">
                        <tr>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Order</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Customer</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Amount</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Shipping</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in recentOrders" :key="order.sub_order_id" class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 font-bold text-[#002b5c]">#{{ order.id }}-{{ order.sub_order_id }}</td>
                            <td class="px-4 py-3 text-gray-700 font-medium">{{ order.customer }}</td>
                            <td class="px-4 py-3 font-bold text-[#111]">৳{{ formatPrice(order.sub_total) }}</td>
                            <td class="px-4 py-3 text-gray-500">৳{{ formatPrice(order.shipping_cost) }}</td>
                            <td class="px-4 py-3">
                                <span :class="statusColor(order.status)" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">
                                    {{ order.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-[12px]">{{ order.created_at }}</td>
                        </tr>
                        <tr v-if="!recentOrders?.length">
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                <p class="font-medium">No orders yet</p>
                                <p class="text-[12px] mt-1">Orders will appear here once customers start buying your products.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </SellerLayout>
</template>
