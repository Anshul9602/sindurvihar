<!-- Welcome Banner -->
<div class="welcome-banner" id="welcome-banner">
    <div>
        <strong><?= esc(lang('App.adminWelcomeBack')) ?></strong>
    </div>
    <button onclick="document.getElementById('welcome-banner').style.display='none'" class="text-white hover:text-gray-200">
        ✕
    </button>
</div>

<!-- Dashboard Overview Banner -->
<div class="dashboard-banner">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold mb-2"><?= esc(lang('App.adminDashboardTitle')) ?></h1>
            <p class="text-white/80"><?= esc(lang('App.adminDashboardOverview')) ?></p>
        </div>
        <a href="/admin/reports" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg font-semibold transition">
            <?= esc(lang('App.adminReportsTitle')) ?>
        </a>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
            <div class="text-white/80 text-sm mb-1"><?= esc(lang('App.adminTotalUsers')) ?></div>
            <div class="text-2xl font-bold text-white"><?= esc($totalUsers ?? 0) ?></div>
            <div class="text-white/70 text-xs mt-1"><?= esc(lang('App.adminActive')) ?> <?= esc($activeUsers ?? 0) ?></div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
            <div class="text-white/80 text-sm mb-1"><?= esc(lang('App.adminTotalApplicationsLabel')) ?></div>
            <div class="text-2xl font-bold text-white"><?= esc($totalApplications ?? 0) ?></div>
            <div class="text-white/70 text-xs mt-1"><?= esc(lang('App.adminPending')) ?> <?= esc($pendingApplications ?? 0) ?></div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
            <div class="text-white/80 text-sm mb-1"><?= esc(lang('App.adminPendingVerificationLabel')) ?></div>
            <div class="text-2xl font-bold text-white"><?= esc($pendingApplications ?? 0) ?></div>
            <div class="text-white/70 text-xs mt-1"><?= esc(lang('App.adminAwaitingReview')) ?></div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
            <div class="text-white/80 text-sm mb-1"><?= esc(lang('App.adminVerifiedApplicationsLabel')) ?></div>
            <div class="text-2xl font-bold text-white"><?= esc($verifiedApplications ?? 0) ?></div>
            <div class="text-white/70 text-xs mt-1"><?= esc(lang('App.adminThisMonth')) ?> <?= esc($verifiedApplications ?? 0) ?></div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
            <div class="text-white/80 text-sm mb-1"><?= esc(lang('App.adminTotalPaymentsLabel')) ?></div>
            <div class="text-2xl font-bold text-white">₹<?= number_format($totalAmount ?? 0, 2) ?></div>
            <div class="text-white/70 text-xs mt-1"><?= esc(lang('App.adminToday')) ?> ₹<?= number_format($todayAmount ?? 0, 2) ?></div>
        </div>
    </div>
</div>

<!-- Detailed Metrics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <div class="metric-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600"><?= esc(lang('App.adminTotalWithdrawals')) ?></h3>
            <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center">
                <span class="text-pink-600">💸</span>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-900">₹<?= number_format($totalAmount ?? 0, 2) ?></div>
        <div class="text-sm text-gray-500 mt-1"><?= esc(lang('App.adminToday')) ?> ₹<?= number_format($todayAmount ?? 0, 2) ?></div>
    </div>

    <div class="metric-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600"><?= esc(lang('App.adminPendingPayments')) ?></h3>
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <span class="text-blue-600">💰</span>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-900">₹<?= number_format($pendingAmount ?? 0, 2) ?></div>
        <div class="text-sm text-gray-500 mt-1"><?= esc(lang('App.adminAwaitingPayout')) ?></div>
    </div>

    <div class="metric-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600"><?= esc(lang('App.adminTotalApplicationsLabel')) ?></h3>
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                <span class="text-green-600">📋</span>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-900"><?= esc($totalApplications ?? 0) ?></div>
        <div class="text-sm text-gray-500 mt-1">
            <a href="/admin/applications" class="text-blue-600 hover:underline"><?= esc(lang('App.adminViewApplications')) ?></a>
        </div>
    </div>

    <div class="metric-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600"><?= esc(lang('App.adminTodayPayments')) ?></h3>
            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                <span class="text-yellow-600">↕️</span>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-900">₹<?= number_format($todayAmount ?? 0, 2) ?></div>
        <div class="text-sm text-gray-500 mt-1"><?= esc(lang('App.adminTotal')) ?> ₹<?= number_format($totalAmount ?? 0, 2) ?></div>
    </div>

    <div class="metric-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600"><?= esc(lang('App.adminVerifiedApplications')) ?></h3>
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                <span class="text-purple-600">✅</span>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-900"><?= esc($verifiedApplications ?? 0) ?></div>
        <div class="text-sm text-gray-500 mt-1">
            <a href="/admin/verification" class="text-blue-600 hover:underline"><?= esc(lang('App.adminViewVerificationQueue')) ?></a>
        </div>
    </div>

    <div class="metric-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600"><?= esc(lang('App.adminPendingVerificationLabel2')) ?></h3>
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                <span class="text-orange-600">⏳</span>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-900"><?= esc($pendingApplications ?? 0) ?></div>
        <div class="text-sm text-gray-500 mt-1"><?= esc(lang('App.adminAwaitingReview')) ?></div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Applications Overview Chart -->
    <div class="metric-card">
        <h3 class="text-lg font-semibold mb-4 text-gray-900"><?= esc(lang('App.adminApplicationsOverview')) ?></h3>
        <p class="text-sm text-gray-600 mb-4"><?= esc(lang('App.adminApplicationsVsVerifications')) ?></p>
        <div style="height: 250px; position: relative;">
            <canvas id="applicationsChart"></canvas>
        </div>
    </div>

    <!-- Payments Overview Chart -->
    <div class="metric-card">
        <h3 class="text-lg font-semibold mb-4 text-gray-900"><?= esc(lang('App.adminPaymentsOverview')) ?></h3>
        <p class="text-sm text-gray-600 mb-4"><?= esc(lang('App.adminPaymentsVsWithdrawals')) ?></p>
        <div style="height: 250px; position: relative;">
            <canvas id="paymentsChart"></canvas>
        </div>
    </div>
</div>

<script>
(function() {
    // Applications Chart
    const appsCtx = document.getElementById('applicationsChart');
    if (appsCtx) {
        new Chart(appsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: '<?= esc(lang('App.adminChartApplications')) ?>',
                    data: [<?= esc($totalApplications ?? 0) ?>, 0, 0, 0, 0, 0],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                }, {
                    label: '<?= esc(lang('App.adminChartVerified')) ?>',
                    data: [<?= esc($verifiedApplications ?? 0) ?>, 0, 0, 0, 0, 0],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    }

    // Payments Chart
    const paymentsCtx = document.getElementById('paymentsChart');
    if (paymentsCtx) {
        new Chart(paymentsCtx, {
            type: 'doughnut',
            data: {
                labels: ['<?= esc(lang('App.adminChartTotalPayments')) ?>', '<?= esc(lang('App.adminChartPending')) ?>'],
                datasets: [{
                    data: [<?= esc($totalAmount ?? 0) ?>, <?= esc($pendingAmount ?? 0) ?>],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    }
})();
</script>
