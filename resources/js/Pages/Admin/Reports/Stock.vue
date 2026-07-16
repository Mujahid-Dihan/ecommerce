<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
const props = defineProps({ lowStock: Array, outOfStock: Array });
</script>
<template>
    <Head title="Stock Report | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Stock Report</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Stock Report</h1></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-5"><p class="text-[12px] text-gray-500 font-bold uppercase">Low Stock</p><p class="text-2xl font-bold text-amber-600 mt-1">{{ lowStock?.length || 0 }}</p></div>
            <div class="bg-white border border-gray-200 rounded-lg p-5"><p class="text-[12px] text-gray-500 font-bold uppercase">Out of Stock</p><p class="text-2xl font-bold text-red-600 mt-1">{{ outOfStock?.length || 0 }}</p></div>
        </div>
        <div v-if="outOfStock?.length" class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-6">
            <div class="px-5 py-3 bg-red-50 border-b border-red-100"><h3 class="text-[14px] font-bold text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i> Out of Stock</h3></div>
            <table class="w-full text-[13px]"><tbody><tr v-for="p in outOfStock" :key="p.id" class="border-b border-gray-50"><td class="px-4 py-3 font-bold text-[#111]">{{ p.name }}</td><td class="px-4 py-3 text-red-600 font-bold">{{ p.variants?.[0]?.stock || 0 }}</td></tr></tbody></table>
        </div>
        <div v-if="lowStock?.length" class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-5 py-3 bg-amber-50 border-b border-amber-100"><h3 class="text-[14px] font-bold text-amber-700"><i class="fas fa-exclamation-circle mr-2"></i> Low Stock</h3></div>
            <table class="w-full text-[13px]"><tbody><tr v-for="p in lowStock" :key="p.id" class="border-b border-gray-50"><td class="px-4 py-3 font-bold text-[#111]">{{ p.name }}</td><td class="px-4 py-3 text-amber-600 font-bold">{{ p.variants?.[0]?.stock }}</td></tr></tbody></table>
        </div>
    </AdminLayout>
</template>
