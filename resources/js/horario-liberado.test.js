import { describe, it, expect, beforeEach, beforeAll, vi } from 'vitest';
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

describe('horario:liberado listener', () => {
  let fetchMock;
  beforeEach(() => {
    document.body.innerHTML = `
      <div id="schedule-modal" class="hidden">
        <input id="schedule-start" />
        <input id="schedule-end" />
        <input id="schedule-paciente" />
        <input id="agendamento-id" />
        <input id="schedule-professional" />
        <input id="schedule-date" />
        <div id="schedule-summary"></div>
        <input id="hora_inicio" />
        <input id="hora_fim" />
        <div id="step-1"></div>
        <div id="step-2" class="hidden"></div>
        <input id="patient-search" />
        <ul id="patient-results"></ul>
        <div id="patient-notfound"></div>
        <span id="selected-patient-name"></span>
        <button id="patient-search-btn"></button>
        <select id="schedule-status"></select>
        <div id="schedule-time"></div>
        <button id="schedule-save" data-store-url="#" data-update-url="#"></button>
      </div>
      <table id="schedule-table"><thead></thead><tbody></tbody></table>
    `;
    window.attachCellHandlers();
    fetchMock = vi.fn(() =>
      Promise.resolve({
        json: () =>
          Promise.resolve({
            waitlist: [
              {
                id: 1,
                paciente: 'Fulano',
                contato: '123',
                observacao: '',
                profissional_id: 1,
                sugestao: { inicio: '09:00', fim: '09:30' },
              },
            ],
          }),
      })
    );
    global.fetch = fetchMock;
    window.confirm = vi.fn(() => true);
  });

  it('prefills modal with suggested times and shows both options in message', async () => {
    document.dispatchEvent(
      new CustomEvent('horario:liberado', {
        detail: { data: '2025-08-07', hora: '10:00', profissional_id: 1 },
      })
    );
    await new Promise(r => setTimeout(r, 0));

    expect(fetchMock).toHaveBeenCalledWith('/admin/agendamentos/waitlist?date=2025-08-07&range=3');
    const msg = window.confirm.mock.calls[0][0];
    expect(msg).toContain('10:00');
    expect(msg).toContain('09:00–09:30');
    expect(document.getElementById('schedule-start').value).toBe('09:00');
    expect(document.getElementById('schedule-end').value).toBe('09:30');
  });
});
