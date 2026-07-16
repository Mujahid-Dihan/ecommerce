<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({ categories: Array });
const form = useForm({ name: '' });
const submit = () => { form.post(route('admin.blog.categories.store'), { onSuccess: () => form.reset() }); };
const deleteCategory = (id) => { if (confirm('Delete category?')) router.delete(route('admin.blog.categories.destroy', id)); };
</script>
<template>
    <Head title="Blog Categories | Admin" />
    <AdminLayout>
        <template #header><Link :href="route('admin.blog.index')" class="text-gray-500 hover:text-ryans transition font-medium">Blog</Link><span class="mx-2 text-gray-300">/</span><span class="text-gray-800 font-bold">Categories</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Blog Categories</h1></div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-lg p-5">
                <h3 class="text-[15px] font-bold text-[#111] mb-4 border-b border-gray-100 pb-3">Add Category</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div><input v-model="form.name" type="text" required placeholder="Category name" class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
                    <button type="submit" :disabled="form.processing" class="w-full py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition">Save</button>
                </form>
            </div>
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg overflow-hidden">
                <table class="w-full text-[13px]"><thead class="bg-[#f8f9fa]"><tr><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Name</th><th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Posts</th><th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase">Actions</th></tr></thead>
                    <tbody>
                        <tr v-for="c in categories" :key="c.id" class="border-b border-gray-50"><td class="px-4 py-3 font-bold text-[#111]">{{ c.name }}</td><td class="px-4 py-3 text-gray-600">{{ c.posts_count }}</td><td class="px-4 py-3 text-center"><button @click="deleteCategory(c.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center mx-auto"><i class="fas fa-trash text-[11px]"></i></button></td></tr>
                        <tr v-if="!categories?.length"><td colspan="3" class="px-4 py-8 text-center text-gray-400">No categories.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
