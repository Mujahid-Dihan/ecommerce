<script setup>
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

defineProps({
    canLogin: Boolean,
    categories: Array,
    topBrands: Array,
    featuredProducts: Array,
    todaysDeals: Array,
});

const formatPrice = (price) => {
    if (!price) return '0';
    return new Intl.NumberFormat('en-IN').format(price);
};
</script>

<template>
    <Head title="Electronics Dokan | Laptop, PC & Electronics Retail Store" />

    <CustomerLayout>
        
        <div class="bg-[#f0f2f5] pb-12 font-sans">
            <!-- Main Content Container -->
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                
                <!-- Hero Section & Right Banners -->
                <div class="flex flex-col lg:flex-row gap-4 mb-8">
                    <!-- Main Slider (Takes up more space) -->
                    <div class="w-full lg:w-[80%]">
                        <div class="bg-white rounded overflow-hidden relative shadow-sm h-[400px]">
                            <img src="https://placehold.co/1200x500/002b5c/ffffff?text=Electronics+Dokan+Mega+Sale" class="w-full h-full object-cover" alt="Banner">
                            <!-- Slider Controls Mockup -->
                            <div class="absolute inset-0 flex items-center justify-between px-4">
                                <button class="w-10 h-10 bg-black/30 hover:bg-black/60 rounded flex items-center justify-center text-white transition"><i class="fas fa-chevron-left"></i></button>
                                <button class="w-10 h-10 bg-black/30 hover:bg-black/60 rounded flex items-center justify-center text-white transition"><i class="fas fa-chevron-right"></i></button>
                            </div>
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-white"></div>
                                <div class="w-3 h-3 rounded-full bg-white/50"></div>
                                <div class="w-3 h-3 rounded-full bg-white/50"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Banners -->
                    <div class="w-full lg:w-[20%] flex flex-col gap-[16px] h-auto lg:h-[400px]">
                        <div class="w-full h-[192px] bg-white rounded overflow-hidden shadow-sm">
                             <img src="https://placehold.co/400x200/ef4444/ffffff?text=Laptops" class="w-full h-full object-cover hover:scale-105 transition duration-500 cursor-pointer" alt="Side Banner 1">
                        </div>
                        <div class="w-full h-[192px] bg-white rounded overflow-hidden shadow-sm">
                             <img src="https://placehold.co/400x200/10b981/ffffff?text=Accessories" class="w-full h-full object-cover hover:scale-105 transition duration-500 cursor-pointer" alt="Side Banner 2">
                        </div>
                    </div>
                </div>

                <!-- Support Boxes -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-md p-4 flex items-center hover:shadow-md transition">
                        <div class="text-ryans text-4xl mr-4"><i class="fas fa-laptop-medical"></i></div>
                        <div>
                            <h6 class="text-[15px] font-bold text-[#111]">Laptop Servicing</h6>
                            <p class="text-[12px] text-gray-500 m-0">Expert Repair Service</p>
                        </div>
                    </div>
                    <div class="bg-white shadow-sm border border-gray-200 rounded-md p-4 flex items-center hover:shadow-md transition">
                        <div class="text-ryans text-4xl mr-4"><i class="fas fa-shield-alt"></i></div>
                        <div>
                            <h6 class="text-[15px] font-bold text-[#111]">100% Authentic</h6>
                            <p class="text-[12px] text-gray-500 m-0">Official Brand Warranty</p>
                        </div>
                    </div>
                    <div class="bg-white shadow-sm border border-gray-200 rounded-md p-4 flex items-center hover:shadow-md transition">
                        <div class="text-ryans text-4xl mr-4"><i class="fas fa-undo"></i></div>
                        <div>
                            <h6 class="text-[15px] font-bold text-[#111]">Return Policy</h6>
                            <p class="text-[12px] text-gray-500 m-0">Easy Return System</p>
                        </div>
                    </div>
                    <div class="bg-white shadow-sm border border-gray-200 rounded-md p-4 flex items-center hover:shadow-md transition">
                        <div class="text-ryans text-4xl mr-4"><i class="fas fa-credit-card"></i></div>
                        <div>
                            <h6 class="text-[15px] font-bold text-[#111]">Secure Payment</h6>
                            <p class="text-[12px] text-gray-500 m-0">EMI & Mobile Banking</p>
                        </div>
                    </div>
                </div>

                <!-- Top Categories Section -->
                <div class="mb-10">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-[#111] m-0 border-l-4 border-ryans pl-3">Top Categories</h2>
                        <Link :href="route('shop.index')" class="text-[13px] font-bold text-gray-600 hover:text-ryans">View All <i class="fas fa-angle-right"></i></Link>
                    </div>
                    
                    <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-8 gap-4">
                        <!-- Category Circles -->
                        <Link v-for="(cat, index) in categories" :key="cat.id" :href="route('shop.index', {category: cat.slug})" class="flex flex-col items-center group">
                            <div class="w-24 h-24 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:border-ryans transition overflow-hidden p-2">
                                <img :src="'https://placehold.co/80x80/f0f2f5/666666?text=' + encodeURIComponent(cat.name.split(' ')[0])" class="w-full h-full object-contain group-hover:scale-110 transition duration-300">
                            </div>
                            <span class="mt-3 text-[13px] font-bold text-[#111] group-hover:text-ryans text-center">{{ cat.name }}</span>
                        </Link>
                    </div>
                </div>

                <!-- Today's Deals Section -->
                <div v-if="todaysDeals.length > 0" class="mb-10">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-[#111] m-0 border-l-4 border-ryans pl-3">Today's Deals</h2>
                        <Link :href="route('shop.index')" class="text-[13px] font-bold text-gray-600 hover:text-ryans">View All <i class="fas fa-angle-right"></i></Link>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <!-- Product Card EXACT Ryans Clone (Dynamic) -->
                        <div v-for="product in todaysDeals" :key="product.id" class="bg-white border border-gray-200 rounded hover:shadow-[0_0_15px_rgba(0,0,0,0.1)] transition duration-300 flex flex-col group p-4 relative">
                            
                            <div class="absolute top-2 left-2 bg-red-600 text-white text-[11px] font-bold px-2 py-0.5 rounded-sm z-10">-10%</div>
                            
                            <!-- Image -->
                            <Link :href="route('product.show', product.slug)" class="group/image h-[180px] w-full flex items-center justify-center mb-4 overflow-hidden relative block">
                                <img v-if="product.thumbnail" :src="'/storage/' + product.thumbnail" alt="product" class="max-h-full max-w-full object-contain group-hover/image:scale-110 transition duration-500">
                                <img v-else :src="'https://placehold.co/300x300/f0f2f5/666666?text=' + encodeURIComponent(product.slug.replace(/-/g, ' '))" alt="product" class="max-h-full max-w-full object-contain group-hover/image:scale-110 transition duration-500">
                                <div class="absolute inset-0 bg-white/70 flex items-center justify-center gap-2 opacity-0 group-hover/image:opacity-100 transition duration-300 z-20">
                                    <button title="Add to Cart" class="w-9 h-9 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-base"><i class="fas fa-shopping-cart"></i></button>
                                    <button title="Add to Compare" class="w-9 h-9 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-base"><i class="fas fa-exchange-alt"></i></button>
                                    <button title="Add to Favorite" class="w-9 h-9 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-base"><i class="far fa-heart"></i></button>
                                    <button title="Quick View" class="w-9 h-9 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-base"><i class="far fa-eye"></i></button>
                                </div>
                            </Link>
                            
                            <!-- Info -->
                            <div class="flex-grow flex flex-col justify-start">
                                <Link :href="route('product.show', product.slug)" class="text-[14px] text-[#0066cc] hover:text-red-600 hover:underline leading-snug font-medium line-clamp-3 mb-2">{{ product.name }}</Link>
                                
                                <ul class="text-[12px] text-gray-600 list-disc pl-4 mb-3 line-clamp-2">
                                    <li>Brand: {{ product.brand?.name }}</li>
                                    <li v-if="product.description">{{ product.description.substring(0, 30) }}...</li>
                                </ul>
                            </div>
                            
                            <!-- Price & Cart (Ryans style static price, green button on hover) -->
                            <div class="mt-auto border-t border-gray-100 pt-3 relative">
                                <div class="text-[12px] text-gray-400 line-through mb-0.5" v-if="product.variants?.[0]?.price">Tk {{ formatPrice(product.variants[0].price * 1.1) }}</div>
                                <div class="text-[18px] font-bold text-[#111]">Tk {{ formatPrice(product.variants?.[0]?.price || 0) }}</div>
                                
                                <!-- Hover Add to Cart -->
                                <div class="absolute left-0 right-0 bottom-0 top-3 bg-white flex flex-col justify-end opacity-0 group-hover:opacity-100 transition duration-300 z-10">
                                    <Link :href="route('product.show', product.slug)" class="w-full bg-ryans hover:bg-[#5da733] text-white text-[14px] font-bold py-2 rounded transition shadow-md flex items-center justify-center">
                                        <i class="fas fa-shopping-cart mr-2"></i> Buy Now
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Products Section -->
                <div class="mb-10">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-[#111] m-0 border-l-4 border-ryans pl-3">Featured Products</h2>
                        <Link :href="route('shop.index')" class="text-[13px] font-bold text-gray-600 hover:text-ryans">View All <i class="fas fa-angle-right"></i></Link>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        <!-- Product Card (Smaller variant for 6 cols) -->
                        <div v-for="product in featuredProducts" :key="product.id" class="bg-white border border-gray-200 rounded hover:shadow-[0_0_15px_rgba(0,0,0,0.1)] transition duration-300 flex flex-col group p-3 relative">
                            
                            <Link :href="route('product.show', product.slug)" class="group/image h-[140px] w-full flex items-center justify-center mb-3 overflow-hidden relative block">
                                <img v-if="product.thumbnail" :src="'/storage/' + product.thumbnail" alt="product" class="max-h-full max-w-full object-contain group-hover/image:scale-110 transition duration-500">
                                <img v-else :src="'https://placehold.co/300x300/f0f2f5/666666?text=' + encodeURIComponent(product.slug.replace(/-/g, ' '))" alt="product" class="max-h-full max-w-full object-contain group-hover/image:scale-110 transition duration-500">
                                <div class="absolute inset-0 bg-white/70 flex items-center justify-center gap-2 opacity-0 group-hover/image:opacity-100 transition duration-300 z-20">
                                    <button title="Add to Cart" class="w-8 h-8 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-sm"><i class="fas fa-shopping-cart"></i></button>
                                    <button title="Add to Compare" class="w-8 h-8 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-sm"><i class="fas fa-exchange-alt"></i></button>
                                    <button title="Add to Favorite" class="w-8 h-8 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-sm"><i class="far fa-heart"></i></button>
                                    <button title="Quick View" class="w-8 h-8 rounded-full bg-white text-gray-700 shadow-md hover:bg-ryans hover:text-white flex items-center justify-center transition text-sm"><i class="far fa-eye"></i></button>
                                </div>
                            </Link>
                            
                            <div class="flex-grow flex flex-col justify-start">
                                <Link :href="route('product.show', product.slug)" class="text-[13px] text-[#0066cc] hover:text-red-600 hover:underline leading-snug font-medium line-clamp-2 mb-2">{{ product.name }}</Link>
                            </div>
                            
                            <div class="mt-auto pt-2 relative">
                                <div class="text-[16px] font-bold text-[#111] mb-1">Tk {{ formatPrice(product.variants?.[0]?.price || 0) }}</div>
                                <Link :href="route('product.show', product.slug)" class="w-full bg-white border border-ryans text-ryans hover:bg-ryans hover:text-white text-[13px] font-bold py-1.5 rounded transition flex items-center justify-center block text-center">
                                    <i class="fas fa-cart-plus mr-1.5"></i> Add to Cart
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Brands Section -->
                <div class="mb-10">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-[#111] m-0 border-l-4 border-ryans pl-3">Top Brands</h2>
                        <Link :href="route('shop.index')" class="text-[13px] font-bold text-gray-600 hover:text-ryans">View All <i class="fas fa-angle-right"></i></Link>
                    </div>
                    
                    <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-10 gap-3">
                        <!-- Brand Logos -->
                        <Link v-for="brand in topBrands" :key="brand.id" :href="route('shop.index', {brand: brand.slug})" class="bg-white border border-gray-200 rounded p-3 flex items-center justify-center h-[70px] hover:shadow-md hover:border-ryans transition group block">
                            <img :src="'https://placehold.co/100x40/ffffff/333333?text=' + encodeURIComponent(brand.name)" class="max-h-full max-w-full opacity-70 group-hover:opacity-100 transition" :alt="brand.name">
                        </Link>
                    </div>
                </div>

                <!-- Web Content SEO section -->
                <div class="bg-white p-6 rounded-md shadow-sm border border-gray-200 mb-8 mt-12">
                    <h1 class="text-xl font-bold text-[#111] mb-4 border-b pb-2">Buy the Best Laptops, Desktops, and Electronics in Bangladesh from Electronics Dokan</h1>
                    <div class="text-[13px] text-gray-600 space-y-4 text-justify leading-relaxed font-medium">
                        <p>Electronics Dokan is the premier destination for tech enthusiasts and professionals in Bangladesh. Whether you are looking for the latest gaming laptops, high-performance desktop PCs, or reliable office equipment, we have it all under one roof. Our commitment to quality and customer satisfaction makes us the top computer shop in Bangladesh.</p>
                        
                        <h2 class="text-[15px] font-bold text-[#111] mt-5">Best Laptop Shop in Bangladesh</h2>
                        <p>We stock all major brands including Apple, Dell, HP, Lenovo, Asus, Acer, and MSI. Get original products with official warranty at the lowest prices. From budget-friendly student laptops to high-end content creation machines, Electronics Dokan has the perfect device for you.</p>

                        <h2 class="text-[15px] font-bold text-[#111] mt-5">PC Components and Accessories</h2>
                        <p>Build your dream PC with our massive collection of processors (Intel & AMD), motherboards, graphics cards, RAM, and storage solutions. We also offer a wide range of peripherals like gaming keyboards, mice, monitors, and networking equipment to complete your setup.</p>
                    </div>
                </div>

            </div>
        </div>
    </CustomerLayout>
</template>
