<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive, ref, watch } from 'vue';

const props = defineProps({
    shiftInfos: Array,
    month: Array,
    earliestClockIn: String,
    latestClockOut: String,
    totalHour: Array
});
// 今日の日付を 'Y-m-d' フォーマットで取得
const today = ref(getFormattedDate(new Date()));
console.log(props.shiftInfos)
// 日付フォーマット関数 (Y-m-d形式)
function getFormattedDate(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1
  const day = String(date.getDate()).padStart(2, '0'); // 日を2桁にする
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
    changeDate();
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
      return 'background: linear-gradient(90deg, transparent 0%, transparent 25%, hsla(200, 100%, 85%, .5) 25%, hsla(200, 100%, 85%, .5) 100%)';
    } else if (clockInMinutes === 30) {
      return 'background: linear-gradient(90deg, transparent 0%, transparent 50%, hsla(200, 100%, 85%, .5) 50%, hsla(200, 100%, 85%, .5) 100%)';
    } else if (clockInMinutes === 45) {
      return 'background: linear-gradient(90deg, transparent 0%, transparent 75%, hsla(200, 100%, 85%, .5) 75%, hsla(200, 100%, 85%, .5) 100%)';
    } else {
      return 'background-color: hsla(200, 100%, 85%, .5);';
    }
  }

  // 終了時間が該当する時間帯かチェック
  if (hour.hour === clockOut) {
    if (clockOutMinutes === 15) {
      return 'background: linear-gradient(90deg, hsla(200, 100%, 85%, .5) 0%, hsla(200, 100%, 85%, .5) 25%, transparent 25%, transparent 100%)';
    } else if (clockOutMinutes === 30) {
      return 'background: linear-gradient(90deg, hsla(200, 100%, 85%, .5) 0%, hsla(200, 100%, 85%, .5) 50%, transparent 50%, transparent 100%)';
    } else if (clockOutMinutes === 45) {
      return 'background: linear-gradient(90deg, hsla(200, 100%, 85%, .5) 0%, hsla(200, 100%, 85%, .5) 75%, transparent 75%, transparent 100%)';
    } else {
      return 'background-color: hsla(200, 100%, 85%, .5);';
    }
  }

  // それ以外の時間帯の全体色
  if (hour.hour > clockIn && hour.hour < clockOut) {
    return 'background-color: hsla(200, 100%, 85%, .5);';
  }

  return '';
}
const printPage = () => {
    window.print();
};
</script>

<template>
    <Head title="DayShift" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">日別シフト</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-center mb-4">
                            <label for="date" class="mr-2">日付:</label>
                            <input type="date" id="date" name="date" v-model="form.date"       :min="todayDate" 
                            :max="maxDate">
                            <button type="button" id="printBtn" @click="printPage" class="bg-blue-600 hover:bg-indigo-700 text-white py-2 px-4 rounded flex">
                                印刷
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                          <table class="table-fixed w-full text-left whitespace-no-wrap">
                            <thead>
                            <tr>
                                <th class="px-4 py-3 border-2 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl text-nowrap">従業員名</th>
                                <th v-for="(hour ,index) in props.totalHour" class="px-4 border-2 py-3 text-center border-x-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                    {{ hour.hour }}
                                </th>
                                <th class="px-4 border-2 py-3 text-center border-x-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                    勤務時間
                                </th>
                            </tr>
                            
                            </thead>
                            <tbody>
                            
                            <tr v-for="(shift, index) in matchedShifts" :key="index" class="border-2">
                                <td class="border-t-2 border-gray-200 p-left-4 py-3 bg-gray-100 text-center  text-nowrap">
                                    {{ shift.employeeName }}
                                </td>
                                <td  v-for="(hour ,index) in props.totalHour" :style="getShiftClass(hour, shift)" class="border-2  border-gray-200 px-4 py-3">
                                </td>
                                <td class="border-t-2 border-gray-200 p-left-4 py-3 text-center">
                                    {{ shift.clockInHour }}:{{ shift.clockInMinutes }}~{{ shift.clockOutHour }}:{{ shift.clockOutMinutes }}
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
<style>
@media print {
  body {
    background-color: white;
    color: black;
  }

  nav, #printBtn, #explanation, h2 {
    display: none;
  }

  #date {
    border: none;
    font-weight: bold;
  }

  table {
    border-collapse: collapse;
    width: 100%;
  }

  th, td {
    border: 1px solid #000;
    text-align: center;
    padding: 8px;
  }

  td {
    background-color: white !important;
  }
}
</style>
<template>
  <!-- コンポーネント内容は省略 -->
  <table class="table-auto w-full text-left whitespace-no-wrap">
    <thead>
      <tr>
        <th id="th1" class="py-2 px-2 border-2 text-center bg-gray-500 text-white">名前</th>
        <th id="th2" class="py-2 px-2 border-2 text-center bg-gray-500 text-white">勤務時間</th>
        <th
          id="th3"
          v-for="(hour, index) in props.totalHour"
          :key="index"
          class="py-2 px-2 border-2 text-center bg-gray-500 text-white"
        >
          {{ hour.hour }}時
        </th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="(shift, index) in matchedShifts"
        :key="index"
        class="hover:bg-gray-400 transition duration-150 ease-in-out"
      >
        <td id="td1" class="py-2 text-center bg-gray-500 text-white">
          {{ shift.employeeName }}
        </td>
        <td id="td2" class="py-2 text-center bg-gray-100">
          {{ shift.clockInHour }}:{{ shift.clockInMinutes }} ~
          {{ shift.clockOutHour === '00' ? '24' : shift.clockOutHour }}:{{ shift.clockOutMinutes }}
        </td>
        <td
          id="td3"
          v-for="(hour, index) in props.totalHour"
          :key="index"
          :style="getShiftClass(hour, shift)"
          class="py-2 text-center border"
        ></td>
      </tr>
    </tbody>
  </table>
</template>

