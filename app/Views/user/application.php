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

    // Aadhaar OTP Verification
    (function() {
        var aadhaarInput = document.getElementById('app-aadhaar');
        var generateOtpBtn = document.getElementById('generate-otp-btn');
        var otpSection = document.getElementById('aadhaar-otp-section');
        var otpInputSection = document.getElementById('otp-input-section');
        var otpGenerateStatus = document.getElementById('otp-generate-status');
        var otpInput = document.getElementById('aadhaar-otp');
        var verifyOtpBtn = document.getElementById('verify-otp-btn');
        var resendOtpBtn = document.getElementById('resend-otp-btn');
        var verifiedStatus = document.getElementById('aadhaar-verified-status');
        var submitBtn = document.getElementById('submit-application-btn');
        var verificationRequiredMsg = document.getElementById('aadhaar-verification-required');
        var verifiedIcon = document.getElementById('aadhaar-verified-icon');
        var isAadhaarVerified = false;

        // Check if Aadhaar is already verified (on input change or page load)
        function checkExistingVerification() {
            var aadhaar = aadhaarInput.value.trim().replace(/[\s\-]/g, '');
            if (aadhaar.length === 12 && /^[0-9]{12}$/.test(aadhaar)) {
                fetch('<?= site_url("user/application/aadhaar/check-verification") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ aadhaar: aadhaar })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.verified) {
                        // Already verified - show tick directly and hide verification section
                        isAadhaarVerified = true;
                        otpSection.classList.add('hidden'); // Hide entire verification section
                        if (submitBtn) submitBtn.disabled = false;
                        if (verificationRequiredMsg) verificationRequiredMsg.classList.add('hidden');
                        
                        // Replace verify button with green checkmark
                        if (generateOtpBtn) {
                            generateOtpBtn.classList.add('hidden');
                        }
                        if (verifiedIcon) {
                            verifiedIcon.classList.remove('hidden');
                        }
                    }
                })
                .catch(err => console.error('Error checking verification:', err));
            }
        }

        // Check on page load if Aadhaar is already filled and verified
        <?php if (!empty($verifiedAadhaar) && $verifiedAadhaar['verified'] == 1): ?>
        // Aadhaar already verified from registration - auto-show verified status
        if (aadhaarInput && aadhaarInput.value.length === 12) {
            isAadhaarVerified = true;
            if (otpSection) otpSection.classList.add('hidden');
            if (submitBtn) submitBtn.disabled = false;
            if (verificationRequiredMsg) verificationRequiredMsg.classList.add('hidden');
            if (generateOtpBtn) generateOtpBtn.classList.add('hidden');
            if (verifiedIcon) verifiedIcon.classList.remove('hidden');
        }
        <?php endif; ?>

        // Generate OTP
        if (generateOtpBtn) {
            generateOtpBtn.addEventListener('click', function() {
                var aadhaar = aadhaarInput.value.trim();
                
                if (aadhaar.length !== 12 || !/^[0-9]{12}$/.test(aadhaar)) {
                    alert('Please enter a valid 12-digit Aadhaar number');
                    return;
                }

                generateOtpBtn.disabled = true;
                generateOtpBtn.textContent = '<?= esc(lang('App.appAadhaarOtpSending')) ?>';
                otpGenerateStatus.innerHTML = '<span class="text-blue-600"><?= esc(lang('App.appAadhaarOtpSending')) ?></span>';

                fetch('<?= site_url("user/application/aadhaar/generate-otp") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ aadhaar: aadhaar })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Network error');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    generateOtpBtn.disabled = false;
                    generateOtpBtn.textContent = '<?= esc(lang('App.appAadhaarVerifyButton')) ?>';
                    
                    if (data.success) {
                        // Check if already verified by same user
                        if (data.verified && data.same_user) {
                            // Already verified by same user - show tick directly and hide verification section
                            isAadhaarVerified = true;
                            otpSection.classList.add('hidden'); // Hide entire verification section
                            submitBtn.disabled = false;
                            verificationRequiredMsg.classList.add('hidden');
                            
                            // Replace verify button with green checkmark
                            if (generateOtpBtn) {
                                generateOtpBtn.classList.add('hidden');
                            }
                            if (verifiedIcon) {
                                verifiedIcon.classList.remove('hidden');
                            }
                        } else {
                            // Generate new OTP
                            otpSection.classList.remove('hidden');
                            otpInputSection.classList.remove('hidden');
                            otpGenerateStatus.innerHTML = '<span class="text-green-600">✓ ' + data.message + '</span>';
                            if (data.otp) {
                                otpGenerateStatus.innerHTML += '<br><span class="text-xs text-gray-600">Demo OTP: ' + data.otp + '</span>';
                            }
                            if (otpInput) otpInput.focus();
                        }
                    } else {
                        // Check if Aadhaar is already used by different user
                        if (data.already_used) {
                            alert(data.message);
                            // Clear the Aadhaar input
                            aadhaarInput.value = '';
                            aadhaarInput.focus();
                        } else {
                            otpSection.classList.remove('hidden');
                            otpGenerateStatus.innerHTML = '<span class="text-red-600">✗ ' + (data.message || 'Failed to generate OTP') + '</span>';
                            if (data.debug) {
                                console.error('Debug info:', data.debug);
                            }
                        }
                    }
                })
                .catch(err => {
                    generateOtpBtn.disabled = false;
                    generateOtpBtn.textContent = 'Verify';
                    otpSection.classList.remove('hidden');
                    otpGenerateStatus.innerHTML = '<span class="text-red-600">✗ Error: ' + err.message + '</span>';
                    console.error('Error generating OTP:', err);
                });
            });
        }

        // Verify OTP
        if (verifyOtpBtn) {
            verifyOtpBtn.addEventListener('click', function() {
                var aadhaar = aadhaarInput.value.trim();
                var otp = otpInput.value.trim();

                if (otp.length !== 6 || !/^[0-9]{6}$/.test(otp)) {
                    alert('Please enter a valid 6-digit OTP');
                    return;
                }

                verifyOtpBtn.disabled = true;
                verifyOtpBtn.textContent = '<?= esc(lang('App.appAadhaarOtpVerifying')) ?>';

                fetch('<?= site_url("user/application/aadhaar/verify-otp") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ aadhaar: aadhaar, otp: otp })
                })
                .then(response => response.json())
                .then(data => {
                    verifyOtpBtn.disabled = false;
                    verifyOtpBtn.textContent = '<?= esc(lang('App.appAadhaarVerifyOtp')) ?>';

                    if (data.success && data.verified) {
                        isAadhaarVerified = true;
                        otpSection.classList.add('hidden'); // Hide entire verification section after successful verification
                        submitBtn.disabled = false;
                        verificationRequiredMsg.classList.add('hidden');
                        
                        // Replace verify button with green checkmark
                        if (generateOtpBtn) {
                            generateOtpBtn.classList.add('hidden');
                        }
                        if (verifiedIcon) {
                            verifiedIcon.classList.remove('hidden');
                        }
                    } else {
                        alert(data.message || 'Invalid OTP. Please try again.');
                    }
                })
                .catch(err => {
                    verifyOtpBtn.disabled = false;
                    verifyOtpBtn.textContent = '<?= esc(lang('App.appAadhaarVerifyOtp')) ?>';
                    alert('Error verifying OTP. Please try again.');
                    console.error('Error verifying OTP:', err);
                });
            });
        }

        // Resend OTP
        if (resendOtpBtn) {
            resendOtpBtn.addEventListener('click', function() {
                if (generateOtpBtn) generateOtpBtn.click();
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

        // Check existing verification on page load
        if (aadhaarInput && aadhaarInput.value.trim().length === 12) {
            checkExistingVerification();
        }

        // Check when Aadhaar input changes (on blur)
        if (aadhaarInput) {
            aadhaarInput.addEventListener('blur', function() {
                var aadhaar = this.value.trim().replace(/[\s\-]/g, '');
                if (aadhaar.length === 12 && /^[0-9]{12}$/.test(aadhaar)) {
                    checkExistingVerification();
                }
            });
        }

        // Allow Enter key to submit OTP
        if (otpInput) {
            otpInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && verifyOtpBtn) {
                    verifyOtpBtn.click();
                }
            });
        }
    })();
</script>
