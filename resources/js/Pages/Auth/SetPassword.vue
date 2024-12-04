<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
// defineProps({
//     status: String,
// });

const page = usePage();
// console.log(page);
const form = useForm({
    password: '',
    confirmation_password: '',
    temporary_password: '',
    employee_number: ''
});

const submit = () => {
    if (form.password != form.confirmation_password) {
        form.errors.confirmation_password = 'パスワードと確認パスワードが一致しません';
        return; // フォームの送信を中止
    }else{
        form.put(route('passwordSets.storePassword'),{
            // onError: (errors) => {
                
            // },
            onSuccess: () =>{confirm('登録が完了しました。ログインしますか？')},
            onFinish: visit => {
                const message = page.props.flash.message;
                if(message != null){
                    confirm('パスワードは設定済みです。ログイン画面からログインしてください。');
                    return;
                }
                form.reset('password', 'password_confirmation', 'temporary_password')
            },
        });
    }
    
};  
</script>

<template>
    <GuestLayout>
        <Head title="Set Password" />

        <div class="mb-4 text-sm text-gray-600">
            パスワードを設定してくだい
        </div>

        <!-- <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div> -->

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
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-2">
                <InputLabel for="temporary_password" value="仮パスワード" />

                <TextInput
                    id="temporary_password"
                    name="temporary_password"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.temporary_password"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-2">
                <InputLabel for="password" value="パスワード" />

                <TextInput
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-2">
                <InputLabel for="confirmation_password" value="確認用パスワード" />

                <TextInput
                    id="confirmation_password"
                    name="confirm_password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.confirmation_password"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.confirmation_password" />
            </div>

            <div  class="flex items-center justify-end mt-4">
                <PrimaryButton id="register-button" class="bg-indigo-500" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    登録
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
