<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
const props = defineProps({ settings: Object });
const form = useForm({ site_name: props.settings?.site_name || '', site_tagline: props.settings?.site_tagline || '', contact_email: props.settings?.contact_email || '', contact_phone: props.settings?.contact_phone || '', address: props.settings?.address || '', footer_text: props.settings?.footer_text || '' });
const submit = () => { form.post(route('admin.settings.general.update')); };
</script>
<template>
    <Head title="General Settings | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">General Settings</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">General Settings</h1></div>
        <div class="bg-white border border-gray-200 rounded-lg p-6 max-w-2xl">
            <form @submit.prevent="submit" class="space-y-5">
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Site Name</label><input v-model="form.site_name" type="text" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Tagline</label><input v-model="form.site_tagline" type="text" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Contact Email</label><input v-model="form.contact_email" type="email" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"></div>
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Contact Phone</label><input v-model="form.contact_phone" type="text" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"></div>
                </div>
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Address</label><textarea v-model="form.address" rows="2" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white"></textarea></div>
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Footer Text</label><input v-model="form.footer_text" type="text" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
                <div class="pt-4 border-t border-gray-100 flex justify-end"><button type="submit" :disabled="form.processing" class="px-8 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition shadow-sm disabled:opacity-50">Save Settings</button></div>
            </form>
        </div>
    </AdminLayout>
</template>
