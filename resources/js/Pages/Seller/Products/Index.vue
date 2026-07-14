<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    products: Object,
});
</script>

<template>
    <Head title="Manage Products" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
                <Link :href="route('seller.products.create')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                    Add New Product
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Success Message -->
                <div v-if="$page.props.flash?.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ $page.props.flash.success }}</span>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="text-left font-bold border-b border-gray-200">
                                    <th class="px-6 pt-4 pb-4">Product Name</th>
                                    <th class="px-6 pt-4 pb-4">Category</th>
                                    <th class="px-6 pt-4 pb-4">Brand</th>
                                    <th class="px-6 pt-4 pb-4">Price</th>
                                    <th class="px-6 pt-4 pb-4">Stock</th>
                                    <th class="px-6 pt-4 pb-4">Status</th>
                                    <th class="px-6 pt-4 pb-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50 focus-within:bg-gray-50 border-b border-gray-100">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 mr-4">
                                                <img v-if="product.thumbnail" :src="'/storage/' + product.thumbnail" class="h-10 w-10 rounded object-cover border" alt="Thumbnail">
                                                <img v-else :src="'https://placehold.co/80x80/f0f2f5/666666?text=' + encodeURIComponent(product.slug.replace(/-/g, ' '))" class="h-10 w-10 rounded object-cover border" alt="Thumbnail">
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ product.name }}</div>
                                                <div class="text-sm text-gray-500">Type: {{ product.product_type }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ product.category?.name || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ product.brand?.name || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">
                                        ${{ product.variants?.[0]?.price || '0.00' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span :class="product.variants?.[0]?.stock > 0 ? 'text-green-600' : 'text-red-600 font-bold'">
                                            {{ product.variants?.[0]?.stock || 0 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                              :class="{
                                                'bg-green-100 text-green-800': product.status === 'active',
                                                'bg-yellow-100 text-yellow-800': product.status === 'draft',
                                                'bg-red-100 text-red-800': product.status === 'inactive'
                                              }">
                                            {{ product.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <Link :href="route('seller.products.edit', product.id)" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded">
                                            Edit
                                        </Link>
                                        <Link :href="route('seller.products.destroy', product.id)" method="delete" as="button" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded">
                                            Delete
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="products.data.length === 0">
                                    <td class="px-6 py-4 border-t text-center text-gray-500" colspan="7">
                                        No products found. Start by adding a new one!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
