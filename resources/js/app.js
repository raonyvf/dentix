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
            this.selectedDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            this.days = buildDays(this.selectedDate);
            this.fetchHorarios(this.selectedDate);
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
            this.fetchHorarios(date);
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
};


Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    if (window.flatpickr) {
        flatpickr('.datepicker', {
            altInput: true,
            altInputClass: 'w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none',
            altFormat: 'd/m/Y',
            dateFormat: 'Y-m-d',
            locale: 'pt',
            allowInput: true
        });

        document.querySelectorAll('.flatpickr-input:not([type="hidden"])').forEach(input => {
            input.addEventListener('input', e => {
                const digits = e.target.value.replace(/\D/g, '').slice(0, 8);
                let v = '';
                if (digits.length > 0) {
                    v = digits.slice(0, 2);
                }
                if (digits.length >= 3) {
                    v += '/' + digits.slice(2, 4);
                } else if (digits.length > 2) {
                    v += '/' + digits.slice(2);
                }
                if (digits.length >= 5) {
                    v += '/' + digits.slice(4, 8);
                }
                e.target.value = v;

                const fp = e.target._flatpickr;
                if (fp) {
                    if (digits.length === 8) {
                        fp.setDate(v, false, 'd/m/Y');
                    } else if (digits.length === 0) {
                        fp.clear();
                    }
                }
            });
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

        const toNumber = val => val.replace(/[^0-9,]/g, '').replace(',', '.');
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

    const toMinutes = t => {
        if (!t) return null;
        const [h, m] = t.split(':');
        return parseInt(h, 10) * 60 + parseInt(m, 10);
    };

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


    const scheduleModal = document.getElementById('schedule-modal');
    if (scheduleModal) {
        const cancel = document.getElementById('schedule-cancel');
        const startInput = document.getElementById('schedule-start');
        const endInput = document.getElementById('schedule-end');
        const saveBtn = document.getElementById('schedule-save');
        const pacienteInput = document.getElementById('schedule-paciente');
        const pacienteList = document.getElementById('schedule-paciente-list');
        const pacienteIdInput = document.getElementById('schedule-paciente-id');
        let searchTimeout;
        const hiddenStart = document.getElementById('hora_inicio');
        const hiddenEnd = document.getElementById('hora_fim');
        let selection = { start: null, end: null, professional: null };

        const clearSelection = () => {
            document.querySelectorAll('#schedule-table td[data-professional].selected')
                .forEach(c => c.classList.remove('selected', 'bg-blue-100'));
            selection = { start: null, end: null, professional: null };
            if (hiddenStart) hiddenStart.value = '';
            if (hiddenEnd) hiddenEnd.value = '';
        };

        const isOpen = time => {
            const row = document.querySelector(`tr[data-row="${time}"]`);
            if (!row || row.classList.contains('hidden')) return false;
            const slot = row.querySelector(`td[data-slot="${time}"]`);
            return slot && !slot.classList.contains('text-gray-400');
        };

        const nextTimes = (start, end) => {
            const times = [];
            let cur = toMinutes(start);
            const final = toMinutes(end);
            while (cur <= final) {
                const h = String(Math.floor(cur / 60)).padStart(2, '0');
                const m = String(cur % 60).padStart(2, '0');
                times.push(`${h}:${m}`);
                cur += 30;
            }
            return times;
        };

        document.querySelectorAll('#schedule-table td[data-professional]').forEach(td => {
            td.addEventListener('click', () => {
                const time = td.dataset.time;
                const prof = td.dataset.professional;

                if (!selection.start) {
                    if (!isOpen(time)) { alert('Horário fora do horário de funcionamento'); return; }
                    selection.start = time;
                    selection.professional = prof;
                    td.classList.add('selected', 'bg-blue-100');
                    if (hiddenStart) hiddenStart.value = time;
                    return;
                }

                if (selection.start && !selection.end) {
                    if (prof !== selection.professional || toMinutes(time) < toMinutes(selection.start)) {
                        clearSelection();
                        if (!isOpen(time)) { alert('Horário fora do horário de funcionamento'); return; }
                        selection.start = time;
                        selection.professional = prof;
                        td.classList.add('selected', 'bg-blue-100');
                        if (hiddenStart) hiddenStart.value = time;
                        return;
                    }
                    const times = nextTimes(selection.start, time);
                    for (const t of times) {
                        if (!isOpen(t)) { alert('Horário fora do horário de funcionamento'); clearSelection(); return; }
                        const cell = document.querySelector(`#schedule-table td[data-professional="${prof}"][data-time="${t}"]`);
                        cell?.classList.add('selected', 'bg-blue-100');
                    }
                    selection.end = time;
                    if (hiddenEnd) hiddenEnd.value = time;
                    if (startInput) startInput.value = selection.start;
                    if (endInput) endInput.value = selection.end;
                    scheduleModal.dataset.time = selection.start;
                    scheduleModal.classList.remove('hidden');
                    return;
                }

                if (selection.start && selection.end) {
                    clearSelection();
                    if (!isOpen(time)) { alert('Horário fora do horário de funcionamento'); return; }
                    selection.start = time;
                    selection.professional = prof;
                    td.classList.add('selected', 'bg-blue-100');
                    if (hiddenStart) hiddenStart.value = time;
                }
            });
        });

        document.addEventListener('click', e => {
            if (selection.start && !e.target.closest('#schedule-table')) {
                clearSelection();
            }
        });

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
                const iso = d.toISOString().slice(0,10);
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