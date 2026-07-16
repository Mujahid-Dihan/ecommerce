<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);

const sidebarOpen = ref(true);
const showUserDropdown = ref(false);
const openMenus = ref(['dashboard']);

const toggleMenu = (key) => {
    const index = openMenus.value.indexOf(key);
    if (index > -1) {
        openMenus.value.splice(index, 1);
    } else {
        openMenus.value.push(key);
    }
};

const isMenuOpen = (key) => openMenus.value.includes(key);

const isActive = (routeName) => {
    try {
        return route().current(routeName);
    } catch {
        return false;
    }
};

const logout = () => {
    router.post(route('logout'));
};

const menuItems = [
    {
        key: 'dashboard',
        label: 'Dashboard',
        icon: 'fas fa-home',
        route: 'seller.dashboard',
        single: true,
    },
    {
        key: 'products',
        label: 'Products',
        icon: 'fas fa-box-open',
        children: [
            { label: 'All Products', route: 'seller.products.index' },
            { label: 'Add New Product', route: 'seller.products.create' },
        ]
    },
    {
        key: 'orders',
        label: 'Orders',
        icon: 'fas fa-shopping-bag',
        route: 'seller.orders.index',
        single: true,
    },
];

const hasRoute = (routeName) => {
    try {
        route(routeName);
        return true;
    } catch {
        return false;
    }
};
</script>

<template>
    <div class="flex h-screen overflow-hidden bg-[#f0f2f5] font-sans">

        <!-- Sidebar Overlay (mobile) -->
        <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
            class="fixed lg:static inset-y-0 left-0 z-50 w-[260px] bg-[#002b5c] text-white flex flex-col transform transition-transform duration-200 lg:translate-x-0 flex-shrink-0"
        >
            <!-- Logo Area -->
            <div class="h-[64px] flex items-center px-5 border-b border-white/10 flex-shrink-0">
                <Link :href="route('seller.dashboard')" class="flex flex-col">
                    <span class="text-[22px] font-black tracking-tighter leading-none text-white">ELECTRONICS</span>
                    <span class="text-[11px] font-bold text-ryans tracking-[0.2em] uppercase leading-none mt-0.5">Dokan Seller</span>
                </Link>
                <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-white/50 hover:text-white">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Scrollable Menu -->
            <nav class="flex-1 overflow-y-auto py-3 sidebar-scroll">
                <template v-for="item in menuItems" :key="item.key">
                    <!-- Single link -->
                    <template v-if="item.single">
                        <Link 
                            v-if="hasRoute(item.route)"
                            :href="route(item.route)" 
                            class="flex items-center px-5 py-2.5 text-[13px] font-medium transition-colors group"
                            :class="isActive(item.route) ? 'bg-[#001d3d] text-ryans border-r-3 border-ryans' : 'text-gray-300 hover:bg-[#001d3d] hover:text-white'"
                        >
                            <i class="w-5 mr-3 text-center text-[14px]" :class="[item.icon, isActive(item.route) ? 'text-ryans' : 'text-gray-400 group-hover:text-ryans']"></i>
                            {{ item.label }}
                        </Link>
                        <div v-else class="flex items-center px-5 py-2.5 text-[13px] font-medium text-gray-500 cursor-not-allowed">
                            <i :class="item.icon" class="w-5 mr-3 text-center text-[14px] text-gray-600"></i>
                            {{ item.label }}
                        </div>
                    </template>

                    <!-- Expandable -->
                    <template v-else>
                        <button 
                            @click="toggleMenu(item.key)" 
                            class="w-full flex items-center justify-between px-5 py-2.5 text-[13px] font-medium transition-colors group"
                            :class="isMenuOpen(item.key) ? 'bg-[#001d3d] text-white' : 'text-gray-300 hover:bg-[#001d3d] hover:text-white'"
                        >
                            <span class="flex items-center">
                                <i class="w-5 mr-3 text-center text-[14px]" :class="[item.icon, isMenuOpen(item.key) ? 'text-ryans' : 'text-gray-400 group-hover:text-ryans']"></i>
                                {{ item.label }}
                            </span>
                            <i class="fas fa-chevron-right text-[10px] transition-transform duration-200" :class="isMenuOpen(item.key) ? 'rotate-90' : ''"></i>
                        </button>

                        <div v-show="isMenuOpen(item.key)" class="bg-[#001a35]">
                            <template v-for="child in item.children" :key="child.route">
                                <Link 
                                    v-if="hasRoute(child.route)"
                                    :href="route(child.route)" 
                                    class="flex items-center pl-12 pr-5 py-2 text-[12px] font-medium transition-colors"
                                    :class="isActive(child.route) ? 'text-ryans bg-[#001230]' : 'text-gray-400 hover:text-white hover:bg-[#001230]'"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full mr-2.5 flex-shrink-0" :class="isActive(child.route) ? 'bg-ryans' : 'bg-gray-600'"></span>
                                    {{ child.label }}
                                </Link>
                                <div v-else class="flex items-center pl-12 pr-5 py-2 text-[12px] font-medium text-gray-600 cursor-not-allowed">
                                    <span class="w-1.5 h-1.5 rounded-full mr-2.5 bg-gray-700 flex-shrink-0"></span>
                                    {{ child.label }}
                                </div>
                            </template>
                        </div>
                    </template>
                </template>
            </nav>

            <!-- Sidebar Footer -->
            <div class="border-t border-white/10 px-5 py-3 flex-shrink-0">
                <Link :href="route('home')" class="flex items-center text-[12px] text-gray-400 hover:text-ryans transition">
                    <i class="fas fa-external-link-alt mr-2"></i> View Storefront
                </Link>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Bar -->
            <header class="h-[64px] bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-6 flex-shrink-0 shadow-sm">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden mr-3 text-gray-500 hover:text-gray-800 transition">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="hidden lg:block">
                        <slot name="breadcrumb">
                            <div class="flex items-center text-[13px]">
                                <Link :href="route('seller.dashboard')" class="text-gray-500 hover:text-ryans transition font-medium">
                                    <i class="fas fa-home"></i>
                                </Link>
                                <span class="mx-2 text-gray-300">/</span>
                                <slot name="header">
                                    <span class="text-gray-800 font-bold">Dashboard</span>
                                </slot>
                            </div>
                        </slot>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button class="relative text-gray-400 hover:text-gray-700 transition">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1.5 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
                    </button>

                    <div class="relative">
                        <button @click="showUserDropdown = !showUserDropdown" class="flex items-center text-[13px] text-gray-700 hover:text-gray-900 font-medium transition">
                            <div class="w-8 h-8 rounded-full bg-[#002b5c] text-white flex items-center justify-center text-[13px] font-bold mr-2">
                                {{ user?.name?.charAt(0)?.toUpperCase() }}
                            </div>
                            <span class="hidden sm:inline">{{ user?.name }}</span>
                            <i class="fas fa-chevron-down text-[10px] ml-2 text-gray-400"></i>
                        </button>

                        <div v-show="showUserDropdown" @click="showUserDropdown = false" class="absolute right-0 top-[calc(100%+8px)] w-48 bg-white border border-gray-200 rounded-lg shadow-xl z-50 py-1">
                            <Link :href="route('profile.edit')" class="block px-4 py-2.5 text-[13px] text-gray-700 hover:bg-gray-50 hover:text-ryans transition font-medium">
                                <i class="fas fa-user-circle mr-2 text-gray-400"></i> My Profile
                            </Link>
                            <div class="border-t border-gray-100 my-1"></div>
                            <button @click="logout" class="block w-full text-left px-4 py-2.5 text-[13px] text-red-600 hover:bg-red-50 transition font-medium">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.sidebar-scroll::-webkit-scrollbar {
    width: 4px;
}
.sidebar-scroll::-webkit-scrollbar-track {
    background: transparent;
}
.sidebar-scroll::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.1);
    border-radius: 2px;
}
.sidebar-scroll::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.2);
}
</style>
