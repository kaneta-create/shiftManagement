export const generateTimeOptions = () => {
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

export const initializeForm = (dayOfWeekNames, form) => {
    Object.entries(dayOfWeekNames).forEach(([key, value]) => {
        if (!form[value]) {
            form[value] = { start_time: '', end_time: '', dayOfNameNumber: '' };
        }
        form[value].dayOfNameNumber = key;
        if (form[value].start_time === '00:00' && form[value].end_time === '00:00') {
            form[value].start_time = '';
            form[value].end_time = '';
        }
    });
};

export const applyDefaultShifts = (defaultShifts, dayOfWeekNames, form) => {
    defaultShifts.forEach(defaultShift => {
        Object.entries(dayOfWeekNames).forEach(([key, value]) => {
            if (defaultShift.day_of_week == key) {
                form[value].start_time = defaultShift.clock_in.slice(0, -3);
                form[value].end_time = defaultShift.clock_out.slice(0, -3);
            }
        });
    });
};