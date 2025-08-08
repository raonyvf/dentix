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
      <input id="schedule-paciente" data-search-url="/search" list="schedule-paciente-list" />
      <datalist id="schedule-paciente-list"></datalist>
      <input id="schedule-paciente-id" />
      <button id="schedule-save"></button>
      <button id="schedule-cancel"></button>
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

  it('opens modal with correct start and end after two clicks', () => {
    const first = document.querySelector('#schedule-table td[data-professional="1"][data-time="09:00"]');
    first.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
    first.dispatchEvent(new MouseEvent('mouseup', { bubbles: true }));
    first.dispatchEvent(new MouseEvent('click', { bubbles: true }));

    const second = document.querySelector('#schedule-table td[data-professional="1"][data-time="10:00"]');
    second.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
    second.dispatchEvent(new MouseEvent('mouseup', { bubbles: true }));
    second.dispatchEvent(new MouseEvent('click', { bubbles: true }));

    const modal = document.getElementById('schedule-modal');
    expect(modal.classList.contains('hidden')).toBe(false);
    expect(document.getElementById('schedule-start').value).toBe('09:00');
    expect(document.getElementById('schedule-end').value).toBe('10:00');
  });

  it('keeps professional info after interacting with modal fields', () => {
    const cell = document.querySelector('#schedule-table td[data-professional="1"][data-time="09:00"]');
    cell.dispatchEvent(new MouseEvent('dblclick', { bubbles: true }));

    const profInput = document.getElementById('schedule-professional');
    const summary = document.getElementById('schedule-summary');
    expect(profInput.value).toBe('1');
    expect(summary.textContent).toContain('Prof 1');

    const start = document.getElementById('schedule-start');
    start.dispatchEvent(new MouseEvent('click', { bubbles: true }));

    expect(profInput.value).toBe('1');
    expect(summary.textContent).toContain('Prof 1');
  });

  it('sets patient id when selecting from search results', async () => {
    vi.useFakeTimers();
    global.fetch = vi.fn(() =>
      Promise.resolve({ json: () => Promise.resolve([{ id: 1, name: 'John Doe' }]) })
    );
    const input = document.getElementById('schedule-paciente');
    input.value = 'Jo';
    input.dispatchEvent(new Event('input', { bubbles: true }));
    await vi.runAllTimersAsync();
    input.value = 'John Doe';
    input.dispatchEvent(new Event('input', { bubbles: true }));
    expect(document.getElementById('schedule-paciente-id').value).toBe('1');
    vi.useRealTimers();
  });
});

