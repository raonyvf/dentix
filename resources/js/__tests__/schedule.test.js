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
        <tr data-row="09:00"><td data-slot="09:00"></td><td data-professional-id="1" data-hora="09:00" data-date="2024-01-01"><div class="minute-grid"></div><div class="schedule-gutter"></div><div class="relative h-full ml-5" style="width:calc(100% - 20px);"><div class="h-full flex flex-col lg:flex-row gap-0.5"></div></div></td></tr>
        <tr data-row="09:30"><td data-slot="09:30"></td><td data-professional-id="1" data-hora="09:30" data-date="2024-01-01"><div class="minute-grid"></div><div class="schedule-gutter"></div><div class="relative h-full ml-5" style="width:calc(100% - 20px);"><div class="h-full flex flex-col lg:flex-row gap-0.5"></div></div></td></tr>
        <tr data-row="10:00"><td data-slot="10:00"></td><td data-professional-id="1" data-hora="10:00" data-date="2024-01-01"><div class="minute-grid"></div><div class="schedule-gutter"></div><div class="relative h-full ml-5" style="width:calc(100% - 20px);"><div class="h-full flex flex-col lg:flex-row gap-0.5"></div></div></td></tr>
        <tr data-row="10:30"><td data-slot="10:30"></td><td data-professional-id="1" data-hora="10:30" data-date="2024-01-01"><div class="minute-grid"></div><div class="schedule-gutter"></div><div class="relative h-full ml-5" style="width:calc(100% - 20px);"><div class="h-full flex flex-col lg:flex-row gap-0.5"></div></div></td></tr>
        <tr data-row="11:00"><td data-slot="11:00"></td><td data-professional-id="1" data-hora="11:00" data-date="2024-01-01"><div class="minute-grid"></div><div class="schedule-gutter"></div><div class="relative h-full ml-5" style="width:calc(100% - 20px);"><div class="h-full flex flex-col lg:flex-row gap-0.5"></div></div></td></tr>
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

  it('opens modal with 15min duration on single click', () => {
    const cell = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="09:00"]');
    const gutter = cell.querySelector('.schedule-gutter');
    gutter.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 4 }));
    gutter.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 4 }));
    gutter.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 4 }));
    const modal = document.getElementById('schedule-modal');
    expect(modal.classList.contains('hidden')).toBe(false);
    expect(document.getElementById('schedule-start').value).toBe('09:00');
    expect(document.getElementById('schedule-end').value).toBe('09:15');
  });

  it('opens modal with correct start and end after drag', () => {
    const first = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="09:00"]');
    const second = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="10:00"]');
    const firstGutter = first.querySelector('.schedule-gutter');
    const secondGutter = second.querySelector('.schedule-gutter');
    firstGutter.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 4 }));
    secondGutter.dispatchEvent(new MouseEvent('mousemove', { bubbles: true, clientY: 4 }));
    secondGutter.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 4 }));
    secondGutter.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 4 }));
    const modal = document.getElementById('schedule-modal');
    expect(modal.classList.contains('hidden')).toBe(false);
    expect(document.getElementById('schedule-start').value).toBe('09:00');
    expect(document.getElementById('schedule-end').value).toBe('10:00');
  });

  it('keeps professional info after interacting with modal fields', () => {
    const cell = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="09:00"]');
    const gutter = cell.querySelector('.schedule-gutter');
    gutter.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 4 }));
    gutter.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 4 }));
    gutter.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 4 }));

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
    const gutter = cell.querySelector('.schedule-gutter');
    gutter.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 4 }));
    gutter.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 4 }));
    gutter.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 4 }));
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
    const gutter = cell.querySelector('.schedule-gutter');
    gutter.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: 4 }));
    gutter.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: 4 }));
    gutter.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: 4 }));
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

  it('snaps click at 11:07 to 11:00', () => {
    const cell = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="11:00"]');
    const pos = 7 * (20 / 30);
    const gutter = cell.querySelector('.schedule-gutter');
    gutter.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: pos }));
    gutter.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: pos }));
    gutter.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: pos }));
    expect(document.getElementById('schedule-start').value).toBe('11:00');
  });

  it('snaps click at 11:22 to 11:15', () => {
    const cell = document.querySelector('#schedule-table td[data-professional-id="1"][data-hora="11:00"]');
    const pos = 22 * (20 / 30);
    const gutter = cell.querySelector('.schedule-gutter');
    gutter.dispatchEvent(new MouseEvent('mousedown', { bubbles: true, clientY: pos }));
    gutter.dispatchEvent(new MouseEvent('mouseup', { bubbles: true, clientY: pos }));
    gutter.dispatchEvent(new MouseEvent('click', { bubbles: true, clientY: pos }));
    expect(document.getElementById('schedule-start').value).toBe('11:15');
  });
});

