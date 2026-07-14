<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    products: Object,
    categories: Array,
    brands: Array,
    filters: Object,
});

const formatPrice = (price) => {
    if (!price) return '0';
    return new Intl.NumberFormat('en-IN').format(price);
};

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');
const selectedBrand = ref(props.filters.brand || '');

const applyFilters = () => {
    let query = {};
    if (search.value) query.search = search.value;
    if (selectedCategory.value) query.category = selectedCategory.value;
    if (selectedBrand.value) query.brand = selectedBrand.value;

    router.get(route('shop.index'), query, {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    search.value = '';
    selectedCategory.value = '';
    selectedBrand.value = '';
    applyFilters();
};

let searchTimeout;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
});
</script>

<template>
    <Head title="Shop - Electronics Dokan" />

    <CustomerLayout>
        <div class="bg-[#f0f2f5] min-h-screen py-8 font-sans">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="text-sm text-gray-500 mb-6 flex items-center">
                    <Link :href="route('home')" class="hover:text-ryans"><i class="fas fa-home"></i> Home</Link>
                    <span class="mx-2"><i class="fas fa-angle-right"></i></span>
                    <span class="text-gray-800 font-medium">All Products</span>
                </nav>

                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="w-full lg:w-1/4 xl:w-1/5 flex-shrink-0">
                        <div class="bg-white p-5 rounded border border-gray-200 shadow-sm sticky top-[100px]">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-[#111] text-lg">Filters</h3>
                                <button @click="clearFilters" class="text-xs text-ryans hover:underline">Clear All</button>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Search</label>
                                <div class="relative">
                                    <input v-model="search" type="text" class="w-full text-sm border-gray-300 rounded focus:ring-ryans focus:border-ryans" placeholder="Find product...">
                                    <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2 border-b pb-2">Categories</label>
                                <div class="max-h-60 overflow-y-auto space-y-2 pr-2">
                                    <div class="flex items-center">
                                        <input type="radio" id="cat_all" value="" v-model="selectedCategory" @change="applyFilters" class="text-ryans focus:ring-ryans border-gray-300">
                                        <label for="cat_all" class="ml-2 text-sm text-gray-600 cursor-pointer">All Categories</label>
                                    </div>
                                    <template v-for="cat in categories" :key="cat.id">
                                        <div class="flex items-center">
                                            <input type="radio" :id="'cat_' + cat.id" :value="cat.slug" v-model="selectedCategory" @change="applyFilters" class="text-ryans focus:ring-ryans border-gray-300">
                                            <label :for="'cat_' + cat.id" class="ml-2 text-sm text-gray-800 font-medium cursor-pointer">{{ cat.name }}</label>
                                        </div>
                                        <div v-for="sub in cat.subcategories" :key="sub.id" class="flex items-center pl-5 mt-1">
                                            <input type="radio" :id="'cat_' + sub.id" :value="sub.slug" v-model="selectedCategory" @change="applyFilters" class="text-ryans focus:ring-ryans border-gray-300">
                                            <label :for="'cat_' + sub.id" class="ml-2 text-sm text-gray-600 cursor-pointer">{{ sub.name }}</label>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 border-b pb-2">Brands</label>
                                <div class="max-h-60 overflow-y-auto space-y-2 pr-2">
                                    <div class="flex items-center">
                                        <input type="radio" id="brand_all" value="" v-model="selectedBrand" @change="applyFilters" class="text-ryans focus:ring-ryans border-gray-300">
                                        <label for="brand_all" class="ml-2 text-sm text-gray-600 cursor-pointer">All Brands</label>
                                    </div>
                                    <div v-for="brand in brands" :key="brand.id" class="flex items-center">
                                        <input type="radio" :id="'brand_' + brand.id" :value="brand.slug" v-model="selectedBrand" @change="applyFilters" class="text-ryans focus:ring-ryans border-gray-300">
                                        <label :for="'brand_' + brand.id" class="ml-2 text-sm text-gray-600 cursor-pointer">{{ brand.name }}</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="flex-1 w-full lg:w-3/4 xl:w-4/5">
                        <div class="bg-white p-3 rounded border border-gray-200 shadow-sm mb-4 flex justify-between items-center text-sm">
                            <span class="text-gray-600">
                                Showing <strong>{{ products.from || 0 }} - {{ products.to || 0 }}</strong> of <strong>{{ products.total }}</strong> products
                            </span>
                            <div class="flex items-center">
                                <label class="text-gray-500 mr-2">Sort By:</label>
                                <select class="border-gray-300 rounded text-sm py-1 pl-3 pr-8 focus:ring-ryans focus:border-ryans">
                                    <option>Default</option>
                                    <option>Price: Low to High</option>
                                    <option>Price: High to Low</option>
                                    <option>Newest First</option>
                                </select>
                            </div>
                        </div>

                        <div v-if="products.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                            <div v-for="product in products.data" :key="product.id" class="bg-white border border-gray-200 rounded hover:shadow-[0_0_15px_rgba(0,0,0,0.1)] transition duration-300 flex flex-col group p-4 relative">
                                
                                <div v-if="product.b2b_wholesale" class="absolute top-2 left-2 bg-purple-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-sm z-10">B2B Available</div>
                                
                                <Link :href="route('product.show', product.slug)" class="group/image h-[180px] w-full flex items-center justify-center mb-4 overflow-hidden relative block">
                                    <img v-if="product.thumbnail" :src="'/storage/' + product.thumbnail" alt="product" class="max-h-full max-w-full object-contain group-hover/image:scale-110 transition duration-500">
                                    <img v-else :src="'https://placehold.co/300x300/f0f2f5/666666?text=' + encodeURIComponent(product.slug.replace(/-/g, ' '))" alt="product" class="max-h-full max-w-full object-contain group-hover/image:scale-110 transition duration-500">
                                    <div class="absolute inset-0 bg-white/70 flex items-center justify-center gap-2 opacity-0 group-hover/image:opacity-100 transition duration-300 z-20">
                                        <button title="Add to Cart" @click.prevent="" class="w-9 h-9 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-base"><i class="fas fa-shopping-cart"></i></button>
                                        <button title="Add to Compare" @click.prevent="" class="w-9 h-9 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-base"><i class="fas fa-exchange-alt"></i></button>
                                        <button title="Add to Favorite" @click.prevent="" class="w-9 h-9 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-base"><i class="far fa-heart"></i></button>
                                        <button title="Quick View" @click.prevent="" class="w-9 h-9 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-base"><i class="far fa-eye"></i></button>
                                    </div>
                                </Link>
                                
                                <div class="flex-grow flex flex-col justify-start">
                                    <Link :href="route('product.show', product.slug)" class="text-[14px] text-[#0066cc] hover:text-red-600 hover:underline leading-snug font-medium line-clamp-3 mb-2">
                                        {{ product.name }}
                                    </Link>
                                    
                                    <ul class="text-[12px] text-gray-600 list-disc pl-4 mb-3 line-clamp-2">
                                        <li v-if="product.brand">Brand: {{ product.brand.name }}</li>
                                        <li v-if="product.description">{{ product.description.substring(0, 30) }}...</li>
                                    </ul>
                                </div>
                                
                                <div class="mt-auto border-t border-gray-100 pt-3 relative">
                                    <div class="text-[18px] font-bold text-[#111]">Tk {{ formatPrice(product.variants?.[0]?.price || 0) }}</div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="bg-white p-12 text-center rounded border border-gray-200 shadow-sm">
                            <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-700">No products found</h3>
                            <p class="text-gray-500 mt-2">Try adjusting your filters or search query.</p>
                            <button @click="clearFilters" class="mt-4 px-6 py-2 bg-ryans text-white rounded font-bold hover:bg-[#5da733] transition">Clear Filters</button>
                        </div>

                        <div v-if="products.links && products.links.length > 3" class="mt-8 flex justify-center">
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <template v-for="(link, index) in products.links" :key="index">
                                    <Link 
                                        v-if="link.url" 
                                        :href="link.url" 
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                        :class="[link.active ? 'z-10 bg-ryans border-ryans text-white' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50']"
                                    />
                                    <span 
                                        v-else 
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-300"
                                    />
                                </template>
                            </nav>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </CustomerLayout>
</template>
