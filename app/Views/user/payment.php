<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-4">
        <a href="/user/documents" 
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

        <p class="mb-4" style="color: #4B5563;">
            <?= esc(lang('App.paymentInfo')) ?>
        </p>

        <form action="<?= site_url('user/payment') ?>" method="POST" class="space-y-4">
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
    </div>
</div>
