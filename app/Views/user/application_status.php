<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        <?= esc(lang('App.statusTitle')) ?>
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <?php if (empty($application)): ?>
            <p style="color:#4B5563;"><?= esc(lang('App.statusNone')) ?></p>
        <?php else: ?>
            <p class="mb-2" style="color:#4B5563;">
                <?= esc(lang('App.statusApplicationId')) ?>
                <strong><?= esc($application['id']) ?></strong>
            </p>

            <?php
            $overallStatus = $overallStatus ?? 'pending';
            switch ($overallStatus) {
                case 'verified':
                    $statusLabel = lang('App.dashboardStatusVerified') ?? 'Verified';
                    $statusBg    = '#D1FAE5';
                    $statusColor = '#065F46';
                    break;
                case 'rejected':
                    $statusLabel = lang('App.dashboardStatusRejected') ?? 'Rejected';
                    $statusBg    = '#FEE2E2';
                    $statusColor = '#991B1B';
                    break;
                case 'submitted':
                    $statusLabel = lang('App.dashboardStatusSubmitted') ?? 'Submitted (all steps completed)';
                    $statusBg    = '#DCFCE7';
                    $statusColor = '#166534';
                    break;
                case 'pending':
                    $statusLabel = lang('App.dashboardStatusPending') ?? 'Pending (some steps are still incomplete)';
                    $statusBg    = '#FEF3C7';
                    $statusColor = '#92400E';
                    break;
                default:
                    $statusLabel = lang('App.statusDraft') ?? 'Draft';
                    $statusBg    = '#E5E7EB';
                    $statusColor = '#374151';
                    break;
            }
            ?>

            <p class="mb-4" style="color:#4B5563;">
                <?= esc(lang('App.statusCurrentStatus')) ?>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold"
                      style="background-color: <?= esc($statusBg) ?>; color: <?= esc($statusColor) ?>;">
                    <?= esc($statusLabel) ?>
                </span>
            </p>

            <?php if ($overallStatus === 'verified'): ?>
            <div class="mb-4 p-3 rounded-md" style="background-color: #DBEAFE; border: 1px solid #3B82F6;">
                <p class="text-sm font-semibold" style="color: #1E40AF;">
                    <?= esc(lang('App.statusLotteryParticipation') ?? 'You are participating in the lottery round.') ?>
                </p>
            </div>
            <?php endif; ?>

            <p class="text-sm mb-4" style="color:#4B5563;">
                <?= esc(lang('App.statusFooterText')) ?>
            </p>

            <?php
            $eligibilityDone = $steps['eligibility']['completed'] ?? false;
            $applicationDone = $steps['application']['completed'] ?? false;
            $documentsDone   = $steps['documents']['completed'] ?? false;
            $paymentDone     = $steps['payment']['completed'] ?? false;
            ?>

            <div class="border-t pt-4 mt-4">
                <h2 class="text-lg font-semibold mb-3" style="color:#0F1F3F;">
                    <?= esc(lang('App.dashboardFlowTitle')) ?>
                </h2>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center justify-between">
                        <span><?= esc(lang('App.dashboardStep1')) ?></span>
                        <span class="font-semibold" style="color:<?= $eligibilityDone ? '#16A34A' : '#92400E' ?>;">
                            <?= $eligibilityDone ? esc(lang('App.statusCompleted')) : esc(lang('App.statusPending')) ?>
                        </span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span><?= esc(lang('App.dashboardStep2')) ?></span>
                        <span class="font-semibold" style="color:<?= $applicationDone ? '#16A34A' : '#92400E' ?>;">
                            <?= $applicationDone ? '✓ Completed' : 'Pending' ?>
                        </span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span><?= esc(lang('App.dashboardStep3')) ?></span>
                        <span class="font-semibold" style="color:<?= $paymentDone ? '#16A34A' : '#92400E' ?>;">
                            <?= $paymentDone ? '✓ Completed' : 'Pending' ?>
                        </span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span><?= esc(lang('App.dashboardStep4')) ?></span>
                        <span class="font-semibold" style="color:<?= $documentsDone ? '#16A34A' : '#92400E' ?>;">
                            <?= $documentsDone ? '✓ Completed' : 'Pending' ?>
                        </span>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
