<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ order: Object });

const statusForm = useForm({ payment_status: props.order.payment_status });
const updatePayment = () => {
    statusForm.put(route('admin.orders.update-payment', props.order.id));
};

const subStatusForm = useForm({ order_status: '' });
const updateSubStatus = (subOrderId, status) => {
    subStatusForm.order_status = status;
    subStatusForm.put(route('admin.orders.update-sub-status', subOrderId));
};

const formatPrice = (p) => new Intl.NumberFormat('en-IN').format(p || 0);

const orderStatusColor = (s) => {
    const c = { pending: 'bg-yellow-100 text-yellow-700', confirmed: 'bg-blue-100 text-blue-700', shipped: 'bg-indigo-100 text-indigo-700', delivered: 'bg-green-100 text-green-700', cancelled: 'bg-red-100 text-red-700' };
    return c[s] || 'bg-gray-100 text-gray-700';
};
const payStatusColor = (s) => {
    const c = { unpaid: 'bg-red-100 text-red-700', paid: 'bg-green-100 text-green-700', failed: 'bg-gray-100 text-gray-700', refunded: 'bg-blue-100 text-blue-700' };
    return c[s] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head :title="'Order #' + order.id + ' | Admin'" />
    <AdminLayout>
        <template #header>
            <Link :href="route('admin.orders.index')" class="text-gray-500 hover:text-ryans transition font-medium">Orders</Link>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-800 font-bold">#{{ order.id }}</span>
        </template>

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#111]">Order #{{ order.id }}</h1>
                <p class="text-[13px] text-gray-500 mt-1 font-medium">Placed on {{ new Date(order.created_at).toLocaleString() }}</p>
            </div>
            <Link :href="route('admin.orders.index')" class="text-[13px] text-gray-500 hover:text-ryans font-bold transition mt-2 lg:mt-0">
                <i class="fas fa-arrow-left mr-1"></i> Back to Orders
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Sub-Orders -->
                <div v-for="sub in order.sub_orders" :key="sub.id" class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="flex items-center justify-between bg-[#f8f9fa] px-5 py-3 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <h3 class="text-[14px] font-bold text-[#111]">{{ sub.shop?.name || 'In-house' }}</h3>
                            <span :class="orderStatusColor(sub.order_status)" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">{{ sub.order_status }}</span>
                        </div>
                        <select @change="updateSubStatus(sub.id, $event.target.value)" :value="sub.order_status" class="text-[12px] border border-gray-300 rounded py-1 px-2 focus:border-ryans bg-white">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <table class="w-full text-[13px]">
                        <thead><tr><th class="text-left px-5 py-2 text-[11px] text-gray-500 uppercase font-bold">Product</th><th class="text-center px-3 py-2 text-[11px] text-gray-500 uppercase font-bold">Qty</th><th class="text-right px-5 py-2 text-[11px] text-gray-500 uppercase font-bold">Price</th><th class="text-right px-5 py-2 text-[11px] text-gray-500 uppercase font-bold">Total</th></tr></thead>
                        <tbody>
                            <tr v-for="item in sub.items" :key="item.id" class="border-t border-gray-50">
                                <td class="px-5 py-3">
                                    <p class="font-bold text-[#111]">{{ item.product?.name || 'Deleted Product' }}</p>
                                    <p v-if="item.variant" class="text-[11px] text-gray-500 mt-0.5">{{ item.variant }}</p>
                                </td>
                                <td class="px-3 py-3 text-center text-gray-600">{{ item.quantity }}</td>
                                <td class="px-5 py-3 text-right text-gray-600">৳{{ formatPrice(item.price) }}</td>
                                <td class="px-5 py-3 text-right font-bold text-[#111]">৳{{ formatPrice(item.price * item.quantity) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-gray-100">
                                <td colspan="3" class="px-5 py-2 text-right font-bold text-gray-600 text-[12px]">Subtotal</td>
                                <td class="px-5 py-2 text-right font-bold text-[#111]">৳{{ formatPrice(sub.sub_total) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-[#111] mb-4 border-b border-gray-100 pb-3">Order Summary</h3>
                    <div class="space-y-2 text-[13px]">
                        <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="font-bold">৳{{ formatPrice(order.sub_total) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span class="font-bold">৳{{ formatPrice(order.shipping_cost) }}</span></div>
                        <div v-if="order.coupon_discount" class="flex justify-between"><span class="text-gray-500">Coupon</span><span class="font-bold text-red-600">-৳{{ formatPrice(order.coupon_discount) }}</span></div>
                        <div class="flex justify-between border-t border-gray-100 pt-2 text-[15px]"><span class="font-bold">Grand Total</span><span class="font-bold text-ryans">৳{{ formatPrice(order.grand_total) }}</span></div>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-[#111] mb-4 border-b border-gray-100 pb-3">Payment</h3>
                    <div class="space-y-3 text-[13px]">
                        <div class="flex justify-between"><span class="text-gray-500">Method</span><span class="font-bold capitalize">{{ order.payment_method }}</span></div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Status</span>
                            <span :class="payStatusColor(order.payment_status)" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">{{ order.payment_status }}</span>
                        </div>
                        <div class="pt-2 border-t border-gray-100">
                            <select v-model="statusForm.payment_status" @change="updatePayment" class="w-full text-[12px] border border-gray-300 rounded py-1.5 px-2 focus:border-ryans bg-gray-50">
                                <option value="unpaid">Unpaid</option><option value="paid">Paid</option><option value="failed">Failed</option><option value="refunded">Refunded</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Customer -->
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-[#111] mb-4 border-b border-gray-100 pb-3">Customer</h3>
                    <div class="space-y-2 text-[13px]">
                        <p class="font-bold text-[#111]">{{ order.user?.name || 'Guest' }}</p>
                        <p class="text-gray-500">{{ order.user?.email }}</p>
                        <p v-if="order.user?.phone" class="text-gray-500">{{ order.user?.phone }}</p>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div v-if="order.address" class="bg-white border border-gray-200 rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-[#111] mb-4 border-b border-gray-100 pb-3">Shipping Address</h3>
                    <p class="text-[13px] text-gray-700 leading-relaxed">
                        {{ order.address.address }}<br>
                        {{ order.address.city }}, {{ order.address.state }}<br>
                        {{ order.address.zip_code }}, {{ order.address.country }}
                    </p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
