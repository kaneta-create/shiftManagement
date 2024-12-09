<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia'

const isShow = ref(false);
const toggleStatus = () => {
    isShow.value = !isShow.value
}

const props = defineProps({
    date: String,
    full_date: String,
    Ymd_date: String,
    userId : Number,
    employeeInfo: Array
});
const form = reactive({
    isDayOff: '',
    clock_in: '',
    clock_out: '',
    date:'',
    employee_name:'',
    employee_id: ''
})

watch(() => form.employee_name, (newName) => {
    const selectedEmployee = props.employeeInfo.find(employee => employee.employee_name === newName);
    if (selectedEmployee) {
        form.employee_id = selectedEmployee.employee_id;  // employee_id を更新
    }
});
onMounted(() => {
    form.date = props.Ymd_date;
});
watch(() => props.Ymd_date, (newDate) => {
    form.date = newDate;
});

watch(form, (newForm) => {
    Object.keys(newForm).forEach(day => {
        const startTime = newForm[day].start_time;
        const endTime = newForm[day].end_time;

        if (startTime && endTime && startTime >= endTime) {
          console.error(`${day} の出勤時間は退勤時間より前に設定してください。`);
        }
      }); 
})

const emit = defineEmits(['updateShiftData']);
const shiftUpdates = [];
const shiftDataUpdate = () => {
    let shiftData = {
        isDayOff: form.isDayOff,
        clock_in: form.clock_in,
        clock_out: form.clock_out,
        date: form.date,
        employee_id: form.employee_id,
        employee_name: form.employee_name
    };
        
    shiftUpdates.push(JSON.parse(JSON.stringify(shiftData)));
    toggleStatus(); // モーダルを閉じる
    // console.log(shiftUpdates);

    emit('updateShiftData', shiftUpdates);  
};

const generateTimeOptions = () => {
    const times = [];
    for (let hour = 0; hour < 24; hour++) {
        for (let minute = 0; minute < 60; minute += 15) {
            const formattedHour = String(hour).padStart(2, '0');
            const formattedMinute = String(minute).padStart(2, '0');
            times.push(`${formattedHour}:${formattedMinute}`);
        }
    }
    return times;
};

const timeOptions = generateTimeOptions();
</script>

<template>
     <div v-show="isShow" class="modal" id="modal-1"> 
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
            <h2 class="modal__title" id="modal-1-title">
                シフト変更
            </h2>
            <!-- <button class="modal__close" aria-label="Close modal" data-micromodal-close></button> -->
            </header>
            <!-- <form @submit.prevent="updateActualShift(props.userId)"> -->
            <main class="modal__content" id="modal-1-content">
                <div class="flex flex-col space-y-2">
                    <div>
                        <p>{{ props.full_date }}</p>
                        <div >
                            <select id="employee_name" name="employee_name" v-model="form.employee_name">
                               <option v-for="name in props.employeeInfo">{{ name.employee_name }}</option> 
                            </select>
                        </div>
                        <div class="mr-2 mt-4">
                            <input name="isDayOff" type="radio" value=0 v-model="form.isDayOff"><span class="mr-2">出勤</span>
                            <input name="isDayOff" type="radio" value=1 v-model="form.isDayOff"><span>休日</span>
                        </div>
                        <div class="mt-2">
                            <div>
                                <!-- <label for="clock_in">出勤時間</label>
                                <input :disabled="form.isDayOff == 1 || form.isDayOff == ''"
                                :class="form.isDayOff == 1 ? 'bg-gray-200 cursor-not-allowed' : ''" id="clock_in" name="clock_in" type="time" v-model="form.clock_in"> -->
                                <label for="clock_in">出勤時間</label>
                                <select id="clock_in" class="mb-2" v-model="form.clock_in" :disabled="form.isDayOff === '1'" :class="{'bg-gray-200': form.isDayOff === '1', 'cursor-not-allowed': form.isDayOff === '1'}">
                                    <option value="" disabled>-- 選択 --</option>
                                    <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                                </select>
                            </div>
                            <div class="mt-2">
                                <!-- <label for="clock_out">退勤時間</label>
                                <input :disabled="form.isDayOff == 1 || form.isDayOff == ''"
                                :class="form.isDayOff == 1 ? 'bg-gray-200 cursor-not-allowed' : ''" id="clock_out" name="clock_out" type="time" v-model="form.clock_out"> -->
                                <label for="clock_out">退勤時間</label>
                                <select id="clock_out" class="mb-2" v-model="form.clock_out" :disabled="form.isDayOff === '1'" :class="{'bg-gray-200': form.isDayOff === '1', 'cursor-not-allowed': form.isDayOff === '1'}">
                                    <option value="" disabled>-- 選択 --</option>
                                    <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                                </select>
                            </div>
                            <div v-if="form.clock_in && form.clock_out&& form.clock_in >= form.clock_out"
                                            class="text-red-500">出勤時間は退勤時間より前に設定してください。</div>

                        </div>
                    </div>
                </div>
            </main>
            <footer class="modal__footer">
                <div class="flex justify-between">
                    <button @click="toggleStatus" type="button" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">閉じる</button>
                    <!-- <button class="text-white bg-indigo-500 border-0 text-sm px-4 py-3 focus:outline-none hover:bg-indigo-600 rounded">登録</button> -->
                    <button type="button" @click="shiftDataUpdate" class="text-white bg-indigo-500 border-0 text-sm px-4 py-3 focus:outline-none hover:bg-indigo-600 rounded">保存</button>
                </div>
            </footer>
            <!-- </form> -->
        </div>
        </div>
    </div>
    <!-- <td 
    @click="new Date(props.Ymd_date) >= new Date().setHours(0,0,0,0) ? toggleStatus() : null"  class="text-center">{{ props.date }}</td> -->
    <td>
        <button @click="new Date(props.Ymd_date) >= new Date().setHours(0,0,0,0) ? toggleStatus()  : null"  class="text-center" type="button">{{ props.date }}</button>
    </td>
</template>