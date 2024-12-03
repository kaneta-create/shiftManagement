import { describe, it, expect, vi } from 'vitest';
import { generateTimeOptions } from '../utils/edit';

describe('edit.vue', () => {
    it('should generate time options', () => {
        const timeOptions = generateTimeOptions();
        // expect(timeOptions).toHaveLength(96); // 24 * 4
        expect(timeOptions[0]).toBe('00:00');
        expect(timeOptions[95]).toBe('23:45');
    });
});