<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({ methods: Object });
const isEditing = ref(false);
const editingId = ref(null);
const form = useForm({ name: '', cost: 0, is_active: true });
const editMethod = (m) => { isEditing.value = true; editingId.value = m.id; form.name = m.name; form.cost = m.cost; form.is_active = m.is_active; };
const cancelEdit = () => { isEditing.value = false; editingId.value = null; form.reset(); };
const submit = () => { if (isEditing.value) { form.put(route('admin.shipping.update', editingId.value), { onSuccess: () => cancelEdit() }); } else { form.post(route('admin.shipping.store'), { onSuccess: () => form.reset() }); } };
const deleteMethod = (id) => { if (confirm('Delete?')) router.delete(route('admin.shipping.destroy', id)); };
const formatPrice = (p) => new Intl.NumberFormat('en-IN').format(p || 0);
</script>
<template>
    <Head title="Shipping | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Shipping</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Shipping Methods</h1></div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-lg p-5">
                <h3 class="text-[15px] font-bold text-[#111] mb-4 border-b border-gray-100 pb-3">{{ isEditing ? 'Edit' : 'Add' }} Method</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Name *</label><input v-model="form.name" type="text" required class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Cost (৳) *</label><input v-model.number="form.cost" type="number" min="0" step="0.01" required class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans bg-gray-50"></div>
                    <div><label class="flex items-center cursor-pointer"><input type="checkbox" v-model="form.is_active" class="w-4 h-4 text-ryans focus:ring-ryans rounded border-gray-300"><span class="ml-2 text-[13px] font-bold text-gray-700">Active</span></label></div>
                    <div class="flex items-center space-x-2"><button type="submit" :disabled="form.processing" class="flex-1 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition">{{ isEditing ? 'Update' : 'Save' }}</button><button v-if="isEditing" type="button" @click="cancelEdit" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded text-[13px] font-bold">Cancel</button></div>
                </form>
            </div>
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg overflow-hidden">
                <table class="w-full text-[13px]"><thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Name</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Cost</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Status</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Actions</th></tr></thead>
                    <tbody>
                        <tr v-for="m in methods.data" :key="m.id" class="border-b border-gray-50"><td class="px-4 py-3 font-bold text-[#111]">{{ m.name }}</td><td class="px-4 py-3 font-bold">৳{{ formatPrice(m.cost) }}</td><td class="px-4 py-3"><span :class="m.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'" class="text-[11px] font-bold px-2 py-0.5 rounded">{{ m.is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="px-4 py-3 text-center"><div class="flex items-center justify-center space-x-1"><button @click="editMethod(m)" class="w-7 h-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center"><i class="fas fa-pen text-[11px]"></i></button><button @click="deleteMethod(m.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center"><i class="fas fa-trash text-[11px]"></i></button></div></td></tr>
                        <tr v-if="!methods.data?.length"><td colspan="4" class="px-4 py-8 text-center text-gray-400">No shipping methods.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
