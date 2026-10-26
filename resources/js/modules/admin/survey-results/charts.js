import Chart from 'chart.js/auto';

let statusChart = null;
let priorityChart = null;

export function renderSurveyCharts(summary) {

    const sections = summary.sections ?? [];

    const totalQuestions = sections.reduce(
        (total, section) => total + section.questions.length,
        0
    );

    document.getElementById('infoRespondents').textContent =
        summary.total_respondents ?? 0;

    document.getElementById('infoSections').textContent =
        sections.length;

    document.getElementById('infoQuestions').textContent =
        totalQuestions;

    // Grafik distribusi status responden
    const counts = summary.status_counts ?? {};

    if (statusChart) {
        statusChart.destroy();
    }

    statusChart = new Chart(
        document.getElementById('chartRespondentStatus'),
        {
            type: 'doughnut',

            data: {
                labels: ['Aktif', 'Tidak Aktif'],

                datasets: [{
                    data: [
                        Number(counts.aktif ?? 0),
                        Number(counts.tidak_aktif ?? 0),
                    ],

                    backgroundColor: [
                        '#2fb344',
                        '#98a6b5',
                    ],

                    borderWidth: 2,
                }],
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
            },
        }
    );

    // Grafik frekuensi aspek teratas per bagian
    const labels = sections.map(
        (section, index) =>
            `Bagian ${index + 1}`
    );

    const values = sections.map((section) => {
        if (!section.winners?.length) {
            return 0;
        }

        return Number(
            section.winners[0].total_rank_1 ?? 0
        );
    });

    if (priorityChart) {
        priorityChart.destroy();
    }

    priorityChart = new Chart(
        document.getElementById('chartTopPriorities'),
        {
            type: 'bar',

            data: {
                labels,

                datasets: [{
                    label: 'Jumlah responden',
                    data: values,

                    backgroundColor: '#206bc4',
                    borderRadius: 5,
                }],
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },

                plugins: {
                    legend: {
                        display: false,
                    },

                    tooltip: {
                        callbacks: {
                            title: (items) => {
                                const section = sections[
                                    items[0].dataIndex
                                ];

                                return section.section_title;
                            },

                            afterLabel: (item) => {
                                const section = sections[
                                    item.dataIndex
                                ];

                                const winners = section.winners ?? [];

                                return winners.map(
                                    (winner) =>
                                        `${winner.question_text}: ${winner.total_rank_1} responden (${winner.percentage}%)`
                                );
                            },
                        },
                    },
                },
            },
        }
    );
}