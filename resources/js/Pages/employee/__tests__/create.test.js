import { describe, it, expect, vi } from 'vitest';
import { generateTemporaryPassword } from '../utils/create';

describe('createEmployee.vue', () => {
  it('return random number', () => {
    const result = generateTemporaryPassword();
    expect(result).toBeGreaterThanOrEqual(10000);
    expect(result).toBeLessThanOrEqual(90000);
  });

  it('should generate unique passwords on multiple calls', () => {
    const passwords = new Set();
    for(let i = 0; i < 100; i++){
        passwords.add(generateTemporaryPassword());
    };
    expect(passwords.size).toBe(100);
  })
});
