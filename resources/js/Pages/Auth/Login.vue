<script setup>
import { ref } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
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

// 'login' or 'register'
const authMode = ref('login'); 

// For login section: 'email' or 'otp'
const activeTab = ref('email'); 
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

const registerForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
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

const submitRegister = () => {
    registerForm.post(route('register'), {
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Auth | Electronics Dokan" />

    <div class="bg-white font-sans h-screen flex flex-col overflow-hidden">
        <!-- Massive Split Screen Layout matching Premium E-commerce Aesthetics -->
        <div class="flex flex-col lg:flex-row flex-grow h-full">
            
            <!-- Left Side: Massive Banner -->
            <div class="hidden lg:flex lg:w-1/2 bg-[#002b5c] relative overflow-hidden items-center justify-center p-8">
                <!-- Premium tech/laptop image -->
                <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=1500&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay" alt="Login Banner">
                
                <div class="relative z-10 text-white max-w-lg">
                    <!-- Back to Home Link -->
                    <Link :href="route('home')" class="inline-flex items-center text-[13px] font-bold text-gray-300 hover:text-white mb-6 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Store
                    </Link>

                    <div class="mb-6 border-l-4 border-ryans pl-5">
                        <h1 class="text-4xl font-black mb-3 leading-tight">Welcome to <br><span class="text-ryans tracking-wider uppercase text-3xl">Electronics Dokan</span></h1>
                        <p class="text-[15px] text-gray-200 font-medium drop-shadow-md">Log in to manage your orders, track shipments, and discover personalized deals just for you.</p>
                    </div>
                    
                    <div class="space-y-4 mt-8">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mr-3 shadow-sm border border-white/30">
                                <i class="fas fa-truck-fast text-white text-[15px]"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-[14px] drop-shadow-sm">Fast Nationwide Delivery</h4>
                                <p class="text-gray-300 text-[12px] drop-shadow-sm">Get your products delivered to your doorstep.</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mr-3 shadow-sm border border-white/30">
                                <i class="fas fa-shield-halved text-white text-[15px]"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-[14px] drop-shadow-sm">100% Authentic Products</h4>
                                <p class="text-gray-300 text-[12px] drop-shadow-sm">Official brand warranty on all electronics.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form Area (Now takes full right half perfectly) -->
            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center bg-white p-6 lg:p-12 overflow-y-auto h-full shadow-[-10px_0_30px_rgba(0,0,0,0.05)] relative z-20">
                
                <div class="max-w-[440px] w-full my-auto">
                    
                    <!-- Mobile only Back to Home Link -->
                    <div class="lg:hidden mb-6 text-center">
                        <Link :href="route('home')" class="inline-flex items-center text-[12px] font-bold text-gray-500 hover:text-ryans transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Store
                        </Link>
                    </div>

                    <!-- Top Level Auth Mode Toggle -->
                    <div class="flex mb-8 border-b border-gray-200">
                        <button 
                            @click="authMode = 'login'" 
                            class="flex-1 pb-3 text-center text-[16px] font-black uppercase tracking-wider transition-all"
                            :class="authMode === 'login' ? 'border-b-2 border-ryans text-ryans' : 'text-gray-400 hover:text-gray-800'"
                        >
                            Sign In
                        </button>
                        <button 
                            @click="authMode = 'register'" 
                            class="flex-1 pb-3 text-center text-[16px] font-black uppercase tracking-wider transition-all"
                            :class="authMode === 'register' ? 'border-b-2 border-ryans text-ryans' : 'text-gray-400 hover:text-gray-800'"
                        >
                            Create Account
                        </button>
                    </div>

                    <div v-if="status || page.props.flash?.status || page.props.flash?.error || page.props.flash?.success" class="mb-5 text-[12px] font-bold p-3 rounded" :class="{'bg-green-50 text-green-700 border border-green-200': status || page.props.flash?.success || page.props.flash?.status, 'bg-red-50 text-red-700 border border-red-200': page.props.flash?.error}">
                        {{ status || page.props.flash?.success || page.props.flash?.status || page.props.flash?.error }}
                    </div>

                    <!-- ============================== -->
                    <!-- LOGIN SECTION                  -->
                    <!-- ============================== -->
                    <div v-show="authMode === 'login'">
                        <!-- Modern Tabs for Login Type -->
                        <div class="flex mb-6 bg-gray-50 p-1 rounded">
                            <button 
                                @click="activeTab = 'email'" 
                                class="flex-1 py-2.5 text-center text-[13px] font-bold rounded transition-all"
                                :class="activeTab === 'email' ? 'bg-white text-ryans shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-800'"
                            >
                                <i class="far fa-envelope mr-1.5"></i> Email
                            </button>
                            <button 
                                @click="activeTab = 'otp'" 
                                class="flex-1 py-2.5 text-center text-[13px] font-bold rounded transition-all"
                                :class="activeTab === 'otp' ? 'bg-white text-ryans shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-800'"
                            >
                                <i class="fas fa-mobile-screen mr-1.5"></i> Phone
                            </button>
                        </div>

                        <!-- Email Login Form -->
                        <form v-if="activeTab === 'email'" @submit.prevent="submitEmail" class="space-y-4">
                            <div>
                                <InputLabel for="email" value="Email Address" class="text-gray-700 font-bold text-[13px] mb-1.5" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="block w-full border-gray-300 focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white rounded py-2.5 text-[14px] transition-colors"
                                    v-model="emailForm.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="your.email@example.com"
                                />
                                <InputError class="mt-1.5 text-[12px]" :message="emailForm.errors.email" />
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-1.5">
                                    <InputLabel for="password" value="Password" class="text-gray-700 font-bold text-[13px]" />
                                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-[12px] text-ryans hover:underline font-bold transition">
                                        Forgot Password?
                                    </Link>
                                </div>
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="block w-full border-gray-300 focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white rounded py-2.5 text-[14px] transition-colors"
                                    v-model="emailForm.password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                />
                                <InputError class="mt-1.5 text-[12px]" :message="emailForm.errors.password" />
                            </div>

                            <div class="flex items-center pt-2">
                                <label class="flex items-center cursor-pointer">
                                    <Checkbox name="remember" v-model:checked="emailForm.remember" class="text-ryans focus:ring-ryans rounded border-gray-300 w-4 h-4" />
                                    <span class="ms-2.5 text-[13px] text-gray-600 font-medium">Keep me signed in</span>
                                </label>
                            </div>

                            <div class="pt-3">
                                <button type="submit" class="w-full bg-ryans hover:bg-[#5da733] text-white text-[15px] font-bold py-3.5 rounded transition shadow-[0_4px_14px_0_rgba(240,84,35,0.39)] flex items-center justify-center disabled:opacity-50 hover:-translate-y-0.5" :disabled="emailForm.processing">
                                    <span v-if="emailForm.processing"><i class="fas fa-spinner fa-spin mr-2"></i> Signing in...</span>
                                    <span v-else>Sign In</span>
                                </button>
                            </div>
                        </form>

                        <!-- OTP Login Form -->
                        <div v-if="activeTab === 'otp'">
                            <form v-if="otpStep === 'request'" @submit.prevent="requestOtp" class="space-y-4">
                                <div>
                                    <InputLabel for="phone" value="Mobile Number" class="text-gray-700 font-bold text-[13px] mb-1.5" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none border-r border-gray-200 pr-2.5 my-1.5">
                                            <span class="text-gray-600 text-[14px] font-bold">+880</span>
                                        </div>
                                        <TextInput
                                            id="phone"
                                            type="text"
                                            class="block w-full pl-16 border-gray-300 focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white rounded py-2.5 text-[14px] transition-colors"
                                            v-model="otpRequestForm.phone"
                                            placeholder="17xxxxxxxx"
                                            required
                                            autofocus
                                        />
                                    </div>
                                    <InputError class="mt-1.5 text-[12px]" :message="otpRequestForm.errors.phone" />
                                </div>
                                <div class="pt-3">
                                    <button type="submit" class="w-full bg-ryans hover:bg-[#5da733] text-white text-[15px] font-bold py-3.5 rounded transition shadow-[0_4px_14px_0_rgba(240,84,35,0.39)] flex items-center justify-center disabled:opacity-50 hover:-translate-y-0.5" :disabled="otpRequestForm.processing">
                                        <span v-if="otpRequestForm.processing"><i class="fas fa-spinner fa-spin mr-2"></i> Sending OTP...</span>
                                        <span v-else>Send OTP</span>
                                    </button>
                                </div>
                            </form>

                            <form v-if="otpStep === 'verify'" @submit.prevent="verifyOtp" class="space-y-4">
                                <div class="p-4 bg-[#f0f2f5] border border-gray-200 rounded text-[13px] text-gray-700 font-medium text-center">
                                    A 4-digit code was sent to <br><strong class="text-[#111] text-[14px] mt-1 block">{{ otpVerifyForm.phone }}</strong>
                                </div>
                                <div>
                                    <InputLabel for="code" value="Enter 4-Digit Code" class="text-center w-full text-gray-700 font-bold text-[13px] mb-2" />
                                    <TextInput
                                        id="code"
                                        type="text"
                                        class="block w-full tracking-[1em] text-center text-2xl font-black border-gray-300 focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white rounded py-3 transition-colors"
                                        v-model="otpVerifyForm.code"
                                        placeholder="••••"
                                        required
                                        autofocus
                                        maxlength="4"
                                    />
                                    <InputError class="mt-1.5 text-[12px] text-center" :message="otpVerifyForm.errors.code" />
                                </div>
                                <div class="pt-3 flex space-x-3">
                                    <button type="button" @click="otpStep = 'request'" class="w-1/3 py-3 text-[14px] font-bold text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-50 transition">
                                        Back
                                    </button>
                                    <button type="submit" class="w-2/3 bg-ryans hover:bg-[#5da733] text-white text-[15px] font-bold py-3 rounded transition shadow-[0_4px_14px_0_rgba(240,84,35,0.39)] flex items-center justify-center disabled:opacity-50 hover:-translate-y-0.5" :disabled="otpVerifyForm.processing">
                                        <span v-if="otpVerifyForm.processing"><i class="fas fa-spinner fa-spin mr-2"></i> Verifying...</span>
                                        <span v-else>Verify</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ============================== -->
                    <!-- REGISTER SECTION               -->
                    <!-- ============================== -->
                    <div v-show="authMode === 'register'">
                        <form @submit.prevent="submitRegister" class="space-y-3">
                            <div>
                                <InputLabel for="reg_name" value="Full Name" class="text-gray-700 font-bold text-[13px] mb-1" />
                                <TextInput
                                    id="reg_name"
                                    type="text"
                                    class="block w-full border-gray-300 focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white rounded py-2.5 text-[14px] transition-colors"
                                    v-model="registerForm.name"
                                    required
                                    placeholder="John Doe"
                                />
                                <InputError class="mt-1 text-[12px]" :message="registerForm.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="reg_email" value="Email Address" class="text-gray-700 font-bold text-[13px] mb-1" />
                                <TextInput
                                    id="reg_email"
                                    type="email"
                                    class="block w-full border-gray-300 focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white rounded py-2.5 text-[14px] transition-colors"
                                    v-model="registerForm.email"
                                    required
                                    placeholder="your.email@example.com"
                                />
                                <InputError class="mt-1 text-[12px]" :message="registerForm.errors.email" />
                            </div>

                            <div>
                                <InputLabel for="reg_password" value="Password" class="text-gray-700 font-bold text-[13px] mb-1" />
                                <TextInput
                                    id="reg_password"
                                    type="password"
                                    class="block w-full border-gray-300 focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white rounded py-2.5 text-[14px] transition-colors"
                                    v-model="registerForm.password"
                                    required
                                    placeholder="••••••••"
                                />
                                <InputError class="mt-1 text-[12px]" :message="registerForm.errors.password" />
                            </div>

                            <div>
                                <InputLabel for="password_confirmation" value="Confirm Password" class="text-gray-700 font-bold text-[13px] mb-1" />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    class="block w-full border-gray-300 focus:border-ryans focus:ring-ryans bg-gray-50 focus:bg-white rounded py-2.5 text-[14px] transition-colors"
                                    v-model="registerForm.password_confirmation"
                                    required
                                    placeholder="••••••••"
                                />
                                <InputError class="mt-1 text-[12px]" :message="registerForm.errors.password_confirmation" />
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full bg-[#111] hover:bg-gray-800 text-white text-[15px] font-bold py-3.5 rounded transition flex items-center justify-center disabled:opacity-50 hover:-translate-y-0.5" :disabled="registerForm.processing">
                                    <span v-if="registerForm.processing"><i class="fas fa-spinner fa-spin mr-2"></i> Creating account...</span>
                                    <span v-else>Create Account</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Common Footer Links -->
                    <div v-show="authMode === 'login'">
                        <!-- Social Login Separator -->
                        <div class="mt-8 relative flex items-center justify-center">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative bg-white px-4 text-[11px] text-gray-400 font-bold tracking-[0.1em] uppercase">
                                Or Sign In With
                            </div>
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <a :href="route('social.redirect', { provider: 'google' })" class="flex items-center justify-center w-full px-4 py-2.5 border border-gray-200 shadow-sm rounded text-[13px] font-bold text-[#111] bg-white hover:bg-gray-50 transition group">
                                <i class="fab fa-google text-red-500 text-[15px] mr-2.5 group-hover:scale-110 transition"></i> Google
                            </a>
                            <a :href="route('social.redirect', { provider: 'facebook' })" class="flex items-center justify-center w-full px-4 py-2.5 border border-gray-200 shadow-sm rounded text-[13px] font-bold text-[#111] bg-white hover:bg-gray-50 transition group">
                                <i class="fab fa-facebook text-[#1877F2] text-[15px] mr-2.5 group-hover:scale-110 transition"></i> Facebook
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</template>
