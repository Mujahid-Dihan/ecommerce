<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
const props = defineProps({ ticket: Object });
const replyForm = useForm({ message: '' });
const submitReply = () => { replyForm.post(route('admin.support.reply', props.ticket.id), { onSuccess: () => replyForm.reset() }); };
const updateStatus = (status) => { router.put(route('admin.support.update-status', props.ticket.id), { status }); };
const statusColor = (s) => ({ open: 'bg-blue-100 text-blue-700', in_progress: 'bg-yellow-100 text-yellow-700', closed: 'bg-gray-100 text-gray-600' }[s] || 'bg-gray-100 text-gray-700');
</script>
<template>
    <Head :title="'Ticket #' + ticket.id + ' | Support'" />
    <AdminLayout>
        <template #header><Link :href="route('admin.support.index')" class="text-gray-500 hover:text-ryans transition font-medium">Support</Link><span class="mx-2 text-gray-300">/</span><span class="text-gray-800 font-bold">#{{ ticket.id }}</span></template>
        <div class="flex items-center justify-between mb-6">
            <div><h1 class="text-2xl font-bold text-[#111]">{{ ticket.subject }}</h1><p class="text-[13px] text-gray-500 mt-1">By {{ ticket.user?.name }} · {{ new Date(ticket.created_at).toLocaleString() }}</p></div>
            <div class="flex items-center space-x-2">
                <span :class="statusColor(ticket.status)" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">{{ ticket.status?.replace('_', ' ') }}</span>
                <select @change="updateStatus($event.target.value)" :value="ticket.status" class="text-[12px] border border-gray-300 rounded py-1 px-2 bg-white focus:border-ryans"><option value="open">Open</option><option value="in_progress">In Progress</option><option value="closed">Closed</option></select>
            </div>
        </div>
        <div class="max-w-3xl space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4"><p class="text-[13px] text-gray-800">{{ ticket.description || ticket.message }}</p></div>
            <div v-for="r in ticket.replies" :key="r.id" class="rounded-lg p-4 border" :class="r.user_id === ticket.user_id ? 'bg-gray-50 border-gray-200' : 'bg-green-50 border-green-200'">
                <div class="flex items-center justify-between mb-2"><p class="text-[12px] font-bold" :class="r.user_id === ticket.user_id ? 'text-gray-700' : 'text-green-700'">{{ r.user?.name || 'Admin' }}</p><p class="text-[11px] text-gray-400">{{ new Date(r.created_at).toLocaleString() }}</p></div>
                <p class="text-[13px] text-gray-800">{{ r.message }}</p>
            </div>
            <form v-if="ticket.status !== 'closed'" @submit.prevent="submitReply" class="bg-white border border-gray-200 rounded-lg p-4">
                <textarea v-model="replyForm.message" rows="3" required placeholder="Type your reply..." class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white mb-3"></textarea>
                <div class="flex justify-end"><button type="submit" :disabled="replyForm.processing" class="px-6 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition">Send Reply</button></div>
            </form>
        </div>
    </AdminLayout>
</template>
