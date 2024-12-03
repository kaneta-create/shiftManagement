import { describe, it, expect, vi } from 'vitest';
import { workDay } from '../utils/index';
import { updateDayInfos, dayInfos, totalTime, countTotalWorkingDay } from '../utils/index';
import { reactive, ref } from 'vue';

describe('createEmployee.vue', () => {
    it('expect return true', () => {
        const employee_name = '斉藤';
        const name = '斉藤';
        const date = '2024-01-01';
        const attendance_date = '2024-01-01';

        const result = workDay(employee_name, name, date, attendance_date);
        expect(result).toBe(true);
    });

    it('should correctly populate dayInfos based on selectedMonth', () => {
        // モックデータ
        const props = {
          month: [
            [
              null,
              { firstMonth: '2024-11', secondMonth: '2024-12', thirdMonth: '2025-01', Ymd_date: '2024-11-01', full_date: '2024-11-01', date: 1, day_of_week: 'Monday', year: 2024 },
              { firstMonth: '2024-11', secondMonth: '2024-12', thirdMonth: '2025-01', Ymd_date: '2024-12-01', full_date: '2024-12-01', date: 1, day_of_week: 'Friday', year: 2024 },
              { firstMonth: '2024-11', secondMonth: '2024-12', thirdMonth: '2025-01', Ymd_date: '2025-01-01', full_date: '2025-01-01', date: 1, day_of_week: 'Tuesday', year: 2025 },
            ],
          ],
        };
    
        const selectMonth = reactive({ selectedMonth: '2024-12' });
        const dayInfos = ref([]);
        const dayShiftInfos = ref([]);
    
        // テスト対象関数
        const updateDayInfos = () => {
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
    
        // 関数実行
        updateDayInfos();
    
        // 検証
        expect(dayInfos.value).toEqual([
          {
            firstMonth: '2024-11',
            secondMonth: '2024-12',
            thirdMonth: '2025-01',
            Ymd_date: '2024-12-01',
            full_date: '2024-12-01',
            date: 1,
            day_of_week: 'Friday',
            year: 2024,
          },
        ]);
      });
    
      it('should correctly populate dayInfos based on selectedMonth', () => {
        // モックデータ
        const props = {
            month: [
                [
                    null,
                    { firstMonth: '2024-11', secondMonth: '2024-12', thirdMonth: '2025-01', Ymd_date: '2024-11-01', full_date: '2024-11-01', date: 1, day_of_week: 'Monday', year: 2024 },
                    { firstMonth: '2024-11', secondMonth: '2024-12', thirdMonth: '2025-01', Ymd_date: '2024-12-01', full_date: '2024-12-01', date: 1, day_of_week: 'Friday', year: 2024 },
                    { firstMonth: '2024-11', secondMonth: '2024-12', thirdMonth: '2025-01', Ymd_date: '2025-01-01', full_date: '2025-01-01', date: 1, day_of_week: 'Tuesday', year: 2025 },
                ],
            ],
        };

        const selectMonth = reactive({ selectedMonth: '2024-12' });

        // 関数実行
        updateDayInfos(props, selectMonth);

        // 検証
        expect(dayInfos.value).toEqual([
            {
                firstMonth: '2024-11',
                secondMonth: '2024-12',
                thirdMonth: '2025-01',
                Ymd_date: '2024-12-01',
                full_date: '2024-12-01',
                date: 1,
                day_of_week: 'Friday',
                year: 2024,
            },
        ]);
    });

    
    it('should correctly populate dayInfos for firstMonth', () => {
        const props = {
            month: [
                [
                    null,
                    { firstMonth: '2024-11', Ymd_date: '2024-11-01', full_date: '2024-11-01', date: 1, day_of_week: 'Monday', year: 2024 },
                    { secondMonth: '2024-12', Ymd_date: '2024-12-01', full_date: '2024-12-01', date: 1, day_of_week: 'Tuesday', year: 2024 },
                    { thirdMonth: '2025-01', Ymd_date: '2025-01-01', full_date: '2025-01-01', date: 1, day_of_week: 'Wednesday', year: 2025 },
                ],
            ],
        };

        const selectMonth = reactive({ selectedMonth: '2024-11' });

        // 関数実行
        updateDayInfos(props, selectMonth);

        // 検証
        expect(dayInfos.value).toEqual([
            {
                firstMonth: '2024-11',
                Ymd_date: '2024-11-01',
                full_date: '2024-11-01',
                date: 1,
                day_of_week: 'Monday',
                year: 2024,
                secondMonth: undefined,
                thirdMonth: undefined,
            },
        ]);
    });

    it('should return total working hours for the first month', () => {
        const employee_id = 1;
        const props = {
            month: [
                [
                    null,
                    { firstMonth: '2024-11', secondMonth: '2024-12', thirdMonth: '2025-01' },
                ],
            ],
            totalWorkingTimes: {
                1: {
                    1: { total_working_hours: 160 },
                },
                2: {
                    1: { total_working_hours: 170 },
                },
                3: {
                    1: { total_working_hours: 180 },
                },
            },
        };
        const selectMonth = { selectedMonth: '2024-11' };

        const result = totalTime(employee_id, props, selectMonth);

        expect(result).toBe(160);
    });

    it('should return attendance count for the first month', () => {
        const employee_id = 1;
        const props = {
            month: [
                [
                    null,
                    { firstMonth: '2024-11', secondMonth: '2024-12', thirdMonth: '2025-01' },
                ],
            ],
            totalWorkingTimes: {
                1: {
                    1: { attendance_count: 20 },
                },
                2: {
                    1: { attendance_count: 22 },
                },
                3: {
                    1: { attendance_count: 18 },
                },
            },
        };
        const selectMonth = { selectedMonth: '2024-11' };

        const result = countTotalWorkingDay(employee_id, props, selectMonth);

        expect(result).toBe(20);
    });

});