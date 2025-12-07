<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- محتوى الصفحة -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js مع fallback -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // إذا فشل تحميل Chart.js، جرب CDN بديل
        if (typeof Chart === 'undefined') {
            console.warn('Primary Chart.js CDN failed, loading from backup...');
            document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"><\/script>');
        }
    </script>

    <script>
        // انتظر حتى يتم تحميل جميع المكتبات
        function initCharts() {
            console.log('Initializing charts...');
            console.log('Chart available:', typeof Chart);

            // 1. الرسم البياني الخطي
            const lineCtx = document.getElementById('lineChart');
            if (lineCtx && typeof Chart !== 'undefined') {
                const lineData = {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                    datasets: [{
                        label: 'Appointments',
                        data: @json(array_slice(array_values($monthlyStats), 0, 6)),
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                };

                new Chart(lineCtx, {
                    type: 'line',
                    data: lineData,
                    options: {
                        responsive: true
                    }
                });
                console.log('Line chart created');
            }

            // 2. الرسم البياني الدائري
            const pieCtx = document.getElementById('pieChart');
            if (pieCtx && typeof Chart !== 'undefined') {
                const pieData = {
                    labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [{
                                {
                                    $statusStats['pending'] ?? 0
                                }
                            },
                            {
                                {
                                    $statusStats['confirmed'] ?? 0
                                }
                            },
                            {
                                {
                                    $statusStats['completed'] ?? 0
                                }
                            },
                            {
                                {
                                    $statusStats['cancelled'] ?? 0
                                }
                            }
                        ],
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0'
                        ]
                    }]
                };

                new Chart(pieCtx, {
                    type: 'pie',
                    data: pieData,
                    options: {
                        responsive: true
                    }
                });
                console.log('Pie chart created');
            }

            // 3. الرسم البياني العمودي
            const barCtx = document.getElementById('barChart');
            if (barCtx && typeof Chart !== 'undefined') {
                const barData = {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                    datasets: [{
                        label: 'Revenue',
                        data: [{
                                {
                                    array_sum(array_slice(array_values($monthlyRevenue), 0, 3))
                                }
                            },
                            {
                                {
                                    array_sum(array_slice(array_values($monthlyRevenue), 3, 3))
                                }
                            },
                            {
                                {
                                    array_sum(array_slice(array_values($monthlyRevenue), 6, 3))
                                }
                            },
                            {
                                {
                                    array_sum(array_slice(array_values($monthlyRevenue), 9, 3))
                                }
                            }
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)'
                    }]
                };

                new Chart(barCtx, {
                    type: 'bar',
                    data: barData,
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                console.log('Bar chart created');
            }
        }

        // تشغيل الرسوم البيانية بعد تحميل الصفحة
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCharts);
        } else {
            initCharts();
        }
    </script>
</body>

</html>
