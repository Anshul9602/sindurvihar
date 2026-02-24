<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-4">
        <a href="/user/eligibility" 
           class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <?= esc(lang('App.backButton')) ?>
        </a>
    </div>
    <h1 class="text-3xl font-bold mb-4 text-center" style="color: #0F1F3F;">
        <?= $isEditMode ? esc(lang('App.appEditTitle') ?? 'Edit Application') : esc(lang('App.appTitle')) ?>
    </h1>

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

    <?php 
    $application = $application ?? null;
    $isEditMode = $isEditMode ?? false;
    $userCategory = $userCategory ?? null;
    $user = $user ?? null;
    $eligibility = $eligibility ?? null;
    $verifiedAadhaar = $verifiedAadhaar ?? null;
    
    // Auto-fill values: Priority: application data > verified Aadhaar KYC > user/eligibility data
    // Name: application > Aadhaar KYC > user registration
    $autoFullName = old('full_name', $application['full_name'] ?? ($verifiedAadhaar['kyc_name'] ?? ($user['name'] ?? '')));
    // Mobile: application > user registration
    $autoMobile = old('mobile', $application['mobile'] ?? ($user['mobile'] ?? ''));
    // Age: application > eligibility (Aadhaar doesn't have age, calculate from DOB if needed)
    $autoAge = old('age', $application['age'] ?? ($eligibility['age'] ?? ''));
    // Income: application > eligibility
    $autoIncome = old('income', $application['income'] ?? ($eligibility['income'] ?? ''));
    // Address: application > Aadhaar KYC
    $autoAddress = old('address', $application['address'] ?? ($verifiedAadhaar['kyc_address'] ?? ''));
    // Aadhaar: application > verified Aadhaar (last 4 digits + full if available)
    $autoAadhaar = old('aadhaar', $application['aadhaar'] ?? ($verifiedAadhaar ? $verifiedAadhaar['aadhaar_number'] : ''));
    ?>

    <form id="application-form"
          action="<?= site_url('user/application/submit') ?>"
          method="POST"
          class="bg-white shadow-md rounded-lg p-6 space-y-4">
        <?php if ($isEditMode && $application): ?>
            <input type="hidden" name="application_id" value="<?= esc($application['id']) ?>">
        <?php endif; ?>
        <h2 class="text-xl font-semibold mb-2" style="color: #0F1F3F;"><?= esc(lang('App.appIdentitySection')) ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="app-name" class="block text-sm font-medium mb-1"><?= esc(lang('App.appFullNameLabel')) ?></label>
                <input id="app-name" name="full_name" type="text" required
                       value="<?= esc($autoFullName) ?>"
                       <?= !empty($verifiedAadhaar) && ($verifiedAadhaar['verified'] ?? 0) == 1 ? 'readonly' : '' ?>
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="app-aadhaar" class="block text-sm font-medium mb-1"><?= esc(lang('App.appAadhaarLabel')) ?></label>
                <div class="flex gap-2 items-center">
                    <input id="app-aadhaar" name="aadhaar" type="text" required maxlength="12" pattern="[0-9]{12}"
                           value="<?= esc($autoAadhaar) ?>"
                           class="flex-1 border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                           placeholder="Enter 12-digit Aadhaar number" <?= !empty($verifiedAadhaar) && $verifiedAadhaar['verified'] == 1 ? 'readonly' : '' ?>>
                    <button type="button" id="generate-otp-btn" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition whitespace-nowrap">
                        <?= esc(lang('App.appAadhaarVerifyButton')) ?>
                    </button>
                    <div id="aadhaar-verified-icon" class="hidden flex items-center justify-center w-10 h-10 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500"><?= esc(lang('App.appAadhaarVerificationMandatory')) ?></p>
                
                
            </div>
        </div>

        <!-- DigiLocker success box (shown after initiate) -->
        <div id="app-digilocker-success-box" class="hidden mt-4 p-4 rounded-md" style="background-color: #D1FAE5; border: 1px solid #10B981;">
            <p class="font-semibold text-green-800 mb-2">Aadhaar verification initiated successfully!</p>
            <p class="text-sm text-green-800 mb-2">Complete your Aadhaar verification:</p>
            <a id="app-digilocker-link" href="#" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 rounded-md font-medium text-white mb-3" style="background: linear-gradient(135deg, #059669, #047857);">
                Open DigiLocker Verification
            </a>
            <div id="app-digilocker-transid" class="text-sm text-green-800 mb-1"></div>
            <p class="text-sm text-green-700 mt-2">After completing verification in the new tab, click <strong>Check Aadhaar Status</strong> below.</p>
        </div>

        <!-- Aadhaar OTP Verification Section -->
        <div id="aadhaar-otp-section" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <h3 class="text-sm font-semibold mb-3" style="color:#1D4ED8;"><?= esc(lang('App.appAadhaarOtpVerification')) ?></h3>
            <div id="otp-generate-status" class="mb-3 text-sm"></div>
            <div id="otp-input-section" class="hidden">
                <div class="flex gap-2 mb-2">
                    <input id="aadhaar-otp" type="text" maxlength="6" pattern="[0-9]{6}"
                           placeholder="<?= esc(lang('App.appAadhaarOtpPlaceholder')) ?>"
                           class="flex-1 border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    <button type="button" id="verify-otp-btn"
                            class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 transition">
                        <?= esc(lang('App.appAadhaarVerifyOtp')) ?>
                    </button>
                </div>
                <button type="button" id="resend-otp-btn" 
                        class="text-sm text-blue-600 hover:underline">
                    <?= esc(lang('App.appAadhaarResendOtp')) ?>
                </button>
            </div>
            <div id="aadhaar-verified-status" class="hidden mt-2 p-2 bg-green-100 border border-green-300 rounded text-sm text-green-800">
                ✓ <?= esc(lang('App.appAadhaarVerifiedSuccess')) ?>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <label for="app-fh-name" class="block text-sm font-medium mb-1"><?= esc(lang('App.appFatherHusbandLabel')) ?></label>
                <input id="app-fh-name" name="father_husband_name" type="text" required
                       value="<?= esc(old('father_husband_name', $application['father_husband_name'] ?? '')) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="app-age" class="block text-sm font-medium mb-1"><?= esc(lang('App.appAgeLabel')) ?></label>
                <input id="app-age" name="age" type="number" min="18" max="70" required
                       value="<?= esc($autoAge) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="app-mobile" class="block text-sm font-medium mb-1"><?= esc(lang('App.appMobileLabel')) ?></label>
                <input id="app-mobile" name="mobile" type="text" required
                       value="<?= esc($autoMobile) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
        </div>

        <h2 class="text-xl font-semibold mt-4 mb-2" style="color: #0F1F3F;"><?= esc(lang('App.appResidenceSection')) ?></h2>
        <div class="space-y-4">
            <div>
                <label for="app-address" class="block text-sm font-medium mb-1"><?= esc(lang('App.appAddressLabel')) ?></label>
                <input id="app-address" name="address" type="text" required
                       value="<?= esc($autoAddress) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="app-tehsil" class="block text-sm font-medium mb-1"><?= esc(lang('App.appTehsilLabel')) ?></label>
                    <input id="app-tehsil" name="tehsil" type="text" required
                           value="<?= esc(old('tehsil', $application['tehsil'] ?? '')) ?>"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                </div>
        <div>
                    <label for="app-district" class="block text-sm font-medium mb-1"><?= esc(lang('App.appDistrictLabel')) ?></label>
                    <input id="app-district" name="district" type="text" required
                           value="<?= esc(old('district', $application['district'] ?? '')) ?>"
                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                </div>
                <div>
                    <label for="app-state" class="block text-sm font-medium mb-1"><?= esc(lang('App.appStateLabel')) ?></label>
                    <input id="app-state" name="state" type="text" 
                           value="<?= esc(old('state', $application['state'] ?? 'Rajasthan')) ?>" 
                           readonly
                           class="w-full border rounded-md px-3 py-2 bg-gray-100 focus:outline-none focus:ring focus:border-blue-500">
                </div>
            </div>
        </div>

        <h2 class="text-xl font-semibold mt-4 mb-2" style="color: #0F1F3F;"><?= esc(lang('App.appIncomeSection')) ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="app-income" class="block text-sm font-medium mb-1"><?= esc(lang('App.appAnnualIncomeLabel')) ?></label>
                <input id="app-income" name="income" type="number" required
                       value="<?= esc($autoIncome) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="app-category" class="block text-sm font-medium mb-1"><?= esc(lang('App.appCategoryLabel')) ?></label>
                <?php $currentCat = old('income_category', $application['income_category'] ?? 'EWS'); ?>
                <?php
                    $soldierCategories = ['Serving Soldier', 'Ex-Serviceman', 'Soldier Widow/Dependent', 'Soldier Category', 'Soldier', 'Army'];
                    $soldierSelected   = in_array($currentCat, $soldierCategories, true);
                ?>
                <select id="app-category" name="income_category"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    <option value="EWS" <?= $currentCat === 'EWS' ? 'selected' : '' ?>>EWS</option>
                    <option value="LIG" <?= $currentCat === 'LIG' ? 'selected' : '' ?>>LIG</option>
                    <option value="MIG" <?= $currentCat === 'MIG' ? 'selected' : '' ?>>MIG</option>
                    <option value="Govt" <?= $currentCat === 'Govt' ? 'selected' : '' ?>>Govt</option>
                    <option value="Soldier" <?= $soldierSelected ? 'selected' : '' ?>>Soldier</option>
                </select>
            </div>
        </div>

        <h2 class="text-xl font-semibold mt-4 mb-2" style="color: #0F1F3F;"><?= esc(lang('App.appLotterySection') ?? 'Lottery & Reservation Details') ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="app-caste-category" class="block text-sm font-medium mb-1"><?= esc(lang('App.appCasteCategoryLabel') ?? 'Caste Category') ?></label>
                <?php 
                    // Pre-select from application if exists, otherwise from user registration, otherwise empty
                    $currentCaste = old('caste_category', $application['caste_category'] ?? ($userCategory ?? ''));
                    // Map user category to caste category if needed (ST, SC, etc. match directly)
                    if (!empty($currentCaste) && !in_array($currentCaste, ['SC', 'ST', 'OBC', 'GENERAL'])) {
                        // If user category doesn't match, try to map it
                        $categoryMap = [
                            'ST' => 'ST',
                            'SC' => 'SC',
                            'OBC' => 'OBC',
                            'General' => 'GENERAL',
                            'GENERAL' => 'GENERAL',
                        ];
                        $currentCaste = $categoryMap[$currentCaste] ?? '';
                    }
                ?>
                <select id="app-caste-category" name="caste_category"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    <option value=""><?= esc(lang('App.selectOption') ?? 'Select') ?></option>
                    <option value="SC" <?= $currentCaste === 'SC' ? 'selected' : '' ?>>SC</option>
                    <option value="ST" <?= $currentCaste === 'ST' ? 'selected' : '' ?>>ST</option>
                    <option value="OBC" <?= $currentCaste === 'OBC' ? 'selected' : '' ?>>OBC</option>
                    <option value="GENERAL" <?= $currentCaste === 'GENERAL' ? 'selected' : '' ?>>GENERAL</option>
                </select>
            </div>
        </div>

        <div class="mt-4 space-y-3">
            <p class="text-sm font-medium mb-2" style="color: #0F1F3F;"><?= esc(lang('App.appReservationCategoriesLabel') ?? 'Reservation Categories (Select all that apply)') ?></p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label class="inline-flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" name="is_disabled" value="1"
                           <?= old('is_disabled', $application['is_disabled'] ?? 0) ? 'checked' : '' ?>
                           class="mt-1">
                    <span class="text-sm" style="color:#4B5563;"><?= esc(lang('App.appDisabledLabel') ?? 'Disabled ') ?></span>
                </label>
                <label class="inline-flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" name="is_single_woman" value="1"
                           <?= old('is_single_woman', $application['is_single_woman'] ?? 0) ? 'checked' : '' ?>
                           class="mt-1">
                    <span class="text-sm" style="color:#4B5563;"><?= esc(lang('App.appSingleWomanLabel') ?? 'Single Woman/Widow ') ?></span>
                </label>
                <label class="inline-flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" name="is_transgender" value="1"
                           <?= old('is_transgender', $application['is_transgender'] ?? 0) ? 'checked' : '' ?>
                           class="mt-1">
                    <span class="text-sm" style="color:#4B5563;"><?= esc(lang('App.appTransgenderLabel') ?? 'Transgender') ?></span>
                </label>
                
                <label class="inline-flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" name="is_media" value="1"
                           <?= old('is_media', $application['is_media'] ?? 0) ? 'checked' : '' ?>
                           class="mt-1">
                    <span class="text-sm" style="color:#4B5563;"><?= esc(lang('App.appMediaLabel') ?? 'Media') ?></span>
                </label>
                
            </div>
        </div>

        <!-- Required forms download area (updates when category changes) -->
        <div id="category-forms-box" class="mt-4 hidden bg-blue-50 border border-blue-200 rounded-md p-4">
            <h3 class="text-sm font-semibold mb-2" style="color:#1D4ED8;"><?= esc(lang('App.appRequiredFormsHeading')) ?></h3>
            <p class="text-xs mb-2" style="color:#4B5563;"><?= esc(lang('App.appRequiredFormsText')) ?></p>
            <div id="category-forms-links" class="space-y-1 text-sm"></div>
        </div>

        <div class="mt-4 space-y-2 text-sm" style="color:#4B5563;">
            <label class="inline-flex items-start gap-2">
                <input type="checkbox" name="declaration_truth" value="1" required
                       <?= old('declaration_truth', $application['declaration_truth'] ?? 0) ? 'checked' : '' ?>
                       class="mt-1">
                <span><?= esc(lang('App.appDeclarationTruth')) ?></span>
            </label>
            <label class="inline-flex items-start gap-2">
                <input type="checkbox" name="declaration_cancellation" value="1" required
                       <?= old('declaration_cancellation', $application['declaration_cancellation'] ?? 0) ? 'checked' : '' ?>
                       class="mt-1">
                <span><?= esc(lang('App.appDeclarationCancellation')) ?></span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <button type="submit" id="submit-application-btn"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed"
                    style="background-color: #0747A6;" disabled>
                <?= $isEditMode ? esc(lang('App.appUpdateButton') ?? 'Update Application') : esc(lang('App.appSubmitButton')) ?>
            </button>
            <div id="aadhaar-verification-required" class="text-sm text-red-600 mt-2 hidden">
                Please verify your Aadhaar number before submitting the application.
            </div>
            <?php if ($isEditMode): ?>
                <a href="/user/dashboard"
                   class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold border-2 text-center"
                   style="border-color: #6B7280; color: #6B7280; hover:bg-gray-50;">
                    <?= esc(lang('App.cancel') ?? 'Cancel') ?>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<script>
    (function () {
        var categorySelect = document.getElementById("app-category");
        var casteCategorySelect = document.getElementById("app-caste-category");
        var box = document.getElementById("category-forms-box");
        var linksContainer = document.getElementById("category-forms-links");
        var bookletBase = "/BookLet%20Sindoor%20Vihar.pdf";
        
        // Reservation category checkboxes
        var isDisabledCheckbox = document.querySelector('input[name="is_disabled"]');
        var isSingleWomanCheckbox = document.querySelector('input[name="is_single_woman"]');
        var isTransgenderCheckbox = document.querySelector('input[name="is_transgender"]');
        var isMediaCheckbox = document.querySelector('input[name="is_media"]');

        // Hide/show reservation options based on verified Aadhaar gender (from PHP)
        var verifiedGender = '<?= isset($verifiedAadhaar['kyc_gender']) ? addslashes((string)$verifiedAadhaar['kyc_gender']) : '' ?>';
        verifiedGender = verifiedGender ? verifiedGender.trim().toUpperCase() : '';

        if (verifiedGender === 'M') {
            // Male: hide Single Woman option
            if (isSingleWomanCheckbox && isSingleWomanCheckbox.closest('label')) {
                isSingleWomanCheckbox.closest('label').style.display = 'none';
                isSingleWomanCheckbox.checked = false;
            }
        } else if (verifiedGender && verifiedGender !== 'OTHER') {
            // Female (or anything not M/OTHER): hide Transgender option
            if (isTransgenderCheckbox && isTransgenderCheckbox.closest('label')) {
                isTransgenderCheckbox.closest('label').style.display = 'none';
                isTransgenderCheckbox.checked = false;
            }
        }

        function getFormsForCategory(cat, casteCat, reservationCategories) {
            // Map categories to scanned JPG forms under /assets/documentform
            var forms = [];
            var imgBase = "/assets/documentform/";
            var formMap = {}; // Use object to avoid duplicates

            // Base annexures (I & II) always required
            formMap["annexure-i"] = {
                label: "Annexure I – Self Declaration / Affidavit (All)",
                url: imgBase + "BookLet Sindoor Vihar_page-0015.jpg"
            };
            formMap["annexure-ii"] = {
                label: "Annexure II – Income Certificate (All)",
                url: imgBase + "BookLet Sindoor Vihar_page-0016.jpg"
            };

            // Normalise category string
            var c = String(cat || "").trim();
            var caste = String(casteCat || "").trim();

            // SC/ST from caste category
            if (caste === "SC" || caste === "ST") {
                formMap["annexure-iii"] = {
                    label: "Annexure III – SC/ST Certificate (SC/ST)",
                    url: imgBase + "BookLet Sindoor Vihar_page-0017.jpg"
                };
            }

            // Soldier related categories
            if (c === "Soldier" || c === "Soldier Category" || c === "Serving Soldier" || c === "Ex-Serviceman" || c === "Soldier Widow/Dependent" || c === "Army") {
                formMap["annexure-iv"] = {
                    label: "Annexure IV – Soldier Certificate (Soldier)",
                    url: imgBase + "BookLet Sindoor Vihar_page-0018.jpg"
                };
                formMap["annexure-v"] = {
                    label: "Annexure V – Soldier Family Affidavit (Soldier)",
                    url: imgBase + "BookLet Sindoor Vihar_page-0019.jpg"
                };
                formMap["annexure-vi"] = {
                    label: "Annexure VI – Soldier Undertaking (Soldier)",
                    url: imgBase + "BookLet Sindoor Vihar_page-0020.jpg"
                };
            }

            // Reservation category forms
            if (reservationCategories.isDisabled) {
                formMap["annexure-vii"] = {
                    label: "Annexure VII – Disability Certificate (Divyang / PwD)",
                    url: imgBase + "BookLet Sindoor Vihar_page-0021.jpg"
                };
            }

            // Convert object to array
            var formArray = [];
            for (var key in formMap) {
                if (formMap.hasOwnProperty(key)) {
                    formArray.push(formMap[key]);
                }
            }

            return formArray;
        }

        function renderForms() {
            if (!box || !linksContainer) return;
            
            var cat = categorySelect ? categorySelect.value : "";
            var casteCat = casteCategorySelect ? casteCategorySelect.value : "";
            
            var reservationCategories = {
                isDisabled: isDisabledCheckbox ? isDisabledCheckbox.checked : false,
                isSingleWoman: isSingleWomanCheckbox ? isSingleWomanCheckbox.checked : false,
                isTransgender: isTransgenderCheckbox ? isTransgenderCheckbox.checked : false,
                isMedia: isMediaCheckbox ? isMediaCheckbox.checked : false
            };
            
            var forms = getFormsForCategory(cat, casteCat, reservationCategories);
            linksContainer.innerHTML = "";

            if (forms.length === 0) {
                box.classList.add("hidden");
                return;
            }

            forms.forEach(function (f) {
                var row = document.createElement("div");
                row.className = "flex items-center justify-between";

                var span = document.createElement("span");
                span.textContent = f.label;
                span.style.color = "#1F2937";

                var a = document.createElement("a");
                a.href = f.url;
                a.target = "_blank";
                a.textContent = "Download";
                a.className = "px-3 py-1 rounded-md text-xs font-semibold";
                a.style.backgroundColor = "#0747A6";
                a.style.color = "#FFFFFF";

                row.appendChild(span);
                row.appendChild(a);
                linksContainer.appendChild(row);
            });

            box.classList.remove("hidden");
        }

        // Listen to income category changes
        if (categorySelect) {
            categorySelect.addEventListener("change", renderForms);
        }

        // Listen to caste category changes
        if (casteCategorySelect) {
            casteCategorySelect.addEventListener("change", renderForms);
        }

        // Listen to reservation category checkbox changes
        if (isDisabledCheckbox) {
            isDisabledCheckbox.addEventListener("change", renderForms);
        }
        if (isSingleWomanCheckbox) {
            isSingleWomanCheckbox.addEventListener("change", renderForms);
        }
        if (isTransgenderCheckbox) {
            isTransgenderCheckbox.addEventListener("change", renderForms);
        }
        if (isMediaCheckbox) {
            isMediaCheckbox.addEventListener("change", renderForms);
        }

        // Initial render
        renderForms();
    })();

    // Aadhaar Verification (DigiLocker via TruthScreen)
    (function() {
        var aadhaarInput = document.getElementById('app-aadhaar');
        var generateOtpBtn = document.getElementById('generate-otp-btn');
        var otpSection = document.getElementById('aadhaar-otp-section');
        var otpGenerateStatus = document.getElementById('otp-generate-status');
        var submitBtn = document.getElementById('submit-application-btn');
        var verificationRequiredMsg = document.getElementById('aadhaar-verification-required');
        var verifiedIcon = document.getElementById('aadhaar-verified-icon');
        var isAadhaarVerified = false;
        var digiStarted = false;

        if (otpSection) otpSection.classList.add('hidden');

        // Check on page load if Aadhaar is already filled and verified
        <?php if (!empty($verifiedAadhaar) && $verifiedAadhaar['verified'] == 1): ?>
        isAadhaarVerified = true;
        if (submitBtn) submitBtn.disabled = false;
        if (verificationRequiredMsg) verificationRequiredMsg.classList.add('hidden');
        if (generateOtpBtn) generateOtpBtn.classList.add('hidden');
        if (verifiedIcon) verifiedIcon.classList.remove('hidden');
        <?php endif; ?>

        // Button: initiate / then check DigiLocker status via backend
        if (generateOtpBtn) {
            generateOtpBtn.addEventListener('click', function() {
                var aadhaar = aadhaarInput.value.trim().replace(/\D/g, '');
                if (aadhaar.length !== 12 || !/^[0-9]{12}$/.test(aadhaar)) {
                    alert('Please enter a valid 12-digit Aadhaar number');
                    return;
                }
                if (!digiStarted) {
                    // First click: initiate DigiLocker
                    generateOtpBtn.disabled = true;
                    generateOtpBtn.textContent = '<?= esc(lang('App.appAadhaarOtpSending')) ?>';
                    if (otpGenerateStatus) otpGenerateStatus.innerHTML = '';

                    fetch('<?= site_url("user/aadhaar/digilocker/initiate") ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ aadhaar: aadhaar })
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        generateOtpBtn.disabled = false;
                        if (!data.success) {
                            throw new Error(data.message || 'Unable to start Aadhaar verification');
                        }
                        digiStarted = true;
                        generateOtpBtn.textContent = '<?= esc(lang('App.appAadhaarCheckStatus') ?? 'Check Aadhaar Status') ?>';
                        if (otpGenerateStatus) {
                            otpGenerateStatus.innerHTML = '<span class="text-green-600">Verification started. Complete steps in the new tab, then click \"Check Aadhaar Status\".</span>';
                        }
                        var successBox = document.getElementById('app-digilocker-success-box');
                        var digiLink = document.getElementById('app-digilocker-link');
                        var transIdEl = document.getElementById('app-digilocker-transid');
                        if (successBox && data.url) {
                            if (digiLink) digiLink.href = data.url;
                            if (transIdEl && data.transId) transIdEl.innerHTML = '<strong>Transaction ID:</strong> ' + data.transId;
                            successBox.classList.remove('hidden');
                        }
                        if (otpSection) otpSection.classList.remove('hidden');
                        if (data.url) {
                            window.open(data.url, '_blank', 'noopener');
                        }
                    })
                    .catch(function (err) {
                        generateOtpBtn.disabled = false;
                        generateOtpBtn.textContent = '<?= esc(lang('App.appAadhaarVerifyButton')) ?>';
                        if (otpGenerateStatus) {
                            otpGenerateStatus.innerHTML = '<span class="text-red-600">✗ Error: ' + err.message + '</span>';
                        } else {
                            alert('Error: ' + err.message);
                        }
                    });
                } else {
                    // Subsequent clicks: check DigiLocker status
                    generateOtpBtn.disabled = true;
                    generateOtpBtn.textContent = '<?= esc(lang('App.appAadhaarCheckingStatus') ?? 'Checking status...') ?>';

                    fetch('<?= site_url("user/aadhaar/digilocker/status") ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({})
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (status) {
                        generateOtpBtn.disabled = false;
                        generateOtpBtn.textContent = '<?= esc(lang('App.appAadhaarCheckStatus') ?? 'Check Aadhaar Status') ?>';

                        if (!status.success) {
                            throw new Error(status.message || 'Unable to check Aadhaar status');
                        }

                        if (status.status !== 'Completed') {
                            alert('Aadhaar verification status: ' + status.status);
                            return;
                        }

                        isAadhaarVerified = true;
                        if (submitBtn) submitBtn.disabled = false;
                        if (verificationRequiredMsg) verificationRequiredMsg.classList.add('hidden');
                        if (generateOtpBtn) generateOtpBtn.classList.add('hidden');
                        if (verifiedIcon) verifiedIcon.classList.remove('hidden');

                        if (status.kyc) {
                            if (status.kyc.name) {
                                var nameEl = document.getElementById('app-name');
                                if (nameEl) {
                                    nameEl.value = status.kyc.name;
                                    nameEl.readOnly = true;
                                }
                            }
                            var fatherName = status.kyc['Father Name'] || status.kyc.fatherName;
                            if (fatherName) {
                                var fhEl = document.getElementById('app-fh-name');
                                if (fhEl) fhEl.value = fatherName;
                            }
                            if (status.kyc.address) {
                                var addrEl = document.getElementById('app-address');
                                if (addrEl) addrEl.value = status.kyc.address;
                            }
                        }
                    })
                    .catch(function (err) {
                        generateOtpBtn.disabled = false;
                        if (otpGenerateStatus) {
                            otpGenerateStatus.innerHTML = '<span class="text-red-600">✗ Error: ' + err.message + '</span>';
                        } else {
                            alert('Error: ' + err.message);
                        }
                    });
                }
            });
        }

        // Block form submission if Aadhaar not verified
        var form = document.getElementById('application-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!isAadhaarVerified) {
                    e.preventDefault();
                    if (verificationRequiredMsg) verificationRequiredMsg.classList.remove('hidden');
                    alert('Please verify your Aadhaar number before submitting the application.');
                    return false;
                }
            });
        }

    })();
</script>
