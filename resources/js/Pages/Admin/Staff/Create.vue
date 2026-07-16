<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
const props = defineProps({ roles: Array });
const form = useForm({ name: '', email: '', password: '', role_id: '' });
const submit = () => { form.post(route('admin.staff.store')); };
</script>
<template>
    <Head title="Add Staff | Admin" />
    <AdminLayout>
        <template #header><Link :href="route('admin.staff.index')" class="text-gray-500 hover:text-ryans transition font-medium">Staff</Link><span class="mx-2 text-gray-300">/</span><span class="text-gray-800 font-bold">Add</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Add Staff Member</h1></div>
        <div class="bg-white border border-gray-200 rounded-lg p-6 max-w-2xl">
            <form @submit.prevent="submit" class="space-y-5">
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Name *</label><input v-model="form.name" type="text" required class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white"><p v-if="form.errors.name" class="text-red-500 text-[12px] mt-1">{{ form.errors.name }}</p></div>
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Email *</label><input v-model="form.email" type="email" required class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"><p v-if="form.errors.email" class="text-red-500 text-[12px] mt-1">{{ form.errors.email }}</p></div>
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Password *</label><input v-model="form.password" type="password" required class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"></div>
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Role</label><select v-model="form.role_id" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"><option value="">No Role</option><option v-for="r in roles" :key="r.id" :value="r.id">{{ r.name }}</option></select></div>
                <div class="pt-4 border-t border-gray-100 flex justify-end"><button type="submit" :disabled="form.processing" class="px-8 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition shadow-sm disabled:opacity-50">Create Staff</button></div>
            </form>
        </div>
    </AdminLayout>
</template>
