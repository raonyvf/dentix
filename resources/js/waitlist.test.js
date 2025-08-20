import { describe, it, expect, vi, beforeEach, beforeAll } from 'vitest';
import { JSDOM } from 'jsdom';

const dom = new JSDOM('<!doctype html><html><body></body></html>');
global.window = dom.window;
global.document = dom.window.document;
global.MutationObserver = dom.window.MutationObserver;
global.CustomEvent = dom.window.CustomEvent;
global.Event = dom.window.Event;

let loaded = false;
beforeAll(async () => {
  if (!loaded) {
    await import('./app.js');
    loaded = true;
  }
});

describe('waitlist integration', () => {
  let fetchMock;
  beforeEach(() => {
    document.body.innerHTML = `
        <div id="root"></div>
        <div id="waitlist-container"></div>
        <button id="btn-waitlist-month"></button>
        <div id="waitlist-month-modal" class="hidden">
          <h2 id="wl-month-title"></h2>
          <table><tbody id="wl-month-body"></tbody></table>
          <button id="wl-month-close"></button>
        </div>
      `;
    window.renderSchedule = vi.fn();
    window.updateScheduleTable = vi.fn();
    fetchMock = vi.fn((url) => {
      if (url.includes('/waitlist/month')) {
        return Promise.resolve({
          json: () =>
            Promise.resolve({
              waitlist: {
                '2025-08-05': [{ paciente: 'Joao' }],
                '2025-08-08': [{ paciente: 'Maria' }]
              }
            })
        });
      }
      if (url.includes('waitlist')) {
        const [, qs = ''] = url.split('?');
        const params = new URLSearchParams(qs);
        const date = params.get('date');
        const range = Number(params.get('range') || 0);

        if (date === '2025-08-09' && range === 0) {
          return Promise.resolve({ json: () => Promise.resolve({ waitlist: [] }) });
        }
        if (date === '2025-08-09' && range > 0) {
          return Promise.resolve({
            json: () => Promise.resolve({ waitlist: [{ id: 3, paciente: 'Carlos', contato: '789', observacao: 'obs3', data: '2025-08-10', sugestao: { data: '2025-08-10', inicio: '10:00', fim: '10:30' } }] })
          });
        }

        const name = date === '2025-08-07' ? 'Joao' : 'Maria';
        const observacao = date === '2025-08-07' ? 'obs1' : 'obs2';
        return Promise.resolve({
          json: () => Promise.resolve({ waitlist: [{ id: 1, paciente: name, contato: '123', observacao, data: date, sugestao: { data: date, inicio: '09:00', fim: '09:30' } }] })
        });
      }
      return Promise.resolve({ json: () => Promise.resolve({}) });
    });
    global.fetch = fetchMock;
    window.openWaitlistMonth = async function(date) {
      const modal = document.getElementById('waitlist-month-modal');
      if (!modal) return;
      const d = new Date(date);
      if (isNaN(d)) return;
      const iso = new Date(d.getFullYear(), d.getMonth(), 1).toISOString().slice(0, 10);
      const res = await fetch(`/waitlist/month?date=${iso}`);
      const data = await res.json();
      const entries = data.waitlist || {};
      const body = modal.querySelector('#wl-month-body');
      body.innerHTML = '';
      Object.keys(entries).sort().forEach(day => {
        const names = entries[day].map(i => i.paciente || i.nome || '').join(', ');
        const tr = document.createElement('tr');
        tr.innerHTML = `<td class="border p-1">${day}</td><td class="border p-1">${names}</td>`;
        body.appendChild(tr);
      });
      modal.classList.remove('hidden');
    };
    document.getElementById('btn-waitlist-month').addEventListener('click', () => {
      if (window.selectedAgendaDate) window.openWaitlistMonth(window.selectedAgendaDate);
    });
  });

  it('calls waitlist endpoint and renders results', async () => {
    const comp = window.agendaCalendar();
    comp.professionalsUrl = null;
    comp.horariosUrl = null;
    comp.waitlistUrl = '/admin/agendamentos/waitlist';
    comp.baseTimes = [];

    await comp.loadData('2025-08-07');

    expect(fetchMock).toHaveBeenCalledWith('/admin/agendamentos/waitlist?date=2025-08-07');
    const content = document.getElementById('waitlist-container').textContent;
    expect(content).toContain('Joao');
    expect(content).toContain('obs1');
    expect(content).toContain('Sugestão: 07/08/2025 09:00–09:30');
  });

  it('updates waitlist when date changes', async () => {
    const comp = window.agendaCalendar();
    comp.professionalsUrl = null;
    comp.horariosUrl = null;
    comp.waitlistUrl = '/admin/agendamentos/waitlist';
    comp.baseTimes = [];

    await comp.loadData('2025-08-07');
    fetchMock.mockClear();
    document.getElementById('waitlist-container').innerHTML = '';

    await comp.loadData('2025-08-08');

    expect(fetchMock).toHaveBeenCalledWith('/admin/agendamentos/waitlist?date=2025-08-08');
    const content = document.getElementById('waitlist-container').textContent;
    expect(content).toContain('Maria');
    expect(content).toContain('obs2');
    expect(content).toContain('Sugestão: 08/08/2025 09:00–09:30');
  });

  it('falls back to range search when no entries for date', async () => {
    await window.loadWaitlist('2025-08-09');
    await new Promise(r => setTimeout(r, 0));

    expect(fetchMock).toHaveBeenCalledWith('/admin/agendamentos/waitlist?date=2025-08-09&range=0');
    expect(fetchMock).toHaveBeenCalledWith('/admin/agendamentos/waitlist?date=2025-08-09&range=3');
    const content = document.getElementById('waitlist-container').textContent;
    expect(content).toContain('Carlos');
    expect(content).toContain('2025-08-10');
    expect(content).toContain('Sugestão: 10/08/2025 10:00–10:30');
  });

  it('opens waitlist month modal and shows patients per day', async () => {
    window.selectedAgendaDate = '2025-08-15';

    document.getElementById('btn-waitlist-month').dispatchEvent(new Event('click'));
    await new Promise(r => setTimeout(r, 0));

    expect(fetchMock).toHaveBeenCalledWith('/waitlist/month?date=2025-08-01');
    const modal = document.getElementById('waitlist-month-modal');
    expect(modal.classList.contains('hidden')).toBe(false);
    const content = document.getElementById('wl-month-body').textContent;
    expect(content).toContain('2025-08-05');
    expect(content).toContain('Joao');
    expect(content).toContain('2025-08-08');
    expect(content).toContain('Maria');
  });

  it('calls loadWaitlist when agenda changes without component', () => {
    const spy = vi.spyOn(window, 'loadWaitlist').mockResolvedValue();
    vi.spyOn(window, 'getAgendaComponent').mockReturnValue(null);
    document.dispatchEvent(new CustomEvent('agenda:changed', { detail: { date: '2025-09-01' } }));
    expect(spy).toHaveBeenCalledWith('2025-09-01');
  });
});
