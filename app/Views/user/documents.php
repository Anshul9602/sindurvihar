<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        <?= esc(lang('App.docTitle') ?? 'Document Details') ?>
    </h1>

    <?php if (!empty($requiredForms)): ?>
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-3" style="color:#0F1F3F;"><?= esc(lang('App.docRequiredHeading') ?? 'Required Forms (as per selected category)') ?></h2>
            <p class="mb-3 text-sm" style="color:#4B5563;">
                <?= esc(lang('App.docRequiredText') ?? 'Please download the following forms, fill them as per instructions and then upload scanned copies.') ?>
            </p>
            <ul class="space-y-2">
                <?php foreach ($requiredForms as $form): ?>
                    <li class="flex items-center justify-between border rounded-md px-3 py-2">
                        <span class="text-sm" style="color:#4B5563;"><?= esc($form['label']) ?></span>
                        <a href="<?= esc($form['url']) ?>" target="_blank"
                           class="text-sm font-semibold px-3 py-1 rounded-md"
                           style="background-color:#0747A6; color:#FFFFFF;">
                            Download
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg p-6">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 rounded-md text-sm" style="background-color:#FEE2E2; color:#B91C1C;">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-3 rounded-md text-sm" style="background-color:#DCFCE7; color:#15803D;">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <p class="mb-4" style="color: #4B5563;">
            <?= esc(lang('App.docIntro') ?? 'Please indicate which documents you have prepared and upload them below.') ?>
        </p>

        <form action="<?= site_url('user/documents') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="flex items-center">
                <input id="doc-identity" type="checkbox" name="has_identity_proof" value="1"
                       class="mr-2" <?= !empty($documents['has_identity_proof']) ? 'checked' : '' ?>>
                <label for="doc-identity" class="text-sm" style="color:#4B5563;"><?= esc(lang('App.docIdentityLabel') ?? 'Identity proof (Aadhaar etc.)') ?></label>
            </div>
            <div class="ml-6">
                <input type="file" name="identity_files[]" multiple
                       class="text-xs text-gray-600">
            </div>
            <div class="flex items-center">
                <input id="doc-income" type="checkbox" name="has_income_proof" value="1"
                       class="mr-2" <?= !empty($documents['has_income_proof']) ? 'checked' : '' ?>>
                <label for="doc-income" class="text-sm" style="color:#4B5563;"><?= esc(lang('App.docIncomeLabel') ?? 'Income certificate') ?></label>
            </div>
            <div class="ml-6">
                <input type="file" name="income_files[]" multiple
                       class="text-xs text-gray-600">
            </div>
            <div class="flex items-center">
                <input id="doc-residence" type="checkbox" name="has_residence_proof" value="1"
                       class="mr-2" <?= !empty($documents['has_residence_proof']) ? 'checked' : '' ?>>
                <label for="doc-residence" class="text-sm" style="color:#4B5563;"><?= esc(lang('App.docResidenceLabel') ?? 'Residence proof') ?></label>
            </div>
            <div class="ml-6">
                <input type="file" name="residence_files[]" multiple
                       class="text-xs text-gray-600">
            </div>

            <div class="pt-2 border-t mt-4">
                <label class="block text-sm font-medium mb-1" style="color:#4B5563;"><?= esc(lang('App.docAnnexureLabel') ?? 'Filled Annexure Forms (scanned copies)') ?></label>
                <p class="text-xs mb-2" style="color:#6B7280;">
                    <?= esc(lang('App.docAnnexureHint') ?? 'Upload scanned images or PDF files of the filled Sindoor Vihar annexure forms.') ?>
                </p>
                <input type="file" name="annexure_files[]" multiple accept=".pdf,image/*"
                       class="text-xs text-gray-600">
            </div>

            <div>
                <label for="doc-notes" class="block text-sm font-medium mb-1"><?= esc(lang('App.docNotesLabel') ?? 'Other details / remarks') ?></label>
                <textarea id="doc-notes" name="notes" rows="3"
                          class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"><?= esc($documents['notes'] ?? '') ?></textarea>
            </div>

            <?php $hasDocs = !empty($documents); ?>
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                    style="background-color:#0747A6;">
                <?= $hasDocs ? (lang('App.docUpdateButton') ?? 'Update & Continue to Payment')
                             : (lang('App.docSaveButton') ?? 'Save & Continue to Payment') ?>
            </button>
        </form>
    </div>
</div>


