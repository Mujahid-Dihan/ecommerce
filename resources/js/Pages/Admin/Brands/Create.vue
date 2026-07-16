<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    logo: null,
    is_active: true,
});

const logoPreview = ref(null);

const handleLogo = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('admin.brands.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Add Brand | Admin" />

    <AdminLayout>
        <template #header>
            <Link :href="route('admin.brands.index')" class="text-gray-500 hover:text-ryans transition font-medium">Brands</Link>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-800 font-bold">Add New</span>
        </template>

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#111]">Add New Brand</h1>
            </div>
            <Link :href="route('admin.brands.index')" class="text-[13px] text-gray-500 hover:text-ryans font-bold transition">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </Link>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6 max-w-2xl">
            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Brand Name *</label>
                    <input v-model="form.name" type="text" required placeholder="e.g. Samsung"
                        class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white transition">
                    <p v-if="form.errors.name" class="text-red-500 text-[12px] mt-1">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Brand Logo</label>
                    <div class="flex items-start gap-4">
                        <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50 hover:border-ryans cursor-pointer p-2" @click="$refs.logoInput.click()">
                            <img v-if="logoPreview" :src="logoPreview" class="max-w-full max-h-full object-contain">
                            <div v-else class="text-center text-gray-400">
                                <i class="fas fa-upload text-2xl mb-1"></i>
                                <p class="text-[11px] font-medium">Upload</p>
                            </div>
                        </div>
                        <input ref="logoInput" type="file" class="hidden" accept="image/*" @change="handleLogo">
                    </div>
                </div>

                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.is_active" class="w-4 h-4 text-ryans focus:ring-ryans rounded border-gray-300">
                        <span class="ml-2 text-[13px] font-bold text-gray-700">Active</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="px-8 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition shadow-sm disabled:opacity-50">
                        <i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i> Save Brand
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
