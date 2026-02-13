<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-4">
        <a href="/user/application" 
           class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <?= esc(lang('App.backButton')) ?>
        </a>
    </div>
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        <?= esc(lang('App.paymentTitle')) ?>
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 rounded-md text-sm" style="background-color: #FEE2E2; color: #B91C1C;">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-3 rounded-md text-sm" style="background-color: #DCFCE7; color: #15803D;">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php 
        $payment = $payment ?? null;
        $paymentCompleted = $payment && $payment['status'] === 'success';
        ?>

        <?php if ($paymentCompleted): ?>
            <!-- Payment Completed Status -->
            <div class="mb-4 p-4 rounded-md" style="background-color: #DCFCE7; border: 2px solid #16A34A;">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5" style="color: #16A34A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-semibold" style="color: #15803D;">Payment Completed Successfully</h3>
                </div>
                <div class="space-y-2 text-sm" style="color: #166534;">
                    <div class="flex justify-between">
                        <span>Amount Paid:</span>
                        <strong>₹<?= esc(number_format($payment['amount'] ?? 1000, 2)) ?></strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Payment Date:</span>
                        <strong><?= isset($payment['created_at']) ? date('d M Y, h:i A', strtotime($payment['created_at'])) : 'N/A' ?></strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Status:</span>
                        <strong style="color: #16A34A;"><?= esc(ucfirst($payment['status'])) ?></strong>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-md" style="background-color: #DBEAFE; border: 1px solid #3B82F6;">
                <p class="text-sm mb-3" style="color: #1E40AF;">
                    <strong>Next Step:</strong> You can now proceed to upload your documents.
                </p>
                <a href="/user/documents"
                   class="inline-block px-4 py-2 rounded-md font-semibold text-white"
                   style="background-color: #0747A6;">
                    Go to Document Upload
                </a>
            </div>

            <!-- Option to resubmit payment (for testing) -->
            <div class="mt-4 p-4 rounded-md border border-gray-300">
                <p class="text-xs mb-3" style="color: #6B7280;">
                    For testing purposes, you can resubmit payment:
                </p>
                <form action="<?= site_url('user/payment') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <div class="flex justify-between items-center mb-2">
                        <span style="color: #4B5563;"><?= esc(lang('App.paymentAmountLabel')) ?></span>
                        <strong style="color: #0F1F3F;">₹1,000</strong>
                    </div>
                    <input type="hidden" name="amount" value="1000">
                    <button type="submit"
                            class="px-4 py-2 rounded-md font-semibold text-white text-sm"
                            style="background-color: #16A34A;">
                        Resubmit Payment (Test)
                    </button>
                </form>
            </div>
        <?php else: ?>
            <!-- Payment Form -->
            <p class="mb-4" style="color: #4B5563;">
                <?= esc(lang('App.paymentInfo')) ?>
            </p>

            <form action="<?= site_url('user/payment') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div class="flex justify-between items-center mb-2">
                    <span style="color: #4B5563;"><?= esc(lang('App.paymentAmountLabel')) ?></span>
                    <strong style="color: #0F1F3F;">₹1,000</strong>
                </div>

                <input type="hidden" name="amount" value="1000">

                <div class="flex flex-col sm:flex-row gap-3 mt-4">
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                            style="background-color: #16A34A;">
                        <?= esc(lang('App.paymentSubmitButton')) ?>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
