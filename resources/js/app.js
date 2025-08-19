import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;

// expose agendaCalendar to Alpine in Blade templates before Alpine.start()
window.agendaCalendar = function agendaCalendar() {
    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    const week = ['SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB', 'DOM'];
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let start = getMonday(new Date());
    let selectedDate = null;

    function getMonday(date) {
        const d = new Date(date);
        const day = d.getDay();
        const diff = day === 0 ? -6 : 1 - day;
        d.setDate(d.getDate() + diff);
        d.setHours(0, 0, 0, 0);
        return d;
    }

    function isToday(date) {
        return date.getTime() === today.getTime();
    }

    function buildDays(selected) {
        const arr = [];
        for (let i = 0; i < 7; i++) {
            const d = new Date(start);
            d.setDate(start.getDate() + i);
            const iso = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
            let classes = 'flex flex-col items-center p-2 rounded cursor-pointer text-xs flex-1 text-center';
            if (iso === selected) {
                classes += ' bg-black text-white';
            } else if (d < today) {
                classes += ' text-gray-400';
            } else {
                classes += ' text-gray-700';
            }
            arr.push({
                date: iso,
                label: week[i],
                number: d.getDate(),
                month: months[d.getMonth()],
                classes,
            });
        }
        return arr;
    }

    return {
        days: [],
        selectedDate: null,
        init() {
            this.horariosUrl = this.$root.dataset.horariosUrl;
            this.professionalsUrl = this.$root.dataset.professionalsUrl;
            this.baseTimes = JSON.parse(this.$root.dataset.baseTimes || '[]');
            const initial = this.$root.dataset.currentDate;
            if (initial) {
                const d = new Date(initial);
                if (!isNaN(d)) {
                    start = getMonday(d);
                    this.selectedDate = initial;
                }
            }
            if (!this.selectedDate) {
                this.selectedDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            }
            this.days = buildDays(this.selectedDate);
            this.loadData(this.selectedDate);
        },
        prevWeek() {
            start.setDate(start.getDate() - 7);
            this.days = buildDays(this.selectedDate);
        },
        nextWeek() {
            start.setDate(start.getDate() + 7);
            this.days = buildDays(this.selectedDate);
        },
        openDatePicker() {
            if (typeof showDatePicker === 'function') {
                showDatePicker(this.selectedDate, date => this.onDateSelected(date));
            } else {
                this.$refs.picker.showPicker();
            }
        },
        onDateSelected(val) {
            const d = new Date(val);
            if (!isNaN(d)) {
                start = getMonday(d);
                this.selectDay(val);
            }
        },
        selectDay(date) {
            this.selectedDate = date;
            this.days = buildDays(this.selectedDate);
            this.loadData(date);
        },
        loadData(date) {
            return Promise.all([
                this.fetchProfessionals(date),
                this.fetchHorarios(date),
            ]).then(([profData, horariosData]) => {
                window.renderSchedule(profData.professionals, profData.agenda, this.baseTimes, date);
                window.updateScheduleTable(
                    horariosData.closed ? [] : horariosData.horarios,
                    horariosData.start,
                    horariosData.end,
                    horariosData.closed
                );
                const dbg = document.getElementById('clinic-hours-debug');
                if (dbg) {
                    if (horariosData.intervals && horariosData.intervals.length) {
                        const list = horariosData.intervals.map(i => `${i.inicio}-${i.fim}`).join(', ');
                        dbg.textContent = `Horários de funcionamento: ${list}`;
                    } else {
                        dbg.textContent = '';
                    }
                }
                document.dispatchEvent(new Event('schedule:rendered'));
            });
        },
        fetchHorarios(date) {
            if (!this.horariosUrl) return Promise.resolve({});
            return fetch(`${this.horariosUrl}?date=${date}`).then(r => r.json());
        },
        fetchProfessionals(date) {
            if (!this.professionalsUrl) return Promise.resolve({ professionals: [], agenda: {} });
            return fetch(`${this.professionalsUrl}?date=${date}`).then(r => r.json());
        },
    };
}

window.updateScheduleTable = function(openTimes, start, end, closed) {
    openTickSet.clear();
    (openTimes || []).forEach(t => openTickSet.add(t));
    const container = document.getElementById('schedule-container');
    const table = document.getElementById('schedule-table');
    const closedMsg = document.getElementById('schedule-closed');

    if (closed) {
        if (container) container.classList.remove('hidden');
        if (table) table.classList.add('hidden');
        if (closedMsg) {
            closedMsg.textContent = 'A clínica está fechada neste dia.';
            closedMsg.classList.remove('hidden');
        }
        return;
    }

    if (closedMsg) closedMsg.classList.add('hidden');
    if (table) table.classList.remove('hidden');


    document.querySelectorAll('tr[data-row]').forEach(tr => {
        const time = tr.dataset.row;
        if (start && end && (time < start || time > end)) {
            tr.classList.add('hidden');
        } else {
            tr.classList.remove('hidden');
        }
    });

    document.querySelectorAll('td[data-slot]').forEach(td => {
        const time = td.dataset.slot;
        if (openTimes.includes(time)) {
            td.classList.remove('text-gray-400');
        } else {
            td.classList.add('text-gray-400');
        }
    });
};

window.renderSchedule = function (professionals, agenda, baseTimes, date) {
    const bar = document.getElementById('professionals-bar');
    const table = document.getElementById('schedule-table');
    const emptyMsg = document.getElementById('schedule-empty');
    const hasProfessionals = professionals.length > 0;

    if (bar) {
        bar.innerHTML = '';
        if (hasProfessionals) {
            bar.insertAdjacentHTML(
                'beforeend',
                '<button class="px-4 py-2 rounded border text-sm whitespace-nowrap bg-primary text-white">Todos os Profissionais</button>'
            );
            professionals.forEach(p => {
                bar.insertAdjacentHTML(
                    'beforeend',
                    `<button class="px-4 py-2 rounded border text-sm whitespace-nowrap bg-white text-gray-700" data-professional-id="${p.id}">${p.name}</button>`
                );
            });
        }
    }

    if (!hasProfessionals) {
        if (table) table.classList.add('hidden');
        if (emptyMsg) emptyMsg.classList.remove('hidden');
        return;
    }

    if (table) {
        const theadRow = table.querySelector('thead tr');
        const tbody = table.querySelector('tbody');
        if (theadRow) {
            theadRow.innerHTML = '<th class="p-2 bg-gray-50 w-24 min-w-[6rem]"></th>';
            professionals.forEach(p => {
                theadRow.insertAdjacentHTML(
                    'beforeend',
                    `<th class="p-2 bg-gray-50 text-left whitespace-nowrap border-l" data-professional-id="${p.id}">${p.name}</th>`
                );
            });
        }
        if (tbody) {
            tbody.innerHTML = '';
            baseTimes.forEach(hora => {
                let row = `<tr class="border-t" data-row="${hora}"><td class="bg-gray-50 w-24 min-w-[6rem] h-16 align-middle" data-slot="${hora}" data-hora="${hora}"><div class="h-full flex items-center justify-end px-2 text-xs text-gray-500 whitespace-nowrap">${hora}</div></td>`;
                professionals.forEach(p => {
                    const cellItems = (agenda[p.id] && agenda[p.id][hora]) || [];
                    row += `<td class="relative h-16 cursor-pointer border-l" data-professional-id="${p.id}" data-hora="${hora}" data-date="${date}"><div class="minute-grid"></div><div class="schedule-gutter absolute left-0 top-0 h-full w-5 z-20 cursor-pointer" aria-label="Selecionar horário"></div><div class="relative h-full"><div class="h-full flex flex-col lg:flex-row gap-0.5 ml-5 card-area" style="width:calc(100% - 20px);">`;
                    if (cellItems.length) {
                        cellItems.forEach(item => {
                            if (item.skip) {
                                row += '<div class="relative lg:flex-1"></div>';
                            } else {
                                const statusClasses = {
                                    confirmado: { color: 'bg-green-100 text-green-700 border-green-800', label: 'Confirmado' },
                                    pendente: { color: 'bg-yellow-100 text-yellow-700 border-yellow-800', label: 'Pendente' },
                                    cancelado: { color: 'bg-red-100 text-red-700 border-red-800', label: 'Cancelado' },
                                    faltou: { color: 'bg-blue-100 text-blue-700 border-blue-800', label: 'Faltou' },
                                };
                                const { color, label } = statusClasses[item.status] || { color: 'bg-gray-100 text-gray-700 border-gray-800', label: 'Sem confirmação' };
                                const title = [item.paciente, `${item.hora_inicio} - ${item.hora_fim}`, item.observacao, label].filter(Boolean).join('\n');
                                row += `<div class="relative lg:flex-1"><div class="appointment-card rounded p-2 text-xs border ${color} absolute z-10" title="${title}" data-id="${item.id}" data-inicio="${item.hora_inicio}" data-fim="${item.hora_fim}" data-observacao="${item.observacao || ''}" data-status="${item.status}" data-date="${date}" data-profissional-id="${p.id}"><div class="font-bold text-sm">${item.paciente}</div><div>${item.hora_inicio} - ${item.hora_fim}</div><div>${label}</div></div></div>`;
                            }
                        });
                    } else {
                        row += '<div class="relative lg:flex-1"></div>';
                    }
                    row += '</div></div></td>';
                });
                row += '</tr>';
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }
        table.classList.remove('hidden');
    }

    if (emptyMsg) emptyMsg.classList.add('hidden');
};

let scheduleModal, cancel, startInput, endInput, saveBtn, pacienteInput, professionalInput, dateInput, summary, hiddenStart, hiddenEnd, patientSearch, patientResults, step1, step2, selectedPatientName, notFoundMsg, searchBtn, statusSelect;

function initPatientSearch() {
    patientSearch = document.getElementById('patient-search');
    patientResults = document.getElementById('patient-results');
    step1 = document.getElementById('step-1');
    step2 = document.getElementById('step-2');
    selectedPatientName = document.getElementById('selected-patient-name');
    notFoundMsg = document.getElementById('patient-notfound');
    pacienteInput = document.getElementById('schedule-paciente');
    scheduleModal = document.getElementById('schedule-modal');
    searchBtn = document.getElementById('patient-search-btn');
    if (!patientSearch || !patientResults || !scheduleModal || !searchBtn) return;

    const searchUrl = patientSearch.dataset.searchUrl;

    searchBtn.onclick = () => {
        const q = patientSearch.value.trim();
        patientResults.innerHTML = '';
        if (q.length < 2) {
            notFoundMsg?.classList.add('hidden');
            if (saveBtn) saveBtn.disabled = true;
            return;
        }
        const url = new URL(searchUrl, window.location.origin);
        const digits = q.replace(/\D/g, '');
        url.searchParams.set('query', q);
        if (digits) url.searchParams.set('digits', digits);
        fetch(url.toString(), { credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
                patientResults.innerHTML = '';
                if (!Array.isArray(data) || !data.length) {
                    notFoundMsg?.classList.remove('hidden');
                    if (saveBtn) saveBtn.disabled = true;
                    return;
                }
                notFoundMsg?.classList.add('hidden');
                data.forEach(p => {
                    const li = document.createElement('li');
                    li.innerHTML = `<div class="font-medium">${p.nome || ''}</div>` +
                        `<div class="text-xs text-gray-500">${p.email || ''}</div>`;
                    li.className = 'p-2 cursor-pointer hover:bg-gray-100';
                    li.addEventListener('click', () => {
                        pacienteInput.value = p.id;
                        selectedPatientName.textContent = p.nome || '';
                        step1.classList.add('hidden');
                        step2.classList.remove('hidden');
                        if (saveBtn) saveBtn.disabled = false;
                    });
                    patientResults.appendChild(li);
                });
            })
            .catch(err => {
                console.error(err);
                patientResults.innerHTML = '';
                notFoundMsg?.classList.remove('hidden');
                if (saveBtn) saveBtn.disabled = true;
            });
    };
}
let selection = { start: null, end: null, professional: null, date: null };

let dragging = false;
let suppressClick = false;
let handleMouseDown, handleMouseMove, handleDblClick, handleClick, handleMouseUp;
let slotMinutes = 15;
let rowMinutes = 30;
let highlightEls = [];
const openTickSet = new Set();

const updateRowMinutes = () => {
    const rows = document.querySelectorAll('#schedule-table tbody tr[data-row]');
    if (rows.length >= 2) {
        const first = rows[0].dataset.row;
        const second = rows[1].dataset.row;
        const diff = toMinutes(second) - toMinutes(first);
        if (diff > 0) rowMinutes = diff;
    }
};

const MIN_CARD_PX = 24;
const GUTTER_WIDTH = 20;


const toMinutes = t => {
    if (!t) return null;
    const [h, m] = t.split(':');
    return parseInt(h, 10) * 60 + parseInt(m, 10);
};

const nextTimes = (start, end) => {
    const times = [];
    let cur = toMinutes(start);
    const final = toMinutes(end);
    while (cur <= final) {
        const h = String(Math.floor(cur / 60)).padStart(2, '0');
        const m = String(cur % 60).padStart(2, '0');
        times.push(`${h}:${m}`);
        cur += slotMinutes;
    }
    return times;
};

const addMinutes = (time, mins) => {
    const [h, m] = time.split(':').map(Number);
    const total = h * 60 + m + mins;
    const hh = String(Math.floor(total / 60)).padStart(2, '0');
    const mm = String(total % 60).padStart(2, '0');
    return `${hh}:${mm}`;
};

const snapTime = (time, type) => {
    if (!time) return '';
    const mins = toMinutes(time);
    if (mins == null) return '';
    const snapped = type === 'start'
        ? Math.floor(mins / slotMinutes) * slotMinutes
        : Math.ceil(mins / slotMinutes) * slotMinutes;
    const h = String(Math.floor(snapped / 60)).padStart(2, '0');
    const m = String(snapped % 60).padStart(2, '0');
    return `${h}:${m}`;
};

const toRowTime = time => {
    const mins = toMinutes(time);
    const rowStart = Math.floor(mins / rowMinutes) * rowMinutes;
    const h = String(Math.floor(rowStart / 60)).padStart(2, '0');
    const m = String(rowStart % 60).padStart(2, '0');
    return `${h}:${m}`;
};

const getEventTime = (e, cell) => {
    const base = cell.dataset.hora;
    const rect = cell.getBoundingClientRect();
    let offset = e.clientY - rect.top;
    if (offset < 0) offset = 0;
    let minutes = (offset / rect.height) * rowMinutes;
    let snapped = Math.round(minutes / slotMinutes) * slotMinutes;
    if (snapped < 0) snapped = 0;
    if (snapped > rowMinutes - slotMinutes) snapped = rowMinutes - slotMinutes;
    return addMinutes(base, snapped);
};

const existeConflitoNaoCancelado = (prof, start, end, ignoreId = null) => {
    const startMin = toMinutes(start);
    const endMin = toMinutes(end);
    const appts = document.querySelectorAll(
        `#schedule-table div[data-profissional-id="${prof}"]`
    );
    for (const appt of appts) {
        if (['cancelado', 'faltou'].includes(appt.dataset.status || '')) continue;
        if (ignoreId && appt.dataset.id === String(ignoreId)) continue;
        const agStart = toMinutes(appt.dataset.inicio);
        const agEnd = toMinutes(appt.dataset.fim);
        if (agStart < endMin && agEnd > startMin) {
            return true;
        }
    }
    return false;
};
window.existeConflitoNaoCancelado = existeConflitoNaoCancelado;

const positionAppointments = () => {
    updateRowMinutes();
    const firstRow = document.querySelector('#schedule-table tbody tr[data-row]:not(.hidden)');
    if (!firstRow) return;
    const cellHeight = firstRow.offsetHeight;
    const pxPerMinute = cellHeight / rowMinutes;
    document.querySelectorAll('#schedule-table td[data-hora]').forEach(cell => {
        const cellStart = toMinutes(cell.dataset.hora);
        cell.querySelectorAll('div[data-id]').forEach(appt => {
            const start = toMinutes(appt.dataset.inicio);
            const end = toMinutes(appt.dataset.fim);
            const topPx = (start - cellStart) * pxPerMinute;
            const heightPx = Math.max((end - start) * pxPerMinute, MIN_CARD_PX);
            appt.style.top = `${topPx}px`;
            appt.style.height = `${heightPx}px`;
        });
    });
};

const renderSelection = () => {
    highlightEls.forEach(el => el.remove());
    highlightEls = [];
    if (!selection.start) return;
    const startMin = toMinutes(selection.start);
    const endMin = toMinutes(selection.end || addMinutes(selection.start, slotMinutes));
    document.querySelectorAll(`#schedule-table td[data-professional-id="${selection.professional}"][data-date="${selection.date}"]`).forEach(cell => {
        const cellStart = toMinutes(cell.dataset.hora);
        const cellEnd = cellStart + rowMinutes;
        const s = Math.max(startMin, cellStart);
        const e = Math.min(endMin, cellEnd);
        if (s >= e) return;
        const rect = cell.getBoundingClientRect();
        const top = ((s - cellStart) / rowMinutes) * rect.height;
        const height = ((e - s) / rowMinutes) * rect.height;
        const div = document.createElement('div');
        div.className = 'selection-highlight absolute bg-blue-200 opacity-50 rounded pointer-events-none';
        div.style.left = `${GUTTER_WIDTH + 10}px`;
        div.style.right = '10px';
        div.style.top = `${top}px`;
        div.style.height = `${height}px`;
        cell.appendChild(div);
        highlightEls.push(div);
    });
};

const clearSelection = (preserveProfessional = false) => {
    highlightEls.forEach(el => el.remove());
    highlightEls = [];
    selection = {
        start: null,
        end: null,
        professional: preserveProfessional ? selection.professional : null,
        date: preserveProfessional ? selection.date : null,
    };
    if (hiddenStart) hiddenStart.value = '';
    if (hiddenEnd) hiddenEnd.value = '';
    if (!preserveProfessional && professionalInput) professionalInput.value = '';
    if (dateInput && !preserveProfessional) dateInput.value = '';
    if (!preserveProfessional && summary) summary.textContent = '';
};

const isOpen = time => {
    if (openTickSet.size) return openTickSet.has(time);
    const rowTime = toRowTime(time);
    const row = document.querySelector(`tr[data-row="${rowTime}"]`);
    if (!row || row.classList.contains('hidden')) return false;
    const slot = row.querySelector(`td[data-slot="${rowTime}"]`);
    return slot && !slot.classList.contains('text-gray-400');
};


const selectRange = (date, prof, start, end) => {
    clearSelection();
    const times = start === end ? [start] : nextTimes(start, end);
    for (const t of times) {
        if (!isOpen(t)) { alert('Horário fora do horário de funcionamento'); clearSelection(); return false; }
    }
    const finalEnd = start === end ? null : end;
    selection = { start, end: finalEnd, professional: prof, date };
    if (hiddenStart) hiddenStart.value = start;
    if (hiddenEnd) hiddenEnd.value = finalEnd || '';
    if (startInput) startInput.value = start;
    if (endInput) endInput.value = finalEnd || '';
    if (professionalInput) professionalInput.value = prof;
    if (dateInput) dateInput.value = date;
    renderSelection();
    return true;
};

const abrirModalAgendamento = ag => {

    const date = selection.date || '';

    if (selection.start && !selection.end) {
        selection.end = addMinutes(selection.start, slotMinutes);
    }
    if (startInput) startInput.value = selection.start || '';
    if (endInput) endInput.value = selection.end || '';
    if (hiddenStart) hiddenStart.value = startInput?.value || '';
    if (hiddenEnd) hiddenEnd.value = endInput?.value || '';

    if (professionalInput) {
        professionalInput.value = selection.professional || '';
    }
    if (dateInput) dateInput.value = date;

    if (summary) {
        const th = document.querySelector(`#schedule-table thead th[data-professional-id="${selection.professional}"]`);
        const profName = th ? th.textContent.trim() : '';
        summary.textContent = `${profName} - ${date}`;
    }

    if (scheduleModal) {
        scheduleModal.dataset.hora = selection.start || '';
        scheduleModal.dataset.date = date;
        const obs = document.getElementById('schedule-observacao');
        const statusSel = statusSelect || document.getElementById('schedule-status');
        if (ag) {
            document.getElementById('agendamento-id').value = ag.id || '';
            if (pacienteInput) pacienteInput.value = ag.paciente_id || '';
            if (selectedPatientName) selectedPatientName.textContent = ag.paciente || '';
            if (obs) obs.value = ag.observacao || '';
            if (statusSel) statusSel.value = ag.status || 'confirmado';
            if (step1 && step2) {
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
            }
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.dataset.action = 'update';
            }
        } else {
            if (patientSearch) patientSearch.value = '';
            if (patientResults) patientResults.innerHTML = '';
            if (notFoundMsg) notFoundMsg.classList.add('hidden');
            if (pacienteInput) pacienteInput.value = '';
            if (selectedPatientName) selectedPatientName.textContent = '';
            if (obs) obs.value = '';
            if (statusSel) statusSel.value = 'confirmado';
            if (step1 && step2) {
                step1.classList.remove('hidden');
                step2.classList.add('hidden');
            }
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.dataset.action = 'store';
            }
            initPatientSearch();
        }
        scheduleModal.classList.remove('hidden');
    }
};
window.abrirModalAgendamento = abrirModalAgendamento;

const abrirModalPaciente = () => {
    const modal = document.getElementById('paciente-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
    window.dispatchEvent(new CustomEvent('abrirModalPaciente'));
};
window.abrirModalPaciente = abrirModalPaciente;

const openScheduleModal = (prof, start, end, date, ag = null) => {
    if (existeConflitoNaoCancelado(prof, start, end, ag?.id)) {
        alert('Existe agendamento ativo nessa faixa de horário.');
        return;
    }
    if (!selectRange(date, prof, start, end)) return;
    abrirModalAgendamento(ag);
};

const openEditModal = id => {
    const appt = document.querySelector(`#schedule-table div[data-id="${id}"]`);
    if (!appt) return;
    const ag = {
        id: appt.dataset.id,
        inicio: appt.dataset.inicio,
        fim: appt.dataset.fim,
        observacao: appt.dataset.observacao || '',
        date: appt.dataset.date,
        profissional: appt.dataset.profissionalId,
        paciente: appt.querySelector('.font-bold, .font-semibold')?.textContent || '',
        status: appt.dataset.status || '',
    };
    openScheduleModal(ag.profissional, ag.inicio, ag.fim, ag.date, ag);
};
window.openEditModal = openEditModal;

function attachCellHandlers() {
    scheduleModal = document.getElementById('schedule-modal');
    startInput = document.getElementById('schedule-start');
    endInput = document.getElementById('schedule-end');
    pacienteInput = document.getElementById('schedule-paciente');
    professionalInput = document.getElementById('schedule-professional');
    dateInput = document.getElementById('schedule-date');
    summary = document.getElementById('schedule-summary');
    hiddenStart = document.getElementById('hora_inicio');
    hiddenEnd = document.getElementById('hora_fim');
    step1 = document.getElementById('step-1');
    step2 = document.getElementById('step-2');
    patientSearch = document.getElementById('patient-search');
    patientResults = document.getElementById('patient-results');
    selectedPatientName = document.getElementById('selected-patient-name');
    notFoundMsg = document.getElementById('patient-notfound');

    if (startInput) {
        startInput.addEventListener('change', e => {
            e.target.value = snapTime(e.target.value, 'start');
            selection.start = e.target.value;
            if (hiddenStart) hiddenStart.value = e.target.value;
            renderSelection();
        });
    }
    if (endInput) {
        endInput.addEventListener('change', e => {
            e.target.value = snapTime(e.target.value, 'end');
            selection.end = e.target.value;
            if (hiddenEnd) hiddenEnd.value = e.target.value;
            renderSelection();
        });
    }

    updateRowMinutes();

    if (handleMouseDown) document.removeEventListener('mousedown', handleMouseDown);
    if (handleMouseMove) document.removeEventListener('mousemove', handleMouseMove);
    if (handleDblClick) document.removeEventListener('dblclick', handleDblClick);
    if (handleClick) document.removeEventListener('click', handleClick);
    if (handleMouseUp) document.removeEventListener('mouseup', handleMouseUp);

    handleMouseDown = e => {
        const cell = e.target.closest('#schedule-table td[data-professional-id]');
        if (!cell || e.button !== 0 || (e.target.closest('div[data-id]') && !e.target.closest('.schedule-gutter')) || selection.start) return;
        const time = getEventTime(e, cell);
        const prof = cell.dataset.professionalId;
        const date = cell.dataset.date;
        if (!isOpen(time)) { alert('Horário fora do horário de funcionamento'); return; }
        e.preventDefault();
        dragging = true;
        suppressClick = true;
        selectRange(date, prof, time, time);
    };

    handleMouseMove = e => {
        if (!dragging) return;
        const cell = e.target.closest('#schedule-table td[data-professional-id]');
        if (!cell || cell.dataset.professionalId !== selection.professional || cell.dataset.date !== selection.date) return;
        const time = getEventTime(e, cell);
        if (toMinutes(time) < toMinutes(selection.start)) return;
        selectRange(selection.date, selection.professional, selection.start, time);
    };

    handleDblClick = e => {
        const appt = e.target.closest('div[data-id]');
        if (appt) { clearSelection(); openEditModal(appt.dataset.id); return; }
        const cell = e.target.closest('#schedule-table td[data-professional-id]');
        if (!cell) return;
        const prof = cell.dataset.professionalId;
        const date = cell.dataset.date;
        const start = getEventTime(e, cell);
        const end = addMinutes(start, slotMinutes);
        openScheduleModal(prof, start, end, date);
    };

    handleMouseUp = e => {
        if (!dragging) return;
        dragging = false;
        const end = selection.end || addMinutes(selection.start, slotMinutes);
        openScheduleModal(selection.professional, selection.start, end, selection.date);
        suppressClick = true;
    };

    handleClick = e => {
        if (suppressClick) { suppressClick = false; return; }
        if (!e.target.closest('#schedule-table') && (!scheduleModal || !scheduleModal.contains(e.target))) {
            clearSelection(true);
        }
    };

    document.addEventListener('mousedown', handleMouseDown);
    document.addEventListener('mousemove', handleMouseMove);
    document.addEventListener('dblclick', handleDblClick);
    document.addEventListener('mouseup', handleMouseUp);
    document.addEventListener('click', handleClick);
}

window.attachCellHandlers = attachCellHandlers;
document.addEventListener('DOMContentLoaded', attachCellHandlers);
document.addEventListener('schedule:rendered', attachCellHandlers);
document.addEventListener('DOMContentLoaded', positionAppointments);
document.addEventListener('schedule:rendered', positionAppointments);
window.getAgendaComponent = function () {
    const rootEl = document.getElementById('agenda-root');
    if (!rootEl || !window.Alpine) return null;
    if (typeof Alpine.$data === 'function') {
        try { return Alpine.$data(rootEl); } catch (_) {}
    }
    const raw = rootEl.__x;
    return raw?.$data || (typeof raw?.getUnobservedData === 'function' ? raw.getUnobservedData() : null);
};

document.addEventListener('agenda:changed', e => {
    try {
        const comp = window.getAgendaComponent();
        if (!comp) return;
        const date = e.detail?.date || comp.selectedDate;
        if (typeof comp.loadData === 'function') {
            comp.loadData(date);
        }
    } catch (err) {
        console.error('Erro ao recarregar agenda', err);
    }
});

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    if (window.flatpickr) {
        flatpickr('.datepicker', {
            altInput: true,
            altInputClass: 'w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none',
            altFormat: 'd/m/Y',
            dateFormat: 'Y-m-d',
            locale: 'pt',
            allowInput: false
        });
    }
    const cnpjInput = document.querySelector('input[name="cnpj"]');
    if (cnpjInput) {
        cnpjInput.addEventListener('input', e => {
            let v = e.target.value.replace(/\D/g, '').slice(0, 14);
            v = v.replace(/^(\d{2})(\d)/, '$1.$2');
            v = v.replace(/^(\d{2}\.\d{3})(\d)/, '$1.$2');
            v = v.replace(/^(\d{2}\.\d{3}\.\d{3})(\d)/, '$1/$2');
            v = v.replace(/^(\d{2}\.\d{3}\.\d{3}\/\d{4})(\d)/, '$1-$2');
            e.target.value = v;
        });
    }

    document.querySelectorAll('input[name="cpf"], input[name="responsavel_cpf"]').forEach(input => {
        input.addEventListener('input', e => {
            let v = e.target.value.replace(/\D/g, '').slice(0, 11);
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
            v = v.replace(/(\d{3})\.(\d{3})\.(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
            e.target.value = v;
        });
    });

    document.querySelectorAll('[data-cpf-cnpj-group]').forEach(group => {
        const input = group.querySelector('input[data-role="cpf_cnpj"]');
        if (!input) return;
        const radios = group.querySelectorAll('input[type="radio"]');
        const indicator = group.querySelector('[data-required-indicator]');

        const form = group.closest('form');
        const bank = form?.querySelector('input[name="conta[nome_banco]"]');
        const typeField = form?.querySelector('select[name="conta[tipo]"]');
        const agency = form?.querySelector('input[name="conta[agencia]"]');

        const setRequired = () => {
            const required = bank?.value.trim() && typeField?.value.trim() && agency?.value.trim();
            radios.forEach(r => r.required = !!required);
            input.required = !!required;
            if (indicator) indicator.style.display = required ? '' : 'none';
        };

        [bank, typeField, agency].forEach(el => el?.addEventListener('input', setRequired));
        setRequired();

        const mask = () => {
            const type = group.querySelector('input[type="radio"]:checked')?.value || 'cpf';
            let v = input.value.replace(/\D/g, '');
            if (type === 'cnpj') {
                v = v.slice(0, 14);
                v = v.replace(/^(\d{2})(\d)/, '$1.$2');
                v = v.replace(/^(\d{2}\.\d{3})(\d)/, '$1.$2');
                v = v.replace(/^(\d{2}\.\d{3}\.\d{3})(\d)/, '$1/$2');
                v = v.replace(/^(\d{2}\.\d{3}\.\d{3}\/\d{4})(\d)/, '$1-$2');
            } else {
                v = v.slice(0, 11);
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
                v = v.replace(/(\d{3})\.(\d{3})\.(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
            }
            input.value = v;
        };
        input.addEventListener('input', mask);
        radios.forEach(r => r.addEventListener('change', mask));
        mask();
    });

    document.querySelectorAll('input[name="telefone"], input[name="phone"]').forEach(input => {
        input.addEventListener('input', e => {
            let v = e.target.value.replace(/\D/g, '').slice(0, 11);
            if (v.length > 10) {
                v = v.replace(/(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
            } else if (v.length > 6) {
                v = v.replace(/(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
            } else if (v.length > 2) {
                v = v.replace(/(\d{2})(\d*)/, '($1) $2');
            } else {
                v = v.replace(/(\d*)/, '($1');
            }
            e.target.value = v.trim();
        });
    });

    document.querySelectorAll('input[name="cep"]').forEach(input => {
        input.addEventListener('input', e => {
            let v = e.target.value.replace(/\D/g, '').slice(0, 8);
            v = v.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = v;
        });

        input.addEventListener('blur', e => {
            const cep = e.target.value.replace(/\D/g, '');
            if (cep.length !== 8) return;

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(resp => resp.json())
                .then(data => {
                    if (data.erro) return;
                    const form = e.target.closest('form');
                    if (!form) return;
                    const logradouro = form.querySelector('input[name="logradouro"]');
                    if (logradouro && data.logradouro) logradouro.value = data.logradouro;
                    const bairro = form.querySelector('input[name="bairro"]');
                    if (bairro && data.bairro) bairro.value = data.bairro;
                    const cidade = form.querySelector('input[name="cidade"]');
                    if (cidade && data.localidade) cidade.value = data.localidade;
                    const estado = form.querySelector('input[name="estado"]');
                    if (estado && data.uf) estado.value = data.uf;
                })
                .catch(() => {});
        });
    });

    document.querySelectorAll('input.currency-brl').forEach(input => {
        const form = input.closest('form');

        const toNumber = val => {
            const cleaned = val.replace(/[^\d.,-]/g, '');
            return cleaned.includes(',')
                ? cleaned.replace(/\./g, '').replace(',', '.')
                : cleaned;
        };
        const format = val => {
            const num = parseFloat(toNumber(val));
            return isNaN(num) ? '' : num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        };

        if (input.value) {
            input.value = format(input.value);
        }

        // Format value only after the user finishes typing to avoid cursor issues
        input.addEventListener('blur', e => {
            e.target.value = format(e.target.value);
        });

        if (form) {
            form.addEventListener('submit', () => {
                input.value = toNumber(input.value);
            });
        }
    });

    document.querySelectorAll('.work-schedule').forEach(section => {
        section.querySelectorAll('tr[data-dia]').forEach(row => {
            const inicio = row.querySelector('input[data-role="inicio"]');
            const fim = row.querySelector('input[data-role="fim"]');
            if (!inicio || !fim) return;
            const min = inicio.getAttribute('min');
            const max = inicio.getAttribute('max');
            const validate = () => {
                inicio.setCustomValidity('');
                fim.setCustomValidity('');

                if (inicio.value && min && toMinutes(inicio.value) < toMinutes(min)) {
                    inicio.setCustomValidity('Início antes da abertura');
                }
                if (fim.value && max && toMinutes(fim.value) > toMinutes(max)) {
                    fim.setCustomValidity('Fim após o fechamento');
                }
                if (inicio.value && fim.value && toMinutes(fim.value) <= toMinutes(inicio.value)) {
                    fim.setCustomValidity('Fim deve ser após o início');
                }

                inicio.reportValidity();
                fim.reportValidity();
            };

            inicio.addEventListener('input', validate);
            fim.addEventListener('input', validate);
            validate();
        });
    });


    scheduleModal = document.getElementById('schedule-modal');
    if (scheduleModal) {
        cancel = document.getElementById('schedule-cancel');
        startInput = document.getElementById('schedule-start');
        endInput = document.getElementById('schedule-end');
        saveBtn = document.getElementById('schedule-save');
        professionalInput = document.getElementById('schedule-professional');
        dateInput = document.getElementById('schedule-date');
        summary = document.getElementById('schedule-summary');
        hiddenStart = document.getElementById('hora_inicio');
        hiddenEnd = document.getElementById('hora_fim');
        statusSelect = document.getElementById('schedule-status');

        if (cancel) {
            cancel.addEventListener('click', () => {
                scheduleModal.classList.add('hidden');
                clearSelection();
                if (saveBtn) saveBtn.disabled = true;
            });
        }
        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                const action = saveBtn.dataset.action || 'store';
                if (action === 'store' && !pacienteInput?.value) {
                    alert('Selecione um paciente');
                    return;
                }
                if (!selection.professional || !startInput.value || !endInput.value) {
                    alert('Preencha todos os campos do agendamento');
                    return;
                }

                const date = scheduleModal.dataset.date;

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let url = saveBtn.dataset.storeUrl;
                let method = 'POST';
                const body = {
                    data: date,
                    hora_inicio: startInput.value,
                    hora_fim: endInput.value,
                    observacao: document.getElementById('schedule-observacao')?.value || '',
                    status: statusSelect?.value || 'confirmado'
                };
                if (action === 'store') {
                    body.paciente_id = pacienteInput.value;
                    body.profissional_id = selection.professional;
                } else {
                    const id = document.getElementById('agendamento-id').value;
                    url = `${saveBtn.dataset.updateUrl}/${id}`;
                    method = 'PUT';
                }
                fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify(body),
                })
                    .then(async response => {
                        if (!response.ok) {
                            let data = {};
                            try { data = await response.json(); } catch (_) {}
                            alert(data.message || 'Erro ao salvar agendamento');
                            if (data.redirect) { window.location.href = data.redirect; }
                            return;
                        }
                        const success = document.getElementById('schedule-success');
                        if (success) {
                            const span = success.querySelector('span');
                            if (span) {
                                span.textContent = 'Agendamento salvo com sucesso';
                            }
                            if (success.__x) {
                                success.__x.$data.show = true;
                                setTimeout(() => {
                                    success.__x.$data.show = false;
                                }, 3000);
                            }
                        }

                        document.dispatchEvent(new CustomEvent('agenda:changed', { detail: { date } }));
                        scheduleModal.classList.add('hidden');
                        clearSelection();

                    })
                    .catch(() => alert('Erro de rede ao salvar agendamento'));
            });
        }
        scheduleModal.addEventListener('click', e => {
            if (e.target === scheduleModal) {
                scheduleModal.classList.add('hidden');
                clearSelection();
                if (saveBtn) saveBtn.disabled = true;
            }
        });
    }

    const datePickerModal = document.getElementById('date-picker-modal');
    if (datePickerModal) {
        const prev = document.getElementById('dp-prev');
        const next = document.getElementById('dp-next');
        const label = document.getElementById('dp-month-label');
        const calendar = document.getElementById('dp-calendar');
        let current = new Date();
        let callback = null;

        function render(selected) {
            label.textContent = current.toLocaleString('pt-BR', { month: 'long', year: 'numeric' });
            calendar.innerHTML = '<table class="w-full border text-center text-sm"><thead><tr></tr></thead><tbody></tbody></table>';
            const headRow = calendar.querySelector('thead tr');
            ['Seg','Ter','Qua','Qui','Sex','Sab','Dom'].forEach(d => {
                const th = document.createElement('th');
                th.className = 'border px-1 py-1';
                th.textContent = d;
                headRow.appendChild(th);
            });

            const tbody = calendar.querySelector('tbody');
            const first = new Date(current.getFullYear(), current.getMonth(), 1);
            const start = new Date(first);
            start.setDate(start.getDate() - ((start.getDay()+6)%7));
            const end = new Date(current.getFullYear(), current.getMonth()+1, 0);
            const last = new Date(end);
            last.setDate(last.getDate() + (7 - ((end.getDay()+6)%7) -1));

            for (let d = new Date(start); d <= last; d.setDate(d.getDate()+1)) {
                if (d.getDay() === 1) tbody.appendChild(document.createElement('tr'));
                const row = tbody.lastElementChild;
                const td = document.createElement('td');
                td.className = 'border px-1 py-1';
                const btn = document.createElement('button');
                btn.type = 'button';
                const iso = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
                btn.dataset.date = iso;
                btn.textContent = d.getDate();
                btn.className = 'w-full rounded ' + (d.getMonth() === current.getMonth() ? '' : 'text-gray-400');
                if (selected === iso) {
                    btn.classList.add('bg-emerald-400', 'text-white');
                }
                btn.addEventListener('click', () => {
                    datePickerModal.classList.add('hidden');
                    if (typeof callback === 'function') callback(iso);
                });
                td.appendChild(btn);
                row.appendChild(td);
            }
        }

        window.showDatePicker = function(selected, cb) {
            callback = cb;
            current = selected ? new Date(selected + 'T00:00:00') : new Date();
            current.setDate(1);
            datePickerModal.classList.remove('hidden');
            render(selected);
        };

        prev.addEventListener('click', () => { current.setMonth(current.getMonth() - 1); render(); });
        next.addEventListener('click', () => { current.setMonth(current.getMonth() + 1); render(); });
        datePickerModal.addEventListener('click', e => {
            if (e.target === datePickerModal) {
                datePickerModal.classList.add('hidden');
            }
        });
    }



    const charts = [
        {
            id: 'consultas-do-dia',
            type: 'bar',
            labels: ['Agendadas', 'Confirmadas', 'Canceladas', 'Realizadas'],
            data: [20, 15, 3, 12],
            color: '#60a5fa'
        },
        {
            id: 'ocupacao-semanal',
            type: 'line',
            labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            data: [70, 65, 75, 80, 78, 60, 50],
            color: '#34d399'
        },
        {
            id: 'taxa-de-cancelamentos-e-faltas-30-dias',
            type: 'line',
            labels: ['1-5', '6-10', '11-15', '16-20', '21-25', '26-30'],
            data: [2, 1, 3, 1, 2, 0],
            color: '#f87171'
        },
        {
            id: 'principais-procedimentos',
            type: 'bar',
            labels: ['Limpeza', 'Restauração', 'Extração', 'Clareamento', 'Canal'],
            data: [15, 10, 5, 8, 7],
            color: '#818cf8'
        }
    ];

    charts.forEach(cfg => {
        const canvas = document.getElementById(cfg.id);
        if (!canvas || typeof Chart === 'undefined') return;

        new Chart(canvas, {
            type: cfg.type,
            data: {
                labels: cfg.labels,
                datasets: [
                    {
                        data: cfg.data,
                        backgroundColor: cfg.type === 'bar' ? cfg.color : 'rgba(0,0,0,0)',
                        borderColor: cfg.color,
                        borderWidth: 2,
                        fill: cfg.type !== 'bar',
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: {
                        ticks: { color: '#374151', font: { size: 12 } },
                        grid: { display: false },
                    },
                    y: {
                        ticks: { display: false },
                        grid: {
                            color: '#E5E7EB',
                            drawBorder: false,
                        },
                    },
                },
            },
        });
    });
});
export { nextTimes, selectRange, isOpen, openTickSet };
