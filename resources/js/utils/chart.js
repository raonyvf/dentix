export default function createChart(id, config) {
    const el = typeof id === 'string' ? document.getElementById(id) : id;
    if (!el || typeof Chart === 'undefined') return null;

    const existing = Chart.getChart(el);
    if (existing) existing.destroy();

    const chart = new Chart(el, config);

    document.addEventListener('turbo:before-cache', () => {
        chart.destroy();
    }, { once: true });

    return chart;
}
