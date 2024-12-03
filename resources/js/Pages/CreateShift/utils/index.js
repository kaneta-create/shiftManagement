//テストすべき関数
//workDay, updateDayInfo, updateShift, totalTime, countTotalWorkingDay
//workDay
import { ref, reactive } from 'vue';
export function workDay(employee_name, name, date, attendance_date){
    if(name === employee_name && date === attendance_date){
        return true;
    };
};


export const dayInfos = ref([]);
export const dayShiftInfos = ref([]);

export const updateDayInfos = (props, selectMonth) => {
    dayInfos.value = [];
    dayShiftInfos.value = [];
    props.month.forEach(day => {
        if (selectMonth.selectedMonth === props.month[0][1].firstMonth) {
            if (day[1]) {
                dayInfos.value.push(day[1]);
            }
        } else if (selectMonth.selectedMonth === props.month[0][1].secondMonth) {
            if (day[2]) {
                dayInfos.value.push(day[2]);
            }
        } else {
            if (day[3]) {
                dayInfos.value.push(day[3]);
            }
        }
    });

    
};

export function totalTime(employee_id, props, selectMonth){
    let workTime = [];
    if (selectMonth.selectedMonth === props.month[0][1].firstMonth) {
        workTime = props.totalWorkingTimes[1][employee_id]['total_working_hours']
    } else if (selectMonth.selectedMonth === props.month[0][1].secondMonth) {
        workTime = props.totalWorkingTimes[2][employee_id]['total_working_hours']
    } else {
        workTime = props.totalWorkingTimes[3][employee_id]['total_working_hours']
    }
    return workTime;
}

export function countTotalWorkingDay(employee_id, props, selectMonth) {
    let totalWorkDay = [];

    if (selectMonth.selectedMonth === props.month[0][1].firstMonth) {
        totalWorkDay = props.totalWorkingTimes[1][employee_id]['attendance_count'];
    } else if (selectMonth.selectedMonth === props.month[0][1].secondMonth) {
        totalWorkDay = props.totalWorkingTimes[2][employee_id]['attendance_count'];
    } else {
        totalWorkDay = props.totalWorkingTimes[3][employee_id]['attendance_count'];
    }

    return totalWorkDay;
}
