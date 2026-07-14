<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    product: Object,
    categories: Array,
    brands: Array,
    attributes: Array,
});

const existingDigitalAsset = props.product.digital_assets && props.product.digital_assets.length > 0
    ? props.product.digital_assets[0]
    : null;

// Map existing variants to the format the form needs
const existingVariants = props.product.variants && props.product.variants.length > 0
    ? props.product.variants.map(v => ({
        variant_string: v.variant_string,
        price: v.price,
        stock: v.stock,
        sku: v.sku || ''
    }))
    : [{ variant_string: 'default', price: 0, stock: 0, sku: '' }];

const form = useForm({
    name: props.product.name,
    category_id: props.product.category_id,
    brand_id: props.product.brand_id || '',
    description: props.product.description || '',
    product_type: props.product.product_type,
    b2b_wholesale: props.product.b2b_wholesale,
    status: props.product.status,
    thumbnail: null,
    images: [],
    digital_file: null,
    download_limit: existingDigitalAsset ? existingDigitalAsset.download_limit : null,
    specifications: props.product.specifications || [],
    variants: existingVariants,
    _method: 'put',
});

// State for selected attributes/values
const selectedAttributes = ref({});

// Infer selected attributes from existing variants on mount
onMounted(() => {
    if (existingVariants.length > 0 && existingVariants[0].variant_string !== 'default' && props.attributes) {
        existingVariants.forEach(variant => {
            const parts = variant.variant_string.split('-');
            parts.forEach(part => {
                props.attributes.forEach(attr => {
                    const found = attr.values.find(v => v.value === part);
                    if (found) {
                        if (!selectedAttributes.value[attr.id]) {
                            selectedAttributes.value[attr.id] = [];
                        }
                        if (!selectedAttributes.value[attr.id].includes(part)) {
                            selectedAttributes.value[attr.id].push(part);
                        }
                    }
                });
            });
        });
    }
});

const toggleAttribute = (attributeId) => {
    if (selectedAttributes.value[attributeId] !== undefined) {
        delete selectedAttributes.value[attributeId];
    } else {
        selectedAttributes.value[attributeId] = [];
    }
    generateVariants();
};

const toggleValue = (attributeId, valStr) => {
    if (selectedAttributes.value[attributeId] === undefined) return;
    
    const index = selectedAttributes.value[attributeId].indexOf(valStr);
    if (index === -1) {
        selectedAttributes.value[attributeId].push(valStr);
    } else {
        selectedAttributes.value[attributeId].splice(index, 1);
    }
    generateVariants();
};

const generateVariants = () => {
    const activeAttributes = Object.keys(selectedAttributes.value)
        .map(key => selectedAttributes.value[key])
        .filter(vals => vals.length > 0);

    if (activeAttributes.length === 0) {
        const existingDefault = form.variants.find(v => v.variant_string === 'default');
        form.variants = [{
            variant_string: 'default',
            price: existingDefault ? existingDefault.price : 0,
            stock: existingDefault ? existingDefault.stock : 0,
            sku: existingDefault ? existingDefault.sku : '',
        }];
        return;
    }

    const cartesian = (...a) => a.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())));
    
    let combinations = activeAttributes.length === 1 
        ? activeAttributes[0].map(v => [v])
        : cartesian(...activeAttributes);

    const newVariants = combinations.map(combo => {
        const variantString = combo.join('-');
        const existingVariant = form.variants.find(v => v.variant_string === variantString);
        
        return {
            variant_string: variantString,
            price: existingVariant ? existingVariant.price : 0,
            stock: existingVariant ? existingVariant.stock : 0,
            sku: existingVariant ? existingVariant.sku : '',
        };
    });

    form.variants = newVariants;
};

const addSpecification = () => {
    form.specifications.push({ group: '', key: '', value: '' });
};

const removeSpecification = (index) => {
    form.specifications.splice(index, 1);
};

const submit = () => {
    form.post(route('seller.products.update', props.product.id));
};
</script>

<template>
    <Head title="Edit Product" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Product: {{ product.name }}</h2>
                <Link :href="route('seller.products.index')" class="text-gray-600 hover:text-gray-900 font-medium">
                    &larr; Back to Products
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Name -->
                            <div>
                                <InputLabel for="name" value="Product Name *" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <!-- Category -->
                            <div>
                                <InputLabel for="category_id" value="Category *" />
                                <select id="category_id" v-model="form.category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="" disabled>Select a category</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.category_id" />
                            </div>

                            <!-- Brand -->
                            <div>
                                <InputLabel for="brand_id" value="Brand (Optional)" />
                                <select id="brand_id" v-model="form.brand_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">No Brand</option>
                                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.brand_id" />
                            </div>

                            <!-- Product Type -->
                            <div>
                                <InputLabel for="product_type" value="Product Type *" />
                                <select id="product_type" v-model="form.product_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="physical">Physical Product</option>
                                    <option value="digital">Digital Product</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.product_type" />
                            </div>
                        </div>

                        <!-- Digital Product Details (Conditional) -->
                        <div v-if="form.product_type === 'digital'" class="bg-indigo-50 p-4 rounded-md mb-6 border border-indigo-100">
                            <h3 class="text-md font-medium text-indigo-800 mb-4">Digital Asset Settings</h3>
                            <div v-if="existingDigitalAsset" class="mb-4 text-sm text-gray-700 p-3 bg-white rounded border border-gray-200">
                                <strong>Current File:</strong> <span class="text-indigo-600">{{ existingDigitalAsset.file_path.split('/').pop() }}</span>
                                <p class="text-xs text-gray-500 mt-1">Upload a new file below to replace it.</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="digital_file" value="Digital File (Zip, PDF, etc.)" />
                                    <input id="digital_file" type="file" @input="form.digital_file = $event.target.files[0]" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700" :required="!existingDigitalAsset" />
                                    <InputError class="mt-2" :message="form.errors.digital_file" />
                                </div>
                                <div>
                                    <InputLabel for="download_limit" value="Download Limit (Leave blank for unlimited)" />
                                    <TextInput id="download_limit" type="number" class="mt-1 block w-full" v-model="form.download_limit" />
                                    <InputError class="mt-2" :message="form.errors.download_limit" />
                                </div>
                            </div>
                        </div>

                        <!-- Description & Image -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <InputLabel for="description" value="Description" />
                                <textarea id="description" v-model="form.description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div>
                                <InputLabel for="thumbnail" value="Update Product Image" />
                                <div v-if="product.thumbnail" class="mb-2">
                                    <img :src="'/storage/' + product.thumbnail" class="h-20 w-auto rounded border" alt="Current thumbnail">
                                </div>
                                <input id="thumbnail" type="file" @input="form.thumbnail = $event.target.files[0]" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                                <div v-if="product.thumbnail" class="mt-2 text-sm text-gray-500">Current image will be kept if no new image is selected.</div>
                                <InputError class="mt-2" :message="form.errors.thumbnail" />
                            </div>
                            
                            <div class="md:col-span-2">
                                <InputLabel for="images" value="Add Additional Images (Gallery)" />
                                <div v-if="product.images && product.images.length > 0" class="mb-3 flex flex-wrap gap-2">
                                    <div v-for="(img, idx) in product.images" :key="idx" class="relative group">
                                        <img :src="'/storage/' + img" class="h-16 w-auto rounded border" alt="Gallery image">
                                    </div>
                                    <p class="text-xs text-gray-500 w-full mt-1">Uploading new images will append to these existing images.</p>
                                </div>
                                <input id="images" type="file" multiple @input="form.images = Array.from($event.target.files)" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                                <InputError class="mt-2" :message="form.errors.images" />
                            </div>
                        </div>

                        <hr class="my-6">
                        
                        <!-- Specifications -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Product Specifications</h3>
                                    <p class="text-sm text-gray-500">Add detailed specs (e.g. Display, Processor, Memory).</p>
                                </div>
                                <button type="button" @click="addSpecification" class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded text-sm font-medium hover:bg-indigo-100 border border-indigo-200">
                                    + Add Specification
                                </button>
                            </div>
                            
                            <div v-if="form.specifications.length > 0" class="space-y-3">
                                <div v-for="(spec, index) in form.specifications" :key="index" class="flex items-center gap-3 bg-gray-50 p-3 rounded border">
                                    <div class="flex-1">
                                        <TextInput type="text" v-model="spec.group" placeholder="Group (e.g. Processor)" class="w-full text-sm" required />
                                    </div>
                                    <div class="flex-1">
                                        <TextInput type="text" v-model="spec.key" placeholder="Key (e.g. Model)" class="w-full text-sm" required />
                                    </div>
                                    <div class="flex-1">
                                        <TextInput type="text" v-model="spec.value" placeholder="Value (e.g. Core i5)" class="w-full text-sm" required />
                                    </div>
                                    <button type="button" @click="removeSpecification(index)" class="text-red-500 hover:text-red-700 p-2">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-500 italic bg-gray-50 p-4 rounded border text-center">
                                No specifications added yet.
                            </div>
                        </div>

                        <hr class="my-6">
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-2 text-gray-900">Product Attributes & Variants</h3>
                            <p class="text-sm text-gray-500 mb-4">Select attributes to generate product variants.</p>
                            
                            <div v-if="attributes && attributes.length > 0" class="space-y-4 mb-6">
                                <div v-for="attribute in attributes" :key="attribute.id" class="border rounded-md p-4 bg-gray-50">
                                    <div class="flex items-center mb-2">
                                        <input type="checkbox" :id="'attr_'+attribute.id" :checked="selectedAttributes[attribute.id] !== undefined" @change="toggleAttribute(attribute.id)" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2">
                                        <label :for="'attr_'+attribute.id" class="font-medium text-gray-700">{{ attribute.name }}</label>
                                    </div>
                                    
                                    <div v-if="selectedAttributes[attribute.id] !== undefined" class="ml-6 mt-2 flex flex-wrap gap-2">
                                        <label v-for="val in attribute.values" :key="val.id" class="inline-flex items-center bg-white border px-3 py-1 rounded-md text-sm cursor-pointer hover:bg-gray-50" :class="{'border-indigo-500 bg-indigo-50 text-indigo-700': selectedAttributes[attribute.id].includes(val.value)}">
                                            <input type="checkbox" :checked="selectedAttributes[attribute.id].includes(val.value)" @change="toggleValue(attribute.id, val.value)" class="sr-only">
                                            {{ val.value }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-500 italic mb-4">
                                No global attributes found. You can still set price and inventory below for a standard product.
                            </div>

                            <!-- Variants Table -->
                            <div class="overflow-x-auto border rounded-md">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price ($) *</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock *</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(variant, index) in form.variants" :key="variant.variant_string">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ variant.variant_string === 'default' ? 'Default / Standard' : variant.variant_string }}
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" step="0.01" v-model="variant.price" class="block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" v-model="variant.stock" class="block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="text" v-model="variant.sku" class="block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Optional">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <InputError class="mt-2" :message="form.errors.variants" />
                        </div>

                        <hr class="my-6">
                        <h3 class="text-lg font-medium mb-4 text-gray-900">Settings</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Status -->
                            <div>
                                <InputLabel for="status" value="Status" />
                                <select id="status" v-model="form.status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="active">Active (Visible)</option>
                                    <option value="draft">Draft (Hidden)</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                            <!-- B2B Wholesale -->
                            <div class="flex items-center mt-6">
                                <input id="b2b_wholesale" type="checkbox" v-model="form.b2b_wholesale" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="b2b_wholesale" class="ms-2 text-sm font-medium text-gray-700">Enable B2B Wholesale</label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save Changes
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
