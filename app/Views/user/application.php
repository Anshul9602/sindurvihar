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
                       value="<?= esc(old('full_name', $application['full_name'] ?? '')) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="app-aadhaar" class="block text-sm font-medium mb-1"><?= esc(lang('App.appAadhaarLabel')) ?></label>
                <input id="app-aadhaar" name="aadhaar" type="text" required
                       value="<?= esc(old('aadhaar', $application['aadhaar'] ?? '')) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
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
                       value="<?= esc(old('age', $application['age'] ?? '')) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="app-mobile" class="block text-sm font-medium mb-1"><?= esc(lang('App.appMobileLabel')) ?></label>
                <input id="app-mobile" name="mobile" type="text" required
                       value="<?= esc(old('mobile', $application['mobile'] ?? '')) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
        </div>

        <h2 class="text-xl font-semibold mt-4 mb-2" style="color: #0F1F3F;"><?= esc(lang('App.appResidenceSection')) ?></h2>
        <div class="space-y-4">
            <div>
                <label for="app-address" class="block text-sm font-medium mb-1"><?= esc(lang('App.appAddressLabel')) ?></label>
                <input id="app-address" name="address" type="text" required
                       value="<?= esc(old('address', $application['address'] ?? '')) ?>"
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
                       value="<?= esc(old('income', $application['income'] ?? '')) ?>"
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
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                <?= $isEditMode ? esc(lang('App.appUpdateButton') ?? 'Update Application') : esc(lang('App.appSubmitButton')) ?>
            </button>
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
</script>
