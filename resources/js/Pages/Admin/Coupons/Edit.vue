<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
const props = defineProps({ coupon: Object });
const form = useForm({ _method: 'PUT', code: props.coupon.code, type: props.coupon.type, discount: props.coupon.discount, min_spend: props.coupon.min_spend || '', usage_limit: props.coupon.usage_limit || '', start_date: props.coupon.start_date?.split('T')[0] || '', end_date: props.coupon.end_date?.split('T')[0] || '', is_active: props.coupon.is_active });
const submit = () => { form.post(route('admin.coupons.update', props.coupon.id)); };
</script>
<template>
    <Head title="Edit Coupon | Admin" />
    <AdminLayout>
        <template #header><Link :href="route('admin.coupons.index')" class="text-gray-500 hover:text-ryans transition font-medium">Coupons</Link><span class="mx-2 text-gray-300">/</span><span class="text-gray-800 font-bold">Edit</span></template>
        <div class="flex items-center justify-between mb-6"><h1 class="text-2xl font-bold text-[#111]">Edit Coupon</h1><Link :href="route('admin.coupons.index')" class="text-[13px] text-gray-500 hover:text-ryans font-bold"><i class="fas fa-arrow-left mr-1"></i> Back</Link></div>
        <div class="bg-white border border-gray-200 rounded-lg p-6 max-w-2xl">
            <form @submit.prevent="submit" class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Coupon Code *</label><input v-model="form.code" type="text" required class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 uppercase font-mono"></div>
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Discount Type *</label><select v-model="form.type" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Discount *</label><input v-model.number="form.discount" type="number" min="0" required class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"></div>
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Min. Spend</label><input v-model.number="form.min_spend" type="number" min="0" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Usage Limit</label><input v-model.number="form.usage_limit" type="number" min="1" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"></div>
                    <div><label class="flex items-center cursor-pointer mt-7"><input type="checkbox" v-model="form.is_active" class="w-4 h-4 text-ryans focus:ring-ryans rounded border-gray-300"><span class="ml-2 text-[13px] font-bold text-gray-700">Active</span></label></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Start Date</label><input v-model="form.start_date" type="date" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"></div>
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">End Date</label><input v-model="form.end_date" type="date" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"></div>
                </div>
                <div class="pt-4 border-t border-gray-100 flex justify-end"><button type="submit" :disabled="form.processing" class="px-8 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition shadow-sm disabled:opacity-50">Update Coupon</button></div>
            </form>
        </div>
    </AdminLayout>
</template>
