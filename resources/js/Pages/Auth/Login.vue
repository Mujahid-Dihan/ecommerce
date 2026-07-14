<script setup>
import { ref } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const page = usePage();
const activeTab = ref('email'); // 'email' or 'otp'
const otpStep = ref('request'); // 'request' or 'verify'

const emailForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const otpRequestForm = useForm({
    phone: '',
});

const otpVerifyForm = useForm({
    phone: '',
    code: '',
});

const submitEmail = () => {
    emailForm.post(route('login'), {
        onFinish: () => emailForm.reset('password'),
    });
};

const requestOtp = () => {
    otpRequestForm.post(route('otp.request'), {
        onSuccess: () => {
            otpStep.value = 'verify';
            otpVerifyForm.phone = otpRequestForm.phone;
        },
    });
};

const verifyOtp = () => {
    otpVerifyForm.post(route('otp.verify'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status || page.props.flash?.status || page.props.flash?.error || page.props.flash?.success" class="mb-4 text-sm font-medium" :class="{'text-green-600': status || page.props.flash?.success || page.props.flash?.status, 'text-red-600': page.props.flash?.error}">
            {{ status || page.props.flash?.success || page.props.flash?.status || page.props.flash?.error }}
        </div>

        <!-- Tabs -->
        <div class="flex mb-6 border-b">
            <button 
                @click="activeTab = 'email'" 
                class="flex-1 py-2 text-center text-sm font-semibold border-b-2 transition-colors"
                :class="activeTab === 'email' ? 'border-ryans text-ryans' : 'border-transparent text-gray-500 hover:text-gray-700'"
            >
                Email Login
            </button>
            <button 
                @click="activeTab = 'otp'" 
                class="flex-1 py-2 text-center text-sm font-semibold border-b-2 transition-colors"
                :class="activeTab === 'otp' ? 'border-ryans text-ryans' : 'border-transparent text-gray-500 hover:text-gray-700'"
            >
                Phone (OTP)
            </button>
        </div>

        <!-- Email Login Form -->
        <form v-if="activeTab === 'email'" @submit.prevent="submitEmail">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="emailForm.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="emailForm.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="emailForm.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="emailForm.errors.password" />
            </div>

            <div class="mt-4 block flex justify-between items-center">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="emailForm.remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-ryans hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>
            </div>

            <div class="mt-6">
                <PrimaryButton class="w-full justify-center py-3 bg-ryans hover:bg-orange-600" :class="{ 'opacity-25': emailForm.processing }" :disabled="emailForm.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>

        <!-- OTP Login Form -->
        <div v-if="activeTab === 'otp'">
            
            <!-- Step 1: Request OTP -->
            <form v-if="otpStep === 'request'" @submit.prevent="requestOtp">
                <div>
                    <InputLabel for="phone" value="Phone Number" />
                    <TextInput
                        id="phone"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="otpRequestForm.phone"
                        placeholder="e.g. 017xxxxxxxx"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="otpRequestForm.errors.phone" />
                </div>
                <div class="mt-6">
                    <PrimaryButton class="w-full justify-center py-3 bg-ryans hover:bg-orange-600" :class="{ 'opacity-25': otpRequestForm.processing }" :disabled="otpRequestForm.processing">
                        Send OTP
                    </PrimaryButton>
                </div>
            </form>

            <!-- Step 2: Verify OTP -->
            <form v-if="otpStep === 'verify'" @submit.prevent="verifyOtp">
                <div class="mb-4 text-sm text-gray-600">
                    We've sent a 4-digit code to {{ otpVerifyForm.phone }}.
                </div>
                <div>
                    <InputLabel for="code" value="Verification Code" />
                    <TextInput
                        id="code"
                        type="text"
                        class="mt-1 block w-full tracking-widest text-center text-lg"
                        v-model="otpVerifyForm.code"
                        placeholder="----"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="otpVerifyForm.errors.code" />
                </div>
                <div class="mt-6 flex space-x-3">
                    <button type="button" @click="otpStep = 'request'" class="w-1/3 py-2 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">
                        Back
                    </button>
                    <PrimaryButton class="w-2/3 justify-center py-3 bg-ryans hover:bg-orange-600" :class="{ 'opacity-25': otpVerifyForm.processing }" :disabled="otpVerifyForm.processing">
                        Verify & Login
                    </PrimaryButton>
                </div>
            </form>

        </div>

        <!-- Social Login Separator -->
        <div class="mt-8 relative flex items-center justify-center">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative bg-white px-4 text-sm text-gray-500 font-semibold">
                OR CONTINUE WITH
            </div>
        </div>

        <!-- Social Login Buttons -->
        <div class="mt-6 grid grid-cols-2 gap-4">
            <a :href="route('social.redirect', { provider: 'google' })" class="flex items-center justify-center w-full px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fab fa-google text-red-500 mr-2"></i> Google
            </a>
            <a :href="route('social.redirect', { provider: 'facebook' })" class="flex items-center justify-center w-full px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fab fa-facebook text-blue-600 mr-2"></i> Facebook
            </a>
        </div>

        <div class="mt-6 text-center text-sm">
            Don't have an account? 
            <Link :href="route('register')" class="text-ryans font-semibold hover:underline">
                Sign up
            </Link>
        </div>
    </GuestLayout>
</template>
