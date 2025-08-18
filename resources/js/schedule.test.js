// @vitest-environment jsdom
import { describe, it, expect, beforeEach, vi } from 'vitest';
import { selectRange, openTickSet } from './app.js';

beforeEach(() => {
  openTickSet.clear();
  global.alert = vi.fn();
});

describe('selectRange availability', () => {
  it('rejects range ending exactly at closing time', () => {
    openTickSet.add('10:00');
    openTickSet.add('10:15');
    openTickSet.add('10:30');
    const result = selectRange('2024-01-01', '1', '10:30', '10:45');
    expect(result).toBe(false);
  });
});
