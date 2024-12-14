<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    organization_name: '',
    name: '',
    employee_number: '',
    password: '',
    password_confirmation: '',
    authority: 3,
    role:'社員',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};


</script>

<template>
    <GuestLayout>
        <Head title="Register" />
        <p class="mb-4 mt-2 text-sm text-gray-500">以下のフォームを入力してください。</p>
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="organization_name" value="組織名" />

                <TextInput
                    id="organization_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.organization_name"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.organization_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="name" value="名前" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="employee_number" value="社員番号" />

                <TextInput
                    id="employee_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employee_number"
                    required
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="パスワード" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="確認用パスワード" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    登録済みの組織の場合
                </Link>

                <PrimaryButton class="ml-4 bg-indigo-500" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    登録
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
