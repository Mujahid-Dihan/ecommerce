<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
const props = defineProps({ deals: Object });
const deleteDeal = (id) => { if (confirm('Delete this flash deal?')) router.delete(route('admin.flash-deals.destroy', id)); };
</script>
<template>
    <Head title="Flash Deals | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Flash Deals</span></template>
        <div class="flex items-center justify-between mb-6">
            <div><h1 class="text-2xl font-bold text-[#111]">Flash Deals</h1><p class="text-[13px] text-gray-500 mt-1 font-medium">Manage time-limited promotions.</p></div>
            <Link :href="route('admin.flash-deals.create')" class="inline-flex items-center bg-ryans hover:bg-[#5da733] text-white text-[13px] font-bold px-5 py-2.5 rounded transition shadow-sm"><i class="fas fa-plus mr-2"></i> New Flash Deal</Link>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-[13px]">
                <thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Title</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Period</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Products</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th></tr></thead>
                <tbody>
                    <tr v-for="d in deals.data" :key="d.id" class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                        <td class="px-4 py-3 font-bold text-[#111]">{{ d.title }}</td>
                        <td class="px-4 py-3 text-gray-600 text-[12px]">{{ new Date(d.start_date).toLocaleDateString() }} → {{ new Date(d.end_date).toLocaleDateString() }}</td>
                        <td class="px-4 py-3"><span class="text-[11px] font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-700">{{ d.products?.length || 0 }}</span></td>
                        <td class="px-4 py-3"><span :class="d.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'" class="text-[11px] font-bold px-2 py-0.5 rounded">{{ d.is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="px-4 py-3 text-center"><button @click="deleteDeal(d.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition mx-auto"><i class="fas fa-trash text-[11px]"></i></button></td>
                    </tr>
                    <tr v-if="!deals.data?.length"><td colspan="5" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-bolt text-4xl mb-3 block"></i><p class="font-medium">No flash deals.</p></td></tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
