<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-4">
        <a href="/user/dashboard"
           class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <?= esc(lang('App.backButton') ?? 'Back to Dashboard') ?>
        </a>
    </div>
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        <?= esc(lang('App.chalanPayTitle') ?? 'Final Payment – Chalan') ?>
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 rounded-md text-sm" style="background-color: #FEE2E2; color: #B91C1C;">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="mb-6 p-4 rounded-lg" style="background-color: #F0FDF4; border: 2px solid #10B981;">
            <h3 class="font-semibold mb-3" style="color: #065F46;">
                <?= esc(lang('App.chalanDetails') ?? 'Chalan Details') ?>
            </h3>
            <div class="space-y-2 text-sm" style="color: #047857;">
                <div class="flex justify-between">
                    <span><?= esc(lang('App.chalanNumber') ?? 'Chalan No') ?>:</span>
                    <strong><?= esc($chalan['chalan_number'] ?? 'N/A') ?></strong>
                </div>
                <div class="flex justify-between">
                    <span><?= esc(lang('App.amount') ?? 'Amount') ?>:</span>
                    <strong>₹<?= esc(number_format($chalan['amount'] ?? 0)) ?></strong>
                </div>
            </div>
        </div>

        <?php $paymentAccounts = $paymentAccounts ?? []; ?>
        <?php if (!empty($paymentAccounts)): ?>
        <h3 class="font-semibold mb-3" style="color: #1E40AF;">
            <?= esc(lang('App.paymentAccountDetails') ?? 'Chalan Bank Details') ?>
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <?php foreach ($paymentAccounts as $pa): ?>
            <div class="p-4 rounded-lg chalan-bank-card" style="background-color: #EFF6FF; border: 2px solid #3B82F6; cursor: pointer;" data-id="<?= (int)($pa['id'] ?? 0) ?>">
                <div class="font-semibold text-sm mb-2" style="color: #1E40AF;"><?= esc($pa['bank_name'] ?? $pa['account_name'] ?? 'Bank') ?></div>
                <div class="space-y-1.5 text-sm" style="color: #1E40AF;">
                    <?php if (!empty($pa['account_name'])): ?>
                    <div><strong><?= esc(lang('App.paymentAccountName') ?? 'Account Name') ?>:</strong> <?= esc($pa['account_name']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($pa['bank_name'])): ?>
                    <div><strong><?= esc(lang('App.paymentBankName') ?? 'Bank Name') ?>:</strong> <?= esc($pa['bank_name']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($pa['account_number'])): ?>
                    <div><strong><?= esc(lang('App.paymentAccountNumber') ?? 'Account Number') ?>:</strong> <?= esc($pa['account_number']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($pa['ifsc_code'])): ?>
                    <div><strong><?= esc(lang('App.paymentIfscCode') ?? 'IFSC Code') ?>:</strong> <?= esc($pa['ifsc_code']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($pa['branch'])): ?>
                    <div><strong><?= esc(lang('App.paymentBranch') ?? 'Branch') ?>:</strong> <?= esc($pa['branch']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($pa['upi_id'])): ?>
                    <div><strong><?= esc(lang('App.paymentUpiId') ?? 'UPI ID') ?>:</strong> <?= esc($pa['upi_id']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($pa['instructions'])): ?>
                    <div class="mt-2 pt-2 border-t border-blue-200"><strong><?= esc(lang('App.paymentInstructions') ?? 'Instructions') ?>:</strong> <?= nl2br(esc($pa['instructions'])) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form action="<?= site_url('user/chalan/' . $chalan['id'] . '/pay') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="amount" value="<?= esc($chalan['amount'] ?? 0) ?>">
            <?php if (!empty($paymentAccounts)): ?>
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #374151;">
                    <?= esc(lang('App.chalanSelectBank') ?? 'Select Bank Account (where you paid)') ?> <span class="text-red-500">*</span>
                </label>
                <select name="payment_account_id" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value=""><?= esc(lang('App.chalanSelectBankPlaceholder') ?? '-- Select bank --') ?></option>
                    <?php foreach ($paymentAccounts as $pa): ?>
                    <option value="<?= (int)($pa['id'] ?? 0) ?>"><?= esc($pa['bank_name'] ?? $pa['account_name'] ?? 'Bank') ?> – <?= esc($pa['account_number'] ?? '') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                    <?= esc(lang('App.transactionRef') ?? 'Transaction / Reference Number') ?>
                </label>
                <input type="text" name="transaction_ref" class="w-full border border-gray-300 rounded-md px-3 py-2"
                       placeholder="<?= esc(lang('App.transactionRefPlaceholder') ?? 'Enter UTR/transaction reference if paid via bank') ?>">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                    <?= esc(lang('App.chalanPaymentProof') ?? 'Payment Screenshot / Proof (optional)') ?>
                </label>
                <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.gif,.pdf" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                <p class="text-xs mt-1" style="color: #6B7280;"><?= esc(lang('App.chalanPaymentProofHelp') ?? 'Upload screenshot (JPG, PNG) or PDF. Max 5MB.') ?></p>
            </div>
            <p class="text-xs" style="color: #6B7280;">
                <?= esc(lang('App.chalanPayInfo') ?? 'Pay the amount at the designated bank or office, then enter your transaction reference and submit.') ?>
            </p>
            <button type="submit"
                    class="w-full px-6 py-3 rounded-md font-semibold text-white"
                    style="background-color: #10B981;">
                <?= esc(lang('App.chalanPayButton') ?? 'Confirm Payment') ?> – ₹<?= number_format($chalan['amount'] ?? 0) ?>
            </button>
        </form>
    </div>
</div>
<script>
document.querySelectorAll('.chalan-bank-card').forEach(function(card) {
    card.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var sel = document.querySelector('select[name="payment_account_id"]');
        if (sel && id) { sel.value = id; }
    });
});
</script>
