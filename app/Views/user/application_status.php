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
                case 'lottery_won':
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
                    $statusBg    = '#D1FAE5';
                    $statusColor = '#065F46';
                    break;
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

            <?php if (!empty($chalans)): ?>
            <div class="border-t pt-4 mt-4">
                <h2 class="text-lg font-semibold mb-3" style="color:#0F1F3F;">
                    <?= esc(lang('App.chalanDetails') ?? 'Chalan Details') ?>
                </h2>
                <?php foreach ($chalans as $ch): ?>
                <div class="mb-3 p-4 rounded-lg border" style="background-color: <?= ($ch['status'] ?? '') === 'paid' ? '#D1FAE5' : '#FEF3C7' ?>; border-color: <?= ($ch['status'] ?? '') === 'paid' ? '#10B981' : '#F59E0B' ?>;">
                    <p class="text-sm mb-2" style="color: #374151;">
                        <strong><?= esc(lang('App.chalanNumber') ?? 'Chalan No') ?>:</strong> <?= esc($ch['chalan_number']) ?><br>
                        <strong><?= esc(lang('App.amount') ?? 'Amount') ?>:</strong> ₹<?= number_format($ch['amount']) ?><br>
                        <strong><?= esc(lang('App.adminStatus') ?? 'Status') ?>:</strong>
                        <span class="px-2 py-0.5 rounded text-xs font-semibold <?= ($ch['status'] ?? '') === 'paid' ? 'bg-green-200 text-green-900' : 'bg-yellow-200 text-yellow-900' ?>">
                            <?= esc(ucfirst($ch['status'] ?? 'pending')) ?>
                        </span>
                    </p>
                    <?php if (($ch['status'] ?? '') === 'pending'): ?>
                    <a href="<?= site_url('user/chalan/' . $ch['id'] . '/pay') ?>" 
                       class="inline-block px-4 py-2 rounded-md font-semibold text-white"
                       style="background-color: #10B981;">
                        <?= esc(lang('App.chalanPayButton') ?? 'Pay Now') ?>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
