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
                            <?= esc(lang('App.docDownload')) ?>
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

        <form id="documents-form" action="<?= site_url('user/documents') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="flex items-center">
                <input id="doc-identity" type="checkbox" name="has_identity_proof" value="1"
                       class="mr-2" <?= !empty($documents['has_identity_proof']) ? 'checked' : '' ?>>
                <label for="doc-identity" class="text-sm" style="color:#4B5563;"><?= esc(lang('App.docIdentityLabel') ?? 'Identity proof (Aadhaar etc.)') ?></label>
            </div>
            <div class="ml-6">
                <input type="file" id="identity-files" name="identity_files[]" multiple
                       class="text-xs text-gray-600">
                <div id="identity-error" class="text-red-600 text-xs mt-1 hidden"></div>
                <?php if (!empty($documents['identity_files'])): 
                    $existingIdentityFiles = json_decode($documents['identity_files'], true);
                    if (!empty($existingIdentityFiles)):
                ?>
                    <p class="text-xs text-gray-500 mt-1"><?= esc(lang('App.docExistingFiles') ?? 'Existing files uploaded') ?>: <?= count($existingIdentityFiles) ?></p>
                <?php endif; endif; ?>
            </div>
            <div class="flex items-center">
                <input id="doc-income" type="checkbox" name="has_income_proof" value="1"
                       class="mr-2" <?= !empty($documents['has_income_proof']) ? 'checked' : '' ?>>
                <label for="doc-income" class="text-sm" style="color:#4B5563;"><?= esc(lang('App.docIncomeLabel') ?? 'Income certificate') ?></label>
            </div>
            <div class="ml-6">
                <input type="file" id="income-files" name="income_files[]" multiple
                       class="text-xs text-gray-600">
                <div id="income-error" class="text-red-600 text-xs mt-1 hidden"></div>
                <?php if (!empty($documents['income_files'])): 
                    $existingIncomeFiles = json_decode($documents['income_files'], true);
                    if (!empty($existingIncomeFiles)):
                ?>
                    <p class="text-xs text-gray-500 mt-1"><?= esc(lang('App.docExistingFiles') ?? 'Existing files uploaded') ?>: <?= count($existingIncomeFiles) ?></p>
                <?php endif; endif; ?>
            </div>
            <div class="flex items-center">
                <input id="doc-residence" type="checkbox" name="has_residence_proof" value="1"
                       class="mr-2" <?= !empty($documents['has_residence_proof']) ? 'checked' : '' ?>>
                <label for="doc-residence" class="text-sm" style="color:#4B5563;"><?= esc(lang('App.docResidenceLabel') ?? 'Residence proof') ?></label>
            </div>
            <div class="ml-6">
                <input type="file" id="residence-files" name="residence_files[]" multiple
                       class="text-xs text-gray-600">
                <div id="residence-error" class="text-red-600 text-xs mt-1 hidden"></div>
                <?php if (!empty($documents['residence_files'])): 
                    $existingResidenceFiles = json_decode($documents['residence_files'], true);
                    if (!empty($existingResidenceFiles)):
                ?>
                    <p class="text-xs text-gray-500 mt-1"><?= esc(lang('App.docExistingFiles') ?? 'Existing files uploaded') ?>: <?= count($existingResidenceFiles) ?></p>
                <?php endif; endif; ?>
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

<script>
(function() {
    var form = document.getElementById('documents-form');
    if (!form) return;

    // Get existing file counts from PHP
    var existingFiles = {
        identity: <?= !empty($documents['identity_files']) ? count(json_decode($documents['identity_files'], true)) : 0 ?>,
        income: <?= !empty($documents['income_files']) ? count(json_decode($documents['income_files'], true)) : 0 ?>,
        residence: <?= !empty($documents['residence_files']) ? count(json_decode($documents['residence_files'], true)) : 0 ?>
    };

    form.addEventListener('submit', function(e) {
        var identityCheckbox = document.getElementById('doc-identity');
        var incomeCheckbox = document.getElementById('doc-income');
        var residenceCheckbox = document.getElementById('doc-residence');
        
        var identityFiles = document.getElementById('identity-files');
        var incomeFiles = document.getElementById('income-files');
        var residenceFiles = document.getElementById('residence-files');

        var identityError = document.getElementById('identity-error');
        var incomeError = document.getElementById('income-error');
        var residenceError = document.getElementById('residence-error');

        var hasError = false;

        // Clear previous errors
        identityError.classList.add('hidden');
        incomeError.classList.add('hidden');
        residenceError.classList.add('hidden');

        // Validate Identity Proof
        if (identityCheckbox.checked) {
            var identityFileCount = identityFiles.files.length;
            if (identityFileCount === 0 && existingFiles.identity === 0) {
                identityError.textContent = '<?= esc(lang('App.docFileRequired') ?? 'Please upload at least one file for identity proof') ?>';
                identityError.classList.remove('hidden');
                hasError = true;
            }
        }

        // Validate Income Proof
        if (incomeCheckbox.checked) {
            var incomeFileCount = incomeFiles.files.length;
            if (incomeFileCount === 0 && existingFiles.income === 0) {
                incomeError.textContent = '<?= esc(lang('App.docFileRequired') ?? 'Please upload at least one file for income proof') ?>';
                incomeError.classList.remove('hidden');
                hasError = true;
            }
        }

        // Validate Residence Proof
        if (residenceCheckbox.checked) {
            var residenceFileCount = residenceFiles.files.length;
            if (residenceFileCount === 0 && existingFiles.residence === 0) {
                residenceError.textContent = '<?= esc(lang('App.docFileRequired') ?? 'Please upload at least one file for residence proof') ?>';
                residenceError.classList.remove('hidden');
                hasError = true;
            }
        }

        if (hasError) {
            e.preventDefault();
            alert('<?= esc(lang('App.docValidationError') ?? 'Please upload files for all checked document types') ?>');
            return false;
        }
    });
})();
</script>

