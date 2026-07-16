<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
const props = defineProps({ pages: Object });
const deletePage = (id) => { if (confirm('Delete page?')) router.delete(route('admin.pages.destroy', id)); };
</script>
<template>
    <Head title="Pages | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Pages</span></template>
        <div class="flex items-center justify-between mb-6">
            <div><h1 class="text-2xl font-bold text-[#111]">Custom Pages</h1><p class="text-[13px] text-gray-500 mt-1 font-medium">Manage static pages (Privacy, Terms, etc.)</p></div>
            <Link :href="route('admin.pages.create')" class="inline-flex items-center bg-ryans hover:bg-[#5da733] text-white text-[13px] font-bold px-5 py-2.5 rounded transition shadow-sm"><i class="fas fa-plus mr-2"></i> New Page</Link>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-[13px]"><thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Title</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Slug</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Updated</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Actions</th></tr></thead>
                <tbody>
                    <tr v-for="p in pages.data" :key="p.id" class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-bold text-[#111]">{{ p.title }}</td><td class="px-4 py-3 text-gray-500 font-mono text-[12px]">/{{ p.slug }}</td><td class="px-4 py-3 text-gray-500">{{ new Date(p.updated_at).toLocaleDateString() }}</td>
                        <td class="px-4 py-3 text-center"><div class="flex items-center justify-center space-x-1"><Link :href="route('admin.pages.edit', p.id)" class="w-7 h-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center"><i class="fas fa-pen text-[11px]"></i></Link><button @click="deletePage(p.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center"><i class="fas fa-trash text-[11px]"></i></button></div></td>
                    </tr>
                    <tr v-if="!pages.data?.length"><td colspan="4" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-file-alt text-4xl mb-3 block"></i><p class="font-medium">No pages.</p></td></tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
