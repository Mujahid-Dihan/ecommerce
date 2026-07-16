<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
const props = defineProps({ refunds: Object, filters: Object });
const status = ref(props.filters?.status || '');
watch(status, () => { router.get(route('admin.refunds.index'), { status: status.value || undefined }, { preserveState: true, replace: true }); });
const approve = (id) => { router.put(route('admin.refunds.approve', id)); };
const reject = (id) => { if (confirm('Reject this refund request?')) router.put(route('admin.refunds.reject', id)); };
const formatPrice = (p) => new Intl.NumberFormat('en-IN').format(p || 0);
const statusColor = (s) => ({ pending: 'bg-yellow-100 text-yellow-700', approved: 'bg-green-100 text-green-700', rejected: 'bg-red-100 text-red-700' }[s] || 'bg-gray-100 text-gray-700');
</script>

<template>
    <Head title="Refunds | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Refund Requests</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Refund Requests</h1><p class="text-[13px] text-gray-500 mt-1 font-medium">Review and manage customer refund requests.</p></div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
            <select v-model="status" class="border border-gray-300 rounded text-[13px] py-2 px-3 focus:border-ryans bg-gray-50 w-48"><option value="">All Status</option><option value="pending">Pending</option><option value="approved">Approved</option><option value="rejected">Rejected</option></select>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead class="bg-[#f8f9fa]"><tr>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">#</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Customer</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Product</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Amount</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Reason</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th>
                        <th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th>
                    </tr></thead>
                    <tbody>
                        <tr v-for="(r, i) in refunds.data" :key="r.id" class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 text-gray-500">{{ r.id }}</td>
                            <td class="px-4 py-3 font-bold text-[#111]">{{ r.user?.name || 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ r.order_item?.product?.name || 'N/A' }}</td>
                            <td class="px-4 py-3 font-bold">৳{{ formatPrice(r.amount) }}</td>
                            <td class="px-4 py-3 text-gray-600 max-w-[200px] truncate">{{ r.reason }}</td>
                            <td class="px-4 py-3"><span :class="statusColor(r.status)" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">{{ r.status }}</span></td>
                            <td class="px-4 py-3 text-center">
                                <div v-if="r.status === 'pending'" class="flex items-center justify-center space-x-1">
                                    <button @click="approve(r.id)" class="w-7 h-7 rounded bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center transition" title="Approve"><i class="fas fa-check text-[11px]"></i></button>
                                    <button @click="reject(r.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Reject"><i class="fas fa-times text-[11px]"></i></button>
                                </div>
                                <span v-else class="text-[11px] text-gray-400">—</span>
                            </td>
                        </tr>
                        <tr v-if="!refunds.data?.length"><td colspan="7" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-undo text-4xl mb-3 block"></i><p class="font-medium">No refund requests.</p></td></tr>
                    </tbody>
                </table>
            </div>
            <div v-if="refunds.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex items-center justify-between bg-[#f8f9fa]">
                <p class="text-[12px] text-gray-500 font-medium">Showing {{ refunds.from }}-{{ refunds.to }} of {{ refunds.total }}</p>
                <div class="flex items-center space-x-1"><template v-for="link in refunds.links" :key="link.label"><Link v-if="link.url" :href="link.url" class="px-3 py-1 rounded text-[12px] font-bold transition" :class="link.active ? 'bg-ryans text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-ryans hover:text-ryans'" v-html="link.label" /><span v-else class="px-3 py-1 text-[12px] text-gray-300" v-html="link.label" /></template></div>
            </div>
        </div>
    </AdminLayout>
</template>
