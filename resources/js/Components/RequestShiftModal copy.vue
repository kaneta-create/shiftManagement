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
    shifts: Array,
    errors: Object
});
// console.log(props.shifts, props.Ymd_date)
const form = reactive({
    isDayOff: '',
    clock_in: '',
    clock_out: '',
    date:'',
    employee_id: props.userId
})

console.log(props.isDayOff)

onMounted(() => {
    form.date = props.Ymd_date;
});


watch(() => props.Ymd_date, (newDate) => {
    form.date = newDate;
});
const emit = defineEmits(['updateShiftData']);
const shiftUpdates = [];
const shiftDataUpdate = () => {
    if (form.isDayOff === '') {
        console.error('出勤か休日のいずれかを選択してください。');
        return;
    }
    let shiftData = {
        isDayOff: form.isDayOff,
        clock_in: form.clock_in,
        clock_out: form.clock_out,
        date: form.date,
        employee_id: form.employee_id
    };
        
    shiftUpdates.push(JSON.parse(JSON.stringify(shiftData)));
    toggleStatus(); // モーダルを閉じる
    // console.log(shiftUpdates);

    emit('updateShiftData', shiftUpdates);  
};


watch(form, (newForm) => {
    Object.keys(newForm).forEach(day => {
        const startTime = newForm[day].start_time;
        const endTime = newForm[day].end_time;
            if (startTime && endTime && startTime >= endTime) {
                console.error(`${day} の出勤時間は退勤時間より前に設定してください。`);
            }        
        }); 
})

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
                        <div class="mr-2 mt-4">
                            <input name="isDayOff" type="radio" value="0" v-model="form.isDayOff" id="isDayOff">
                            <span class="mr-2">出勤</span>
                            
                            <input name="isDayOff" type="radio" value="1" v-model="form.isDayOff" id="isDayOff">
                            <span>休日</span>
                        </div>
                        <div v-if="form.isDayOff === ''" class="text-red-500">*出勤か休日のいずれかを選択してください。</div>
                        <div class="mt-2">
                            <div>
                                <label for="clock_in">出勤時間</label>
                                <select class="mb-2" v-model="form.clock_in" id="clock_in">
                                    <option value="" disabled>-- 選択 --</option>
                                    <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                                </select>
                            </div>
                            <div class="mt-2">
                                <label for="clock_out">退勤時間</label>
                                <select class="mb-2" v-model="form.clock_out" id="clock_out">
                                    <option value="" disabled>-- 選択 --</option>
                                    <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                                </select>
                            </div>
                            <div v-if="form.clock_in && form.clock_out&& form.clock_in >= form.clock_out && form.clock_out != '00:00'"
                                            class="text-red-500">*出勤時間は退勤時間より前に設定してください。</div>

                        </div>
                    </div>
                </div>
            </main>
            <footer class="modal__footer">
                <div class="flex justify-between">
                    <button @click="toggleStatus" type="button" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">閉じる</button>
                    <button id="store" type="button" @click="shiftDataUpdate" class="text-white bg-indigo-500 border-0 text-sm px-4 py-3 focus:outline-none hover:bg-indigo-600 rounded">保存</button>
                </div>
            </footer>
            <!-- </form> -->
        </div>
        </div>
    </div>
    <td>
        <button @click="new Date(props.Ymd_date) >= new Date().setHours(0,0,0,0) ? toggleStatus()  : null"  class="text-center" type="button">{{ props.date }}</button>
    </td>
</template>