<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({ products: Array, customers: Array });

const searchQuery = ref('');
const searchResults = ref(props.products);
const cart = ref([]);
const selectedCustomer = ref('');
const paymentMethod = ref('cash');
const discount = ref(0);
const showSuccess = ref(false);
const lastOrderId = ref(null);

let searchTimer = null;
const searchProducts = () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(async () => {
        if (!searchQuery.value) { searchResults.value = props.products; return; }
        try {
            const res = await axios.get(route('admin.pos.search'), { params: { search: searchQuery.value } });
            searchResults.value = res.data;
        } catch (e) { /* ignore */ }
    }, 300);
};

const addToCart = (product, variant = null) => {
    const key = product.id + '-' + (variant?.id || 'default');
    const existing = cart.value.find(i => i.key === key);
    if (existing) { existing.quantity++; return; }
    cart.value.push({
        key,
        product_id: product.id,
        variant_id: variant?.id || null,
        name: product.name,
        variant_name: variant?.variant_string || 'Default',
        price: variant?.price || product.variants?.[0]?.price || 0,
        quantity: 1,
    });
};

const removeFromCart = (index) => { cart.value.splice(index, 1); };
const cartSubtotal = computed(() => cart.value.reduce((sum, i) => sum + i.price * i.quantity, 0));
const cartTotal = computed(() => Math.max(cartSubtotal.value - discount.value, 0));
const formatPrice = (p) => new Intl.NumberFormat('en-IN').format(p || 0);

const processing = ref(false);
const checkout = async () => {
    if (!cart.value.length) return;
    processing.value = true;
    try {
        const res = await axios.post(route('admin.pos.checkout'), {
            customer_id: selectedCustomer.value || null,
            items: cart.value.map(i => ({ product_id: i.product_id, variant_id: i.variant_id, quantity: i.quantity, price: i.price })),
            payment_method: paymentMethod.value,
            discount: discount.value,
        });
        lastOrderId.value = res.data.order_id;
        cart.value = [];
        discount.value = 0;
        showSuccess.value = true;
        setTimeout(() => { showSuccess.value = false; }, 4000);
    } catch (e) { alert('Checkout failed. Please try again.'); }
    processing.value = false;
};
</script>

<template>
    <Head title="POS | Admin" />
    <AdminLayout>
        <template #header><span class="text-gray-800 font-bold">Point of Sale</span></template>

        <!-- Success Toast -->
        <div v-if="showSuccess" class="fixed top-4 right-4 z-50 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3 animate-slide-in">
            <i class="fas fa-check-circle text-lg"></i>
            <div><p class="font-bold text-[14px]">Order Created!</p><p class="text-[12px] opacity-90">Order #{{ lastOrderId }}</p></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 h-[calc(100vh-140px)]">
            <!-- Products Grid (Left) -->
            <div class="lg:col-span-3 flex flex-col">
                <div class="mb-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input v-model="searchQuery" @input="searchProducts" type="text" placeholder="Search products or scan barcode..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-[14px] focus:border-ryans focus:ring-ryans bg-white shadow-sm" autofocus>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3">
                        <div v-for="p in searchResults" :key="p.id" @click="addToCart(p, p.variants?.[0])"
                            class="bg-white border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-ryans hover:shadow-sm transition group">
                            <div class="aspect-square bg-gray-100 rounded mb-2 flex items-center justify-center overflow-hidden">
                                <img v-if="p.thumbnail" :src="'/storage/' + p.thumbnail" class="w-full h-full object-cover">
                                <i v-else class="fas fa-image text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-[12px] font-bold text-[#111] truncate group-hover:text-ryans transition">{{ p.name }}</p>
                            <p class="text-[13px] font-bold text-ryans mt-1">৳{{ formatPrice(p.variants?.[0]?.price) }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Stock: {{ p.variants?.[0]?.stock || 0 }}</p>
                        </div>
                        <div v-if="!searchResults?.length" class="col-span-full py-12 text-center text-gray-400">
                            <i class="fas fa-search text-4xl mb-3 block"></i><p>No products found.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Panel (Right) -->
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg flex flex-col">
                <div class="px-4 py-3 border-b border-gray-200 bg-[#f8f9fa] rounded-t-lg">
                    <h3 class="text-[14px] font-bold text-[#111]"><i class="fas fa-shopping-cart mr-2 text-ryans"></i> Cart ({{ cart.length }})</h3>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    <div v-for="(item, index) in cart" :key="item.key" class="flex items-center justify-between bg-[#f8f9fa] rounded-lg p-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-bold text-[#111] truncate">{{ item.name }}</p>
                            <p class="text-[11px] text-gray-500">{{ item.variant_name }} · ৳{{ formatPrice(item.price) }}</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-3">
                            <button @click="item.quantity > 1 ? item.quantity-- : null" class="w-6 h-6 rounded bg-gray-200 text-gray-600 text-[11px] flex items-center justify-center hover:bg-gray-300">-</button>
                            <span class="text-[13px] font-bold w-6 text-center">{{ item.quantity }}</span>
                            <button @click="item.quantity++" class="w-6 h-6 rounded bg-gray-200 text-gray-600 text-[11px] flex items-center justify-center hover:bg-gray-300">+</button>
                        </div>
                        <p class="text-[13px] font-bold text-[#111] ml-3 w-20 text-right">৳{{ formatPrice(item.price * item.quantity) }}</p>
                        <button @click="removeFromCart(index)" class="ml-2 text-red-400 hover:text-red-600 text-[12px]"><i class="fas fa-times"></i></button>
                    </div>
                    <div v-if="!cart.length" class="py-12 text-center text-gray-400"><i class="fas fa-shopping-cart text-3xl mb-2 block"></i><p class="text-[13px]">Cart is empty</p></div>
                </div>

                <!-- Cart Footer -->
                <div class="border-t border-gray-200 p-4 space-y-3 bg-[#f8f9fa] rounded-b-lg">
                    <select v-model="selectedCustomer" class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] bg-white focus:border-ryans">
                        <option value="">Walk-in Customer</option>
                        <option v-for="c in customers" :key="c.id" :value="c.id">{{ c.name }} ({{ c.email }})</option>
                    </select>
                    <div class="flex items-center space-x-2">
                        <input v-model.number="discount" type="number" min="0" placeholder="Discount" class="flex-1 border border-gray-300 rounded py-2 px-3 text-[13px] bg-white focus:border-ryans">
                        <select v-model="paymentMethod" class="flex-1 border border-gray-300 rounded py-2 px-3 text-[13px] bg-white focus:border-ryans">
                            <option value="cash">Cash</option><option value="card">Card</option><option value="mobile_banking">Mobile Banking</option>
                        </select>
                    </div>
                    <div class="space-y-1 text-[13px]">
                        <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="font-bold">৳{{ formatPrice(cartSubtotal) }}</span></div>
                        <div v-if="discount" class="flex justify-between"><span class="text-gray-500">Discount</span><span class="font-bold text-red-600">-৳{{ formatPrice(discount) }}</span></div>
                        <div class="flex justify-between text-[16px] border-t border-gray-200 pt-2"><span class="font-bold">Total</span><span class="font-bold text-ryans">৳{{ formatPrice(cartTotal) }}</span></div>
                    </div>
                    <button @click="checkout" :disabled="!cart.length || processing"
                        class="w-full py-3 bg-ryans hover:bg-[#5da733] text-white rounded-lg text-[14px] font-bold transition shadow-sm disabled:opacity-50 flex items-center justify-center">
                        <i v-if="processing" class="fas fa-spinner fa-spin mr-2"></i>
                        <i v-else class="fas fa-cash-register mr-2"></i> Complete Sale
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
