<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-4 rounded-md border border-green-300 bg-green-50 px-4 py-3 text-sm" style="color:#166534;">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-sm" style="color:#B91C1C;">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<div class="mb-6">
    <a href="<?= site_url('admin/chalans') ?>" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        <?= esc(lang('App.adminBackToList') ?? 'Back to Chalans') ?>
    </a>
</div>

<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminChalanDetailTitle') ?? 'Chalan Detail') ?>
    </h1>
    <p class="text-sm" style="color: #6B7280;">
        <?= esc(lang('App.adminChalanDetailSubtitle') ?? 'View and verify chalan payment details.') ?>
    </p>
</div>

<?php if (empty($chalan)): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <p style="color: #4B5563;"><?= esc(lang('App.adminChalanNotFound') ?? 'Chalan not found') ?></p>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 w-full">
        <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
            <?= esc(lang('App.chalanDetails') ?? 'Chalan Details') ?>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.chalanNumber') ?? 'Chalan No') ?></label>
                <p class="font-semibold" style="color: #111827;"><?= esc($chalan['chalan_number'] ?? 'N/A') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.amount') ?? 'Amount') ?></label>
                <p class="font-semibold" style="color: #111827;">₹<?= number_format($chalan['amount'] ?? 0) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.adminStatus') ?? 'Status') ?></label>
                <?php $st = $chalan['status'] ?? 'pending'; $stClass = ($st === 'paid') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>
                <span class="px-2 py-1 rounded text-sm font-semibold <?= $stClass ?>"><?= esc(ucfirst($st)) ?></span>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.adminAllotmentName')) ?></label>
                <p style="color: #111827;"><?= esc($chalan['full_name'] ?? 'N/A') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.adminApplicationMobile') ?? 'Mobile') ?></label>
                <p style="color: #111827;"><?= esc($chalan['mobile'] ?? 'N/A') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.adminAllotmentPlot')) ?></label>
                <p class="font-semibold" style="color: #111827;"><?= esc($chalan['plot_number'] ?? 'N/A') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.adminAllotmentCreatedDate') ?? 'Created') ?></label>
                <p style="color: #111827;"><?= isset($chalan['created_at']) ? esc(date('d M Y, h:i A', strtotime($chalan['created_at']))) : '—' ?></p>
            </div>
            <?php if (($chalan['status'] ?? '') === 'paid'): ?>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.chalanPaidDate') ?? 'Paid Date') ?></label>
                <p style="color: #111827;"><?= !empty($chalan['paid_at']) ? esc(date('d M Y, h:i A', strtotime($chalan['paid_at']))) : '—' ?></p>
            </div>
            <?php endif; ?>
        </div>

        <?php if (($chalan['status'] ?? '') === 'paid'): ?>
        <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold mb-3" style="color: #0F1F3F;">
                <?= esc(lang('App.adminPaymentVerification') ?? 'Payment & Verification') ?>
            </h3>
            <?php if (!empty($payment['transaction_ref'])): ?>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.transactionRef') ?? 'Transaction / Reference') ?></label>
                <p style="color: #111827;"><?= esc($payment['transaction_ref']) ?></p>
            </div>
            <?php endif; ?>
            <?php if (!empty($paymentAccount)): ?>
            <div class="mb-3 p-3 rounded-lg" style="background-color: #EFF6FF; border: 1px solid #3B82F6;">
                <label class="block text-sm font-medium mb-1" style="color: #1E40AF;"><?= esc(lang('App.paymentBankName') ?? 'Bank') ?></label>
                <p style="color: #1E40AF;"><?= esc($paymentAccount['bank_name'] ?? $paymentAccount['account_name'] ?? 'N/A') ?> – <?= esc($paymentAccount['account_number'] ?? '') ?></p>
            </div>
            <?php endif; ?>
            <?php if (!empty($chalan['payment_proof'])): ?>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.chalanPaymentProof') ?? 'Payment Proof') ?></label>
                <a href="/<?= esc($chalan['payment_proof']) ?>" target="_blank" rel="noopener" class="text-blue-600 hover:underline"><?= esc(lang('App.adminDownload') ?? 'View/Download') ?></a>
            </div>
            <?php endif; ?>
            <?php if (!empty($chalan['verified_at'])): ?>
            <div class="p-3 rounded-lg bg-green-50 border border-green-200">
                <span class="text-sm font-semibold" style="color: #166534;"><?= esc(lang('App.adminChalanVerified') ?? 'Verified') ?></span>
                <span class="text-sm" style="color: #6B7280;"> – <?= esc(date('d M Y, h:i A', strtotime($chalan['verified_at']))) ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="border-t pt-6 flex flex-wrap gap-3">
            <?php
            $chalanId = $chalanId ?? $chalan['id'] ?? 0;
            $status = strtolower((string)($chalan['status'] ?? ''));
            $isPaid = $status === 'paid';
            $isVerified = isset($chalan['verified_at']) && $chalan['verified_at'] !== null && $chalan['verified_at'] !== '';
            $allotmentStatus = strtolower((string)($chalan['allotment_status'] ?? 'provisional'));
            $canVerify = $isPaid && !$isVerified;
            $canAllocate = $isPaid && $isVerified && in_array($allotmentStatus, ['provisional', 'final'], true);
            $isAllocated = in_array($allotmentStatus, ['allotted', 'alloted'], true);
            ?>
            <?php if ($canVerify && $chalanId): ?>
                <form action="<?= site_url('admin/chalans/' . $chalanId . '/verify') ?>" method="post" class="inline" onsubmit="return confirm('<?= esc(lang('App.adminConfirmVerifyChalan') ?? 'Verify this chalan payment?') ?>');">
                    <?= csrf_field() ?>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-md font-semibold text-white bg-amber-600 hover:bg-amber-700 transition" style="background:green;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?= esc(lang('App.adminVerifyChalan') ?? 'Verify') ?>
                    </button>
                </form>
            <?php endif; ?>
            <?php if ($canAllocate && $chalanId): ?>
                <form action="<?= site_url('admin/chalans/' . $chalanId . '/mark-allotted') ?>" method="post" class="inline" onsubmit="return confirm('<?= esc(lang('App.adminConfirmMarkAllotted') ?? 'Mark allotment as Allotted?') ?>');">
                    <?= csrf_field() ?>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-md font-semibold text-white bg-green-600 hover:bg-green-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <?= esc(lang('App.adminMarkAllotted') ?? 'Allocate') ?>
                    </button>
                </form>
            <?php elseif ($isAllocated): ?>
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-md font-semibold bg-green-100 text-green-800 border border-green-200">
                    <?= esc(lang('App.adminAllottedStatus') ?? 'Allotted') ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
