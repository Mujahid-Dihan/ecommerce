<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
const props = defineProps({ posts: Object });
const deletePost = (id) => { if (confirm('Delete post?')) router.delete(route('admin.blog.destroy', id)); };
</script>
<template>
    <Head title="Blog | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Blog</span></template>
        <div class="flex items-center justify-between mb-6">
            <div><h1 class="text-2xl font-bold text-[#111]">Blog Posts</h1></div>
            <div class="flex items-center space-x-3">
                <Link :href="route('admin.blog.categories')" class="text-[13px] text-gray-600 hover:text-ryans font-bold"><i class="fas fa-folder mr-1"></i> Categories</Link>
                <Link :href="route('admin.blog.create')" class="inline-flex items-center bg-ryans hover:bg-[#5da733] text-white text-[13px] font-bold px-5 py-2.5 rounded transition shadow-sm"><i class="fas fa-plus mr-2"></i> New Post</Link>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-[13px]"><thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Title</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Category</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Date</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th></tr></thead>
                <tbody>
                    <tr v-for="p in posts.data" :key="p.id" class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-bold text-[#111]">{{ p.title }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ p.category?.name || '-' }}</td>
                        <td class="px-4 py-3"><span :class="p.status === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">{{ p.status }}</span></td>
                        <td class="px-4 py-3 text-gray-500">{{ new Date(p.created_at).toLocaleDateString() }}</td>
                        <td class="px-4 py-3 text-center"><div class="flex items-center justify-center space-x-1"><Link :href="route('admin.blog.edit', p.id)" class="w-7 h-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center"><i class="fas fa-pen text-[11px]"></i></Link><button @click="deletePost(p.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center"><i class="fas fa-trash text-[11px]"></i></button></div></td>
                    </tr>
                    <tr v-if="!posts.data?.length"><td colspan="5" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-newspaper text-4xl mb-3 block"></i><p class="font-medium">No blog posts.</p></td></tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
