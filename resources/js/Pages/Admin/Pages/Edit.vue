<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
const props = defineProps({ page: Object });
const form = useForm({ _method: 'PUT', title: props.page.title, content: props.page.content || '' });
const submit = () => { form.post(route('admin.pages.update', props.page.id)); };
</script>
<template>
    <Head title="Edit Page | Admin" />
    <AdminLayout>
        <template #header><Link :href="route('admin.pages.index')" class="text-gray-500 hover:text-ryans transition font-medium">Pages</Link><span class="mx-2 text-gray-300">/</span><span class="text-gray-800 font-bold">Edit</span></template>
        <div class="mb-6"><h1 class="text-2xl font-bold text-[#111]">Edit Page</h1></div>
        <div class="bg-white border border-gray-200 rounded-lg p-6 max-w-3xl">
            <form @submit.prevent="submit" class="space-y-5">
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Title *</label><input v-model="form.title" type="text" required class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white"></div>
                <div><label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Content</label><textarea v-model="form.content" rows="12" class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans bg-gray-50 focus:bg-white"></textarea></div>
                <div class="pt-4 border-t border-gray-100 flex justify-end"><button type="submit" :disabled="form.processing" class="px-8 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition shadow-sm disabled:opacity-50">Update Page</button></div>
            </form>
        </div>
    </AdminLayout>
</template>
