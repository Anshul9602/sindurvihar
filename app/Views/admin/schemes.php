<div class="container mx-auto px-4 py-8 max-w-6xl">
    <h1 class="text-3xl font-bold mb-6" style="color: #0F1F3F;">
        <?= esc(lang('App.adminSchemeManageTitle') ?? 'Manage Schemes') ?>
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Scheme Basic Info -->
        <div class="bg-white shadow-md rounded-lg p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold mb-4" style="color:#0F1F3F;">
                <?= esc(lang('App.adminSchemeInfoTitle') ?? 'Scheme Information') ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase mb-1" style="color:#6B7280;">
                        <?= esc(lang('App.adminSchemeName') ?? 'Scheme Name') ?>
                    </p>
                    <p class="text-lg font-bold" style="color:#111827;">
                        <?= esc($scheme['name'] ?? 'Sindoor Vihar') ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase mb-1" style="color:#6B7280;">
                        <?= esc(lang('App.adminSchemeTotalPlots') ?? 'Total Plots') ?>
                    </p>
                    <p class="text-lg font-bold" style="color:#111827;">
                        <?= esc($scheme['total_plots'] ?? 0) ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase mb-1" style="color:#6B7280;">
                        EWS / LIG / MIG / HIG
                    </p>
                    <p class="text-sm" style="color:#111827;">
                        EWS: <?= esc($scheme['income_groups']['EWS'] ?? 0) ?>,
                        LIG: <?= esc($scheme['income_groups']['LIG'] ?? 0) ?>,
                        MIG: <?= esc($scheme['income_groups']['MIG'] ?? 0) ?>,
                        HIG: <?= esc($scheme['income_groups']['HIG'] ?? 0) ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase mb-1" style="color:#6B7280;">
                        <?= esc(lang('App.adminSchemeLastDate') ?? 'Application Last Date') ?>
                    </p>
                    <p class="text-sm" style="color:#111827;">
                        <?= esc($scheme['last_date'] ?? '—') ?>
                    </p>
                    <p class="text-xs font-semibold uppercase mt-3 mb-1" style="color:#6B7280;">
                        <?= esc(lang('App.adminSchemeLotteryDate') ?? 'Lottery Date') ?>
                    </p>
                    <p class="text-sm" style="color:#111827;">
                        <?= esc($scheme['lottery_date'] ?? 'Not Generated') ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase mb-1" style="color:#6B7280;">
                        <?= esc(lang('App.adminSchemeStatus') ?? 'Status') ?>
                    </p>
                    <?php
                    $status = $scheme['status'] ?? 'closed';
                    $statusLabel = $status === 'open'
                        ? (lang('App.adminSchemeStatusOpen') ?? 'Applications Open')
                        : (lang('App.adminSchemeStatusClosed') ?? 'Applications Closed');
                    $statusColor = $status === 'open' ? '#065F46' : '#B91C1C';
                    $statusBg    = $status === 'open' ? '#DCFCE7' : '#FEE2E2';
                    ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                          style="background-color:<?= $statusBg ?>; color:<?= $statusColor ?>;">
                        <?= esc($statusLabel) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Lottery Readiness -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4" style="color:#0F1F3F;">
                <?= esc(lang('App.adminLotteryReadinessTitle') ?? 'Lottery Readiness') ?>
            </h2>
            <?php $ready = $readiness['ready'] ?? false; ?>
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                      style="background-color:<?= $ready ? '#DCFCE7' : '#FEE2E2' ?>; color:<?= $ready ? '#065F46' : '#B91C1C' ?>;">
                    <?= esc($ready ? (lang('App.adminLotteryReady') ?? 'READY') : (lang('App.adminLotteryNotReady') ?? 'NOT READY')) ?>
                </span>
            </div>
            <ul class="space-y-1 text-sm" style="color:#374151;">
                <li>
                    <?= ($readiness['all_docs_verified'] ?? false) ? '✔' : '⚠' ?>
                    <?= esc(lang('App.adminLotteryCheckDocuments') ?? 'All documents verified') ?>
                </li>
                <li>
                    <?= ($readiness['reservations_calculated'] ?? false) ? '✔' : '⚠' ?>
                    <?= esc(lang('App.adminLotteryCheckReservations') ?? 'Reservation calculated') ?>
                </li>
                <li>
                    <?= ($readiness['plots_available'] ?? false) ? '✔' : '⚠' ?>
                    <?= esc(lang('App.adminLotteryCheckPlots') ?? 'Plots available') ?>
                    (<?= esc($scheme['total_plots'] ?? 0) ?>)
                </li>
                <li>
                    <?php $aadhaarPending = $readiness['aadhaar_pending'] ?? 0; ?>
                    <?= $aadhaarPending === 0 ? '✔' : '⚠' ?>
                    <?= str_replace('{count}', (string) $aadhaarPending, lang('App.adminLotteryCheckAadhaarPending') ?? 'Aadhaar verification pending') ?>
                </li>
            </ul>
            <p class="mt-3 text-xs" style="color:#6B7280;">
                <?= esc($ready ? (lang('App.adminLotteryCanRun') ?? 'Lottery can be run. Please proceed when ready.') : (lang('App.adminLotteryCannotRun') ?? 'Lottery cannot be run until all critical checks are green.')) ?>
            </p>
            <div class="mt-4">
                <a href="/admin/lottery"
                   class="inline-flex items-center px-4 py-2 rounded-md text-sm font-semibold text-white"
                   style="background-color:#0747A6;">
                    <?= esc(lang('App.adminLotteryRunFromHere') ?? 'Go to Lottery Page') ?>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Reservation Summary -->
        <div class="bg-white shadow-md rounded-lg p-6 overflow-x-auto">
            <h2 class="text-lg font-semibold mb-4" style="color:#0F1F3F;">
                <?= esc(lang('App.adminReservationSummaryTitle') ?? 'Reservation Summary') ?>
            </h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr style="color:#6B7280;" class="border-b">
                        <th class="px-3 py-2 text-left"><?= esc(lang('App.adminReservationCategory') ?? 'Category') ?></th>
                        <th class="px-3 py-2 text-right"><?= esc(lang('App.adminReservationTotalPlots') ?? 'Total Plots') ?></th>
                        <th class="px-3 py-2 text-right"><?= esc(lang('App.adminReservationDisabled') ?? '5% Disabled') ?></th>
                        <th class="px-3 py-2 text-right"><?= esc(lang('App.adminReservationSingleWoman') ?? '10% Single Woman/Widow') ?></th>
                        <th class="px-3 py-2 text-right"><?= esc(lang('App.adminReservationGeneral') ?? 'General') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($reservationSummary ?? []) as $row): ?>
                        <tr class="border-b last:border-0">
                            <td class="px-3 py-2" style="color:#111827;"><?= esc($row['category']) ?></td>
                            <td class="px-3 py-2 text-right"><?= esc($row['total_plots']) ?></td>
                            <td class="px-3 py-2 text-right"><?= esc($row['disabled']) ?></td>
                            <td class="px-3 py-2 text-right"><?= esc($row['single_woman'] ?? $row['single'] ?? 0) ?></td>
                            <td class="px-3 py-2 text-right"><?= esc($row['general']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Application Status Summary -->
        <div class="bg-white shadow-md rounded-lg p-6 overflow-x-auto">
            <h2 class="text-lg font-semibold mb-4" style="color:#0F1F3F;">
                <?= esc(lang('App.adminApplicationSummaryTitle') ?? 'Application Status Summary') ?>
            </h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr style="color:#6B7280;" class="border-b">
                        <th class="px-3 py-2 text-left"><?= esc(lang('App.adminSchemeIncomeGroup') ?? 'Income Group') ?></th>
                        <th class="px-3 py-2 text-right"><?= esc(lang('App.adminTotal') ?? 'Total') ?></th>
                        <th class="px-3 py-2 text-right"><?= esc(lang('App.statusVerified') ?? 'Verified') ?></th>
                        <th class="px-3 py-2 text-right"><?= esc(lang('App.statusRejected') ?? 'Rejected') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($applicationSummary ?? []) as $row): ?>
                        <tr class="border-b last:border-0">
                            <td class="px-3 py-2" style="color:#111827;"><?= esc($row['group']) ?></td>
                            <td class="px-3 py-2 text-right"><?= esc($row['total']) ?></td>
                            <td class="px-3 py-2 text-right"><?= esc($row['verified']) ?></td>
                            <td class="px-3 py-2 text-right"><?= esc($row['rejected']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Category-wise Applicant Count -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <h2 class="text-lg font-semibold" style="color:#0F1F3F;">
                <?= esc(lang('App.adminLotteryCategorySummaryTitle') ?? 'Category-wise Applicant Count') ?>
            </h2>
            <div class="flex flex-wrap gap-2 mt-3 md:mt-0">
                <?php
                $cats = ['General','Govt','ST','SC','Media','Transgender','Army'];
                $selectedCat = $categorySummary['category'] ?? 'General';
                foreach ($cats as $cat):
                    $isActive = ($selectedCat === $cat);
                ?>
                    <a href="/admin/schemes?category=<?= urlencode($cat) ?>"
                       class="px-3 py-1 rounded-full text-xs font-semibold <?= $isActive ? 'text-white' : 'text-gray-700' ?>"
                       style="background-color:<?= $isActive ? '#2563EB' : '#E5E7EB' ?>;">
                        <?= esc($cat) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php
        $cs = $categorySummary ?? ['buckets' => [], 'total_eligible' => 0, 'quota_plots' => 0];
        $b  = $cs['buckets'] ?? [];
        $quota = $cs['quota_plots'] ?? 0;
        $eligible = $cs['total_eligible'] ?? 0;
        ?>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 text-sm" style="color:#111827;">
            <div class="bg-gray-50 rounded-md p-3">
                <p class="text-xs font-semibold mb-1" style="color:#6B7280;">Disabled</p>
                <p class="text-lg font-bold"><?= esc($b['disabled'] ?? 0) ?></p>
            </div>
            <div class="bg-gray-50 rounded-md p-3">
                <p class="text-xs font-semibold mb-1" style="color:#6B7280;">Single Woman/Widow</p>
                <p class="text-lg font-bold"><?= esc($b['single_woman'] ?? 0) ?></p>
            </div>
            <div class="bg-gray-50 rounded-md p-3">
                <p class="text-xs font-semibold mb-1" style="color:#6B7280;">Transgender</p>
                <p class="text-lg font-bold"><?= esc($b['transgender'] ?? 0) ?></p>
            </div>
            <div class="bg-gray-50 rounded-md p-3">
                <p class="text-xs font-semibold mb-1" style="color:#6B7280;">Army</p>
                <p class="text-lg font-bold"><?= esc($b['army'] ?? 0) ?></p>
            </div>
            <div class="bg-gray-50 rounded-md p-3">
                <p class="text-xs font-semibold mb-1" style="color:#6B7280;">General <?= esc($selectedCat) ?></p>
                <p class="text-lg font-bold"><?= esc($b['general'] ?? 0) ?></p>
            </div>
        </div>

        <div class="text-sm" style="color:#374151;">
            <p>
                <?= esc($selectedCat) ?> quota plots =
                <strong><?= esc($quota) ?></strong>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                Eligible applicants =
                <strong><?= esc($eligible) ?></strong>
            </p>
            <p class="mt-1">
                <?php if ($eligible > $quota && $quota > 0): ?>
                    <span class="text-green-600 font-semibold">Lottery Required ✔</span>
                <?php elseif ($quota > 0): ?>
                    <span class="text-blue-600 font-semibold">Auto allotment possible (no lottery needed)</span>
                <?php else: ?>
                    <span class="text-yellow-600 font-semibold">No plots configured for this category.</span>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
