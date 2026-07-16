<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    products: Object,
    categories: Array,
    brands: Array,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const categoryId = ref(props.filters?.category_id || '');
const brandId = ref(props.filters?.brand_id || '');
const status = ref(props.filters?.status || '');
const productType = ref(props.filters?.product_type || '');

let debounceTimer = null;
const applyFilters = () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        router.get(route('admin.products.index'), {
            search: search.value || undefined,
            category_id: categoryId.value || undefined,
            brand_id: brandId.value || undefined,
            status: status.value || undefined,
            product_type: productType.value || undefined,
        }, { preserveState: true, replace: true });
    }, 300);
};

watch([search, categoryId, brandId, status, productType], applyFilters);

const clearFilters = () => {
    search.value = '';
    categoryId.value = '';
    brandId.value = '';
    status.value = '';
    productType.value = '';
};

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('admin.products.destroy', id));
    }
};

const formatPrice = (price) => {
    if (!price) return '0';
    return new Intl.NumberFormat('en-IN').format(price);
};

const statusColor = (s) => {
    const c = { active: 'bg-green-100 text-green-700', draft: 'bg-yellow-100 text-yellow-700', inactive: 'bg-red-100 text-red-700' };
    return c[s] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head title="Products | Admin" />

    <AdminLayout>
        <template #header>
            <span class="text-gray-800 font-bold">Products</span>
        </template>

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#111]">Products</h1>
                <p class="text-[13px] text-gray-500 mt-1 font-medium">Manage all your products in one place.</p>
            </div>
            <Link :href="route('admin.products.create')" class="mt-3 sm:mt-0 inline-flex items-center bg-ryans hover:bg-[#5da733] text-white text-[13px] font-bold px-5 py-2.5 rounded transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Add New Product
            </Link>
        </div>

        <!-- Filters Bar -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-2 lg:grid-cols-6 gap-3">
                <div class="lg:col-span-2">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[13px]"></i>
                        <input v-model="search" type="text" placeholder="Search products, SKU..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded text-[13px] focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white transition">
                    </div>
                </div>
                <select v-model="categoryId" class="border border-gray-300 rounded text-[13px] py-2 focus:border-ryans focus:ring-ryans bg-gray-50">
                    <option value="">All Categories</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <select v-model="brandId" class="border border-gray-300 rounded text-[13px] py-2 focus:border-ryans focus:ring-ryans bg-gray-50">
                    <option value="">All Brands</option>
                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                </select>
                <select v-model="status" class="border border-gray-300 rounded text-[13px] py-2 focus:border-ryans focus:ring-ryans bg-gray-50">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="draft">Draft</option>
                    <option value="inactive">Inactive</option>
                </select>
                <button @click="clearFilters" class="text-[13px] text-gray-500 hover:text-ryans font-bold transition flex items-center justify-center">
                    <i class="fas fa-times mr-1"></i> Clear
                </button>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead class="bg-[#f8f9fa]">
                        <tr>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider w-12">#</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Product</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Category</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Brand</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Price</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Stock</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Type</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Status</th>
                            <th class="text-center px-4 py-3 font-bold text-gray-600 text-[12px] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, index) in products.data" :key="product.id" class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 text-gray-500">{{ (products.current_page - 1) * products.per_page + index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded bg-gray-100 flex-shrink-0 overflow-hidden mr-3">
                                        <img v-if="product.thumbnail" :src="'/storage/' + product.thumbnail" class="w-full h-full object-cover" alt="">
                                        <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-bold text-[#111] truncate max-w-[200px]">{{ product.name }}</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ product.variants?.[0]?.sku || 'No SKU' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 font-medium">{{ product.category?.name || '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 font-medium">{{ product.brand?.name || '-' }}</td>
                            <td class="px-4 py-3 font-bold text-[#111]">৳{{ formatPrice(product.variants?.[0]?.price) }}</td>
                            <td class="px-4 py-3">
                                <span v-if="product.variants?.[0]?.stock <= 0" class="text-[11px] font-bold px-2 py-0.5 rounded bg-red-100 text-red-700">Out</span>
                                <span v-else-if="product.variants?.[0]?.stock <= 5" class="text-[11px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700">{{ product.variants?.[0]?.stock }}</span>
                                <span v-else class="text-[13px] font-medium text-gray-700">{{ product.variants?.[0]?.stock }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded capitalize" :class="product.product_type === 'digital' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600'">
                                    {{ product.product_type }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span :class="statusColor(product.status)" class="text-[11px] font-bold px-2 py-0.5 rounded capitalize">
                                    {{ product.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <Link :href="route('admin.products.edit', product.id)" class="w-7 h-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" title="Edit">
                                        <i class="fas fa-pen text-[11px]"></i>
                                    </Link>
                                    <button @click="deleteProduct(product.id)" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Delete">
                                        <i class="fas fa-trash text-[11px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!products.data?.length">
                            <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                                <i class="fas fa-box-open text-4xl mb-3 block"></i>
                                <p class="font-medium">No products found</p>
                                <Link :href="route('admin.products.create')" class="text-ryans font-bold hover:underline mt-2 inline-block text-[13px]">
                                    Add your first product →
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="products.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex items-center justify-between bg-[#f8f9fa]">
                <p class="text-[12px] text-gray-500 font-medium">
                    Showing {{ products.from }}-{{ products.to }} of {{ products.total }} products
                </p>
                <div class="flex items-center space-x-1">
                    <template v-for="link in products.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="px-3 py-1 rounded text-[12px] font-bold transition"
                            :class="link.active ? 'bg-ryans text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-ryans hover:text-ryans'"
                            v-html="link.label"
                        />
                        <span v-else class="px-3 py-1 text-[12px] text-gray-300" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
