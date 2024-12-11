<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive, ref, watch } from 'vue';
import { defineExpose } from 'vue';

const props = defineProps({
    shiftInfos: Array,
    month: Array,
    earliestClockIn: String,
    latestClockOut: String,
    totalHour: Array,
    userRole: Object
});
// console.log(props.userRole.authority)
// 今日の日付を 'Y-m-d' フォーマットで取得
const today = ref(getFormattedDate(new Date()));
console.log(props.shiftInfos)
// 日付フォーマット関数 (Y-m-d形式)
function getFormattedDate(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0'); 
  const day = String(date.getDate()).padStart(2, '0'); 
  return `${year}-${month}-${day}`;
}

const form = reactive({
    date: ''
});
form.date = getFormattedDate(new Date());
const todayDate = ref(getFormattedDate(new Date()));
const maxDate = ref(getFormattedDate(getEndOfNovember()));
// 11月末の日付を取得
function getEndOfNovember() {
  const now = new Date();
  const currentMonth = now.getMonth();
  const novemberEnd = new Date(now.getFullYear(), now.getMonth() + 3, 0); // 11月末(11月30日)を指定
  return novemberEnd;
}

const matchedShifts = ref([]);
const changeDate = () => {
    matchedShifts.value = [];
    props.shiftInfos.forEach(shiftInfo => {
        if (shiftInfo.date == form.date) {
            matchedShifts.value.push(shiftInfo);
        }
    });
};
watch(() => form.date, () => {
    changeDate();getFormattedDate
}, { immediate: true });

function getShiftClass(hour, shift) {
  const clockIn = parseInt(shift.clockIn.substring(0, 2));
  let clockOut = parseInt(shift.clockOut.substring(0, 2));

  if(clockOut == 0) {
    clockOut = 24;
  }

  const clockInMinutes = parseInt(shift.clockIn.substring(3, 5));
  const clockOutMinutes = parseInt(shift.clockOut.substring(3, 5));

  // 開始時間が該当する時間帯かチェック
  if (hour.hour === clockIn) {
  if (clockInMinutes === 15) {
    return 'background: linear-gradient(90deg, #d1d5db 0%, #d1d5db 75%, hsla(208, 75%, 57%, 0.627) 75%, hsla(208, 75%, 57%, 0.627) 100%)';
  } else if (clockInMinutes === 30) {
    return 'background: linear-gradient(90deg, #d1d5db 0%, #d1d5db 50%, hsla(208, 75%, 57%, 0.627) 50%, hsla(208, 75%, 57%, 0.627) 100%)';
  } else if (clockInMinutes === 45) {
    return 'background: linear-gradient(90deg, #d1d5db 0%, #d1d5db 25%, hsla(208, 75%, 57%, 0.627) 25%, hsla(208, 75%, 57%, 0.627) 100%)';
  } else {
    return 'background-color: hsla(208, 75%, 57%, 0.627);';
  }
}

if (hour.hour === clockOut) {
  if (clockOutMinutes === 15) {
    return 'background: linear-gradient(90deg, hsla(208, 75%, 57%, 0.627) 0%, hsla(208, 75%, 57%, 0.627) 15%, #d1d5db 15%, #d1d5db 100%)';
  } else if (clockOutMinutes === 30) {
    return 'background: linear-gradient(90deg, hsla(208, 75%, 57%, 0.627) 0%, hsla(208, 75%, 57%, 0.627) 30%, #d1d5db 30%, #d1d5db 100%)';
  } else if (clockOutMinutes === 45) {
    return 'background: linear-gradient(90deg, hsla(208, 75%, 57%, 0.627) 0%, hsla(208, 75%, 57%, 0.627) 45%, #d1d5db 45%, #d1d5db 100%)';
  } else {
    return 'background-color: hsla(208, 75%, 57%, 0.627);';
  }
}

  // それ以外の時間帯の全体色
  if (hour.hour > clockIn && hour.hour < clockOut) {
    return 'background-color:hsla(208, 75%, 57%, 0.627);';
  }

  return ''
}
const printPage = () => {
    window.print();
};
defineExpose({
  getFormattedDate
});
</script>

<template>
    <Head title="DayShift" />

    <AuthenticatedLayout :userRole="props.userRole.authority">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">日別シフト</h2>
        </template> -->

        <div class="py-4 bg-gray-300">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-center mb-4">
                            <h1 class="sm:text-4xl text-3xl font-mono title-font mb-4 text-gray-900">シフト表</h1>
                            <p id="explanation"class="lg:w-2/3 mx-auto leading-relaxed text-base text-gray-600">日付を選択してください。</p>
                            <div class="mt-6">
                              <label for="date" class="mr-2">日付 : </label>
                              <input type="date" id="date" name="date" v-model="form.date" :min="todayDate" :max="maxDate">
                            </div>

                        </div>
                        <div class="overflow-x-auto">
                          <table  class="table-auto w-full text-left whitespace-no-wrap">
                            <thead>
                            <tr>
                                <th id="th1" class="py-2 px-2 border-2 title-font tracking-wider font-medium text-white text-sm bg-gray-500 rounded-tl rounded-bl text-nowrap text-center">名前</th>
                                <th id="th2" class="py-2 px-2 border-2 border-2text-center border-x-1 title-font tracking-wider font-medium text-white text-sm bg-gray-500 text-center">
                                    勤務時間
                                </th>
                                <th id="th3" v-for="(hour ,index) in props.totalHour" class="py-2 px-2 border-2 border-x-1 title-font tracking-wider font-medium text-white text-sm bg-gray-500 text-center">
                                  <div class="flex justify-center">
                                    <span>{{ hour.hour }}</span><span>時</span>
                                  </div>  
                                </th>
                                
                            </tr>
                            
                            </thead>
                            <tbody>
                            
                            <tr v-for="(shift, index) in matchedShifts" :key="index" class="border-2 hover:bg-gray-400 transition duration-150 ease-in-out">
                                <td id="td1" class="border-t-2 border-gray-200 p-left-4 py-2 bg-gray-500 text-white text-center text-sm text-nowrap">
                                    {{ shift.employeeName }}
                                </td>
                                <td id="td2" class="border-t-2 border-gray-200 p-left-4 py-2 text-center text-sm bg-gray-100">
                                    {{ shift.clockInHour }}:{{ shift.clockInMinutes }}~{{  shift.clockOutHour === '00' ? '24' : shift.clockOutHour  }}:{{ shift.clockOutMinutes }}
                                </td>
                                <td id="td3" v-for="(hour ,index) in props.totalHour" :style="getShiftClass(hour, shift)" class="border-2 bg-gray-300 border-gray-200 px-4 py-2">
                                </td>
                            </tr>
                           
                            </tbody>
                        </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
