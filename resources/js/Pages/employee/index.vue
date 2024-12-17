<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import MicroModal from '@/Components/MicroModal.vue';
import { Inertia } from '@inertiajs/inertia'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
    employees: Object,
    paginate:Object,
    userRole: Object,
    alertMessage: String
});

const deleteEmployee = id => {
  Inertia.delete(route('admins.destroy', { admin: id }), {
    onBefore: () => confirm('本当に削除しますか?'),
  })
  location.reload()
}

const updateAuthority = () => {
    Inertia.put(route('admins.update', {admin:props.id}), form);
}

</script>

<template>
    <Head title="従業員管理ページ" />

    <AuthenticatedLayout :userRole="props.userRole.authority">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">従業員管理ページ</h2>
        </template> -->

        <div class="py-4 bg-gray-300">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <FlashMessage/>
                        
                        <section class="text-gray-600 body-font">
                        <div class="container px-5 py-2 mx-auto">
                            <!-- <div class="flex flex-col text-center w-full mb-8">
                                <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">従業員一覧</h1>
                            </div> -->
                            <h1 class="sm:text-4xl text-center text-3xl font-mono title-font mb-4 text-gray-900">従業員一覧</h1>

                            <div class="flex justify-end my-4 lg:w-3/4 w-full mx-auto space-x-2">
                                <Link as="button" :href="route('admins.create')" class="text-white bg-indigo-600 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-700 rounded">
                                        <div class="flex items-center ">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg> 
                                            <span class="ml-1 items-center mt-1 hover:bg-indigo-700">新規登録</span>
                                        </div>
                                </Link>
                            </div>
                            <div class="lg:w-3/4 w-full mx-auto overflow-auto">
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                                <thead>
                                <tr class="rounded">
                                    <!-- <th class="px-4 py-3title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">No</th> -->
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 rounded-l">社員番号</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">名前</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">雇用形態</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">権限</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">パスワード</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">編集</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 rounded-r" style="white-space: nowrap;">削除</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(employee, index) in props.employees" :key="employee.id" :class="{ 'even:bg-gray-50 odd:bg-whit': index % 2 === 1  }">
                                    <td class="px-4 py-3 border-b-2 border-gray-200">{{ employee.employee_number }}</td>
                                    <td class="px-4 py-3 border-b-2 border-gray-200">{{ employee.name }}</td>
                                    <td class="px-4 py-3 border-b-2 border-gray-200">{{ employee.role }}</td>
                                    <td class="px-4 py-3 border-b-2 border-gray-200"><MicroModal :authority="employee.authority" :id="employee.id" :role="employee.role"/></td>
                                    <!-- <td class="px-4 py-3 border-b-2"><MicroModal :employee="employee"/></td> -->
                                    <td v-if="employee.temporary_password" class="px-4 py-3 border-b-2 border-gray-200">{{ employee.temporary_password }}</td>
                                    <td v-else class="px-4 py-3 border-b-2 border-gray-200">設定済み</td>
                                    <!-- <td class="px-4 py-3 border-b-2">
                                        <Link as="button" :href="route('defaultShifts.edit', { defaultShift: employee.id })" dusk="edit-page-button" class="text-white text-nowrap border-b-2 bg-blue-500 mt-1 border-0 py-2 px-2 focus:outline-none hover:bg-blue-600 rounded">更新</Link>
                                    </td> -->
                                    <td class="px-4 py-3 border-b-2 border-gray-200">
                                        <Link as="button" :href="route('defaultShifts.edit', { defaultShift: employee.id })" 
                                            dusk="edit-page-button" 
                                            class="text-white text-nowrap border-b-2 bg-indigo-600 mt-1 border-0 py-2 px-2 focus:outline-none hover:bg-indigo-700 rounded flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>
                                        </Link>
                                    </td>

                                    <!-- <td class="border-b-2">
                                        <button @click="deleteEmployee(employee.id)" class="text-white border-b-2 bg-red-500 mt-1 border-0 py-2 px-2 focus:outline-none hover:bg-red-600 rounded">削除</button>
                                    </td> -->
                                    <td class="border-b-2 border-gray-200">
                                        <button @click="deleteEmployee(employee.id)" 
                                                class="text-white border-b-2 bg-red-600 mt-1 border-0 py-2 px-2 focus:outline-none hover:bg-red-700 rounded flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                            <Pagination class="mt-6 flex justify-end" :links="props.paginate.links"></Pagination>
                            </div>
                            
                        </div>
                        
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
