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
    `;
    window.renderSchedule = vi.fn();
    window.updateScheduleTable = vi.fn();
    fetchMock = vi.fn((url) => {
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

  it('calls loadWaitlist when agenda changes without component', () => {
    const spy = vi.spyOn(window, 'loadWaitlist').mockResolvedValue();
    vi.spyOn(window, 'getAgendaComponent').mockReturnValue(null);
    document.dispatchEvent(new CustomEvent('agenda:changed', { detail: { date: '2025-09-01' } }));
    expect(spy).toHaveBeenCalledWith('2025-09-01');
  });
});
