<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
const props = defineProps({ staff: Object });
const deleteStaff = (id) => { if (confirm('Remove staff member?')) router.delete(route('admin.staff.destroy', id)); };
</script>
<template>
    <Head title="Staff | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Staff Management</span></template>
        <div class="flex items-center justify-between mb-6">
            <div><h1 class="text-2xl font-bold text-[#111]">Staff Members</h1></div>
            <div class="flex items-center space-x-3">
                <Link :href="route('admin.staff.roles')" class="text-[13px] text-gray-600 hover:text-ryans font-bold"><i class="fas fa-shield-alt mr-1"></i> Roles</Link>
                <Link :href="route('admin.staff.create')" class="inline-flex items-center bg-ryans hover:bg-[#5da733] text-white text-[13px] font-bold px-5 py-2.5 rounded transition shadow-sm"><i class="fas fa-plus mr-2"></i> Add Staff</Link>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-[13px]"><thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">#</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Name</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Email</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Role</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Actions</th></tr></thead>
                <tbody>
                    <tr v-for="(s, i) in staff.data" :key="s.id" class="border-b border-gray-50 hover:bg-gray-50/50"><td class="px-4 py-3 text-gray-500">{{ i + 1 }}</td><td class="px-4 py-3 font-bold text-[#111]">{{ s.name }}</td><td class="px-4 py-3 text-gray-600">{{ s.email }}</td><td class="px-4 py-3"><span v-for="r in s.staff_roles" :key="r.id" class="text-[11px] font-bold px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 mr-1">{{ r.name }}</span><span v-if="!s.staff_roles?.length" class="text-[11px] text-gray-400">No Role</span></td><td class="px-4 py-3 text-center"><button @click="deleteStaff(s.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center mx-auto"><i class="fas fa-trash text-[11px]"></i></button></td></tr>
                    <tr v-if="!staff.data?.length"><td colspan="5" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-users-cog text-4xl mb-3 block"></i><p class="font-medium">No staff members.</p></td></tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
