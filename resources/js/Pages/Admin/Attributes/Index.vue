<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    attributes: Object
});

const isModalOpen = ref(false);
const modalMode = ref('create'); // 'create' or 'edit'

const form = useForm({
    id: null,
    name: '',
    values: '', // Comma separated string
});

const openModal = (mode = 'create', attribute = null) => {
    modalMode.value = mode;
    if (mode === 'edit' && attribute) {
        form.id = attribute.id;
        form.name = attribute.name;
        form.values = attribute.values.map(v => v.value).join(', ');
    } else {
        form.reset();
        form.id = null;
    }
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const submit = () => {
    if (modalMode.value === 'create') {
        form.post(route('admin.attributes.store'), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.put(route('admin.attributes.update', form.id), {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteForm = useForm({});
const deleteAttribute = (id) => {
    if (confirm('Are you sure you want to delete this attribute? All related attribute values will also be deleted.')) {
        deleteForm.delete(route('admin.attributes.destroy', id));
    }
};
</script>

<template>
    <Head title="Manage Attributes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Attributes</h2>
                <PrimaryButton @click="openModal('create')">
                    Add New Attribute
                </PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Attribute Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Values
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="attribute in attributes.data" :key="attribute.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ attribute.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ attribute.name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <span v-for="(val, index) in attribute.values" :key="val.id" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-1 mb-1">
                                                {{ val.value }}
                                            </span>
                                            <span v-if="attribute.values.length === 0" class="text-gray-400 italic">No values defined</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="openModal('edit', attribute)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                                            <button @click="deleteAttribute(attribute.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                    <tr v-if="attributes.data.length === 0">
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                            No attributes found. Click "Add New Attribute" to create one.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4 flex items-center justify-between" v-if="attributes.links && attributes.links.length > 3">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <Link v-if="attributes.prev_page_url" :href="attributes.prev_page_url" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:text-gray-500">
                                    Previous
                                </Link>
                                <Link v-if="attributes.next_page_url" :href="attributes.next_page_url" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:text-gray-500">
                                    Next
                                </Link>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing <span class="font-medium">{{ attributes.from || 0 }}</span> to <span class="font-medium">{{ attributes.to || 0 }}</span> of <span class="font-medium">{{ attributes.total }}</span> results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <template v-for="(link, index) in attributes.links" :key="index">
                                            <Link 
                                                v-if="link.url" 
                                                :href="link.url" 
                                                v-html="link.label"
                                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                :class="[link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50']"
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
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ modalMode === 'create' ? 'Add New Attribute' : 'Edit Attribute' }}
                </h2>
                
                <form @submit.prevent="submit" class="mt-4">
                    <div class="mb-4">
                        <InputLabel for="name" value="Attribute Name *" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="e.g. Color, Size, Material"
                            required
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="values" value="Values (Comma Separated)" />
                        <TextInput
                            id="values"
                            v-model="form.values"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="e.g. Red, Blue, Green"
                        />
                        <p class="mt-1 text-xs text-gray-500">Separate multiple values with a comma (,)</p>
                        <InputError :message="form.errors.values" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal" class="mr-3">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ modalMode === 'create' ? 'Create' : 'Save Changes' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
