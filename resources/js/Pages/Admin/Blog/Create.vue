<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
const props = defineProps({ categories: Array });
const form = useForm({ title: '', blog_category_id: '', content: '', status: 'draft', featured_image: null });
const submit = () => { form.post(route('admin.blog.store'), { forceFormData: true }); };
</script>
<template>
    <Head title="New Post | Admin" />
    <AdminLayout>
        <template #header><Link :href="route('admin.blog.index')" class="text-gray-500 hover:text-ryans transition font-medium">Blog</Link><span class="mx-2 text-gray-300">/</span><span class="text-gray-800 font-bold">New Post</span></template>
        <div class="flex items-center justify-between mb-6"><h1 class="text-2xl font-bold text-[#111]">New Blog Post</h1></div>
        <div class="bg-white border border-gray-200 rounded-lg p-6 max-w-3xl">
            <form @submit.prevent="submit" class="space-y-5">
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Title *</label><input v-model="form.title" type="text" required class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Category</label><select v-model="form.blog_category_id" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"><option value="">None</option><option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option></select></div>
                    <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Status</label><select v-model="form.status" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50"><option value="draft">Draft</option><option value="published">Published</option></select></div>
                </div>
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Content</label><textarea v-model="form.content" rows="10" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white"></textarea></div>
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Featured Image</label><input type="file" @change="form.featured_image = $event.target.files[0]" accept="image/*" class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] bg-gray-50"></div>
                <div class="pt-4 border-t border-gray-100 flex justify-end"><button type="submit" :disabled="form.processing" class="px-8 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition shadow-sm disabled:opacity-50">Publish</button></div>
            </form>
        </div>
    </AdminLayout>
</template>
