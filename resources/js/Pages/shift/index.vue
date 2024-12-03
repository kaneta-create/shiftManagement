<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    shifts: Array,
    month: Array,
    names : Array,
    shiftRequests : Array
})
// const shiftInfo = ref(props.shifts);
// console.log(shiftInfo.value);
const workDay = (employee_name, name, day_of_week, work_day_of_week) => {
    if(name == employee_name && day_of_week == work_day_of_week){
        return true
    }
}
console.log(props.shiftRequests)
let updatedClockIn = '';
let updatedClockOut = '';
const getUpdateTime = (shift, index, timeType) => {
    

    props.shiftRequests.forEach(requestedShift => {
        const employeeIdUpdate  = requestedShift.employee_id;
        const targetDate = requestedShift.requested_date;

        if(index +1 == requestedShift.requested_date){
            updatedClockIn = requestedShift.new_clock_in;
            updatedClockOut = requestedShift.new_clock_out;
            return true;
        } else{
            return false;
        }
    })
    // return false;
}


const totalWorkingTime = computed(() => {
      let total = 0;
      props.shifts.forEach(shift => {
        total += shift.dayWorkingTime;
      });
      return total;
    });

</script>

<template>
    <Head title="シフト表" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">シフト表</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-col text-centerw-full mb-8">
                            <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">シフト表</h1>
                            <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-300">
                                            <div>{{ props.month[0].month }}</div>
                                            <div class="border-t border-gray-300 mt-1"></div>
                                            <div>名前</div>
                                        </th>
                                        <!-- 他のヘッダ要素 -->
                                        <th v-for="(day, index) in props.month" :key="index" class="border border-gray-300 text-center">
                                            <div>{{ day.date }}</div>
                                            <div class="border-t border-gray-300 mt-1"></div>
                                            <div>{{ day.day_of_week }}</div>
                                        </th>
                                        <th class="border text-xs border-gray-300">
                                            <div class="">時間</div>
                                            <div class="border-t border-gray-300 mt-1"></div>
                                            <div class="vertical-text">日数</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="name in props.names">
                                        <td class="border border-gray-300 text-sm px-2 py-2">{{ name.employee_name }}</td>
                                        <td v-for="(day, index) in props.month" class="border border-gray-30 py-2">
                                            <div v-for="(shift, shiftIndex) in props.shifts">
                                                 <div v-if="workDay(index, shift.employee_name, name.employee_name, day.day_of_week, shift.day_of_week)" class="text-center text-xs">
                                                   <div v-if="getUpdateTime(shift, index)">
                                                        <div>{{ updatedClockIn }}</div>
                                                        <div class="border-t">{{ updatedClockOut }}</div>
                                                   </div>
                                                    <div>
                                                        <div>{{ shift.clock_in }}</div>
                                                        <div class="border-t">{{ shift.clock_out }}</div>
                                                    </div>                                                        
                                            </div>
                                            </div>
                                        </td>
                                        <td class="border border-gray-300 text-center px-2 py-2">
                                            <div class="text-xs">{{ totalWorkingTime }}</div>
                                            <div class="border-t text-xs">14</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>