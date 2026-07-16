<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    category: Object,
    parentCategories: Array,
});

const form = useForm({
    _method: 'PUT',
    name: props.category.name,
    parent_id: props.category.parent_id || '',
    icon: props.category.icon || '',
});

const submit = () => {
    form.post(route('admin.categories.update', props.category.id));
};
</script>

<template>
    <Head :title="'Edit Category | Admin'" />

    <AdminLayout>
        <template #header>
            <Link :href="route('admin.categories.index')" class="text-gray-500 hover:text-ryans transition font-medium">Categories</Link>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-800 font-bold">Edit</span>
        </template>

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#111]">Edit Category</h1>
                <p class="text-[13px] text-gray-500 mt-1 font-medium">{{ category.name }}</p>
            </div>
            <Link :href="route('admin.categories.index')" class="text-[13px] text-gray-500 hover:text-ryans font-bold transition">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </Link>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6 max-w-2xl">
            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Category Name *</label>
                    <input v-model="form.name" type="text" required
                        class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white transition">
                    <p v-if="form.errors.name" class="text-red-500 text-[12px] mt-1">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Parent Category</label>
                    <select v-model="form.parent_id"
                        class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50">
                        <option value="">None (Top Level)</option>
                        <optgroup v-for="parent in parentCategories" :key="parent.id" :label="parent.name">
                            <option :value="parent.id">{{ parent.name }}</option>
                            <option v-for="sub in parent.subcategories" :key="sub.id" :value="sub.id">&nbsp;&nbsp;-- {{ sub.name }}</option>
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">FontAwesome Icon Class</label>
                    <input v-model="form.icon" type="text"
                        class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white transition">
                    <div v-if="form.icon" class="mt-2 flex items-center text-[13px] text-gray-600">
                        Preview: <i :class="form.icon" class="ml-2 text-ryans text-lg"></i>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="px-8 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition shadow-sm disabled:opacity-50">
                        <i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
