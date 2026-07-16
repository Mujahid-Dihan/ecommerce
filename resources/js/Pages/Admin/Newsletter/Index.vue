<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
const props = defineProps({ subscribers: Object });
const remove = (id) => { if (confirm('Remove subscriber?')) router.delete(route('admin.newsletter.destroy', id)); };
</script>
<template>
    <Head title="Newsletter | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Newsletter</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Newsletter Subscribers</h1><p class="text-[13px] text-gray-500 mt-1 font-medium">{{ subscribers.total || 0 }} total subscribers.</p></div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-[13px]"><thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">#</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Email</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Subscribed</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th></tr></thead>
                <tbody>
                    <tr v-for="(s, i) in subscribers.data" :key="s.id" class="border-b border-gray-50 hover:bg-gray-50/50"><td class="px-4 py-3 text-gray-500">{{ (subscribers.current_page - 1) * subscribers.per_page + i + 1 }}</td><td class="px-4 py-3 font-bold text-[#111]">{{ s.email }}</td><td class="px-4 py-3 text-gray-500">{{ new Date(s.created_at).toLocaleDateString() }}</td><td class="px-4 py-3 text-center"><button @click="remove(s.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition mx-auto"><i class="fas fa-trash text-[11px]"></i></button></td></tr>
                    <tr v-if="!subscribers.data?.length"><td colspan="4" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-envelope text-4xl mb-3 block"></i><p class="font-medium">No subscribers yet.</p></td></tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
