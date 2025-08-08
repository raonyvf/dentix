/* @vitest-environment jsdom */
import { describe, it, expect, beforeEach, vi } from 'vitest';

vi.mock('alpinejs', () => ({ default: { plugin: vi.fn(), start: vi.fn() } }));
vi.mock('@alpinejs/collapse', () => ({ default: {} }));

const buildDom = () => {
  document.body.innerHTML = `
    <div id="schedule-modal" class="hidden">
      <input id="schedule-start" />
      <input id="schedule-end" />
      <input id="schedule-professional" />
      <input id="schedule-date" />
      <div id="schedule-summary"></div>
      <input id="hora_inicio" />
      <input id="hora_fim" />
    </div>
    <table id="schedule-table">
      <thead>
        <tr><th data-professional="1">Prof 1</th></tr>
      </thead>
      <tbody>
        <tr data-row="09:00"><td data-slot="09:00"></td><td data-professional="1" data-time="09:00" data-hora="09:00"></td></tr>
        <tr data-row="09:30"><td data-slot="09:30"></td><td data-professional="1" data-time="09:30" data-hora="09:30"></td></tr>
        <tr data-row="10:00"><td data-slot="10:00"></td><td data-professional="1" data-time="10:00" data-hora="10:00"></td></tr>
      </tbody>
    </table>
  `;
};

describe('schedule selection', () => {
  beforeEach(async () => {
    vi.resetModules();
    buildDom();
    global.alert = vi.fn();
    await import('../app.js');
    document.dispatchEvent(new Event('DOMContentLoaded'));
  });

  it('opens modal with 30min duration on double click', () => {
    const cell = document.querySelector('#schedule-table td[data-professional="1"][data-time="09:00"]');
    cell.dispatchEvent(new MouseEvent('dblclick', { bubbles: true }));
    const modal = document.getElementById('schedule-modal');
    expect(modal.classList.contains('hidden')).toBe(false);
    expect(document.getElementById('schedule-start').value).toBe('09:00');
    expect(document.getElementById('schedule-end').value).toBe('09:30');
  });

  it('opens modal with correct start and end after two clicks', async () => {
    const first = document.querySelector('#schedule-table td[data-professional="1"][data-time="09:00"]');
    first.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
    first.dispatchEvent(new MouseEvent('mouseup', { bubbles: true }));
    first.dispatchEvent(new MouseEvent('click', { bubbles: true }));
    await new Promise(r => setTimeout(r, 0));

    const second = document.querySelector('#schedule-table td[data-professional="1"][data-time="10:00"]');
    second.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
    second.dispatchEvent(new MouseEvent('mouseup', { bubbles: true }));
    second.dispatchEvent(new MouseEvent('click', { bubbles: true }));

    const modal = document.getElementById('schedule-modal');
    expect(modal.classList.contains('hidden')).toBe(false);
    expect(document.getElementById('schedule-start').value).toBe('09:00');
    expect(document.getElementById('schedule-end').value).toBe('10:00');
  });
});

