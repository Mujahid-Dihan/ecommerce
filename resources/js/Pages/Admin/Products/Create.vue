<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: Array,
    brands: Array,
    attributes: Array,
});

const form = useForm({
    name: '',
    category_id: '',
    brand_id: '',
    description: '',
    product_type: 'physical',
    status: 'active',
    is_featured: false,
    todays_deal: false,
    b2b_wholesale: false,
    thumbnail: null,
    images: [],
    variants: [{ variant_string: 'Default', price: 0, stock: 0, sku: '' }],
    specifications: [],
    digital_file: null,
    download_limit: null,
});

const activeTab = ref('general');
const thumbnailPreview = ref(null);
const imagesPreviews = ref([]);

const handleThumbnail = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.thumbnail = file;
        thumbnailPreview.value = URL.createObjectURL(file);
    }
};

const handleImages = (e) => {
    const files = Array.from(e.target.files);
    form.images = files;
    imagesPreviews.value = files.map(f => URL.createObjectURL(f));
};

const addVariant = () => {
    form.variants.push({ variant_string: '', price: 0, stock: 0, sku: '' });
};

const removeVariant = (index) => {
    if (form.variants.length > 1) {
        form.variants.splice(index, 1);
    }
};

const addSpec = () => {
    form.specifications.push({ group: '', key: '', value: '' });
};

const removeSpec = (index) => {
    form.specifications.splice(index, 1);
};

const submit = () => {
    form.post(route('admin.products.store'), {
        forceFormData: true,
    });
};

const tabs = [
    { key: 'general', label: 'General', icon: 'fas fa-info-circle' },
    { key: 'pricing', label: 'Pricing & Variants', icon: 'fas fa-tags' },
    { key: 'media', label: 'Media', icon: 'fas fa-images' },
    { key: 'specs', label: 'Specifications', icon: 'fas fa-list-alt' },
    { key: 'digital', label: 'Digital', icon: 'fas fa-cloud-download-alt' },
];
</script>

<template>
    <Head title="Add Product | Admin" />

    <AdminLayout>
        <template #header>
            <Link :href="route('admin.products.index')" class="text-gray-500 hover:text-ryans transition font-medium">Products</Link>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-800 font-bold">Add New</span>
        </template>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#111]">Add New Product</h1>
                <p class="text-[13px] text-gray-500 mt-1 font-medium">Fill in the product details below.</p>
            </div>
            <Link :href="route('admin.products.index')" class="text-[13px] text-gray-500 hover:text-ryans font-bold transition">
                <i class="fas fa-arrow-left mr-1"></i> Back to Products
            </Link>
        </div>

        <form @submit.prevent="submit">
            <!-- Tabs -->
            <div class="bg-white border border-gray-200 rounded-lg mb-6">
                <div class="flex border-b border-gray-200 overflow-x-auto">
                    <button v-for="tab in tabs" :key="tab.key" type="button" @click="activeTab = tab.key"
                        class="flex items-center px-5 py-3 text-[13px] font-bold border-b-2 transition whitespace-nowrap"
                        :class="activeTab === tab.key ? 'border-ryans text-ryans' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300'">
                        <i :class="tab.icon" class="mr-2 text-[12px]"></i> {{ tab.label }}
                    </button>
                </div>

                <div class="p-6">
                    <!-- General Tab -->
                    <div v-show="activeTab === 'general'" class="space-y-5">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            <div>
                                <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Product Name *</label>
                                <input v-model="form.name" type="text" required placeholder="Enter product name"
                                    class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white transition">
                                <p v-if="form.errors.name" class="text-red-500 text-[12px] mt-1">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Category *</label>
                                <select v-model="form.category_id" required
                                    class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50">
                                    <option value="" disabled>Select Category</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                                <p v-if="form.errors.category_id" class="text-red-500 text-[12px] mt-1">{{ form.errors.category_id }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                            <div>
                                <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Brand</label>
                                <select v-model="form.brand_id"
                                    class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50">
                                    <option value="">No Brand</option>
                                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Product Type *</label>
                                <select v-model="form.product_type" required
                                    class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50">
                                    <option value="physical">Physical Product</option>
                                    <option value="digital">Digital Product</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Status *</label>
                                <select v-model="form.status" required
                                    class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50">
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Description</label>
                            <textarea v-model="form.description" rows="4" placeholder="Enter product description..."
                                class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white transition"></textarea>
                        </div>

                        <!-- Flags -->
                        <div class="flex flex-wrap gap-6 pt-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.is_featured" class="w-4 h-4 text-ryans focus:ring-ryans rounded border-gray-300">
                                <span class="ml-2 text-[13px] font-medium text-gray-700">Featured Product</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.todays_deal" class="w-4 h-4 text-ryans focus:ring-ryans rounded border-gray-300">
                                <span class="ml-2 text-[13px] font-medium text-gray-700">Today's Deal</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.b2b_wholesale" class="w-4 h-4 text-ryans focus:ring-ryans rounded border-gray-300">
                                <span class="ml-2 text-[13px] font-medium text-gray-700">B2B Wholesale</span>
                            </label>
                        </div>
                    </div>

                    <!-- Pricing & Variants Tab -->
                    <div v-show="activeTab === 'pricing'" class="space-y-5">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-[15px] font-bold text-[#111]">Product Variants</h3>
                            <button type="button" @click="addVariant" class="text-[13px] text-ryans font-bold hover:underline">
                                <i class="fas fa-plus mr-1"></i> Add Variant
                            </button>
                        </div>

                        <div v-for="(variant, index) in form.variants" :key="index" class="border border-gray-200 rounded-lg p-4 bg-[#f8f9fa] relative">
                            <button v-if="form.variants.length > 1" type="button" @click="removeVariant(index)"
                                class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-[11px]">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                                <div>
                                    <label class="text-[12px] font-bold text-gray-600 mb-1 block">Variant Name *</label>
                                    <input v-model="variant.variant_string" type="text" required placeholder="e.g. Red-16GB"
                                        class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans focus:ring-ryans bg-white">
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-gray-600 mb-1 block">Price (৳) *</label>
                                    <input v-model.number="variant.price" type="number" required min="0" step="0.01"
                                        class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans focus:ring-ryans bg-white">
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-gray-600 mb-1 block">Stock *</label>
                                    <input v-model.number="variant.stock" type="number" required min="0"
                                        class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans focus:ring-ryans bg-white">
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-gray-600 mb-1 block">SKU</label>
                                    <input v-model="variant.sku" type="text" placeholder="e.g. PRD-001"
                                        class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans focus:ring-ryans bg-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media Tab -->
                    <div v-show="activeTab === 'media'" class="space-y-5">
                        <div>
                            <label class="text-[13px] font-bold text-gray-700 mb-2 block">Product Thumbnail</label>
                            <div class="flex items-start gap-4">
                                <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50 hover:border-ryans transition cursor-pointer" @click="$refs.thumbnailInput.click()">
                                    <img v-if="thumbnailPreview" :src="thumbnailPreview" class="w-full h-full object-cover">
                                    <div v-else class="text-center text-gray-400">
                                        <i class="fas fa-camera text-2xl mb-1"></i>
                                        <p class="text-[11px] font-medium">Upload</p>
                                    </div>
                                </div>
                                <input ref="thumbnailInput" type="file" class="hidden" accept="image/*" @change="handleThumbnail">
                            </div>
                        </div>

                        <div>
                            <label class="text-[13px] font-bold text-gray-700 mb-2 block">Product Gallery</label>
                            <div class="flex flex-wrap gap-3">
                                <div v-for="(preview, index) in imagesPreviews" :key="index"
                                    class="w-24 h-24 border border-gray-200 rounded-lg overflow-hidden">
                                    <img :src="preview" class="w-full h-full object-cover">
                                </div>
                                <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50 hover:border-ryans transition cursor-pointer" @click="$refs.imagesInput.click()">
                                    <div class="text-center text-gray-400">
                                        <i class="fas fa-plus text-lg"></i>
                                        <p class="text-[10px] font-medium mt-1">Add</p>
                                    </div>
                                </div>
                                <input ref="imagesInput" type="file" class="hidden" accept="image/*" multiple @change="handleImages">
                            </div>
                        </div>
                    </div>

                    <!-- Specifications Tab -->
                    <div v-show="activeTab === 'specs'" class="space-y-5">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-[15px] font-bold text-[#111]">Product Specifications</h3>
                            <button type="button" @click="addSpec" class="text-[13px] text-ryans font-bold hover:underline">
                                <i class="fas fa-plus mr-1"></i> Add Specification
                            </button>
                        </div>

                        <div v-if="!form.specifications.length" class="text-center py-8 text-gray-400">
                            <i class="fas fa-list-alt text-3xl mb-2 block"></i>
                            <p class="text-[13px] font-medium">No specifications added yet.</p>
                        </div>

                        <div v-for="(spec, index) in form.specifications" :key="index" class="border border-gray-200 rounded-lg p-4 bg-[#f8f9fa] relative">
                            <button type="button" @click="removeSpec(index)"
                                class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-[11px]">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-[12px] font-bold text-gray-600 mb-1 block">Group</label>
                                    <input v-model="spec.group" type="text" placeholder="e.g. Display"
                                        class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans focus:ring-ryans bg-white">
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-gray-600 mb-1 block">Key</label>
                                    <input v-model="spec.key" type="text" placeholder="e.g. Screen Size"
                                        class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans focus:ring-ryans bg-white">
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-gray-600 mb-1 block">Value</label>
                                    <input v-model="spec.value" type="text" placeholder='e.g. 15.6"'
                                        class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] focus:border-ryans focus:ring-ryans bg-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Digital Tab -->
                    <div v-show="activeTab === 'digital'" class="space-y-5">
                        <div v-if="form.product_type !== 'digital'" class="text-center py-8 text-gray-400">
                            <i class="fas fa-info-circle text-3xl mb-2 block"></i>
                            <p class="text-[13px] font-medium">Switch product type to "Digital" in the General tab to configure digital assets.</p>
                        </div>

                        <template v-else>
                            <div>
                                <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Digital File</label>
                                <input type="file" @change="form.digital_file = $event.target.files[0]"
                                    class="w-full border border-gray-300 rounded py-2 px-3 text-[13px] bg-gray-50">
                                <p class="text-[11px] text-gray-400 mt-1">Max file size: 50MB</p>
                            </div>
                            <div>
                                <label class="text-[13px] font-bold text-gray-700 mb-1.5 block">Download Limit</label>
                                <input v-model.number="form.download_limit" type="number" min="0" placeholder="Leave empty for unlimited"
                                    class="w-full border border-gray-300 rounded py-2.5 px-3 text-[14px] focus:border-ryans focus:ring-ryans bg-gray-50">
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3">
                <Link :href="route('admin.products.index')" class="px-6 py-2.5 bg-white border border-gray-300 rounded text-[13px] font-bold text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </Link>
                <button type="submit" :disabled="form.processing"
                    class="px-8 py-2.5 bg-ryans hover:bg-[#5da733] text-white rounded text-[13px] font-bold transition shadow-sm disabled:opacity-50 flex items-center">
                    <i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i>
                    <i v-else class="fas fa-save mr-2"></i>
                    Save Product
                </button>
            </div>
        </form>
    </AdminLayout>
</template>
