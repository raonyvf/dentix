/* @vitest-environment jsdom */
import { describe, it, expect, beforeEach, vi } from 'vitest';

vi.mock('alpinejs', () => ({ default: { plugin: vi.fn(), start: vi.fn(), $data: vi.fn(() => ({ loadData: vi.fn() })) } }));
vi.mock('@alpinejs/collapse', () => ({ default: {} }));
vi.mock('tom-select', () => ({
  default: class {
    constructor(el, opts) {
      this.el = el;
      this.opts = opts;
      this.events = {};
      el.tomselect = this;
    }
    on(event, cb) {
      this.events[event] = cb;
    }
    load() {}
  }
}));

const buildDom = () => {
  document.head.innerHTML = '<meta name="csrf-token" content="token">';
  document.body.innerHTML = `
    <div id="schedule-success" class="hidden"><span></span></div>
    <div id="schedule-modal" class="hidden" data-hora="" data-date="">
      <input id="schedule-start" />
      <input id="schedule-end" />
      <input id="schedule-professional" />
      <input id="schedule-date" />
      <div id="schedule-summary"></div>
      <input id="hora_inicio" />
      <input id="hora_fim" />
      <select id="schedule-paciente" data-search-url="/search">
        <option value=""></option>
        <option value="1">John Doe</option>
      </select>
      <button id="schedule-save" data-store-url="/save"></button>
      <button id="schedule-cancel"></button>
    </div>
    <table id="schedule-table">
      <thead>
        <tr><th data-professional-id="1">Prof 1</th></tr>
      </thead>
      <tbody>
        <tr data-row="09:00"><td data-slot="09:00"></td><td data-professional-id="1" data-hora="09:00" data-date="2024-01-01"></td></tr>
        <tr data-row="09:30"><td data-slot="09:30"></td><td data-professional-id="1" data-hora="09:30" data-date="2024-01-01"></td></tr>
        <tr data-row="10:00"><td data-slot="10:00"></td><td data-professional-id="1" data-hora="10:00" data-date="2024-01-01"></td></tr>
      </tbody>
    </table>
  `;
  const success = document.getElementById('schedule-success');
  success.__x = { $data: { show: false } };
  document.querySelectorAll('#schedule-table td[data-professional-id]').forEach(td => {
    td.getBoundingClientRect = () => ({ top: 0, height: 20 });
  });
};

describe('schedule selection', () => {
  beforeEach(async () => {
    vi.resetModules();
    buildDom();
    global.alert = vi.fn();
    await import('../app.js');
    document.dispatchEvent(new Event('DOMContentLoaded'));
  });

  it('opens modal with 15min duration on double click', () => {
    const cell = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="09:00"]');
    cell.dispatchEvent(new MouseEvent('dblclick', { bubbles: true, clientY: 5 }));
    const modal = document.getElementById('schedule-modal');
    expect(modal.classList.contains('hidden')).toBe(false);
    expect(document.getElementById('schedule-start').value).toBe('09:00');
    expect(document.getElementById('schedule-end').value).toBe('09:15');
  });

  it('opens modal with correct start and end after two clicks', () => {
    const first = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="09:00"]');
    first.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 5 }));
    first.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 5 }));
    first.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 5 }));

    const second = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="10:00"]');
    second.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 5 }));
    second.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 5 }));
    second.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 5 }));

    const modal = document.getElementById('schedule-modal');
    expect(modal.classList.contains('hidden')).toBe(false);
    expect(document.getElementById('schedule-start').value).toBe('09:00');
    expect(document.getElementById('schedule-end').value).toBe('10:15');
  });

  it('normalizes selection when clicks are in reverse order', () => {
    const first = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="10:00"]');
    first.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 5 }));
    first.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 5 }));
    first.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 5 }));

    const second = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="09:00"]');
    second.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 5 }));
    second.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 5 }));
    second.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 5 }));

    const modal = document.getElementById('schedule-modal');
    expect(modal.classList.contains('hidden')).toBe(false);
    expect(document.getElementById('schedule-start').value).toBe('09:00');
    expect(document.getElementById('schedule-end').value).toBe('10:15');
  });

  it('clears selection when second click has different date', () => {
    const first = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="09:00"]');
    first.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 5 }));
    first.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 5 }));
    first.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 5 }));

    const second = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="10:00"]');
    second.dataset.date = '2024-01-02';
    second.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 5 }));
    second.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 5 }));
    second.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 5 }));

    const modal = document.getElementById('schedule-modal');
    expect(modal.classList.contains('hidden')).toBe(true);
    expect(first.classList.contains('selected')).toBe(false);
    expect(second.classList.contains('selected')).toBe(true);
  });

  it('keeps professional info after interacting with modal fields', () => {
    const cell = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="09:00"]');
    cell.dispatchEvent(new MouseEvent('dblclick', { bubbles: true, clientY: 5 }));

    const profInput = document.getElementById('schedule-professional');
    const summary = document.getElementById('schedule-summary');
    expect(profInput.value).toBe('1');
    expect(summary.textContent).toContain('Prof 1');

    const start = document.getElementById('schedule-start');
    start.dispatchEvent(new MouseEvent('click', { bubbles: true }));

    expect(profInput.value).toBe('1');
    expect(summary.textContent).toContain('Prof 1');
  });

  it('sends selected patient id on save', async () => {
    const cell = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="09:00"]');
    cell.dispatchEvent(new MouseEvent('dblclick', { bubbles: true, clientY: 5 }));
    const select = document.getElementById('schedule-paciente');
    select.value = '1';
    global.fetch = vi.fn(() =>
      Promise.resolve({ ok: true, json: () => Promise.resolve({}) })
    );
    const save = document.getElementById('schedule-save');
    save.dispatchEvent(new MouseEvent('click', { bubbles: true }));
    expect(fetch).toHaveBeenCalled();
    const body = JSON.parse(fetch.mock.calls[0][1].body);
    expect(body.paciente_id).toBe('1');
  });

  it('shows success alert after saving', async () => {
    const cell = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="09:00"]');
    cell.dispatchEvent(new MouseEvent('dblclick', { bubbles: true, clientY: 5 }));
    const select = document.getElementById('schedule-paciente');
    select.value = '1';
    global.fetch = vi.fn(() =>
      Promise.resolve({ ok: true, json: () => Promise.resolve({}) })
    );
    const save = document.getElementById('schedule-save');
    save.dispatchEvent(new MouseEvent('click', { bubbles: true }));
    await new Promise(r => setTimeout(r, 0));
    const success = document.getElementById('schedule-success');
    expect(success.__x.$data.show).toBe(true);
  });
});

