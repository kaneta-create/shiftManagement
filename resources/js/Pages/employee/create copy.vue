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
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">従業員登録</h2>
        </template>

        <div class="py-12">
            <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font relative">
                        <div class="container px-5 py-12 mx-auto">
                            <div class="flex flex-col text-center w-full mb-4">
                                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">従業員登録</h1>
                            </div>
                            <form @submit.prevent="storeEmployee">
                            <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            <div class="flex flex-wrap -m-2">
                                <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">名前</label>
                                    <input type="text" id="name" name="name" v-model="form.name" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <div v-if="props.errors.name" class="text-red-600">*{{ props.errors.name }}</div>
                                </div>
                                </div>
                                <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="employee_number" class="leading-7 text-sm text-gray-600">社員番号</label>
                                    <input type="number" id="employee_number" min="1" required v-model="form.employee_number" name="employee_number" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <div v-if="props.errors.employee_number" class="text-red-600">*{{ props.errors.employee_number }}</div>
                                </div>
                                </div>
                                <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="role" class="leading-7 text-sm text-gray-600">雇用形態</label>
                                    <input type="text" id="role" name="role" v-model="form.role" required placeholder="アルバイト" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <div v-if="props.errors.role" class="text-red-600">*{{ props.errors.role }}</div>
                                    
                                </div>
                                </div>
                                <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="authority" class="leading-7 text-sm text-gray-600">権限</label>
                                    <select name="authority" id="authority" v-model="form.authority" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        <option value="1">一般従業員権限</option>
                                        <option value="2">シフト作成者権限</option>
                                        <option value="3">管理者権限</option>
                                    </select>
                                    <div v-if="props.errors.authority" class="text-red-600">*{{ props.errors.authority }}</div>

                                </div>
                                </div>
                                <div class="p-2 w-full mt-4">
                                    <div class="items-center flex justify-center relative">
                                        <button dusk="generate-temporary-password" type="button" @click="generateTemporaryPassword" class="flex mr-2 mx-auto text-white py-3 px-1 bg-indigo-500 border-0 focus:outline-none hover:bg-indigo-600 rounded text-sm text-nowrap">
                                            仮パスワード発行
                                        </button>
                                        <!-- <label for="authority" class="leading-7 text-sm text-gray-600">権限</label> -->
                                        
                                        <!-- <p v-if="form.temporary_password"> -->
                                            <input 
                                                dusk="temporary-password-input"
                                                type="text" 
                                                readonly
                                                required
                                                v-model="form.temporary_password" 
                                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                            />
                                        <!-- </p> -->
                                    </div>
                                </div>
                                <!-- <div v-show="form.hasErrors" class="border border-red-100 p-1 m-1 text-sm text-red-600">
                                    入力された値をもう一度確認してください。
                                    <ul class="list-disc list-inside">
                                        <li v-for="error in form.errors" >{{error}}</li>
                                    </ul>
                                </div> -->

                                <div class="p-2 w-full">
                                    <button dusk="register-button" class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録</button>
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