<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentOrders: Array,
    lowStockProducts: Array,
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

const paymentColor = (status) => {
    return status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700';
};
</script>

<template>
    <Head title="Admin Dashboard | Electronics Dokan" />

    <AdminLayout>
        <template #header>
            <span class="text-gray-800 font-bold">Dashboard</span>
        </template>

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#111]">Dashboard</h1>
            <p class="text-[13px] text-gray-500 mt-1 font-medium">Welcome back! Here's what's happening with your store.</p>
        </div>

        <!-- Stats Cards Row 1 -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Sales -->
            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-ryans/10 flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-ryans text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded">
                        <i class="fas fa-arrow-up mr-0.5"></i> Revenue
                    </span>
                </div>
                <p class="text-2xl font-black text-[#111]">৳{{ stats.totalSales }}</p>
                <p class="text-[12px] text-gray-500 font-medium mt-1">Total Sales</p>
            </div>

            <!-- Total Orders -->
            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-blue-500 text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Orders</span>
                </div>
                <p class="text-2xl font-black text-[#111]">{{ stats.totalOrders }}</p>
                <p class="text-[12px] text-gray-500 font-medium mt-1">Total Orders</p>
            </div>

            <!-- Total Products -->
            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                        <i class="fas fa-box-open text-purple-500 text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">Products</span>
                </div>
                <p class="text-2xl font-black text-[#111]">{{ stats.totalProducts }}</p>
                <p class="text-[12px] text-gray-500 font-medium mt-1">Total Products</p>
            </div>

            <!-- Total Customers -->
            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                        <i class="fas fa-users text-orange-500 text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded">Users</span>
                </div>
                <p class="text-2xl font-black text-[#111]">{{ stats.totalCustomers }}</p>
                <p class="text-[12px] text-gray-500 font-medium mt-1">Total Customers</p>
            </div>
        </div>

        <!-- Stats Cards Row 2 -->
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

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Recent Orders (2/3 width) -->
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg overflow-hidden">
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
                                <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Payment</th>
                                <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th>
                                <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in recentOrders" :key="order.id" class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 font-bold text-[#002b5c]">#{{ order.id }}</td>
                                <td class="px-4 py-3 text-gray-700 font-medium">{{ order.customer }}</td>
                                <td class="px-4 py-3 font-bold text-[#111]">৳{{ formatPrice(order.grand_total) }}</td>
                                <td class="px-4 py-3">
                                    <span :class="paymentColor(order.payment_status)" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">
                                        {{ order.payment_status }}
                                    </span>
                                </td>
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
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <h3 class="text-[15px] font-bold text-[#111] mb-4 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-ryans"></i> Store Overview
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <span class="text-[13px] text-gray-600 font-medium"><i class="fas fa-store mr-2 text-gray-400"></i>Active Sellers</span>
                            <span class="text-[14px] font-bold text-[#111]">{{ stats.totalSellers }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <span class="text-[13px] text-gray-600 font-medium"><i class="fas fa-tags mr-2 text-gray-400"></i>Categories</span>
                            <span class="text-[14px] font-bold text-[#111]">{{ stats.totalCategories }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-[13px] text-gray-600 font-medium"><i class="fas fa-award mr-2 text-gray-400"></i>Brands</span>
                            <span class="text-[14px] font-bold text-[#111]">{{ stats.totalBrands }}</span>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-[15px] font-bold text-[#111] flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2 text-amber-500"></i> Low Stock
                        </h3>
                    </div>
                    <div v-if="lowStockProducts?.length">
                        <div v-for="product in lowStockProducts" :key="product.id" class="px-5 py-3 border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition">
                            <p class="text-[13px] font-bold text-[#111] line-clamp-1">{{ product.name }}</p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-[11px] text-gray-500">{{ product.category }}</span>
                                <span class="text-[12px] font-bold px-2 py-0.5 rounded" :class="product.stock <= 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'">
                                    {{ product.stock <= 0 ? 'Out of Stock' : product.stock + ' left' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="px-5 py-8 text-center text-gray-400">
                        <i class="fas fa-check-circle text-3xl mb-2 block text-green-400"></i>
                        <p class="text-[12px] font-medium">All products are well stocked</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
