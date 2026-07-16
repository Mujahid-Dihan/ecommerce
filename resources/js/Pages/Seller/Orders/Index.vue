<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({ orders: Object, filters: Object });

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

let dt = null;
const applyFilters = () => {
    clearTimeout(dt);
    dt = setTimeout(() => {
        router.get(route('seller.orders.index'), {
            search: search.value || undefined,
            status: status.value || undefined,
        }, { preserveState: true, replace: true });
    }, 300);
};
watch([search, status], applyFilters);

const formatPrice = (p) => new Intl.NumberFormat('en-IN').format(p || 0);
const orderStatusColor = (s) => {
    const c = { pending: 'bg-yellow-100 text-yellow-700', confirmed: 'bg-blue-100 text-blue-700', shipped: 'bg-indigo-100 text-indigo-700', delivered: 'bg-green-100 text-green-700', cancelled: 'bg-red-100 text-red-700' };
    return c[s] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head title="My Orders | Seller" />
    <SellerLayout>
        <template #header><span class="text-gray-800 font-bold">Orders</span></template>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#111]">My Orders</h1>
            <p class="text-[13px] text-gray-500 mt-1 font-medium">Manage orders from your shop.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[13px]"></i>
                    <input v-model="search" type="text" placeholder="Search order ID, customer..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded text-[13px] focus:border-ryans bg-gray-50 focus:bg-white">
                </div>
                <select v-model="status" class="border border-gray-300 rounded text-[13px] py-2 focus:border-ryans bg-gray-50">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option><option value="confirmed">Confirmed</option><option value="shipped">Shipped</option><option value="delivered">Delivered</option><option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead class="bg-[#f8f9fa]"><tr>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Order</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Customer</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Amount</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Date</th>
                        <th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th>
                    </tr></thead>
                    <tbody>
                        <tr v-for="ord in orders.data" :key="ord.id" class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 font-bold text-ryans">#{{ ord.order_id }}-{{ ord.id }}</td>
                            <td class="px-4 py-3 font-medium text-[#111]">{{ ord.order?.user?.name || 'Guest' }}</td>
                            <td class="px-4 py-3 font-bold text-[#111]">৳{{ formatPrice(ord.sub_total) }}</td>
                            <td class="px-4 py-3"><span :class="orderStatusColor(ord.order_status)" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">{{ ord.order_status }}</span></td>
                            <td class="px-4 py-3 text-gray-500">{{ new Date(ord.created_at).toLocaleDateString() }}</td>
                            <td class="px-4 py-3 text-center">
                                <Link :href="route('seller.orders.show', ord.id)" class="inline-flex items-center justify-center w-8 h-8 rounded bg-ryans/10 text-ryans hover:bg-ryans hover:text-white transition"><i class="fas fa-eye text-[12px]"></i></Link>
                            </td>
                        </tr>
                        <tr v-if="!orders.data?.length"><td colspan="6" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-box-open text-4xl mb-3 block"></i><p class="font-medium">No orders found.</p></td></tr>
                    </tbody>
                </table>
            </div>
            <div v-if="orders.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex items-center justify-between bg-[#f8f9fa]">
                <p class="text-[12px] text-gray-500 font-medium">Showing {{ orders.from }}-{{ orders.to }} of {{ orders.total }}</p>
                <div class="flex items-center space-x-1">
                    <template v-for="link in orders.links" :key="link.label"><Link v-if="link.url" :href="link.url" class="px-3 py-1 rounded text-[12px] font-bold transition" :class="link.active ? 'bg-ryans text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-ryans hover:text-ryans'" v-html="link.label" /><span v-else class="px-3 py-1 text-[12px] text-gray-300" v-html="link.label" /></template>
                </div>
            </div>
        </div>
    </SellerLayout>
</template>
