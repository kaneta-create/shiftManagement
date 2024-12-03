import { describe, it, expect, vi } from 'vitest';
import { generateTimeOptions, initializeForm, applyDefaultShifts  } from '../utils/edit';

describe('edit.vue', () => {
    it('should generate time options', () => {
        const timeOptions = generateTimeOptions();
        // expect(timeOptions).toHaveLength(96); // 24 * 4
        expect(timeOptions[0]).toBe('00:00');
        expect(timeOptions[95]).toBe('23:45');
    });

    it('should initialize the form correctly', () => {
        const dayOfWeekNames = {
            1: '月曜日',
            2: '火曜日',
            3: '水曜日',
            4: '木曜日',
            5: '金曜日',
            6: '土曜日',
            7: '日曜日'
        };

        const form = {
            '月曜日': { start_time: '00:00', end_time: '00:00', dayOfNameNumber: '' },
            '火曜日': { start_time: '', end_time: '', dayOfNameNumber: '' },
            // ... (略)
        };

        initializeForm(dayOfWeekNames, form);

        expect(form['月曜日'].dayOfNameNumber).toBe('1');
        expect(form['月曜日'].start_time).toBe('');
        expect(form['月曜日'].end_time).toBe('');
    });

    it('should apply default shifts correctly', () => {
        const defaultShifts = [
            { day_of_week: '1', clock_in: '09:00:00', clock_out: '17:00:00' },
            { day_of_week: '2', clock_in: '10:00:00', clock_out: '18:00:00' }
        ];

        const dayOfWeekNames = {
            1: '月曜日',
            2: '火曜日',
            3: '水曜日',
            4: '木曜日',
            5: '金曜日',
            6: '土曜日',
            7: '日曜日'
        };

        const form = {
            '月曜日': { start_time: '', end_time: '', dayOfNameNumber: '' },
            '火曜日': { start_time: '', end_time: '', dayOfNameNumber: '' },
            // ... (略)
        };

        applyDefaultShifts(defaultShifts, dayOfWeekNames, form);

        expect(form['月曜日'].start_time).toBe('09:00');
        expect(form['月曜日'].end_time).toBe('17:00');
        expect(form['火曜日'].start_time).toBe('10:00');
        expect(form['火曜日'].end_time).toBe('18:00');
    });
});