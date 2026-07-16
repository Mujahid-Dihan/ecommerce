<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: Array,
    allCategories: Array,
});

const deleteCategory = (id) => {
    if (confirm('Are you sure you want to delete this category? All subcategories will also be deleted.')) {
        router.delete(route('admin.categories.destroy', id));
    }
};

const expandedNodes = ref([]);

const toggleNode = (id) => {
    const index = expandedNodes.value.indexOf(id);
    if (index > -1) {
        expandedNodes.value.splice(index, 1);
    } else {
        expandedNodes.value.push(id);
    }
};

const isExpanded = (id) => expandedNodes.value.includes(id);
</script>

<template>
    <Head title="Categories | Admin" />

    <AdminLayout>
        <template #header>
            <span class="text-gray-800 font-bold">Categories</span>
        </template>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#111]">Categories</h1>
                <p class="text-[13px] text-gray-500 mt-1 font-medium">Manage product categories hierarchy.</p>
            </div>
            <Link :href="route('admin.categories.create')" class="mt-3 sm:mt-0 inline-flex items-center bg-ryans hover:bg-[#5da733] text-white text-[13px] font-bold px-5 py-2.5 rounded transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Add New Category
            </Link>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-[#f8f9fa]">
                <h3 class="text-[14px] font-bold text-gray-700 uppercase tracking-wider">Category Tree</h3>
            </div>
            <div class="p-4">
                <ul v-if="categories.length > 0" class="space-y-1">
                    <!-- Level 1 (Parent) -->
                    <li v-for="cat in categories" :key="cat.id" class="border border-gray-100 rounded">
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 transition group">
                            <div class="flex items-center">
                                <button v-if="cat.subcategories?.length" @click="toggleNode(cat.id)" class="w-6 h-6 mr-2 flex items-center justify-center text-gray-400 hover:text-ryans">
                                    <i class="fas text-[12px] transition-transform" :class="isExpanded(cat.id) ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                </button>
                                <div v-else class="w-6 mr-2"></div>
                                <span class="text-[14px] font-bold text-[#111]">{{ cat.name }}</span>
                                <span class="ml-3 text-[11px] text-gray-400 bg-gray-100 px-2 rounded-full">{{ cat.slug }}</span>
                            </div>
                            <div class="flex space-x-2">
                                <Link :href="route('admin.categories.edit', cat.id)" class="text-blue-500 hover:text-blue-700 text-[12px]"><i class="fas fa-pen"></i></Link>
                                <button @click="deleteCategory(cat.id)" class="text-red-500 hover:text-red-700 text-[12px]"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>

                        <!-- Level 2 (Sub) -->
                        <ul v-show="isExpanded(cat.id) && cat.subcategories?.length" class="pl-8 pb-2 space-y-1 border-l-2 border-gray-100 ml-5 mt-1">
                            <li v-for="sub in cat.subcategories" :key="sub.id" class="relative">
                                <div class="absolute w-4 h-px bg-gray-200 left-[-16px] top-1/2"></div>
                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded group">
                                    <div class="flex items-center">
                                        <button v-if="sub.subcategories?.length" @click="toggleNode(sub.id)" class="w-5 h-5 mr-1 flex items-center justify-center text-gray-400 hover:text-ryans">
                                            <i class="fas text-[10px] transition-transform" :class="isExpanded(sub.id) ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                        </button>
                                        <div v-else class="w-5 mr-1"></div>
                                        <span class="text-[13px] font-medium text-gray-700">{{ sub.name }}</span>
                                    </div>
                                    <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition">
                                        <Link :href="route('admin.categories.edit', sub.id)" class="text-blue-500 hover:text-blue-700 text-[11px]"><i class="fas fa-pen"></i></Link>
                                        <button @click="deleteCategory(sub.id)" class="text-red-500 hover:text-red-700 text-[11px]"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>

                                <!-- Level 3 (Sub-Sub) -->
                                <ul v-show="isExpanded(sub.id) && sub.subcategories?.length" class="pl-6 pb-1 space-y-1 border-l border-gray-100 ml-4 mt-1">
                                    <li v-for="subsub in sub.subcategories" :key="subsub.id" class="relative">
                                        <div class="absolute w-3 h-px bg-gray-200 left-[-12px] top-1/2"></div>
                                        <div class="flex items-center justify-between p-1.5 hover:bg-gray-50 rounded group">
                                            <div class="flex items-center pl-4">
                                                <span class="text-[12px] text-gray-600">{{ subsub.name }}</span>
                                            </div>
                                            <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition">
                                                <Link :href="route('admin.categories.edit', subsub.id)" class="text-blue-500 hover:text-blue-700 text-[11px]"><i class="fas fa-pen"></i></Link>
                                                <button @click="deleteCategory(subsub.id)" class="text-red-500 hover:text-red-700 text-[11px]"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div v-else class="text-center py-12 text-gray-400">
                    <i class="fas fa-sitemap text-4xl mb-3 block"></i>
                    <p>No categories found.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
