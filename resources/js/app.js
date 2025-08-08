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
            this.fetchProfessionals(this.selectedDate).then(profs => {
                if (profs && profs.length) {
                    this.fetchHorarios(this.selectedDate);
                }
            });
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
            this.fetchProfessionals(date).then(profs => {
                if (profs && profs.length) {
                    this.fetchHorarios(date);
                }
            });
        },
        fetchHorarios(date) {
            if (!this.horariosUrl) return;
            fetch(`${this.horariosUrl}?date=${date}`)
                .then(r => r.json())
                .then(data => {
                    window.updateScheduleTable(
                        data.closed ? [] : data.horarios,
                        data.start,
                        data.end,
                        data.closed
                    );
                    const dbg = document.getElementById('clinic-hours-debug');
                    if (dbg) {
                        if (data.intervals && data.intervals.length) {
                            const list = data.intervals.map(i => `${i.inicio}-${i.fim}`).join(', ');
                            dbg.textContent = `Horários de funcionamento: ${list}`;
                        } else {
                            dbg.textContent = '';
                        }
                    }
                });
        },
        fetchProfessionals(date) {
            if (!this.professionalsUrl) return Promise.resolve([]);
            return fetch(`${this.professionalsUrl}?date=${date}`)
                .then(r => r.json())
                .then(data => {
                    window.renderSchedule(data.professionals, data.agenda, this.baseTimes);
                    return data.professionals;
                });
        },
    };
}

window.updateScheduleTable = function(openTimes, start, end, closed) {
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
    document.dispatchEvent(new Event('schedule:rendered'));
};

window.renderSchedule = function (professionals, agenda, baseTimes) {
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
                    `<button class="px-4 py-2 rounded border text-sm whitespace-nowrap bg-white text-gray-700" data-professional="${p.id}">${p.name}</button>`
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
                    `<th class="p-2 bg-gray-50 text-left whitespace-nowrap border-l" data-professional="${p.id}">${p.name}</th>`
                );
            });
        }
        if (tbody) {
            tbody.innerHTML = '';
            baseTimes.forEach(hora => {
                let row = `<tr class="border-t" data-row="${hora}"><td class="bg-gray-50 w-24 min-w-[6rem] h-16 align-middle" data-slot="${hora}" data-hora="${hora}"><div class="h-full flex items-center justify-end px-2 text-xs text-gray-500 whitespace-nowrap">${hora}</div></td>`;
                professionals.forEach(p => {
                    row += `<td class="h-16 cursor-pointer border-l" data-professional="${p.id}" data-time="${hora}" data-hora="${hora}">`;
                    const item = agenda[p.id] && agenda[p.id][hora];
                    if (item) {
                        let color = 'bg-gray-100 text-gray-700';
                        if (item.status === 'confirmado') color = 'bg-green-100 text-green-700';
                        else if (item.status === 'cancelado') color = 'bg-red-100 text-red-700';
                        row += `<div class="rounded p-2 text-xs ${color}"><div class="font-semibold">${item.paciente}</div><div>${item.tipo}</div><div class="text-[10px]">${item.contato}</div></div>`;
                    }
                    row += '</td>';
                });
                row += '</tr>';
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }
        table.classList.remove('hidden');
    }

    if (emptyMsg) emptyMsg.classList.add('hidden');
    document.dispatchEvent(new Event('schedule:rendered'));
};

let scheduleModal, cancel, startInput, endInput, saveBtn, pacienteInput, pacienteList, pacienteIdInput, professionalInput, dateInput, summary, hiddenStart, hiddenEnd;
let selection = { start: null, end: null, professional: null };
let dragging = false;
let suppressClick = false;
let handleMouseDown, handleMouseMove, handleDblClick, handleClick, handleMouseUp;

const toMinutes = t => {
    if (!t) return null;
    const [h, m] = t.split(':');
    return parseInt(h, 10) * 60 + parseInt(m, 10);
};

const nextTimes = (start, end) => {
    const times = [];
    let cur = toMinutes(start);
    const final = toMinutes(end);
    while (cur < final) {
        const h = String(Math.floor(cur / 60)).padStart(2, '0');
        const m = String(cur % 60).padStart(2, '0');
        times.push(`${h}:${m}`);
        cur += 30;
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

const clearSelection = (preserveProfessional = false) => {
    document.querySelectorAll('#schedule-table td[data-professional].selected')
        .forEach(c => c.classList.remove('selected', 'bg-blue-100'));
    selection = {
        start: null,
        end: null,
        professional: preserveProfessional ? selection.professional : null,
    };
    if (hiddenStart) hiddenStart.value = '';
    if (hiddenEnd) hiddenEnd.value = '';
    if (!preserveProfessional && professionalInput) professionalInput.value = '';
    if (dateInput && !preserveProfessional) dateInput.value = '';
    if (!preserveProfessional && summary) summary.textContent = '';
};

const isOpen = time => {
    const row = document.querySelector(`tr[data-row="${time}"]`);
    if (!row || row.classList.contains('hidden')) return false;
    const slot = row.querySelector(`td[data-slot="${time}"]`);
    return slot && !slot.classList.contains('text-gray-400');
};

const selectRange = (prof, start, end) => {
    clearSelection();
    const times = start === end ? [start] : nextTimes(start, end);
    for (const t of times) {
        if (!isOpen(t)) { alert('Horário fora do horário de funcionamento'); clearSelection(); return false; }
        const cell = document.querySelector(`#schedule-table td[data-professional="${prof}"][data-time="${t}"]`);
        cell?.classList.add('selected', 'bg-blue-100');
    }
    const finalEnd = start === end ? null : end;
    selection = { start, end: finalEnd, professional: prof };
    if (hiddenStart) hiddenStart.value = start;
    if (hiddenEnd) hiddenEnd.value = finalEnd || '';
    if (startInput) startInput.value = start;
    if (endInput) endInput.value = finalEnd || '';
    if (professionalInput) professionalInput.value = prof;
    return true;
};

const abrirModalAgendamento = () => {
    const root = scheduleModal?.closest('[x-data]');
    const date = root?.__x?.$data?.selectedDate || '';

    if (selection.start && !selection.end) {
        selection.end = addMinutes(selection.start, 30);
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
        const th = document.querySelector(`#schedule-table thead th[data-professional="${selection.professional}"]`);
        const profName = th ? th.textContent.trim() : '';
        summary.textContent = `${profName} - ${date}`;
    }

    if (scheduleModal) {
        scheduleModal.dataset.time = selection.start || '';
        scheduleModal.classList.remove('hidden');
    }
};
window.abrirModalAgendamento = abrirModalAgendamento;

const openScheduleModal = (prof, start, end) => {
    if (!selectRange(prof, start, end)) return;
    abrirModalAgendamento();
};

function attachCellHandlers() {
    scheduleModal = document.getElementById('schedule-modal');
    startInput = document.getElementById('schedule-start');
    endInput = document.getElementById('schedule-end');
    pacienteInput = document.getElementById('schedule-paciente');
    pacienteList = document.getElementById('schedule-paciente-list');
    pacienteIdInput = document.getElementById('schedule-paciente-id');
    professionalInput = document.getElementById('schedule-professional');
    dateInput = document.getElementById('schedule-date');
    summary = document.getElementById('schedule-summary');
    hiddenStart = document.getElementById('hora_inicio');
    hiddenEnd = document.getElementById('hora_fim');

    if (handleMouseDown) document.removeEventListener('mousedown', handleMouseDown);
    if (handleMouseMove) document.removeEventListener('mousemove', handleMouseMove);
    if (handleDblClick) document.removeEventListener('dblclick', handleDblClick);
    if (handleClick) document.removeEventListener('click', handleClick);
    if (handleMouseUp) document.removeEventListener('mouseup', handleMouseUp);

    handleMouseDown = e => {
        // Allow multiple mousedown events; double clicks are handled in
        // handleDblClick so we don't filter them here.
        const cell = e.target.closest('#schedule-table td[data-professional]');
        if (!cell || e.button !== 0 || selection.start) return;
        const time = cell.dataset.time;
        const prof = cell.dataset.professional;
        if (!isOpen(time)) { alert('Horário fora do horário de funcionamento'); return; }
        e.preventDefault();
        dragging = true;
        suppressClick = true;
        selectRange(prof, time, time);
    };

    handleMouseMove = e => {
        if (!dragging) return;
        const cell = e.target.closest('#schedule-table td[data-professional]');
        if (!cell || cell.dataset.professional !== selection.professional) return;
        const time = cell.dataset.time;
        if (toMinutes(time) < toMinutes(selection.start)) return;
        selectRange(selection.professional, selection.start, time);
    };

    handleDblClick = e => {
        const cell = e.target.closest('#schedule-table td[data-professional]');
        if (!cell) return;
        // Clear any pending selection so the modal is the only action taken
        // on a double click.
        clearSelection();
        const start = cell.dataset.time;
        const prof = cell.dataset.professional;
        const end = addMinutes(start, 30);
        openScheduleModal(prof, start, end);
    };

    handleClick = e => {
        if (suppressClick || e.detail > 1) {
            if (selection.start && !e.target.closest('#schedule-table')) {
                if (!scheduleModal || !scheduleModal.contains(e.target)) {
                    clearSelection(true);
                }
            }
            return;
        }
        const cell = e.target.closest('#schedule-table td[data-professional]');
        if (!cell) {
            if (selection.start && (!scheduleModal || !scheduleModal.contains(e.target))) {
                clearSelection(true);
            }
            return;
        }
        const time = cell.dataset.time;
        const prof = cell.dataset.professional;

        if (!selection.start) {
            if (!isOpen(time)) { alert('Horário fora do horário de funcionamento'); return; }
            selection.start = time;
            selection.professional = prof;
            cell.classList.add('selected', 'bg-blue-100');
            if (hiddenStart) hiddenStart.value = time;
            return;
        }

        if (selection.start && selection.end == null) {
            if (prof !== selection.professional || toMinutes(time) < toMinutes(selection.start)) {
                clearSelection();
                if (!isOpen(time)) { alert('Horário fora do horário de funcionamento'); return; }
                selection.start = time;
                selection.professional = prof;
                cell.classList.add('selected', 'bg-blue-100');
                if (hiddenStart) hiddenStart.value = time;
                return;
            }
            selection.end = time;
            openScheduleModal(prof, selection.start, selection.end);
            return;
        }

        if (selection.start && selection.end != null) {
            clearSelection();
            if (!isOpen(time)) { alert('Horário fora do horário de funcionamento'); return; }
            selection.start = time;
            selection.professional = prof;
            cell.classList.add('selected', 'bg-blue-100');
            if (hiddenStart) hiddenStart.value = time;
        }
    };

    handleMouseUp = e => {
        if (!dragging) return;
        dragging = false;

        if (selection.start && selection.end && e.target.closest('#schedule-table')) {
            openScheduleModal(selection.professional, selection.start, selection.end);
        } else if (!selection.end) {
            // keep partial selection
        } else {
            clearSelection();
        }

        setTimeout(() => { suppressClick = false; }, 0);
    };

    document.addEventListener('mousedown', handleMouseDown);
    document.addEventListener('mousemove', handleMouseMove);
    document.addEventListener('dblclick', handleDblClick);
    document.addEventListener('click', handleClick);
    document.addEventListener('mouseup', handleMouseUp);
}

window.attachCellHandlers = attachCellHandlers;
document.addEventListener('DOMContentLoaded', attachCellHandlers);
document.addEventListener('schedule:rendered', attachCellHandlers);

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
        pacienteInput = document.getElementById('schedule-paciente');
        pacienteList = document.getElementById('schedule-paciente-list');
        pacienteIdInput = document.getElementById('schedule-paciente-id');
        professionalInput = document.getElementById('schedule-professional');
        dateInput = document.getElementById('schedule-date');
        summary = document.getElementById('schedule-summary');
        hiddenStart = document.getElementById('hora_inicio');
        hiddenEnd = document.getElementById('hora_fim');
        let searchTimeout;

        if (pacienteInput && pacienteList) {
            pacienteInput.addEventListener('input', e => {
                clearTimeout(searchTimeout);
                const term = e.target.value.trim();
                if (term.length < 2) { pacienteList.innerHTML = ''; return; }
                searchTimeout = setTimeout(() => {
                    const url = pacienteInput.dataset.searchUrl;
                    fetch(`${url}?q=${encodeURIComponent(term)}`)
                        .then(r => r.json())
                        .then(data => {
                            pacienteList.innerHTML = data.map(n => `<option data-id="${n.id}" value="${n.name}"></option>`).join('');
                        });
                }, 300);
            });
            pacienteInput.addEventListener('change', () => {
                const option = Array.from(pacienteList.options).find(o => o.value === pacienteInput.value);
                if (pacienteIdInput) pacienteIdInput.value = option?.dataset.id || '';
            });
        }

        if (cancel) {
            cancel.addEventListener('click', () => {
                scheduleModal.classList.add('hidden');
                clearSelection();
            });
        }
        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                if (!pacienteIdInput?.value) {
                    alert('Selecione um paciente');
                    return;
                }
                if (!selection.professional || !startInput.value || !endInput.value) {
                    alert('Preencha todos os campos do agendamento');
                    return;
                }

                const root = document.querySelector('[x-data]');
                const date = root?.__x?.$data?.selectedDate;
                const url = saveBtn.dataset.storeUrl;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        data: date,
                        hora_inicio: startInput.value,
                        hora_fim: endInput.value,
                        paciente_id: pacienteIdInput.value,
                        observacao: document.getElementById('schedule-observacao')?.value || '',
                        profissional_id: selection.professional,
                    }),
                })
                    .then(async response => {
                        if (!response.ok) {
                            let data = {};
                            try { data = await response.json(); } catch (_) {}
                            alert(data.message || 'Erro ao salvar agendamento');
                            if (data.redirect) { window.location.href = data.redirect; }
                            return;
                        }
                        window.location.reload();
                    })
                    .catch(() => alert('Erro de rede ao salvar agendamento'));

                scheduleModal.classList.add('hidden');
                clearSelection();
            });
        }
        scheduleModal.addEventListener('click', e => {
            if (e.target === scheduleModal) {
                scheduleModal.classList.add('hidden');
                clearSelection();
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