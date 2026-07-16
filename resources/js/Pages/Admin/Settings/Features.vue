<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
const props = defineProps({ settings: Object });
const form = useForm({ pos_enabled: props.settings?.pos_enabled === '1', wallet_enabled: props.settings?.wallet_enabled === '1', affiliate_enabled: props.settings?.affiliate_enabled === '1', wholesale_enabled: props.settings?.wholesale_enabled === '1', auction_enabled: props.settings?.auction_enabled === '1', classified_enabled: props.settings?.classified_enabled === '1', blog_enabled: props.settings?.blog_enabled === '1', newsletter_enabled: props.settings?.newsletter_enabled === '1', refund_enabled: props.settings?.refund_enabled === '1', flash_deals_enabled: props.settings?.flash_deals_enabled === '1' });
const submit = () => { form.post(route('admin.settings.features.update')); };
const features = [
    { key: 'pos_enabled', label: 'POS System', icon: 'fas fa-cash-register' },
    { key: 'wallet_enabled', label: 'Customer Wallet', icon: 'fas fa-wallet' },
    { key: 'affiliate_enabled', label: 'Affiliate System', icon: 'fas fa-handshake' },
    { key: 'wholesale_enabled', label: 'Wholesale Pricing', icon: 'fas fa-box' },
    { key: 'auction_enabled', label: 'Auction System', icon: 'fas fa-gavel' },
    { key: 'classified_enabled', label: 'Classified Listings', icon: 'fas fa-list' },
    { key: 'blog_enabled', label: 'Blog', icon: 'fas fa-newspaper' },
    { key: 'newsletter_enabled', label: 'Newsletter', icon: 'fas fa-envelope' },
    { key: 'refund_enabled', label: 'Refund System', icon: 'fas fa-undo' },
    { key: 'flash_deals_enabled', label: 'Flash Deals', icon: 'fas fa-bolt' },
];
</script>
<template>
    <Head title="Feature Settings | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Feature Settings</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Feature Activation</h1><p class="text-[13px] text-gray-500 mt-1 font-medium">Toggle system modules on or off.</p></div>
        <form @submit.prevent="submit" class="max-w-2xl">
            <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100">
                <div v-for="f in features" :key="f.key" class="flex items-center justify-between px-5 py-4 hover:bg-gray-50/50 transition">
                    <div class="flex items-center"><i :class="f.icon" class="w-8 text-gray-400 text-[14px]"></i><span class="text-[14px] font-bold text-[#111]">{{ f.label }}</span></div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form[f.key]" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-ryans/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ryans"></div>
                    </label>
                </div>
            </div>
            <div class="mt-4 flex justify-end"><button type="submit" :disabled="form.processing" class="px-8 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition shadow-sm disabled:opacity-50">Save Features</button></div>
        </form>
    </AdminLayout>
</template>
