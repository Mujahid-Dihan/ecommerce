<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({ roles: Array, allPermissions: Array });
const form = useForm({ name: '', permissions: [] });
const togglePermission = (p) => { const idx = form.permissions.indexOf(p); if (idx >= 0) form.permissions.splice(idx, 1); else form.permissions.push(p); };
const submit = () => { form.post(route('admin.staff.roles.store'), { onSuccess: () => form.reset() }); };
const deleteRole = (id) => { if (confirm('Delete role?')) router.delete(route('admin.staff.roles.destroy', id)); };
</script>
<template>
    <Head title="Roles | Admin" />
    <AdminLayout>
        <template #header><Link :href="route('admin.staff.index')" class="text-gray-500 hover:text-ryans transition font-medium">Staff</Link><span class="mx-2 text-gray-300">/</span><span class="text-gray-800 font-bold">Roles</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Staff Roles</h1></div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-lg p-5">
                <h3 class="text-[15px] font-bold text-[#111] mb-4 border-b border-gray-100 pb-3">Add Role</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Role Name *</label><input v-model="form.name" type="text" required class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
                    <div><label class="text-[13px] font-bold text-gray-700 mb-2 block">Permissions</label>
                        <div class="space-y-1.5 max-h-60 overflow-y-auto">
                            <label v-for="p in allPermissions" :key="p" class="flex items-center cursor-pointer"><input type="checkbox" :checked="form.permissions.includes(p)" @change="togglePermission(p)" class="w-3.5 h-3.5 text-ryans focus:ring-ryans rounded border-gray-300"><span class="ml-2 text-[12px] text-gray-700 capitalize">{{ p.replace('_', ' ') }}</span></label>
                        </div>
                    </div>
                    <button type="submit" :disabled="form.processing" class="w-full py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition">Save Role</button>
                </form>
            </div>
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg overflow-hidden">
                <table class="w-full text-[13px]"><thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Role</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Users</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Permissions</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Actions</th></tr></thead>
                    <tbody>
                        <tr v-for="r in roles" :key="r.id" class="border-b border-gray-50"><td class="px-4 py-3 font-bold text-[#111]">{{ r.name }}</td><td class="px-4 py-3 text-gray-600">{{ r.users_count }}</td><td class="px-4 py-3"><div class="flex flex-wrap gap-1"><span v-for="p in r.permissions" :key="p" class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 capitalize">{{ p.replace('_', ' ') }}</span></div></td><td class="px-4 py-3 text-center"><button @click="deleteRole(r.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center mx-auto"><i class="fas fa-trash text-[11px]"></i></button></td></tr>
                        <tr v-if="!roles?.length"><td colspan="4" class="px-4 py-8 text-center text-gray-400">No roles.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
