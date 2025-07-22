import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
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

    const modal = document.getElementById('schedule-modal');
    const patientInput = document.getElementById('schedule-patient');
    const patientList = document.getElementById('schedule-patient-list');
    const cancelBtn = document.getElementById('schedule-cancel');
    const saveBtn = document.getElementById('schedule-save');
    let targetCell = null;

    if (patientInput) {
        patientInput.addEventListener('input', () => {
            const q = patientInput.value.trim();
            if (q.length < 2) {
                patientList.innerHTML = '';
                return;
            }
            fetch(`/admin/pacientes/buscar?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    patientList.innerHTML = '';
                    data.forEach(name => {
                        const opt = document.createElement('option');
                        opt.value = name;
                        patientList.appendChild(opt);
                    });
                });
        });
    }

    if (modal) {
        document.querySelectorAll('td[data-professional]').forEach(td => {
            td.addEventListener('click', () => {
                targetCell = td;
                patientInput.value = '';
                modal.classList.remove('hidden');
            });
        });

        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        saveBtn.addEventListener('click', () => {
            const name = patientInput.value;
            if (!name || !targetCell) return;
            targetCell.innerHTML =
                `<div class="rounded p-2 text-xs bg-green-100 text-green-700"><div class="font-semibold">${name}</div><div>Consulta</div></div>`;
            modal.classList.add('hidden');
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

// expose agendaCalendar to Alpine in Blade templates
window.agendaCalendar = function agendaCalendar() {
    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    const week = ['SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB', 'DOM'];
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let start = getMonday(new Date());

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

    function buildDays() {
        const arr = [];
        for (let i = 0; i < 7; i++) {
            const d = new Date(start);
            d.setDate(start.getDate() + i);
            let classes = 'flex flex-col items-center p-2 rounded cursor-pointer text-xs flex-1 text-center';
            if (isToday(d)) {
                classes += ' bg-black text-white';
            } else if (d < today) {
                classes += ' text-gray-400';
            } else {
                classes += ' text-gray-700';
            }
            arr.push({
                date: d.toISOString().slice(0, 10),
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
        init() {
            this.days = buildDays();
        },
        prevWeek() {
            start.setDate(start.getDate() - 7);
            this.days = buildDays();
        },
        nextWeek() {
            start.setDate(start.getDate() + 7);
            this.days = buildDays();
        },
        openDatePicker() {
            this.$refs.picker.showPicker();
        },
        onDateSelected(val) {
            const d = new Date(val);
            if (!isNaN(d)) {
                start = getMonday(d);
                this.days = buildDays();
            }
        },
    };
}

