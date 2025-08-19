import createChart from '../utils/chart';

export default function initFinanceiro() {
    const el = document.getElementById('formas-chart');
    if (!el) return;

    let formas = window.formasPagamento;
    if (!formas) {
        const dataAttr = el.dataset.formas;
        if (dataAttr) {
            try {
                formas = JSON.parse(dataAttr);
            } catch (e) {
                console.error('Invalid formasPagamento data', e);
                formas = null;
            }
        }
    }
    if (!Array.isArray(formas) || !formas.length) return;

    const labels = formas.map(f => f.label);
    const data = formas.map(f => f.percent);

    createChart('formas-chart', {
        type: 'doughnut',
        data: {
            labels,
            datasets: [
                {
                    data,
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#6366f1'],
                },
            ],
        },
        options: { responsive: true, maintainAspectRatio: false },
    });
}
