<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
const props = defineProps({ seller: Object, stats: Object, recentProducts: Array });
const formatPrice = (p) => new Intl.NumberFormat('en-IN').format(p || 0);
const commissionForm = useForm({ commission_rate: props.seller.shop?.commission_rate || 0 });
const updateCommission = () => { commissionForm.put(route('admin.sellers.update-commission', props.seller.id)); };
</script>

<template>
    <Head :title="seller.name + ' | Seller Detail'" />
    <AdminLayout>
        <template #header>
            <Link :href="route('admin.sellers.index')" class="text-gray-500 hover:text-ryans transition font-medium">Sellers</Link>
            <span class="mx-2 text-gray-300">/</span><span class="text-gray-800 font-bold">{{ seller.name }}</span>
        </template>

        <div class="flex items-center justify-between mb-6">
            <div><h1 class="text-2xl font-bold text-[#111]">{{ seller.name }}</h1><p class="text-[13px] text-gray-500 mt-1">{{ seller.email }} · Shop: {{ seller.shop?.name || 'N/A' }}</p></div>
            <Link :href="route('admin.sellers.index')" class="text-[13px] text-gray-500 hover:text-ryans font-bold"><i class="fas fa-arrow-left mr-1"></i> Back</Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-5"><p class="text-[12px] text-gray-500 font-bold uppercase">Products</p><p class="text-2xl font-bold text-[#111] mt-1">{{ stats.total_products || 0 }}</p></div>
            <div class="bg-white border border-gray-200 rounded-lg p-5"><p class="text-[12px] text-gray-500 font-bold uppercase">Orders</p><p class="text-2xl font-bold text-[#111] mt-1">{{ stats.total_orders || 0 }}</p></div>
            <div class="bg-white border border-gray-200 rounded-lg p-5"><p class="text-[12px] text-gray-500 font-bold uppercase">Revenue</p><p class="text-2xl font-bold text-[#111] mt-1">৳{{ formatPrice(stats.total_revenue) }}</p></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-5 py-3 bg-[#f8f9fa] border-b border-gray-200"><h3 class="text-[14px] font-bold text-[#111]">Recent Products</h3></div>
                    <table class="w-full text-[13px]">
                        <thead><tr><th class="text-left px-4 py-2 text-[11px] text-gray-500 uppercase font-bold">Product</th><th class="text-left px-4 py-2 text-[11px] text-gray-500 uppercase font-bold">Status</th></tr></thead>
                        <tbody>
                            <tr v-for="p in recentProducts" :key="p.id" class="border-t border-gray-50">
                                <td class="px-4 py-3 font-bold text-[#111]">{{ p.name }}</td>
                                <td class="px-4 py-3"><span class="text-[11px] font-bold px-2 py-0.5 rounded capitalize" :class="p.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'">{{ p.status }}</span></td>
                            </tr>
                            <tr v-if="!recentProducts?.length"><td colspan="2" class="px-4 py-8 text-center text-gray-400">No products.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-[#111] mb-4 border-b border-gray-100 pb-3">Commission Rate</h3>
                    <div class="flex items-center space-x-2">
                        <input v-model.number="commissionForm.commission_rate" type="number" min="0" max="100" step="0.5" class="w-20 border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans bg-gray-50">
                        <span class="text-[13px] text-gray-600 font-bold">%</span>
                        <button @click="updateCommission" :disabled="commissionForm.processing" class="px-4 py-2 bg-ryans hover:bg-[#5da733] text-white rounded text-[12px] font-bold transition">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
