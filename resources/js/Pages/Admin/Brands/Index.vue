<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    brands: Object,
});

const deleteBrand = (id) => {
    if (confirm('Are you sure you want to delete this brand?')) {
        router.delete(route('admin.brands.destroy', id));
    }
};
</script>

<template>
    <Head title="Brands | Admin" />

    <AdminLayout>
        <template #header>
            <span class="text-gray-800 font-bold">Brands</span>
        </template>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#111]">Brands</h1>
                <p class="text-[13px] text-gray-500 mt-1 font-medium">Manage product brands.</p>
            </div>
            <Link :href="route('admin.brands.create')" class="mt-3 sm:mt-0 inline-flex items-center bg-ryans hover:bg-[#5da733] text-white text-[13px] font-bold px-5 py-2.5 rounded transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Add New Brand
            </Link>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead class="bg-[#f8f9fa]">
                        <tr>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider w-12">ID</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Logo</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Name</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th>
                            <th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="brand in brands.data" :key="brand.id" class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 text-gray-500 font-bold">{{ brand.id }}</td>
                            <td class="px-4 py-3">
                                <div class="w-12 h-12 rounded border border-gray-200 bg-white flex items-center justify-center overflow-hidden p-1">
                                    <img v-if="brand.logo" :src="'/storage/' + brand.logo" class="max-w-full max-h-full object-contain" alt="">
                                    <i v-else class="fas fa-image text-gray-300"></i>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-bold text-[#111]">{{ brand.name }}</td>
                            <td class="px-4 py-3">
                                <span v-if="brand.is_active" class="text-[11px] font-bold px-2 py-0.5 rounded bg-green-100 text-green-700">Active</span>
                                <span v-else class="text-[11px] font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-600">Inactive</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <Link :href="route('admin.brands.edit', brand.id)" class="w-7 h-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition">
                                        <i class="fas fa-pen text-[11px]"></i>
                                    </Link>
                                    <button @click="deleteBrand(brand.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition">
                                        <i class="fas fa-trash text-[11px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!brands.data?.length">
                            <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                                <i class="fas fa-tags text-4xl mb-3 block"></i>
                                <p class="font-medium">No brands found.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div v-if="brands.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex items-center justify-between bg-[#f8f9fa]">
                <p class="text-[12px] text-gray-500 font-medium">
                    Showing {{ brands.from }}-{{ brands.to }} of {{ brands.total }} brands
                </p>
                <div class="flex items-center space-x-1">
                    <template v-for="link in brands.links" :key="link.label">
                        <Link v-if="link.url" :href="link.url" class="px-3 py-1 rounded text-[12px] font-bold transition" :class="link.active ? 'bg-ryans text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-ryans hover:text-ryans'" v-html="link.label" />
                        <span v-else class="px-3 py-1 text-[12px] text-gray-300" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
