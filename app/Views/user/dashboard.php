<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.dashboardWelcome')) ?> <?= esc($user['name'] ?? '') ?>
    </h1>
    <p class="mb-6 text-sm" style="color:#6B7280;">
        <?= esc(lang('App.dashboardSubtitle')) ?>
    </p>

    <?php
    $eligibilityDone = $steps['eligibility']['completed'] ?? false;
    $applicationDone = $steps['application']['completed'] ?? false;
    $documentsDone   = $steps['documents']['completed'] ?? false;
    $paymentDone     = $steps['payment']['completed'] ?? false;

    $hasApplication  = !empty($application);
    $applicationStatus = $application['status'] ?? null;
    
    if (! $hasApplication) {
        $statusLabel = lang('App.dashboardStatusNoApplication') ?? 'No application found. Start a new application.';
        $statusColor = '#4B5563';
        $statusBg    = 'transparent';
        $statusPill  = false;
    } elseif ($applicationStatus === 'verified') {
        $statusLabel = lang('App.dashboardStatusVerified') ?? 'Verified';
        $statusColor = '#065F46';
        $statusBg    = '#D1FAE5';
        $statusPill  = true;
    } elseif ($applicationStatus === 'rejected') {
        $statusLabel = lang('App.dashboardStatusRejected') ?? 'Rejected';
        $statusColor = '#991B1B';
        $statusBg    = '#FEE2E2';
        $statusPill  = true;
    } elseif (!empty($lotteryWon)) {
        $plotNum  = $userAllotment['plot_number'] ?? null;
        $plotArea = $userAllotment['plot_area'] ?? null;
        $allotmentStatus = strtolower((string)($userAllotment['allotment_status'] ?? ''));
        $isAllotted = in_array($allotmentStatus, ['allotted', 'alloted'], true);
        if ($plotNum && $plotArea) {
            $statusLabel = $isAllotted
                ? sprintf(lang('App.statusPlotAllottedSize') ?? 'Plot Allotted! Plot: %s (%s sq ft)', $plotNum, $plotArea)
                : sprintf(lang('App.dashboardCongratulationsPlotSize') ?? 'Congratulations! You won the lottery! Plot: %s (%s sq ft)', $plotNum, $plotArea);
        } elseif ($plotNum) {
            $statusLabel = $isAllotted
                ? sprintf(lang('App.statusPlotAllotted') ?? 'Plot Allotted! Plot: %s', $plotNum)
                : sprintf(lang('App.dashboardCongratulationsPlot') ?? 'Congratulations! You won the lottery! Plot: %s', $plotNum);
        } else {
            $statusLabel = $isAllotted
                ? (lang('App.statusAllotted') ?? 'Plot Allotted!')
                : (lang('App.dashboardCongratulations') ?? 'Congratulations! You won the lottery!');
        }
        $statusColor = '#065F46';
        $statusBg    = '#D1FAE5';
        $statusPill  = true;
    } elseif ($eligibilityDone && $applicationDone && $documentsDone && $paymentDone) {
        $statusLabel = lang('App.dashboardStatusSubmitted') ?? 'Submitted';
        $statusColor = '#166534';
        $statusBg    = '#DCFCE7';
        $statusPill  = true;
    } else {
        $statusLabel = lang('App.dashboardStatusPending') ?? 'Pending';
        $statusColor = '#92400E';
        $statusBg    = '#FEF3C7';
        $statusPill  = true;
    }
    ?>

    <?php
    $pendingChalans = array_filter($chalans ?? [], function ($c) { return ($c['status'] ?? '') === 'pending'; });
    $hasPendingChalan = ! empty($pendingChalans);
    $hasChalans      = ! empty($chalans);
    $showChalanCard  = $hasPendingChalan || (! empty($lotteryWon) && $hasChalans);
    ?>

    <?php if (! empty($lotteryWon)): ?>
    <!-- Winners Dashboard Layout -->
    <div class="space-y-6 mb-8">
        <!-- Row 1: Congratulations + Chalan -->
        <div class="grid grid-cols-1 <?= $showChalanCard ? 'lg:grid-cols-2' : '' ?> gap-6">
            <div class="bg-white shadow-md rounded-lg p-6 border-l-4" style="border-color: #10B981;">
                <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                    <?= esc(lang('App.dashboardApplicationStatus')) ?>
                </h3>
                <div class="py-2">
                    <span class="inline-block px-4 py-2 rounded-lg text-base font-semibold"
                          style="background-color: <?= esc($statusBg) ?>; color: <?= esc($statusColor) ?>;">
                        <?= esc($statusLabel) ?>
                    </span>
                </div>
            </div>
            <?php if ($showChalanCard): ?>
            <div class="bg-white shadow-md rounded-lg p-6 border-l-4" style="border-color: #F59E0B;">
                <h3 class="text-lg font-semibold mb-3" style="color: #0F1F3F;">
                    <?= esc(lang('App.chalanFinalPayment') ?? 'Final Payment Chalan') ?>
                </h3>
                <?php $chalansToShow = $chalans; ?>
                <?php foreach ($chalansToShow as $ch): ?>
                <div class="mb-3 p-4 rounded-lg" style="background-color: <?= ($ch['status'] ?? '') === 'paid' ? '#D1FAE5' : '#FEF3C7' ?>; border: 1px solid <?= ($ch['status'] ?? '') === 'paid' ? '#10B981' : '#F59E0B' ?>;">
                    <p class="text-sm mb-3" style="color: #374151;">
                        <strong><?= esc(lang('App.chalanNumber') ?? 'Chalan') ?>:</strong> <?= esc($ch['chalan_number']) ?><br>
                        <strong><?= esc(lang('App.amount') ?? 'Amount') ?>:</strong> ₹<?= number_format($ch['amount']) ?><br>
                        <strong><?= esc(lang('App.adminStatus') ?? 'Status') ?>:</strong>
                        <span class="px-2 py-0.5 rounded text-xs font-semibold <?= ($ch['status'] ?? '') === 'paid' ? 'bg-green-200 text-green-900' : 'bg-yellow-200 text-yellow-900' ?>">
                            <?= esc(ucfirst($ch['status'] ?? 'pending')) ?>
                        </span>
                    </p>
                    <?php if (($ch['status'] ?? '') === 'pending'): ?>
                    <a href="<?= site_url('user/chalan/' . $ch['id'] . '/pay') ?>"
                       class="inline-block px-5 py-2.5 rounded-md font-semibold text-white"
                       style="background-color: #10B981;">
                        <?= esc(lang('App.chalanPayButton') ?? 'Pay Now') ?>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <!-- Row 2: Quick Links (Steps 6, 7, 8) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="<?= site_url('user/lottery-results') ?>" class="block">
                <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition border border-gray-100 h-full">
                    <h3 class="text-base font-semibold mb-2" style="color: #0F1F3F;"><?= esc(lang('App.dashboardStep6')) ?></h3>
                    <span class="text-sm" style="color: #6B7280;"><?= esc(lang('App.dashboardLotteryResultsDesc') ?? 'View lottery results') ?></span>
                    <span class="block mt-3 text-blue-600 font-semibold text-sm"><?= esc(lang('App.dashboardGo')) ?> →</span>
                </div>
            </a>
            <a href="<?= site_url('user/allotment') ?>" class="block">
                <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition border border-gray-100 h-full">
                    <h3 class="text-base font-semibold mb-2" style="color: #0F1F3F;"><?= esc(lang('App.dashboardStep7')) ?></h3>
                    <span class="text-sm" style="color: #6B7280;"><?= esc(lang('App.dashboardAllotmentDesc') ?? 'View your plot details') ?></span>
                    <span class="block mt-3 text-blue-600 font-semibold text-sm"><?= esc(lang('App.dashboardGo')) ?> →</span>
                </div>
            </a>
            <a href="<?= site_url('user/refund-status') ?>" class="block">
                <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition border border-gray-100 h-full">
                    <h3 class="text-base font-semibold mb-2" style="color: #0F1F3F;"><?= esc(lang('App.dashboardStep8')) ?></h3>
                    <span class="text-sm" style="color: #6B7280;"><?= esc(lang('App.dashboardRefundDesc') ?? 'Check refund status') ?></span>
                    <span class="block mt-3 text-blue-600 font-semibold text-sm"><?= esc(lang('App.dashboardGo')) ?> →</span>
                </div>
            </a>
        </div>
        <!-- Row 3: Profile + View Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;"><?= esc(lang('App.dashboardProfile')) ?></h3>
                <a href="<?= site_url('user/profile') ?>">
                    <button class="px-4 py-2 rounded-md font-semibold border" style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.dashboardEditProfile')) ?>
                    </button>
                </a>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;"><?= esc(lang('App.dashboardViewStatus')) ?></h3>
                <a href="<?= site_url('user/application/status') ?>">
                    <button class="px-4 py-2 rounded-md font-semibold border" style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.dashboardViewStatus')) ?>
                    </button>
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Non-winners Dashboard Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                <?= esc(lang('App.dashboardApplicationStatus')) ?>
            </h3>
            <div id="application-status-container">
                <?php if ($statusPill): ?>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold"
                          style="background-color: <?= esc($statusBg) ?>; color: <?= esc($statusColor) ?>;">
                        <?= esc($statusLabel) ?>
                    </span>
                <?php else: ?>
                    <p style="color: <?= esc($statusColor) ?>;"><?= esc($statusLabel) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                <?= esc(lang('App.dashboardQuickActions')) ?>
            </h3>
            <div class="space-y-2">
                <a href="<?= site_url('user/eligibility') ?>">
                    <button class="w-full px-4 py-2 rounded-md font-semibold text-white" style="background-color: #0747A6;">
                        <?= esc(lang('App.dashboardCheckEligibility')) ?>
                    </button>
                </a>
                <a href="<?= site_url('user/application') ?>">
                    <button class="w-full px-4 py-2 rounded-md font-semibold text-white" style="background-color: #2563EB;">
                        <?= esc(lang('App.dashboardStartApplication')) ?>
                    </button>
                </a>
                <a href="<?= site_url('user/application/status') ?>">
                    <button class="w-full px-4 py-2 rounded-md font-semibold border" style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.dashboardViewStatus')) ?>
                    </button>
                </a>
            </div>
        </div>
        <?php if ($showChalanCard): ?>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                <?= esc(lang('App.chalanFinalPayment') ?? 'Final Payment Chalan') ?>
            </h3>
            <?php $chalansToShow = $pendingChalans; ?>
            <?php foreach ($chalansToShow as $ch): ?>
            <div class="mb-3 p-3 rounded-lg" style="background-color: <?= ($ch['status'] ?? '') === 'paid' ? '#D1FAE5' : '#FEF3C7' ?>; border: 1px solid <?= ($ch['status'] ?? '') === 'paid' ? '#10B981' : '#F59E0B' ?>;">
                <p class="text-sm mb-2" style="color: #374151;">
                    <strong><?= esc(lang('App.chalanNumber') ?? 'Chalan') ?>:</strong> <?= esc($ch['chalan_number']) ?><br>
                    <strong><?= esc(lang('App.amount') ?? 'Amount') ?>:</strong> ₹<?= number_format($ch['amount']) ?><br>
                    <strong><?= esc(lang('App.adminStatus') ?? 'Status') ?>:</strong>
                    <span class="px-2 py-0.5 rounded text-xs font-semibold <?= ($ch['status'] ?? '') === 'paid' ? 'bg-green-200 text-green-900' : 'bg-yellow-200 text-yellow-900' ?>">
                        <?= esc(ucfirst($ch['status'] ?? 'pending')) ?>
                    </span>
                </p>
                <?php if (($ch['status'] ?? '') === 'pending'): ?>
                <a href="<?= site_url('user/chalan/' . $ch['id'] . '/pay') ?>"
                   class="inline-block px-4 py-2 rounded-md font-semibold text-white" style="background-color: #10B981;">
                    <?= esc(lang('App.chalanPayButton') ?? 'Pay Now') ?>
                </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                <?= esc(lang('App.dashboardProfile')) ?>
            </h3>
            <a href="<?= site_url('user/profile') ?>">
                <button class="w-full px-4 py-2 rounded-md font-semibold border" style="border-color: #0747A6; color: #0747A6;">
                    <?= esc(lang('App.dashboardEditProfile')) ?>
                </button>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($lotteryWon)): ?>
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4" style="color: #0F1F3F;">
            <?= esc(lang('App.dashboardFlowTitle')) ?>
        </h2>
        <div class="space-y-4">
            <!-- Step 1: Eligibility -->
            <?php 
            $isVerified = ($applicationStatus === 'verified');
            if ($isVerified && $eligibilityDone) {
                // Show View button for verified applications
            ?>
                <div class="flex items-center justify-between p-4 border rounded-lg">
                    <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep1')) ?></span>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                              style="background-color:#16A34A;">✓</span>
                        <span class="text-xs font-semibold" style="color:#16A34A;"><?= esc(lang('App.dashboardCompleted')) ?></span>
                        <button onclick="showEligibilityView()" 
                                class="px-3 py-1 border rounded-md text-sm cursor-pointer"
                                style="border-color:#16A34A; color:#16A34A;">
                            <?= esc(lang('App.dashboardView') ?? 'View') ?>
                        </button>
                    </div>
                </div>
            <?php } else { ?>
                <a href="/user/eligibility">
                    <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                        <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep1')) ?></span>
                        <?php if ($eligibilityDone): ?>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                      style="background-color:#16A34A;">✓</span>
                                <span class="text-xs font-semibold" style="color:#16A34A;"><?= esc(lang('App.dashboardCompleted')) ?></span>
                                <span class="px-3 py-1 border rounded-md text-sm"
                                      style="border-color:#16A34A; color:#16A34A;">
                                    <?= esc(lang('App.dashboardEdit')) ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <span class="px-3 py-1 border rounded-md text-sm"
                                  style="border-color:#0747A6; color:#0747A6;">
                                <?= esc(lang('App.dashboardGo')) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php } ?>

            <!-- Step 2: Application Form -->
            <?php if ($eligibilityDone): ?>
                <?php 
                $isVerified = ($applicationStatus === 'verified');
                if ($isVerified && $applicationDone) {
                    // Show View button for verified applications
                ?>
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep2')) ?></span>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                  style="background-color:#16A34A;">✓</span>
                            <span class="text-xs font-semibold" style="color:#16A34A;"><?= esc(lang('App.dashboardCompleted')) ?></span>
                            <button onclick="showApplicationView()" 
                                    class="px-3 py-1 border rounded-md text-sm cursor-pointer"
                                    style="border-color:#16A34A; color:#16A34A;">
                                <?= esc(lang('App.dashboardView') ?? 'View') ?>
                            </button>
                        </div>
                    </div>
                <?php } else {
                    // Show Edit button or Go button for non-verified applications
                    $editUrl = $applicationDone ? site_url('user/application/edit') : site_url('user/application');
                ?>
                    <a href="<?= $editUrl ?>" 
                       class="block no-underline">
                        <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                            <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep2')) ?></span>
                            <?php if ($applicationDone): ?>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                          style="background-color:#16A34A;">✓</span>
                                    <span class="text-xs font-semibold" style="color:#16A34A;"><?= esc(lang('App.dashboardCompleted')) ?></span>
                                    <span class="px-3 py-1 border rounded-md text-sm"
                                          style="border-color:#16A34A; color:#16A34A;">
                                        <?= esc(lang('App.dashboardEdit')) ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <span class="px-3 py-1 border rounded-md text-sm"
                                      style="border-color:#0747A6; color:#0747A6;">
                                    <?= esc(lang('App.dashboardGo')) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php } ?>
            <?php else: ?>
                <div class="opacity-60 cursor-not-allowed">
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep2')) ?></span>
                        <span class="px-3 py-1 border rounded-md text-sm"
                              style="border-color:#0747A6; color:#0747A6;">
                            <?= esc(lang('App.dashboardGo')) ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Step 3: Payment -->
            <?php if ($applicationDone): ?>
                <?php if ($isVerified && $paymentDone): ?>
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep3')) ?></span>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                  style="background-color:#16A34A;">✓</span>
                            <span class="text-xs font-semibold" style="color:#16A34A;"><?= esc(lang('App.dashboardCompleted')) ?></span>
                            <button onclick="showPaymentView()" 
                                    class="px-3 py-1 border rounded-md text-sm cursor-pointer"
                                    style="border-color:#16A34A; color:#16A34A;">
                                <?= esc(lang('App.dashboardView') ?? 'View') ?>
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/user/payment" class="block no-underline">
                        <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                            <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep3')) ?></span>
                            <?php if ($paymentDone): ?>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                          style="background-color:#16A34A;">✓</span>
                                    <span class="text-xs font-semibold" style="color:#16A34A;"><?= esc(lang('App.dashboardCompleted')) ?></span>
                                    <span class="px-3 py-1 border rounded-md text-sm"
                                          style="border-color:#16A34A; color:#16A34A;">
                                        <?= esc(lang('App.dashboardEdit')) ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <span class="px-3 py-1 border rounded-md text-sm"
                                      style="border-color:#0747A6; color:#0747A6;">
                                    <?= esc(lang('App.dashboardGo')) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <div class="opacity-60 cursor-not-allowed">
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep3')) ?></span>
                        <span class="px-3 py-1 border rounded-md text-sm"
                              style="border-color:#0747A6; color:#0747A6;">
                            <?= esc(lang('App.dashboardGo')) ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Step 4: Documents -->
            <?php if ($paymentDone): ?>
                <?php if ($isVerified && $documentsDone): ?>
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep4')) ?></span>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                  style="background-color:#16A34A;">✓</span>
                            <span class="text-xs font-semibold" style="color:#16A34A;"><?= esc(lang('App.dashboardCompleted')) ?></span>
                            <button onclick="showDocumentsView()" 
                                    class="px-3 py-1 border rounded-md text-sm cursor-pointer"
                                    style="border-color:#16A34A; color:#16A34A;">
                                <?= esc(lang('App.dashboardView') ?? 'View') ?>
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/user/documents" class="block no-underline">
                        <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                            <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep4')) ?></span>
                            <?php if ($documentsDone): ?>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                          style="background-color:#16A34A;">✓</span>
                                    <span class="text-xs font-semibold" style="color:#16A34A;"><?= esc(lang('App.dashboardCompleted')) ?></span>
                                    <span class="px-3 py-1 border rounded-md text-sm"
                                          style="border-color:#16A34A; color:#16A34A;">
                                        <?= esc(lang('App.dashboardEdit')) ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <span class="px-3 py-1 border rounded-md text-sm"
                                      style="border-color:#0747A6; color:#0747A6;">
                                    <?= esc(lang('App.dashboardGo')) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <div class="opacity-60 cursor-not-allowed">
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep4')) ?></span>
                        <span class="px-3 py-1 border rounded-md text-sm"
                              style="border-color:#0747A6; color:#0747A6;">
                            <?= esc(lang('App.dashboardGo')) ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
            <!-- Steps 5-8 in Cards Grid (or just 6-8 when lottery won) -->
            <div class="grid grid-cols-1 md:grid-cols-2 <?= !empty($lotteryWon) ? 'lg:grid-cols-3' : 'lg:grid-cols-4' ?> gap-4 <?= empty($lotteryWon) ? 'mt-4' : '' ?>">
                <?php if (empty($lotteryWon)): ?>
                <a href="/user/application/status">
                    <div class="bg-white border rounded-lg p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-sm mb-2" style="color: #0F1F3F;"><?= esc(lang('App.dashboardStep5')) ?></h3>
                        </div>
                        <button class="w-full px-3 py-2 border rounded-md text-sm font-semibold mt-3"
                                style="border-color: #0747A6; color: #0747A6;">
                            <?= esc(lang('App.dashboardGo')) ?>
                        </button>
                    </div>
                </a>
                <?php endif; ?>
                <a href="/user/lottery-results">
                    <div class="bg-white border rounded-lg p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-sm mb-2" style="color: #0F1F3F;"><?= esc(lang('App.dashboardStep6')) ?></h3>
                        </div>
                        <button class="w-full px-3 py-2 border rounded-md text-sm font-semibold mt-3"
                                style="border-color: #0747A6; color: #0747A6;">
                            <?= esc(lang('App.dashboardGo')) ?>
                        </button>
                    </div>
                </a>
                <a href="/user/allotment">
                    <div class="bg-white border rounded-lg p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-sm mb-2" style="color: #0F1F3F;"><?= esc(lang('App.dashboardStep7')) ?></h3>
                        </div>
                        <button class="w-full px-3 py-2 border rounded-md text-sm font-semibold mt-3"
                                style="border-color: #0747A6; color: #0747A6;">
                            <?= esc(lang('App.dashboardGo')) ?>
                        </button>
                    </div>
                </a>
                <a href="/user/refund-status">
                    <div class="bg-white border rounded-lg p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-sm mb-2" style="color: #0F1F3F;"><?= esc(lang('App.dashboardStep8')) ?></h3>
                        </div>
                        <button class="w-full px-3 py-2 border rounded-md text-sm font-semibold mt-3"
                                style="border-color: #0747A6; color: #0747A6;">
                            <?= esc(lang('App.dashboardGo')) ?>
                        </button>
                    </div>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Application View Modal -->
    <?php if (!empty($application)): ?>
    <div id="applicationViewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-2 sm:p-4" style="overflow-y: auto;margin-top:80px;" >
        <div class="bg-white rounded-lg shadow-xl max-w-5xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto my-auto">
            <div class="sticky top-0 bg-white border-b px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between z-10">
                <h2 class="text-xl sm:text-2xl font-bold" style="color: #0F1F3F;">
                    <?= esc(lang('App.appViewTitle') ?? 'Application Details') ?>
                </h2>
                <button onclick="closeApplicationView()" class="text-gray-500 hover:text-gray-700 flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 sm:p-6">
                <!-- Application ID and Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.adminApplicationId') ?? 'Application ID') ?>
                        </label>
                        <p class="text-sm sm:text-base font-semibold" style="color: #111827;">
                            <?= esc($application['id']) ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.adminApplicationStatus') ?? 'Status') ?>
                        </label>
                        <p class="text-base">
                            <span class="px-2 sm:px-3 py-1 rounded text-xs sm:text-sm font-semibold bg-green-100 text-green-800">
                                <?= esc(ucfirst($application['status'] ?? 'draft')) ?>
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Identity Details -->
                <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4" style="color: #0F1F3F;">
                    <?= esc(lang('App.appIdentitySection') ?? 'Identity Details') ?>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appFullNameLabel') ?? 'Full Name') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['full_name'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appAadhaarLabel') ?? 'Aadhaar Number') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['aadhaar'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appFatherHusbandLabel') ?? 'Father / Husband Name') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['father_husband_name'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appAgeLabel') ?? 'Age') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['age'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appMobileLabel') ?? 'Mobile Number') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['mobile'] ?? 'N/A') ?>
                        </p>
                    </div>
                </div>

                <!-- Residence Details -->
                <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4" style="color: #0F1F3F;">
                    <?= esc(lang('App.appResidenceSection') ?? 'Residence Details') ?>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div class="sm:col-span-2 lg:col-span-4">
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appAddressLabel') ?? 'Address') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['address'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appTehsilLabel') ?? 'Tehsil') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['tehsil'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appDistrictLabel') ?? 'District') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['district'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appStateLabel') ?? 'State') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['state'] ?? 'Rajasthan') ?>
                        </p>
                    </div>
                </div>

                <!-- Income Details -->
                <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4" style="color: #0F1F3F;">
                    <?= esc(lang('App.appIncomeSection') ?? 'Income Details') ?>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appAnnualIncomeLabel') ?? 'Annual Income (₹)') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            ₹ <?= esc(number_format($application['income'] ?? 0, 2)) ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appCategoryLabel') ?? 'Category') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['income_category'] ?? 'N/A') ?>
                        </p>
                    </div>
                </div>

                <!-- Lottery & Reservation Details -->
                <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4" style="color: #0F1F3F;">
                    <?= esc(lang('App.appLotterySection') ?? 'Lottery & Reservation Details') ?>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appCasteCategoryLabel') ?? 'Caste Category') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($application['caste_category'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appDisabledLabel') ?? 'Disabled') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= ($application['is_disabled'] ?? 0) ? 'Yes' : 'No' ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appSingleWomanLabel') ?? 'Single Woman/Widow') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= ($application['is_single_woman'] ?? 0) ? 'Yes' : 'No' ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appTransgenderLabel') ?? 'Transgender') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= ($application['is_transgender'] ?? 0) ? 'Yes' : 'No' ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appArmyLabel') ?? 'Army/Ex-serviceman') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= ($application['is_army'] ?? 0) ? 'Yes' : 'No' ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appMediaLabel') ?? 'Media') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= ($application['is_media'] ?? 0) ? 'Yes' : 'No' ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.appGovtEmployeeLabel') ?? 'Government Employee') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= ($application['is_govt_employee'] ?? 0) ? 'Yes' : 'No' ?>
                        </p>
                    </div>
                </div>

                <!-- Created Date -->
                <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t">
                    <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.appCreatedDate') ?? 'Application Date') ?>
                    </label>
                    <p class="text-xs sm:text-sm" style="color: #6B7280;">
                        <?= esc($application['created_at'] ?? 'N/A') ?>
                    </p>
                </div>
            </div>
            <div class="sticky bottom-0 bg-gray-50 border-t px-4 sm:px-6 py-3 sm:py-4 flex justify-end">
                <button onclick="closeApplicationView()" 
                        class="px-4 py-2 rounded-md text-sm sm:text-base font-semibold border-2 w-full sm:w-auto"
                        style="border-color: #6B7280; color: #6B7280;">
                    <?= esc(lang('App.close') ?? 'Close') ?>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Eligibility View Modal -->
    <?php if (!empty($eligibility)): ?>
    <div id="eligibilityViewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold" style="color: #0F1F3F;">
                    <?= esc(lang('App.eligibilityViewTitle') ?? 'Eligibility Details') ?>
                </h2>
                <button onclick="closeEligibilityView()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.eligibilityAgeLabel') ?? 'Age') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($eligibility['age'] ?? 'N/A') ?> <?= esc(lang('App.years') ?? 'years') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.eligibilityIncomeLabel') ?? 'Annual Income') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            ₹ <?= esc(number_format($eligibility['income'] ?? 0, 2)) ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.eligibilityResidencyLabel') ?? 'Residency') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($eligibility['residency'] === 'state' ? lang('App.eligibilityResidencyState') : lang('App.eligibilityResidencyOutside')) ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.eligibilityPropertyLabel') ?? 'Property Status') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($eligibility['property_status'] === 'none' ? lang('App.eligibilityPropertyNone') : lang('App.eligibilityPropertyHas')) ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.eligibilityStatus') ?? 'Eligibility Status') ?>
                        </label>
                        <p class="text-base">
                            <span class="px-3 py-1 rounded text-sm font-semibold bg-green-100 text-green-800">
                                <?= esc($eligibility['is_eligible'] ? 'Eligible' : 'Not Eligible') ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="sticky bottom-0 bg-gray-50 border-t px-6 py-4 flex justify-end">
                <button onclick="closeEligibilityView()" 
                        class="px-4 py-2 rounded-md font-semibold border-2"
                        style="border-color: #6B7280; color: #6B7280;">
                    <?= esc(lang('App.close') ?? 'Close') ?>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Payment View Modal -->
    <?php if (!empty($payment)): ?>
    <div id="paymentViewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold" style="color: #0F1F3F;">
                    <?= esc(lang('App.paymentViewTitle') ?? 'Payment Details') ?>
                </h2>
                <button onclick="closePaymentView()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.paymentId') ?? 'Payment ID') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($payment['id'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.paymentStatus') ?? 'Status') ?>
                        </label>
                        <p class="text-base">
                            <span class="px-3 py-1 rounded text-sm font-semibold bg-green-100 text-green-800">
                                <?= esc(ucfirst($payment['status'] ?? 'N/A')) ?>
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.paymentAmount') ?? 'Amount') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            ₹ <?= esc(number_format($payment['amount'] ?? 0, 2)) ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.paymentMethod') ?? 'Payment Method') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc(ucfirst($payment['payment_method'] ?? 'N/A')) ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.paymentDate') ?? 'Payment Date') ?>
                        </label>
                        <p class="text-sm" style="color: #6B7280;">
                            <?= esc($payment['created_at'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <?php if (!empty($payment['transaction_id'])): ?>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.transactionId') ?? 'Transaction ID') ?>
                        </label>
                        <p class="text-sm sm:text-base" style="color: #111827;">
                            <?= esc($payment['transaction_id']) ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="sticky bottom-0 bg-gray-50 border-t px-6 py-4 flex justify-end">
                <button onclick="closePaymentView()" 
                        class="px-4 py-2 rounded-md font-semibold border-2"
                        style="border-color: #6B7280; color: #6B7280;">
                    <?= esc(lang('App.close') ?? 'Close') ?>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Documents View Modal -->
    <?php if (!empty($documents)): ?>
    <div id="documentsViewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold" style="color: #0F1F3F;">
                    <?= esc(lang('App.documentsViewTitle') ?? 'Documents Details') ?>
                </h2>
                <button onclick="closeDocumentsView()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="space-y-4 mb-6">
                    <?php
                    $docFields = [
                        'identity_proof' => lang('App.docIdentityProof') ?? 'Identity Proof',
                        'address_proof' => lang('App.docAddressProof') ?? 'Address Proof',
                        'income_proof' => lang('App.docIncomeProof') ?? 'Income Proof',
                        'category_proof' => lang('App.docCategoryProof') ?? 'Category Proof',
                    ];
                    foreach ($docFields as $field => $label):
                        $files = json_decode($documents[$field] ?? '[]', true);
                        if (!empty($files)):
                    ?>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                                <?= esc($label) ?>
                            </label>
                            <div class="space-y-2">
                                <?php foreach ($files as $file): ?>
                                    <a href="<?= base_url($file) ?>" target="_blank" 
                                       class="block px-3 py-2 border rounded-md hover:bg-gray-50 text-sm"
                                       style="color: #0747A6;">
                                        <?= esc(basename($file)) ?>
                                        <svg class="w-4 h-4 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; endforeach; ?>
                    <?php if (!empty($documents['notes'])): ?>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.docNotes') ?? 'Notes') ?>
                        </label>
                        <p class="text-sm" style="color: #6B7280;">
                            <?= esc($documents['notes']) ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="sticky bottom-0 bg-gray-50 border-t px-6 py-4 flex justify-end">
                <button onclick="closeDocumentsView()" 
                        class="px-4 py-2 rounded-md font-semibold border-2"
                        style="border-color: #6B7280; color: #6B7280;">
                    <?= esc(lang('App.close') ?? 'Close') ?>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function showApplicationView() {
            const modal = document.getElementById('applicationViewModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                // Scroll to top of modal on mobile
                setTimeout(() => {
                    modal.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }

        function closeApplicationView() {
            const modal = document.getElementById('applicationViewModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        function showEligibilityView() {
            document.getElementById('eligibilityViewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEligibilityView() {
            document.getElementById('eligibilityViewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function showPaymentView() {
            document.getElementById('paymentViewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePaymentView() {
            document.getElementById('paymentViewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function showDocumentsView() {
            document.getElementById('documentsViewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDocumentsView() {
            document.getElementById('documentsViewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modals when clicking outside
        ['applicationViewModal', 'eligibilityViewModal', 'paymentViewModal', 'documentsViewModal'].forEach(function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        const closeFunc = modalId.replace('Modal', '');
                        if (closeFunc === 'applicationView') closeApplicationView();
                        else if (closeFunc === 'eligibilityView') closeEligibilityView();
                        else if (closeFunc === 'paymentView') closePaymentView();
                        else if (closeFunc === 'documentsView') closeDocumentsView();
                    }
                });
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeApplicationView();
                closeEligibilityView();
                closePaymentView();
                closeDocumentsView();
            }
        });
    </script>
</div>
