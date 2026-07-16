<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ order: Object });
const form = useForm({ order_status: props.order.order_status });
const updateStatus = () => { form.put(route('seller.orders.update-status', props.order.id)); };
const formatPrice = (p) => new Intl.NumberFormat('en-IN').format(p || 0);
const orderStatusColor = (s) => {
    const c = { pending: 'bg-yellow-100 text-yellow-700', confirmed: 'bg-blue-100 text-blue-700', shipped: 'bg-indigo-100 text-indigo-700', delivered: 'bg-green-100 text-green-700', cancelled: 'bg-red-100 text-red-700' };
    return c[s] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head :title="'Order #' + order.order_id + '-' + order.id + ' | Seller'" />
    <SellerLayout>
        <template #header>
            <Link :href="route('seller.orders.index')" class="text-gray-500 hover:text-ryans transition font-medium">Orders</Link>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-800 font-bold">#{{ order.order_id }}-{{ order.id }}</span>
        </template>

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-[#111]">Order #{{ order.order_id }}-{{ order.id }}</h1>
            <Link :href="route('seller.orders.index')" class="text-[13px] text-gray-500 hover:text-ryans font-bold"><i class="fas fa-arrow-left mr-1"></i> Back</Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-5 py-3 bg-[#f8f9fa] border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <h3 class="text-[14px] font-bold text-[#111]">Order Items</h3>
                            <span :class="orderStatusColor(order.order_status)" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">{{ order.order_status }}</span>
                        </div>
                        <select v-model="form.order_status" @change="updateStatus" class="text-[12px] border border-gray-300 rounded py-1 px-2 bg-white focus:border-ryans">
                            <option value="pending">Pending</option><option value="confirmed">Confirmed</option><option value="shipped">Shipped</option><option value="delivered">Delivered</option><option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <table class="w-full text-[13px]">
                        <thead><tr><th class="text-left px-5 py-2 text-[11px] text-gray-500 uppercase font-bold">Product</th><th class="text-center px-3 py-2 text-[11px] text-gray-500 uppercase font-bold">Qty</th><th class="text-right px-5 py-2 text-[11px] text-gray-500 uppercase font-bold">Price</th><th class="text-right px-5 py-2 text-[11px] text-gray-500 uppercase font-bold">Total</th></tr></thead>
                        <tbody>
                            <tr v-for="item in order.items" :key="item.id" class="border-t border-gray-50">
                                <td class="px-5 py-3"><p class="font-bold text-[#111]">{{ item.product?.name || 'Deleted' }}</p><p v-if="item.variant" class="text-[11px] text-gray-500 mt-0.5">{{ item.variant }}</p></td>
                                <td class="px-3 py-3 text-center text-gray-600">{{ item.quantity }}</td>
                                <td class="px-5 py-3 text-right text-gray-600">৳{{ formatPrice(item.price) }}</td>
                                <td class="px-5 py-3 text-right font-bold text-[#111]">৳{{ formatPrice(item.price * item.quantity) }}</td>
                            </tr>
                        </tbody>
                        <tfoot><tr class="border-t border-gray-100"><td colspan="3" class="px-5 py-2 text-right font-bold text-gray-600 text-[12px]">Subtotal</td><td class="px-5 py-2 text-right font-bold text-[#111]">৳{{ formatPrice(order.sub_total) }}</td></tr></tfoot>
                    </table>
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-[#111] mb-4 border-b border-gray-100 pb-3">Customer</h3>
                    <p class="text-[13px] font-bold text-[#111]">{{ order.order?.user?.name || 'Guest' }}</p>
                    <p class="text-[13px] text-gray-500 mt-1">{{ order.order?.user?.email }}</p>
                </div>
                <div v-if="order.order?.address" class="bg-white border border-gray-200 rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-[#111] mb-4 border-b border-gray-100 pb-3">Shipping Address</h3>
                    <p class="text-[13px] text-gray-700 leading-relaxed">{{ order.order.address.address }}<br>{{ order.order.address.city }}, {{ order.order.address.state }}<br>{{ order.order.address.zip_code }}, {{ order.order.address.country }}</p>
                </div>
            </div>
        </div>
    </SellerLayout>
</template>
