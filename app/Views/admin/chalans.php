<!-- Title Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminChalansTitle') ?? 'Chalans') ?>
    </h1>
    <p class="text-sm" style="color: #6B7280;">
        <?= esc(lang('App.adminChalansSubtitle') ?? 'View and manage all final payment chalans.') ?>
    </p>
</div>

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

<?php
$accounts = $paymentAccounts ?? [];
$paEdit   = $paymentAccountToEdit ?? [];
?>
<!-- Bank Account Cards -->
<div class="mb-6">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-sm flex items-center gap-2" style="color: #374151;">
            <span>🏦</span>
            <?= esc(lang('App.paymentBankAccount') ?? 'Bank Accounts') ?>
        </h3>
        <a href="<?= site_url('admin/chalans?add=1') ?>" id="addBankBtn" class="px-3 py-1.5 rounded-md text-sm font-medium text-white inline-block" style="background-color: #3B82F6;">
            <?= esc(lang('App.addBankAccount') ?? 'Add Bank Account') ?>
        </a>
    </div>
    <?php if (empty($accounts)): ?>
    <div class="bg-gray-50 rounded-lg border border-dashed border-gray-300 p-4 inline-block">
        <p class="text-sm" style="color: #6B7280;"><?= esc(lang('App.noBankAccountSet') ?? 'No bank account set') ?></p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($accounts as $pa): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="font-semibold text-sm" style="color: #374151;"><?= esc($pa['bank_name'] ?? $pa['account_name'] ?? 'Bank') ?></span>
                <div class="flex items-center gap-2">
                    <a href="<?= site_url('admin/chalans?edit=' . ($pa['id'] ?? 0)) ?>" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                        <?= esc(lang('App.adminEdit') ?? 'Edit') ?>
                    </a>
                    <?php if (!empty($pa['id'])): ?>
                    <form action="<?= site_url('admin/payment-account/' . $pa['id'] . '/delete') ?>" method="post" class="inline" onsubmit="return confirm('<?= esc(lang('App.adminConfirmDelete') ?? 'Delete this bank account?') ?>');">
                        <?= csrf_field() ?>
                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium"><?= esc(lang('App.delete') ?? 'Delete') ?></button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <div class="space-y-1.5 text-sm" style="color: #374151;">
                <?php if (!empty($pa['account_name'])): ?>
                <div><strong><?= esc(lang('App.paymentAccountName') ?? 'Account') ?>:</strong> <?= esc($pa['account_name']) ?></div>
                <?php endif; ?>
                <?php if (!empty($pa['bank_name'])): ?>
                <div><strong><?= esc(lang('App.paymentBankName') ?? 'Bank') ?>:</strong> <?= esc($pa['bank_name']) ?></div>
                <?php endif; ?>
                <?php if (!empty($pa['account_number'])): ?>
                <div><strong><?= esc(lang('App.paymentAccountNumber') ?? 'A/C No') ?>:</strong> <?= esc($pa['account_number']) ?></div>
                <?php endif; ?>
                <?php if (!empty($pa['ifsc_code'])): ?>
                <div><strong><?= esc(lang('App.paymentIfscCode') ?? 'IFSC') ?>:</strong> <?= esc($pa['ifsc_code']) ?></div>
                <?php endif; ?>
                <?php if (!empty($pa['branch'])): ?>
                <div><strong><?= esc(lang('App.paymentBranch') ?? 'Branch') ?>:</strong> <?= esc($pa['branch']) ?></div>
                <?php endif; ?>
                <?php if (isset($pa['is_active'])): ?>
                <span class="inline-block px-2 py-0.5 rounded text-xs <?= $pa['is_active'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' ?>">
                    <?= $pa['is_active'] ? (esc(lang('App.paymentAccountActive') ?? 'Active')) : (esc(lang('App.inactive') ?? 'Inactive')) ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Side Slide Panel - Payment Account Form -->
<div id="paymentAccountSlideOverlay" onclick="closePaymentAccountSlide()" class="fixed inset-0 bg-black/40 z-40 hidden transition-opacity"></div>
<div id="paymentAccountSlide" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-xl z-50 transform translate-x-full transition-transform duration-300 ease-out overflow-y-auto">
    <div class="sticky top-0 bg-white border-b border-gray-200 px-4 py-4 flex items-center justify-between">
        <h3 class="font-semibold" style="color: #0F1F3F;">
            <?= esc(lang('App.paymentAccountDetails') ?? 'Payment Account Details') ?>
        </h3>
        <button type="button" onclick="closePaymentAccountSlide()" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <div class="p-4">
        <form action="<?= site_url('admin/payment-account/save') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= esc($paEdit['id'] ?? 0) ?>">
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #374151;"><?= esc(lang('App.paymentAccountName') ?? 'Account Name') ?></label>
                <input type="text" name="account_name" value="<?= esc($paEdit['account_name'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="e.g. Sindoor Vihar Housing">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #374151;"><?= esc(lang('App.paymentBankName') ?? 'Bank Name') ?></label>
                <input type="text" name="bank_name" value="<?= esc($paEdit['bank_name'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="e.g. State Bank of India">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #374151;"><?= esc(lang('App.paymentAccountNumber') ?? 'Account Number') ?></label>
                <input type="text" name="account_number" value="<?= esc($paEdit['account_number'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="e.g. 12345678901">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #374151;"><?= esc(lang('App.paymentIfscCode') ?? 'IFSC Code') ?></label>
                <input type="text" name="ifsc_code" value="<?= esc($paEdit['ifsc_code'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="e.g. SBIN0001234">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #374151;"><?= esc(lang('App.paymentBranch') ?? 'Branch') ?></label>
                <input type="text" name="branch" value="<?= esc($paEdit['branch'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="e.g. Main Branch">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #374151;"><?= esc(lang('App.paymentUpiId') ?? 'UPI ID') ?></label>
                <input type="text" name="upi_id" value="<?= esc($paEdit['upi_id'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="e.g. sindoor@upi">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #374151;"><?= esc(lang('App.paymentInstructions') ?? 'Instructions') ?></label>
                <textarea name="instructions" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2"
                          placeholder="Additional instructions..."><?= esc($paEdit['instructions'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" <?= ($paEdit['is_active'] ?? 1) ? 'checked' : '' ?>>
                    <span class="text-sm" style="color: #374151;"><?= esc(lang('App.paymentAccountActive') ?? 'Active') ?></span>
                </label>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-md font-semibold text-white" style="background-color: #10B981;">
                    <?= esc(lang('App.save') ?? 'Save') ?>
                </button>
                <button type="button" onclick="closePaymentAccountSlide()" class="px-4 py-2 rounded-md font-semibold border border-gray-300" style="color: #374151;">
                    <?= esc(lang('App.cancel') ?? 'Cancel') ?>
                </button>
            </div>
        </form>
    </div>
</div>
<script>
function openPaymentAccountSlide() {
    document.getElementById('paymentAccountSlideOverlay').classList.remove('hidden');
    document.getElementById('paymentAccountSlide').classList.remove('translate-x-full');
    document.body.style.overflow = 'hidden';
}
function closePaymentAccountSlide() {
    document.getElementById('paymentAccountSlideOverlay').classList.add('hidden');
    document.getElementById('paymentAccountSlide').classList.add('translate-x-full');
    document.body.style.overflow = '';
}
(function(){
    if (/[?&]edit=\d+/.test(window.location.search) || /[?&]add=1/.test(window.location.search))
        openPaymentAccountSlide();
})();
</script>

<!-- Chalans List Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="font-semibold text-sm flex items-center gap-2" style="color: #374151;">
            <span>📝</span>
            <?= esc(lang('App.adminChalansTitle') ?? 'Chalans') ?>
        </h3>
        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
            <?= isset($chalans) ? count($chalans) : 0 ?> <?= esc(lang('App.adminTotal')) ?>
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.chalanNumber') ?? 'Chalan No') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminAllotmentName') ?? 'Applicant') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminApplicationMobile') ?? 'Mobile') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminAllotmentPlot') ?? 'Plot') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.amount') ?? 'Amount') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminStatus') ?? 'Status') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminAllotmentCreatedDate') ?? 'Created') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.chalanPaidDate') ?? 'Paid Date') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminActions') ?? 'Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($chalans)): ?>
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center" style="color: #9CA3AF;">
                            <?= esc(lang('App.adminNoChalansFound') ?? 'No chalans found.') ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($chalans as $ch): ?>
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-semibold" style="color: #111827;"><?= esc($ch['chalan_number'] ?? 'N/A') ?></td>
                            <td class="px-4 py-3" style="color: #111827;"><?= esc($ch['full_name'] ?? 'N/A') ?></td>
                            <td class="px-4 py-3" style="color: #111827;"><?= esc($ch['mobile'] ?? 'N/A') ?></td>
                            <td class="px-4 py-3" style="color: #111827;"><?= esc($ch['plot_number'] ?? 'N/A') ?></td>
                            <td class="px-4 py-3 font-semibold" style="color: #111827;">₹<?= number_format($ch['amount'] ?? 0) ?></td>
                            <td class="px-4 py-3">
                                <?php
                                $st = $ch['status'] ?? 'pending';
                                $stClass = ($st === 'paid') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                ?>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $stClass ?>">
                                    <?= esc(ucfirst($st)) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color: #6B7280;">
                                <?= isset($ch['created_at']) ? esc(date('d M Y', strtotime($ch['created_at']))) : '—' ?>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color: #6B7280;">
                                <?= ($ch['status'] ?? '') === 'paid' && !empty($ch['paid_at']) ? esc(date('d M Y', strtotime($ch['paid_at']))) : '—' ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <a href="<?= site_url('admin/chalans/' . ($ch['id'] ?? '')) ?>" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md font-medium text-sm bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm" 
                                       title="<?= esc(lang('App.adminViewChalan') ?? 'View Chalan') ?>">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <?= esc(lang('App.adminView') ?? 'View') ?>
                                    </a>
                                    <?php if (($ch['allotment_status'] ?? '') === 'allotted' || ($ch['allotment_status'] ?? '') === 'final'): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                                            <?= esc(ucfirst($ch['allotment_status'] ?? 'Allotted')) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
