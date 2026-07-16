<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
const props = defineProps({ sellers: Object, filters: Object });
const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');
let dt = null;
const applyFilters = () => { clearTimeout(dt); dt = setTimeout(() => { router.get(route('admin.sellers.index'), { search: search.value || undefined, status: status.value || undefined }, { preserveState: true, replace: true }); }, 300); };
watch([search, status], applyFilters);
const approve = (id) => { router.put(route('admin.sellers.approve', id)); };
const reject = (id) => { if (confirm('Revoke seller approval?')) router.put(route('admin.sellers.reject', id)); };
</script>

<template>
    <Head title="Sellers | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Sellers</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Seller Management</h1><p class="text-[13px] text-gray-500 mt-1 font-medium">Manage all seller/vendor accounts.</p></div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative md:col-span-2"><i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[13px]"></i><input v-model="search" type="text" placeholder="Search by name, email, or shop..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded text-[13px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
                <select v-model="status" class="border border-gray-300 rounded text-[13px] py-2 focus:border-ryans bg-gray-50"><option value="">All</option><option value="approved">Approved</option><option value="pending">Pending</option></select>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead class="bg-[#f8f9fa]"><tr>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">#</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Seller</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Shop</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Joined</th>
                        <th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th>
                    </tr></thead>
                    <tbody>
                        <tr v-for="(s, i) in sellers.data" :key="s.id" class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 text-gray-500">{{ (sellers.current_page - 1) * sellers.per_page + i + 1 }}</td>
                            <td class="px-4 py-3"><p class="font-bold text-[#111]">{{ s.name }}</p><p class="text-[11px] text-gray-500">{{ s.email }}</p></td>
                            <td class="px-4 py-3 font-medium text-gray-700">{{ s.shop?.name || 'No Shop' }}</td>
                            <td class="px-4 py-3">
                                <span v-if="s.shop?.is_approved" class="text-[11px] font-bold px-2 py-0.5 rounded bg-green-100 text-green-700">Approved</span>
                                <span v-else class="text-[11px] font-bold px-2 py-0.5 rounded bg-yellow-100 text-yellow-700">Pending</span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ new Date(s.created_at).toLocaleDateString() }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <Link :href="route('admin.sellers.show', s.id)" class="w-7 h-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition"><i class="fas fa-eye text-[11px]"></i></Link>
                                    <button v-if="!s.shop?.is_approved" @click="approve(s.id)" class="w-7 h-7 rounded bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center transition" title="Approve"><i class="fas fa-check text-[11px]"></i></button>
                                    <button v-else @click="reject(s.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Revoke"><i class="fas fa-times text-[11px]"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!sellers.data?.length"><td colspan="6" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-store text-4xl mb-3 block"></i><p class="font-medium">No sellers found.</p></td></tr>
                    </tbody>
                </table>
            </div>
            <div v-if="sellers.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex items-center justify-between bg-[#f8f9fa]">
                <p class="text-[12px] text-gray-500 font-medium">Showing {{ sellers.from }}-{{ sellers.to }} of {{ sellers.total }}</p>
                <div class="flex items-center space-x-1"><template v-for="link in sellers.links" :key="link.label"><Link v-if="link.url" :href="link.url" class="px-3 py-1 rounded text-[12px] font-bold transition" :class="link.active ? 'bg-ryans text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-ryans hover:text-ryans'" v-html="link.label" /><span v-else class="px-3 py-1 text-[12px] text-gray-300" v-html="link.label" /></template></div>
            </div>
        </div>
    </AdminLayout>
</template>
