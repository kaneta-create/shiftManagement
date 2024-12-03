import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import { getFormattedDate } from '../utils/date';
import { getEndOfDayAfterThreeMonth } from '../utils/date';
import { getShiftClass } from '../utils/date';

describe('index.vue', () => {
  it('getFormattedDate関数', () => {
      const mockShiftInfos = [
        { date: '2024-11-18' },
        { date: '2024-11-19' },
      ];
      const result = getFormattedDate('2024-11-18', mockShiftInfos);
      expect(result).toBe('2024-11-18');
  });

  it('should return the last day of after three month', () => {
    // モック日付の設定
    const mockDate = new Date(2024, 8, 20); // 2024年9月20日
    vi.setSystemTime(mockDate); // Vitest のモックで現在時刻を固定

    const result = getEndOfDayAfterThreeMonth();

    // 期待する結果 (2024年11月30日)
    const expectedDate = new Date(2024, 10, 30); // 月は 0-indexed (10 = 11月)
    expect(result).toEqual(expectedDate);

    // モックをリセット
    vi.useRealTimers();
  });

  it('should return the correct style for the clock-in time (e.g., 09:15)', () => {
    const hour = { hour: 9 };
    const shift = { clockIn: '09:15', clockOut: '17:30' };

    const result = getShiftClass(hour, shift);

    expect(result).toBe(
      'background: linear-gradient(90deg, transparent 0%, transparent 25%, hsla(200, 100%, 85%, .5) 25%, hsla(200, 100%, 85%, .5) 100%)'
    );
  });

  it('should return the correct style for the clock-out time (e.g., 17:45)', () => {
    const hour = { hour: 17 };
    const shift = { clockIn: '09:00', clockOut: '17:45' };

    const result = getShiftClass(hour, shift);

    expect(result).toBe(
      'background: linear-gradient(90deg, hsla(200, 100%, 85%, .5) 0%, hsla(200, 100%, 85%, .5) 75%, transparent 75%, transparent 100%)'
    );
  });

  it('should return the correct style for hours fully within the shift (e.g., 10:00)', () => {
    const hour = { hour: 10 };
    const shift = { clockIn: '09:00', clockOut: '17:00' };

    const result = getShiftClass(hour, shift);

    expect(result).toBe('background-color: hsla(200, 100%, 85%, .5);');
  });

  it('should return an empty string for hours outside the shift (e.g., 18:00)', () => {
    const hour = { hour: 18 };
    const shift = { clockIn: '09:00', clockOut: '17:00' };

    const result = getShiftClass(hour, shift);

    expect(result).toBe('');
  });
});
