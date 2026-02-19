<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center print:mb-4" style="color: #0F1F3F;">
        <?= esc(lang('App.allotmentDetailsTitle')) ?>
    </h1>

    <?php if (empty($allotment)): ?>
        <div class="bg-white shadow-md rounded-lg p-6">
            <p style="color: #4B5563;"><?= esc(lang('App.allotmentNoAllotmentFound')) ?></p>
        </div>
    <?php else: ?>
        <!-- Congratulations Card -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                    <span class="text-2xl text-white">🎉</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold" style="color:#065F46;">
                        <?= esc(lang('App.lotteryCongratulations') ?? 'Congratulations!') ?>
                    </h2>
                    <p class="text-sm" style="color:#047857;">
                        <?= esc(lang('App.lotteryYouWon') ?? 'You are a winner in the lottery!') ?>
                    </p>
                </div>
            </div>
            <p class="text-sm" style="color:#065F46;">
                <?= esc(lang('App.allotmentPrintMessage')) ?>
            </p>
        </div>

        <!-- Allotment Letter Style -->
        <div class="bg-white shadow-md rounded-lg p-6 print:border print:border-gray-300 print:p-8">
            <div class="flex justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold" style="color:#0F1F3F;">Allotment Letter</h2>
                </div>
                <div class="text-sm" style="color:#4B5563;">
                    <div><strong>Date:</strong> <?= esc(date('d M Y')) ?></div>
                    <div><strong>Ref No:</strong> ALLOT-<?= esc($allotment['id']) ?></div>
                </div>
            </div>

            <div class="mb-6 text-sm" style="color:#111827;">
                <p><strong>To,</strong></p>
                <p><?= esc($allotment['full_name'] ?? 'Applicant') ?></p>
                <?php if (!empty($allotment['location'])): ?>
                    <p><?= esc($allotment['location']) ?></p>
                <?php endif; ?>
            </div>

            <?php
            $allotmentStatus = strtolower((string)($allotment['status'] ?? ''));
            $isAllotted = in_array($allotmentStatus, ['allotted', 'alloted'], true);
            ?>
            <p class="font-semibold mb-4 text-sm" style="color:#111827;">
                Subject: <?= $isAllotted ? 'Final Allotment of Plot' : 'Provisional Allotment of Plot' ?>
            </p>

            <div class="space-y-3 text-sm leading-relaxed" style="color:#111827;">
                <p>
                    This is with reference to your application for the Sindoor Vihar housing scheme.
                    <?php if ($isAllotted): ?>
                    We are pleased to confirm that you have been allotted the following plot:
                    <?php else: ?>
                    We are pleased to inform you that you have been selected in the lottery for
                    provisional allotment of the following plot:
                    <?php endif; ?>
                </p>
                <p>
                    <strong>Plot No:</strong> <?= esc($allotment['plot_number'] ?? 'N/A') ?><?php if (!empty($allotment['plot_category'])): ?>,
                    <strong>Category:</strong> <?= esc($allotment['plot_category']) ?><?php endif; ?>
                    <?php if (!empty($allotment['dimensions'])): ?>,
                    <strong>Size:</strong> <?= esc($allotment['dimensions']) ?><?php endif; ?>
                </p>
                <?php if ($isAllotted): ?>
                <p>
                    All formalities have been completed and your plot allotment is hereby confirmed.
                </p>
                <?php else: ?>
                <p>
                    You are requested to complete the required formalities and make payment as per
                    the scheme guidelines within the stipulated time. A duly signed final allotment
                    letter will be issued after completion of all formalities.
                </p>
                <?php endif; ?>
                <p>
                    This letter is generated electronically and does not require a physical signature.
                </p>
            </div>

            <div class="mt-8 text-sm" style="color:#111827;">
                <p>Thanking you,</p>
                <p class="mt-4 font-semibold">Executive Officer</p>
                <p>Sindoor Vihar, Chaksu Nagar Palika</p>
            </div>

            <?php if (!empty($chalan) && ($chalan['status'] ?? '') === 'pending'): ?>
            <div class="mt-6 p-4 rounded-lg print:hidden" style="background-color: #FEF3C7; border: 2px solid #F59E0B;">
                <h3 class="font-semibold mb-2" style="color: #92400E;">
                    <?= esc(lang('App.chalanFinalPayment') ?? 'Final Payment Chalan') ?>
                </h3>
                <p class="text-sm mb-3" style="color: #92400E;">
                    <strong><?= esc(lang('App.chalanNumber') ?? 'Chalan No') ?>:</strong> <?= esc($chalan['chalan_number']) ?><br>
                    <strong><?= esc(lang('App.amount') ?? 'Amount') ?>:</strong> ₹<?= number_format($chalan['amount']) ?>
                </p>
                <a href="/user/chalan/<?= esc($chalan['id']) ?>/pay"
                   class="inline-block px-6 py-2 rounded-md font-semibold text-white"
                   style="background-color:#10B981;">
                    <?= esc(lang('App.chalanPayButton') ?? 'Pay Now') ?>
                </a>
            </div>
            <?php elseif (!empty($chalan) && ($chalan['status'] ?? '') === 'paid'): ?>
            <div class="mt-6 p-4 rounded-lg print:hidden" style="background-color: #D1FAE5; border: 2px solid #10B981;">
                <p class="text-sm" style="color: #065F46;">
                    <strong>✓ <?= esc(lang('App.chalanPaid') ?? 'Chalan Paid') ?></strong> – <?= esc($chalan['chalan_number']) ?>
                </p>
            </div>
            <?php endif; ?>
            <div class="mt-6 flex flex-wrap gap-3 print:hidden">
                <button type="button"
                        onclick="window.print()"
                        class="px-6 py-2 rounded-md font-semibold text-white"
                        style="background-color:#0747A6;">
                    <?= esc(lang('App.allotmentPrintButton') ?? 'Print Allotment Letter') ?>
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>
