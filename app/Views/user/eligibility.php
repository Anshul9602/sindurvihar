<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-4">
        <a href="/user/dashboard" 
           class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <?= esc(lang('App.backToDashboard')) ?>
        </a>
    </div>
    <h1 class="text-3xl font-bold mb-4 text-center" style="color: #0F1F3F;">
        <?= esc(lang('App.eligibilityTitle')) ?>
    </h1>

    <!-- Eligibility criteria summary (as per scheme booklet) -->
    <div class="bg-white shadow-sm rounded-lg p-4 mb-6 border border-gray-200">
        <h2 class="text-lg font-semibold mb-2" style="color:#0F1F3F;">
            <?= esc(lang('App.eligibilityCriteriaHeading')) ?>
        </h2>
        <ul class="list-disc pl-5 space-y-1 text-sm" style="color:#4B5563;">
            <li><?= esc(lang('App.eligibilityCriteria1')) ?></li>
            <li><?= esc(lang('App.eligibilityCriteria2')) ?></li>
            <li><?= esc(lang('App.eligibilityCriteria3')) ?></li>
            <li><?= esc(lang('App.eligibilityCriteria4')) ?></li>
        </ul>
        <p class="mt-2 text-xs" style="color:#6B7280;">
            <?= esc(lang('App.eligibilityCriteriaNote')) ?>
        </p>
    </div>

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

    <?php $existing = $eligibility ?? null; ?>

    <form action="<?= site_url('user/eligibility') ?>" method="POST"
          class="bg-white shadow-md rounded-lg p-6 space-y-4">
        <div>
            <label for="age" class="block text-sm font-medium mb-1"><?= esc(lang('App.eligibilityAgeLabel')) ?></label>
            <input id="age" name="age" type="number" min="18" max="70" required
                   value="<?= esc(old('age', $existing['age'] ?? '')) ?>"
                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            <p class="mt-1 text-xs" style="color: #6B7280;"></p>
        </div>

        <div>
            <label for="income" class="block text-sm font-medium mb-1"><?= esc(lang('App.eligibilityIncomeLabel')) ?></label>
            <input id="income" name="income" type="number" min="0" required
                   value="<?= esc(old('income', $existing['income'] ?? '')) ?>"
                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
        </div>

        <div>
            <label for="residency" class="block text-sm font-medium mb-1"><?= esc(lang('App.eligibilityResidencyLabel')) ?></label>
            <select id="residency" name="residency"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                <?php $resVal = old('residency', $existing['residency'] ?? 'state'); ?>
                <option value="state" <?= $resVal === 'state' ? 'selected' : '' ?>><?= esc(lang('App.eligibilityResidencyState')) ?></option>
                <option value="outside" <?= $resVal === 'outside' ? 'selected' : '' ?>><?= esc(lang('App.eligibilityResidencyOutside')) ?></option>
            </select>
        </div>

        <div>
            <label for="property" class="block text-sm font-medium mb-1"><?= esc(lang('App.eligibilityPropertyLabel')) ?></label>
            <select id="property" name="property"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                <?php $propVal = old('property', $existing['property_status'] ?? 'none'); ?>
                <option value="none" <?= $propVal === 'none' ? 'selected' : '' ?>><?= esc(lang('App.eligibilityPropertyNone')) ?></option>
                <option value="has" <?= $propVal === 'has' ? 'selected' : '' ?>><?= esc(lang('App.eligibilityPropertyHas')) ?></option>
            </select>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <?php $hasExisting = !empty($existing); ?>
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                <?= esc(lang($hasExisting ? 'App.eligibilityUpdateButton' : 'App.eligibilityCheckButton')) ?>
            </button>
        </div>
    </form>
</div>
