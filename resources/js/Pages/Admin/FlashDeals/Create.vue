<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
const props = defineProps({ products: Array });
const form = useForm({ title: '', start_date: '', end_date: '', is_active: true, banner: null, products: [] });
const addProduct = () => { form.products.push({ product_id: '', discount: 0, discount_type: 'percentage' }); };
const removeProduct = (i) => { form.products.splice(i, 1); };
const submit = () => { form.post(route('admin.flash-deals.store'), { forceFormData: true }); };
</script>
<template>
    <Head title="New Flash Deal | Admin" />
    <AdminLayout>
        <template #header><Link :href="route('admin.flash-deals.index')" class="text-gray-500 hover:text-ryans transition font-medium">Flash Deals</Link><span class="mx-2 text-gray-300">/</span><span class="text-gray-800 font-bold">Create</span></template>
        <div class="flex items-center justify-between mb-6"><h1 class="text-2xl font-bold text-[#111]">New Flash Deal</h1><Link :href="route('admin.flash-deals.index')" class="text-[13px] text-gray-500 hover:text-ryans font-bold"><i class="fas fa-arrow-left mr-1"></i> Back</Link></div>
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-5">
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Title *</label><input v-model="form.title" type="text" required class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Start Date *</label><input v-model="form.start_date" type="datetime-local" required class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"></div>
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">End Date *</label><input v-model="form.end_date" type="datetime-local" required class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"></div>
                </div>
                <div><label class="flex items-center cursor-pointer"><input type="checkbox" v-model="form.is_active" class="w-4 h-4 text-ryans focus:ring-ryans rounded border-gray-300"><span class="ml-2 text-[13px] font-bold text-gray-700">Active</span></label></div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4"><h3 class="text-[15px] font-bold text-[#111]">Deal Products</h3><button type="button" @click="addProduct" class="text-[13px] text-ryans font-bold hover:underline"><i class="fas fa-plus mr-1"></i> Add Product</button></div>
                <div v-for="(p, i) in form.products" :key="i" class="border border-gray-200 rounded-lg p-4 bg-[#f8f9fa] mb-3 relative">
                    <button type="button" @click="removeProduct(i)" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-[11px]"><i class="fas fa-times"></i></button>
                    <div class="grid grid-cols-3 gap-3">
                        <select v-model="p.product_id" class="border border-gray-300 rounded py-2 px-3 text-[13px] bg-white focus:border-ryans"><option value="" disabled>Select Product</option><option v-for="pr in products" :key="pr.id" :value="pr.id">{{ pr.name }}</option></select>
                        <input v-model.number="p.discount" type="number" min="0" placeholder="Discount" class="border border-gray-300 rounded py-2 px-3 text-[13px] bg-white focus:border-ryans">
                        <select v-model="p.discount_type" class="border border-gray-300 rounded py-2 px-3 text-[13px] bg-white focus:border-ryans"><option value="percentage">Percentage</option><option value="fixed">Fixed Amount</option></select>
                    </div>
                </div>
            </div>
            <div class="flex justify-end"><button type="submit" :disabled="form.processing" class="px-8 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition shadow-sm disabled:opacity-50"><i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i> Create Flash Deal</button></div>
        </form>
    </AdminLayout>
</template>
