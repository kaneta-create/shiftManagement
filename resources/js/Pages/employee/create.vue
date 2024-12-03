<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { createInertiaApp, Head, useForm, router } from '@inertiajs/vue3';
import { onMounted, reactive, ref} from 'vue';
import { Inertia } from '@inertiajs/inertia'
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    errors: Object,
    userRole: Object
})

const form = useForm({
    'name':null,
    'employee_number':null,
    // 'employee_number':null,
    'role':null,
    'authority':null,
    'temporary_password': null
});

const storeEmployee = () => {
    router.post('/admins', form);
};

// const temporaryPassword = ref(null);
const generateTemporaryPassword = () => {
    form.temporary_password =  Math.floor(10000 + Math.random() * 90000);
}
console.log(props.errors.name);
</script>

<template>
    <Head title="登録ページ" />

    <AuthenticatedLayout :userRole="props.userRole.authority">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">従業員登録</h2>
        </template> -->

        <div class="py-4 bg-gray-300">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
            <div class="p-8 text-gray-900">
                <section class="text-gray-700 body-font relative">
                    <div class="container px-5 py-10 mx-auto">
                        <div class="flex flex-col text-center w-full mb-6">
                            <h1 class="sm:text-4xl text-3xl font-mono text-center title-font mb-4 text-gray-900">従業員登録</h1>
                            <p class="lg:w-2/3 mx-auto leading-relaxed text-base text-gray-600">新しい従業員の情報を登録してください。</p>
                        </div>
                        <form @submit.prevent="storeEmployee">
                            <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                <div class="flex flex-wrap -m-4">
                                    <!-- 名前 -->
                                    <div class="p-4 w-full">
                                        <div class="relative">
                                            <label for="name" class="leading-7 text-sm text-gray-600">名前</label>
                                            <input 
                                                type="text" 
                                                id="name" 
                                                name="name" 
                                                v-model="form.name" 
                                                required 
                                                placeholder="例：山田 太郎"
                                                class="w-full bg-gray-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-2 px-4 leading-8 transition-colors duration-200 ease-in-out"
                                            >
                                            <div v-if="props.errors.name" class="text-red-500 text-sm mt-1">*{{ props.errors.name }}</div>
                                        </div>
                                    </div>
                                    <!-- 社員番号 -->
                                    <div class="p-4 w-full">
                                        <div class="relative">
                                            <label for="employee_number" class="leading-7 text-sm text-gray-600">社員番号</label>
                                            <input 
                                                type="number" 
                                                id="employee_number" 
                                                name="employee_number" 
                                                v-model="form.employee_number" 
                                                min="1" 
                                                required 
                                                placeholder="例：12345"
                                                class="w-full bg-gray-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-2 px-4 leading-8 transition-colors duration-200 ease-in-out"
                                            >
                                            <div v-if="props.errors.employee_number" class="text-red-500 text-sm mt-1">*{{ props.errors.employee_number }}</div>
                                        </div>
                                    </div>
                                    <!-- 雇用形態 -->
                                    <div class="p-4 w-full">
                                        <div class="relative">
                                            <label for="role" class="leading-7 text-sm text-gray-600">雇用形態</label>
                                            <input 
                                                type="text" 
                                                id="role" 
                                                name="role" 
                                                v-model="form.role" 
                                                required 
                                                placeholder="例：アルバイト"
                                                class="w-full bg-gray-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-2 px-4 leading-8 transition-colors duration-200 ease-in-out"
                                            >
                                            <div v-if="props.errors.role" class="text-red-500 text-sm mt-1">*{{ props.errors.role }}</div>
                                        </div>
                                    </div>
                                    <!-- 権限 -->
                                    <div class="p-4 w-full">
                                        <div class="relative">
                                            <label for="authority" class="leading-7 text-sm text-gray-600">権限</label>
                                            <select 
                                                id="authority" 
                                                name="authority" 
                                                v-model="form.authority" 
                                                required 
                                                class="w-full bg-gray-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-2 px-4 leading-8 transition-colors duration-200 ease-in-out"
                                            >
                                                <option value="1">一般従業員権限</option>
                                                <option value="2">シフト作成者権限</option>
                                                <option value="3">管理者権限</option>
                                            </select>
                                            <div v-if="props.errors.authority" class="text-red-500 text-sm mt-1">*{{ props.errors.authority }}</div>
                                        </div>
                                    </div>
                                    <!-- 仮パスワード発行 -->
                                    <div class="p-4 w-full mt-4">
                                        <div class="flex justify-between items-center">
                                            <button 
                                                dusk="generate-temporary-password" 
                                                type="button" 
                                                @click="generateTemporaryPassword" 
                                                class="text-white py-4 px-2 mr-2 bg-indigo-500 border-0 focus:outline-none hover:bg-indigo-600 rounded text-sm text-nowrap"
                                            >
                                                仮パスワード発行
                                            </button>
                                            <input 
                                                dusk="temporary-password-input" 
                                                type="text" 
                                                readonly 
                                                v-model="form.temporary_password" 
                                                placeholder="仮パスワードがここに表示されます" 
                                                class="w-full bg-gray-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-2 px-4 leading-8 transition-colors duration-200 ease-in-out"
                                            >
                                        </div>
                                    </div>
                                    <!-- 登録ボタン -->
                                    <div class="p-4 w-full">
                                        <button 
                                            dusk="register-button" 
                                            type="submit" 
                                            class="flex mx-auto text-white bg-indigo-500 border-0 py-3 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                                        >
                                            登録
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

    </AuthenticatedLayout>
</template>