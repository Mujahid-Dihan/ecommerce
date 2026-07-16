<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
const props = defineProps({ coupons: Object });
const formatPrice = (p) => new Intl.NumberFormat('en-IN').format(p || 0);
const deleteCoupon = (id) => { if (confirm('Delete this coupon?')) router.delete(route('admin.coupons.destroy', id)); };
</script>
<template>
    <Head title="Coupons | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Coupons</span></template>
        <div class="flex items-center justify-between mb-6">
            <div><h1 class="text-2xl font-bold text-[#111]">Coupon Codes</h1><p class="text-[13px] text-gray-500 mt-1 font-medium">Manage promotional coupons.</p></div>
            <Link :href="route('admin.coupons.create')" class="inline-flex items-center bg-ryans hover:bg-[#5da733] text-white text-[13px] font-bold px-5 py-2.5 rounded transition shadow-sm"><i class="fas fa-plus mr-2"></i> New Coupon</Link>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-[13px]">
                <thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Code</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Type</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Discount</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Usage</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Expires</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th></tr></thead>
                <tbody>
                    <tr v-for="c in coupons.data" :key="c.id" class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                        <td class="px-4 py-3 font-bold text-ryans font-mono">{{ c.code }}</td>
                        <td class="px-4 py-3 capitalize text-gray-700">{{ c.type }}</td>
                        <td class="px-4 py-3 font-bold">{{ c.type === 'percentage' ? c.discount + '%' : '৳' + formatPrice(c.discount) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ c.used_count }}/{{ c.usage_limit || '∞' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ c.end_date ? new Date(c.end_date).toLocaleDateString() : 'Never' }}</td>
                        <td class="px-4 py-3"><span :class="c.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'" class="text-[11px] font-bold px-2 py-0.5 rounded">{{ c.is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center space-x-1">
                                <Link :href="route('admin.coupons.edit', c.id)" class="w-7 h-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition"><i class="fas fa-pen text-[11px]"></i></Link>
                                <button @click="deleteCoupon(c.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition"><i class="fas fa-trash text-[11px]"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!coupons.data?.length"><td colspan="7" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-ticket-alt text-4xl mb-3 block"></i><p class="font-medium">No coupons.</p></td></tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
