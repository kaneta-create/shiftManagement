<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    employee_number: '',
    password: '',
    remember: false,
});

const submit = () => {
    // if (form.password !== form.temporary_password) {
    //     form.errors.confirmation_password = 'パスワードと確認パスワードが一致しません';
    //     return; // フォームの送信を中止
    // }
    form.post(route('login')
)};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="employee_number" value="社員番号" />

                <TextInput
                    id="employee_number"
                    name="employee_number"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.employee_number"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.employee_number" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="パスワード" />

                <TextInput
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">ログインを保持する</span>
                </label>
            </div>

            <div class="flex items-center justify-around mt-4">
                <Link 
                    :href="route('passwordSets.index')" dusk="initial-login-link"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    初回ログイン
                </Link>
                <Link
                    v-if="canResetPassword"
                    
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    パスワードをお忘れですか?
                </Link>

                <PrimaryButton id="register-button" class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    ログイン
                </PrimaryButton>

                <!-- <button
                :disabled="false"
                 dusk="register-button" id="register-button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ログイン
                </button> -->
            </div>
        </form>
        
    </GuestLayout>
</template>
