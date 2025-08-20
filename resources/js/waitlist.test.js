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
        const date = url.split('date=')[1];
        const name = date === '2025-08-07' ? 'Joao' : 'Maria';
        return Promise.resolve({
          json: () => Promise.resolve({ waitlist: [{ id: 1, paciente: name, contato: '123' }] })
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
    expect(document.getElementById('waitlist-container').textContent).toContain('Joao');
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
    expect(document.getElementById('waitlist-container').textContent).toContain('Maria');
  });
});
