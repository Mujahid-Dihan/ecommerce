<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    product: Object,
    attributes: Array,
    relatedProducts: Array,
});

const formatPrice = (price) => {
    if (!price) return '0';
    return new Intl.NumberFormat('en-IN').format(price);
};

const groupedSpecifications = computed(() => {
    if (!props.product.specifications || !Array.isArray(props.product.specifications) || props.product.specifications.length === 0) return {};
    
    return props.product.specifications.reduce((acc, spec) => {
        if (!acc[spec.group]) acc[spec.group] = [];
        acc[spec.group].push(spec);
        return acc;
    }, {});
});

const selectedOptions = ref({});
const quantity = ref(1);

const availableAttributes = computed(() => {
    if (!props.product.variants || props.product.variants.length === 0 || props.product.variants[0].variant_string === 'default') {
        return [];
    }

    const usedParts = new Set();
    props.product.variants.forEach(v => {
        const parts = v.variant_string.split('-');
        parts.forEach(p => usedParts.add(p));
    });

    const activeAttrs = [];
    props.attributes.forEach(attr => {
        const usedValues = attr.values.filter(val => usedParts.has(val.value));
        if (usedValues.length > 0) {
            activeAttrs.push({
                ...attr,
                values: usedValues
            });
        }
    });

    return activeAttrs;
});

onMounted(() => {
    if (availableAttributes.value.length > 0) {
        const firstVariant = props.product.variants.find(v => v.stock > 0) || props.product.variants[0];
        if (firstVariant && firstVariant.variant_string !== 'default') {
            const parts = firstVariant.variant_string.split('-');
            availableAttributes.value.forEach(attr => {
                const foundPart = parts.find(p => attr.values.some(v => v.value === p));
                if (foundPart) {
                    selectedOptions.value[attr.id] = foundPart;
                }
            });
        }
    }
});

const activeVariant = computed(() => {
    if (availableAttributes.value.length === 0) {
        return props.product.variants?.[0] || null;
    }

    const selectedValues = Object.values(selectedOptions.value);
    if (selectedValues.length !== availableAttributes.value.length) {
        return null; 
    }

    return props.product.variants.find(v => {
        const parts = v.variant_string.split('-');
        return selectedValues.every(val => parts.includes(val));
    }) || null;
});

const selectOption = (attrId, val) => {
    selectedOptions.value[attrId] = val;
    quantity.value = 1;
};

const incrementQty = () => {
    if (activeVariant.value && quantity.value < activeVariant.value.stock) {
        quantity.value++;
    }
};

const decrementQty = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const addToCart = () => {
    if (!activeVariant.value) {
        alert("Please select all options before adding to cart.");
        return;
    }
    if (activeVariant.value.stock === 0) {
        alert("Sorry, this item is out of stock.");
        return;
    }
    alert(`Added ${quantity.value}x ${props.product.name} to cart!`);
};

const defaultImage = computed(() => 'https://placehold.co/600x600/ffffff/666666?text=' + encodeURIComponent(props.product.slug.replace(/-/g, ' ')));

const allImages = computed(() => {
    let imgs = [];
    if (props.product.thumbnail) {
        imgs.push('/storage/' + props.product.thumbnail);
    } else {
        imgs.push(defaultImage.value);
    }
    
    if (props.product.images && Array.isArray(props.product.images)) {
        props.product.images.forEach(img => imgs.push('/storage/' + img));
    }
    return imgs;
});

const activeImageIndex = ref(0);
const activeTab = ref('Details');
</script>

<template>
    <Head :title="product.name + ' - Electronics Dokan'" />

    <CustomerLayout>
        <div class="bg-[#f0f2f5] min-h-screen py-6 font-sans text-sm">
            <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Breadcrumbs -->
                <nav class="text-gray-500 mb-4 text-[13px] flex items-center">
                    <Link :href="route('home')" class="hover:text-ryans"><i class="fas fa-home"></i></Link>
                    <span class="mx-2"><i class="fas fa-angle-right"></i></span>
                    <Link :href="route('shop.index')" class="hover:text-ryans">Products</Link>
                    <span class="mx-2"><i class="fas fa-angle-right"></i></span>
                    <Link v-if="product.category" :href="route('shop.index', {category: product.category.slug})" class="hover:text-ryans">{{ product.category.name }}</Link>
                    <span v-if="product.category" class="mx-2"><i class="fas fa-angle-right"></i></span>
                    <span class="text-gray-800">{{ product.name }}</span>
                </nav>

                <!-- Product Top Section -->
                <div class="bg-white border border-gray-200 shadow-sm mb-6 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                        
                        <!-- Left Image Gallery -->
                        <div class="md:col-span-5 relative flex flex-row gap-4">
                            <!-- Thumbnails vertical list -->
                            <div class="w-[70px] flex flex-col gap-2 h-[400px] overflow-y-auto pr-1">
                                <div v-for="(img, idx) in allImages" :key="idx" 
                                    @click="activeImageIndex = idx"
                                    class="border p-1 flex items-center justify-center h-[70px] cursor-pointer hover:border-ryans transition bg-white"
                                    :class="activeImageIndex === idx ? 'border-ryans shadow-sm' : 'border-gray-200'">
                                    <img :src="img" class="max-w-full max-h-full object-contain">
                                </div>
                            </div>
                            <!-- Main Image -->
                            <div class="flex-1 border border-gray-200 p-2 flex items-center justify-center relative bg-white h-[400px]">
                                <img :src="allImages[activeImageIndex]" :alt="product.name" class="max-h-full max-w-full object-contain cursor-zoom-in">
                            </div>
                        </div>

                        <!-- Right Details -->
                        <div class="md:col-span-7 flex flex-col">
                            <h1 class="text-[22px] text-[#111] font-bold mb-4 leading-tight">{{ product.name }}</h1>
                            
                            <!-- Price Block -->
                            <div class="bg-gray-50 p-4 border border-gray-200 mb-5 flex flex-col gap-2">
                                <div class="flex items-center">
                                    <span class="w-[120px] text-gray-600 font-medium text-[14px]">Price</span>
                                    <span v-if="activeVariant" class="text-[24px] font-black text-[#111]">Tk {{ formatPrice(activeVariant.price * 1.1) }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-[120px] text-gray-600 font-medium text-[14px]">Special Price</span>
                                    <span v-if="activeVariant" class="text-[28px] font-black text-ryans">Tk {{ formatPrice(activeVariant.price) }}</span>
                                    <span v-else class="text-[20px] font-bold text-red-500">Not available</span>
                                </div>
                                <div v-if="activeVariant && activeVariant.stock > 0" class="mt-1 flex items-center text-[#5da733] font-bold text-[13px]">
                                    <i class="fas fa-check-circle mr-1"></i> In Stock ({{ activeVariant.stock }})
                                </div>
                                <div v-else-if="activeVariant" class="mt-1 flex items-center text-red-600 font-bold text-[13px]">
                                    <i class="fas fa-times-circle mr-1"></i> Out of Stock
                                </div>
                            </div>

                            <!-- Variants Selection -->
                            <div v-if="availableAttributes.length > 0" class="mb-5 space-y-4 border-b border-gray-200 pb-5">
                                <div v-for="attr in availableAttributes" :key="attr.id" class="flex items-center">
                                    <span class="w-[120px] text-gray-600 font-bold text-[14px]">{{ attr.name }}</span>
                                    <div class="flex flex-wrap gap-2">
                                        <button 
                                            v-for="val in attr.values" 
                                            :key="val.id"
                                            @click="selectOption(attr.id, val.value)"
                                            class="px-4 py-1.5 border rounded-sm text-[13px] transition font-medium"
                                            :class="selectedOptions[attr.id] === val.value ? 'border-ryans bg-ryans text-white' : 'border-gray-300 text-gray-700 hover:border-ryans hover:text-ryans bg-white'"
                                        >
                                            {{ val.value }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Actions -->
                            <div class="flex items-center gap-4 mb-6">
                                <div class="flex items-center h-[42px] border border-gray-300 rounded overflow-hidden w-[100px] bg-white">
                                    <button @click="decrementQty" class="w-8 h-full flex items-center justify-center text-gray-600 hover:bg-gray-100 transition border-r"><i class="fas fa-minus text-xs"></i></button>
                                    <input type="text" v-model="quantity" class="w-full text-center border-none focus:ring-0 text-[14px] font-bold h-full p-0" readonly>
                                    <button @click="incrementQty" class="w-8 h-full flex items-center justify-center text-gray-600 hover:bg-gray-100 transition border-l"><i class="fas fa-plus text-xs"></i></button>
                                </div>

                                <button 
                                    @click="addToCart" 
                                    class="h-[42px] bg-ryans hover:bg-[#5da733] text-white font-bold rounded shadow-md flex items-center justify-center text-[15px] px-8 transition disabled:opacity-50"
                                    :disabled="!activeVariant || activeVariant.stock === 0"
                                >
                                    Buy Now
                                </button>
                            </div>

                            <!-- Quick Overview -->
                            <div class="mb-4">
                                <h3 class="text-[16px] font-bold text-[#111] mb-2">Quick Overview</h3>
                                <ul class="list-disc pl-5 text-[13px] text-gray-700 space-y-1">
                                    <li v-if="product.brand">Brand: <strong>{{ product.brand.name }}</strong></li>
                                    <li v-if="product.category">Category: <strong>{{ product.category.name }}</strong></li>
                                    <li>Product ID: <strong>{{ product.id }}</strong></li>
                                    <li v-if="activeVariant">SKU: <strong>{{ activeVariant.sku || 'N/A' }}</strong></li>
                                    <li>Description: {{ product.description ? product.description.substring(0, 80) + '...' : 'N/A' }}</li>
                                </ul>
                                <a href="#specifications" class="text-ryans text-[13px] hover:underline mt-2 inline-block font-medium">View More Info</a>
                            </div>

                            <!-- Social -->
                            <div class="mt-auto border-t border-gray-200 pt-4 flex gap-4 text-[13px]">
                                <button class="text-gray-600 hover:text-ryans flex items-center font-medium"><i class="far fa-heart mr-1.5 text-lg"></i> Add to Favorite</button>
                                <button class="text-gray-600 hover:text-ryans flex items-center font-medium"><i class="fas fa-exchange-alt mr-1.5 text-lg"></i> Add to Compare</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Section -->
                <div class="bg-white border border-gray-200 shadow-sm mb-6">
                    <div class="flex border-b border-gray-200 bg-gray-50 overflow-x-auto">
                        <button @click="activeTab = 'Details'" :class="activeTab === 'Details' ? 'border-t-2 border-t-ryans bg-white text-ryans font-bold' : 'text-gray-600 hover:text-ryans font-medium border-t-2 border-transparent'" class="px-8 py-3.5 text-[15px] transition focus:outline-none whitespace-nowrap">Details</button>
                        <button @click="activeTab = 'Q&A'" :class="activeTab === 'Q&A' ? 'border-t-2 border-t-ryans bg-white text-ryans font-bold' : 'text-gray-600 hover:text-ryans font-medium border-t-2 border-transparent'" class="px-8 py-3.5 text-[15px] transition border-l border-gray-200 focus:outline-none whitespace-nowrap">Q&A</button>
                        <button @click="activeTab = 'Review'" :class="activeTab === 'Review' ? 'border-t-2 border-t-ryans bg-white text-ryans font-bold' : 'text-gray-600 hover:text-ryans font-medium border-t-2 border-transparent'" class="px-8 py-3.5 text-[15px] transition border-l border-gray-200 focus:outline-none whitespace-nowrap">Review</button>
                    </div>

                    <!-- Details Tab Content -->
                    <div v-show="activeTab === 'Details'" class="p-8">
                        <h2 class="text-[20px] font-bold text-[#111] mb-6">Specification</h2>
                        <table class="w-full text-[13px] text-left border-collapse mb-8 border border-gray-200">
                            <tbody>
                                <!-- Dynamic Specs -->
                                <template v-for="(specs, groupName) in groupedSpecifications" :key="groupName">
                                    <tr class="border-b border-gray-200">
                                        <td colspan="2" class="py-3 font-bold text-ryans bg-gray-50 px-4 text-[15px] uppercase">{{ groupName }}</td>
                                    </tr>
                                    <tr v-for="spec in specs" :key="spec.key" class="border-b border-gray-200 hover:bg-gray-50/50">
                                        <td class="w-1/4 py-3 font-bold text-gray-700 bg-gray-50 px-4 border-r border-gray-200">{{ spec.key }}</td>
                                        <td class="py-3 px-4 text-gray-800 whitespace-pre-line">{{ spec.value }}</td>
                                    </tr>
                                </template>

                                <!-- General / Fallback -->
                                <tr class="border-b border-gray-200">
                                    <td colspan="2" class="py-3 font-bold text-ryans bg-gray-50 px-4 text-[15px] uppercase">General</td>
                                </tr>
                                <tr class="border-b border-gray-200 hover:bg-gray-50/50">
                                    <td class="w-1/4 py-3 font-bold text-gray-700 bg-gray-50 px-4 border-r border-gray-200">Brand</td>
                                    <td class="py-3 px-4 text-gray-800">{{ product.brand?.name || 'N/A' }}</td>
                                </tr>
                                <tr class="border-b border-gray-200 hover:bg-gray-50/50">
                                    <td class="w-1/4 py-3 font-bold text-gray-700 bg-gray-50 px-4 border-r border-gray-200">Category</td>
                                    <td class="py-3 px-4 text-gray-800">{{ product.category?.name || 'N/A' }}</td>
                                </tr>
                                <tr class="border-b border-gray-200 hover:bg-gray-50/50">
                                    <td class="w-1/4 py-3 font-bold text-gray-700 bg-gray-50 px-4 border-r border-gray-200">Product Type</td>
                                    <td class="py-3 px-4 text-gray-800">{{ product.product_type === 'digital' ? 'Digital Download' : 'Physical Product' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div v-if="product.description" class="prose max-w-none text-gray-800 text-[14px]">
                            <h2 class="text-[20px] font-bold text-[#111] mb-4">Description</h2>
                            <div class="whitespace-pre-line leading-relaxed">{{ product.description }}</div>
                        </div>
                    </div>

                    <!-- Q&A Tab Content -->
                    <div v-show="activeTab === 'Q&A'" class="p-8">
                        <h2 class="text-[20px] font-bold text-[#111] mb-2">Questions and Answers</h2>
                        <p class="text-sm text-gray-500 mb-6">Have a question about this product? Ask us!</p>
                        <button class="bg-ryans hover:bg-[#5da733] text-white px-6 py-2.5 rounded shadow text-sm font-bold transition">Ask a Question</button>
                        <hr class="my-8 border-gray-200">
                        <div class="bg-gray-50 p-6 text-center border border-gray-200 rounded">
                            <i class="far fa-comments text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-gray-600 text-sm font-medium">There are no questions asked yet. Be the first one to ask a question.</p>
                        </div>
                    </div>

                    <!-- Review Tab Content -->
                    <div v-show="activeTab === 'Review'" class="p-8">
                        <h2 class="text-[20px] font-bold text-[#111] mb-6">Customer Reviews</h2>
                        <div class="flex flex-col md:flex-row items-center gap-8 mb-8 bg-gray-50 p-6 border border-gray-200 rounded">
                            <div class="text-center md:border-r border-gray-300 md:pr-8">
                                <div class="text-5xl font-black text-ryans mb-2">0.0</div>
                                <div class="text-yellow-400 text-lg mb-1 tracking-widest">
                                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                                </div>
                                <div class="text-xs text-gray-500 font-medium">0 Reviews</div>
                            </div>
                            <div class="flex-1 text-center md:text-left">
                                <p class="text-gray-600 text-sm mb-4">Share your thoughts with other customers</p>
                                <button class="bg-white border-2 border-ryans text-ryans px-8 py-2.5 rounded shadow-sm text-sm font-bold hover:bg-ryans hover:text-white transition">Write a Review</button>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm text-center py-8">No reviews found for this product.</p>
                    </div>
                </div>

                <!-- Similar Products -->
                <div class="bg-white border border-gray-200 shadow-sm mb-6 p-6">
                    <h2 class="text-[18px] font-bold text-[#111] mb-4 pb-2 border-b border-gray-100">Similar Products</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <p class="text-sm text-gray-500 italic py-4 col-span-full">No similar products found.</p>
                    </div>
                </div>

                <!-- Related Products -->
                <div class="bg-white border border-gray-200 shadow-sm mb-6 p-6">
                    <h2 class="text-[18px] font-bold text-[#111] mb-4 pb-2 border-b border-gray-100">Related Products</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <p class="text-sm text-gray-500 italic py-4 col-span-full">No related products found.</p>
                    </div>
                </div>

            </div>
        </div>
    </CustomerLayout>
</template>
