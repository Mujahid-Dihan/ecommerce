<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;
const showingAccountDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen flex flex-col bg-[#f0f2f5] font-sans text-sm">
        
        <!-- Top Contact Bar (Dark Gray) -->
        <div class="bg-[#1f1f1f] text-gray-300 text-[12px]">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-center md:justify-start items-center h-[34px] overflow-hidden whitespace-nowrap">
                    <div class="flex space-x-6 items-center w-full">
                        <a href="tel:16810" class="hover:text-ryans transition flex items-center font-bold text-white"><span class="bg-gray-700 rounded-full w-5 h-5 flex items-center justify-center mr-1.5"><i class="fas fa-phone-alt text-[10px]"></i></span> 16 810</a>
                        <a href="#" class="hover:text-ryans transition flex items-center hidden sm:flex"><span class="bg-gray-700 rounded-full w-5 h-5 flex items-center justify-center mr-1.5"><i class="far fa-envelope text-[10px]"></i></span> info@electronicsdokan.com</a>
                        <a href="#" class="hover:text-ryans transition flex items-center hidden md:flex"><span class="bg-gray-700 rounded-full w-5 h-5 flex items-center justify-center mr-1.5"><i class="fas fa-wrench text-[10px]"></i></span> Customer Service</a>
                        <a href="#" class="hover:text-ryans transition flex items-center hidden lg:flex"><span class="bg-gray-700 rounded-full w-5 h-5 flex items-center justify-center mr-1.5"><i class="fas fa-box-open text-[10px]"></i></span> Offer</a>
                        <a href="#" class="hover:text-ryans transition flex items-center hidden lg:flex"><span class="bg-gray-700 rounded-full w-5 h-5 flex items-center justify-center mr-1.5"><i class="fab fa-sith text-[10px]"></i></span> New Arrival</a>
                        <a href="#" class="hover:text-ryans transition flex items-center hidden xl:flex"><span class="bg-gray-700 rounded-full w-5 h-5 flex items-center justify-center mr-1.5"><i class="fas fa-store text-[10px]"></i></span> Store</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header (Dark Blue / Black like Ryans) -->
        <header class="bg-[#002b5c] sticky top-0 z-50">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-[72px]">
                    
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center w-[200px]">
                        <Link :href="route('home')" class="flex flex-col">
                            <!-- Mimicking the Ryans Logo -->
                            <div class="text-[28px] font-black text-white leading-none tracking-tighter">ELECTRONICS</div>
                            <div class="text-[14px] font-bold text-ryans tracking-[0.2em] uppercase leading-none mt-1">Dokan</div>
                        </Link>
                    </div>

                    <!-- Centered Search Bar -->
                    <div class="flex-1 max-w-3xl mx-6 hidden md:flex relative">
                        <div class="flex w-full rounded shadow-sm bg-white overflow-hidden border-2 border-[#002b5c] focus-within:border-ryans transition">
                            <input type="text" class="flex-1 border-none focus:ring-0 px-4 py-2.5 text-[14px] text-gray-800" placeholder="Enter Your Keyword...">
                            <button class="bg-[#e9ecef] hover:bg-gray-300 text-gray-700 px-5 transition border-l border-gray-300">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Right Side: User & Cart Icons -->
                    <div class="flex items-center justify-end space-x-6">
                        
                        <!-- PC Builder -->
                        <div class="hidden xl:flex">
                            <a href="#" class="bg-transparent border border-ryans text-ryans hover:bg-ryans hover:text-white px-4 py-1.5 rounded transition text-[13px] font-bold flex flex-col items-center leading-tight">
                                <span>SYSTEM BUILDER</span>
                            </a>
                        </div>

                        <!-- Icons Menu -->
                        <div class="flex items-center space-x-5 text-white">
                            
                            <!-- Account -->
                            <div v-if="user" class="relative flex flex-col items-center pt-2 pb-2">
                                <div @click="showingAccountDropdown = !showingAccountDropdown" class="flex flex-col items-center cursor-pointer hover:text-ryans">
                                    <i class="fas fa-user-circle text-2xl mb-1"></i>
                                    <span class="text-[11px] font-bold">{{ user.name.split(' ')[0] }}</span>
                                </div>
                                
                                <!-- Dropdown -->
                                <div v-show="showingAccountDropdown" class="absolute right-0 top-[100%] w-48 bg-white border border-gray-100 rounded shadow-xl transition-all z-50">
                                    <div class="px-4 py-2 border-b text-sm font-bold text-gray-800">{{ user.name }}</div>
                                    <Link v-if="user.user_type === 'admin'" :href="route('admin.dashboard')" class="block px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50 hover:text-ryans">Admin Dashboard</Link>
                                    <Link v-else-if="user.user_type === 'seller'" :href="route('seller.dashboard')" class="block px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50 hover:text-ryans">Seller Dashboard</Link>
                                    <Link v-else :href="route('dashboard')" class="block px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50 hover:text-ryans">My Profile</Link>
                                    <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-[13px] text-red-600 hover:bg-gray-50">Logout</Link>
                                </div>
                            </div>
                            <Link v-else :href="route('login')" class="flex flex-col items-center hover:text-ryans transition">
                                <i class="fas fa-user-circle text-2xl mb-1"></i>
                                <span class="text-[11px]">Sign In</span>
                            </Link>

                            <!-- Compare Widget -->
                            <Link href="#" class="flex flex-col items-center hover:text-ryans relative">
                                <div class="relative">
                                    <i class="fas fa-exchange-alt text-2xl mb-1"></i>
                                    <span class="absolute -top-1 -right-2 bg-red-600 text-white text-[9px] font-bold px-1 rounded-full">0</span>
                                </div>
                                <span class="text-[11px]">Compare</span>
                            </Link>

                            <!-- Wishlist Widget -->
                            <Link href="#" class="flex flex-col items-center hover:text-ryans relative">
                                <div class="relative">
                                    <i class="fas fa-heart text-2xl mb-1"></i>
                                    <span class="absolute -top-1 -right-2 bg-red-600 text-white text-[9px] font-bold px-1 rounded-full">0</span>
                                </div>
                                <span class="text-[11px]">Favorite</span>
                            </Link>

                            <!-- Cart Widget -->
                            <Link href="#" class="flex flex-col items-center hover:text-ryans relative">
                                <div class="relative">
                                    <i class="fas fa-cart-arrow-down text-2xl mb-1"></i>
                                    <span class="absolute -top-1 -right-2 bg-red-600 text-white text-[9px] font-bold px-1 rounded-full">0</span>
                                </div>
                                <span class="text-[11px]">Cart</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Category Navbar (Ryans Blue bottom bar) -->
            <div class="bg-[#001d3d] text-white shadow-md border-t border-white/10">
                <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                    <ul class="flex overflow-x-auto hide-scrollbar space-x-1 h-10 items-center text-[13px] font-bold tracking-tight">
                        <li class="h-full flex items-center group">
                            <Link :href="route('shop.index')" class="px-4 py-1.5 bg-transparent group-hover:bg-ryans rounded transition whitespace-nowrap"><i class="fas fa-bars mr-1.5"></i> All Categories</Link>
                        </li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'desktop'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap">Desktop</Link></li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'laptop'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap">Laptop</Link></li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'component'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap">Component</Link></li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'monitor'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap">Monitor</Link></li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'ups'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap">UPS</Link></li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'tablet'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap">Tablet</Link></li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'office-equipment'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap">Office Equipment</Link></li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'camera'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap">Camera</Link></li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'security'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap">Security</Link></li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'network'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap">Network</Link></li>
                        <li class="h-full flex items-center"><Link :href="route('shop.index', {category: 'software'})" class="px-3 hover:text-ryans h-full flex items-center transition whitespace-nowrap text-yellow-400">Software</Link></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Main Footer -->
        <footer class="bg-[#212529] text-gray-300 pt-12 pb-6 border-t-[5px] border-ryans">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-5 gap-8">
                <!-- Col 1 -->
                <div class="md:col-span-2">
                    <h3 class="text-3xl font-black text-white mb-2 leading-none tracking-tighter">ELECTRONICS <span class="text-ryans text-lg tracking-widest uppercase">Dokan</span></h3>
                    <p class="text-[13px] mb-4 mt-6 leading-relaxed pr-8 text-gray-400">
                        Electronics Dokan is the largest retail destination for computers, laptops, monitors, accessories, and office equipment in Bangladesh.
                    </p>
                    <div class="flex space-x-3 mt-6">
                        <a href="#" class="w-10 h-10 rounded bg-[#3b5998] flex items-center justify-center hover:opacity-80 transition text-white text-lg"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 rounded bg-[#cd201f] flex items-center justify-center hover:opacity-80 transition text-white text-lg"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="w-10 h-10 rounded bg-[#c13584] flex items-center justify-center hover:opacity-80 transition text-white text-lg"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <!-- Col 2 -->
                <div>
                    <h4 class="text-white font-bold mb-5 uppercase text-[14px] border-b border-gray-700 pb-2">About Us</h4>
                    <ul class="space-y-3 text-[13px]">
                        <li><a href="#" class="hover:text-ryans transition text-gray-400">About Electronics Dokan</a></li>
                        <li><a href="#" class="hover:text-ryans transition text-gray-400">Careers</a></li>
                        <li><a href="#" class="hover:text-ryans transition text-gray-400">Terms & Conditions</a></li>
                        <li><a href="#" class="hover:text-ryans transition text-gray-400">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <!-- Col 3 -->
                <div>
                    <h4 class="text-white font-bold mb-5 uppercase text-[14px] border-b border-gray-700 pb-2">Customer Service</h4>
                    <ul class="space-y-3 text-[13px]">
                        <li><a href="#" class="hover:text-ryans transition text-gray-400">Online Delivery Info</a></li>
                        <li><a href="#" class="hover:text-ryans transition text-gray-400">Refund & Return Policy</a></li>
                        <li><a href="#" class="hover:text-ryans transition text-gray-400">Warranty Policy</a></li>
                        <li><a href="#" class="hover:text-ryans transition text-gray-400">Contact Us</a></li>
                    </ul>
                </div>
                
                <!-- Col 4 -->
                <div>
                    <h4 class="text-white font-bold mb-5 uppercase text-[14px] border-b border-gray-700 pb-2">Contact Info</h4>
                    <ul class="space-y-4 text-[13px]">
                        <li class="flex items-start text-gray-400">
                            <i class="fas fa-headset mt-1 mr-3 text-ryans text-lg"></i> 
                            <span><strong class="text-white">Support: +88 096 1234 5678</strong><br>(10 AM - 7 PM)</span>
                        </li>
                        <li class="flex items-start text-gray-400">
                            <i class="fas fa-envelope mt-1 mr-3 text-ryans text-lg"></i> 
                            <span>info@electronicsdokan.com</span>
                        </li>
                        <li class="flex items-start text-gray-400">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-ryans text-lg"></i> 
                            <span>IDB Bhaban, Agargaon, Dhaka</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-6 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center text-[12px] text-gray-500">
                <div>&copy; {{ new Date().getFullYear() }} Electronics Dokan. All rights reserved.</div>
                <div class="mt-2 md:mt-0">Powered by Antigravity IDE</div>
            </div>
        </footer>
    </div>
</template>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
