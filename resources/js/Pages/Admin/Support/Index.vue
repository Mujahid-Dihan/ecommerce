<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
const props = defineProps({ tickets: Object, filters: Object });
const status = ref(props.filters?.status || '');
watch(status, () => { router.get(route('admin.support.index'), { status: status.value || undefined }, { preserveState: true, replace: true }); });
const statusColor = (s) => ({ open: 'bg-blue-100 text-blue-700', in_progress: 'bg-yellow-100 text-yellow-700', closed: 'bg-gray-100 text-gray-600' }[s] || 'bg-gray-100 text-gray-700');
</script>
<template>
    <Head title="Support | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Support Tickets</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Support Tickets</h1></div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6"><select v-model="status" class="border border-gray-300 rounded text-[13px] py-2 px-3 focus:border-ryans bg-gray-50 w-48"><option value="">All</option><option value="open">Open</option><option value="in_progress">In Progress</option><option value="closed">Closed</option></select></div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-[13px]"><thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">#</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Subject</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">User</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Date</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th></tr></thead>
                <tbody>
                    <tr v-for="t in tickets.data" :key="t.id" class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-gray-500">{{ t.id }}</td><td class="px-4 py-3 font-bold text-[#111]">{{ t.subject }}</td><td class="px-4 py-3 text-gray-600">{{ t.user?.name || 'N/A' }}</td><td class="px-4 py-3"><span :class="statusColor(t.status)" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">{{ t.status?.replace('_', ' ') }}</span></td><td class="px-4 py-3 text-gray-500">{{ new Date(t.created_at).toLocaleDateString() }}</td><td class="px-4 py-3 text-center"><Link :href="route('admin.support.show', t.id)" class="w-8 h-8 rounded bg-ryans/10 text-ryans hover:bg-ryans hover:text-white flex items-center justify-center transition mx-auto"><i class="fas fa-eye text-[12px]"></i></Link></td>
                    </tr>
                    <tr v-if="!tickets.data?.length"><td colspan="6" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-headset text-4xl mb-3 block"></i><p class="font-medium">No tickets.</p></td></tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
