<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
const props = defineProps({ customers: Object, filters: Object });
const search = ref(props.filters?.search || '');
let dt = null;
watch(search, () => { clearTimeout(dt); dt = setTimeout(() => { router.get(route('admin.customers.index'), { search: search.value || undefined }, { preserveState: true, replace: true }); }, 300); });
const toggleBan = (id) => { if (confirm('Toggle ban status for this customer?')) { router.put(route('admin.customers.toggle-ban', id)); } };
</script>

<template>
    <Head title="Customers | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Customers</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Customers</h1><p class="text-[13px] text-gray-500 mt-1 font-medium">Manage all registered customers.</p></div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
            <div class="relative max-w-md"><i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[13px]"></i><input v-model="search" type="text" placeholder="Search by name or email..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded text-[13px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">#</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Name</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Email</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Orders</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Joined</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th></tr></thead>
                    <tbody>
                        <tr v-for="(c, i) in customers.data" :key="c.id" class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 text-gray-500">{{ (customers.current_page - 1) * customers.per_page + i + 1 }}</td>
                            <td class="px-4 py-3 font-bold text-[#111]">{{ c.name }}<span v-if="c.banned" class="ml-2 text-[10px] font-bold px-1.5 py-0.5 bg-red-100 text-red-700 rounded">Banned</span></td>
                            <td class="px-4 py-3 text-gray-600">{{ c.email }}</td>
                            <td class="px-4 py-3 font-medium">{{ c.orders_count || 0 }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ new Date(c.created_at).toLocaleDateString() }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <Link :href="route('admin.customers.show', c.id)" class="w-7 h-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition"><i class="fas fa-eye text-[11px]"></i></Link>
                                    <button @click="toggleBan(c.id)" class="w-7 h-7 rounded flex items-center justify-center transition" :class="c.banned ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-red-50 text-red-600 hover:bg-red-100'"><i :class="c.banned ? 'fas fa-unlock' : 'fas fa-ban'" class="text-[11px]"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!customers.data?.length"><td colspan="6" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-users text-4xl mb-3 block"></i><p class="font-medium">No customers found.</p></td></tr>
                    </tbody>
                </table>
            </div>
            <div v-if="customers.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex items-center justify-between bg-[#f8f9fa]">
                <p class="text-[12px] text-gray-500 font-medium">Showing {{ customers.from }}-{{ customers.to }} of {{ customers.total }}</p>
                <div class="flex items-center space-x-1"><template v-for="link in customers.links" :key="link.label"><Link v-if="link.url" :href="link.url" class="px-3 py-1 rounded text-[12px] font-bold transition" :class="link.active ? 'bg-ryans text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-ryans hover:text-ryans'" v-html="link.label" /><span v-else class="px-3 py-1 text-[12px] text-gray-300" v-html="link.label" /></template></div>
            </div>
        </div>
    </AdminLayout>
</template>
