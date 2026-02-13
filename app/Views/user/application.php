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
        <?= esc(lang('App.appTitle')) ?>
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

    <?php $application = $application ?? null; ?>

    <form id="application-form"
          action="<?= site_url('user/application/submit') ?>"
          method="POST"
          class="bg-white shadow-md rounded-lg p-6 space-y-4">
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
                    <input id="app-state" name="state" type="text" value="Rajasthan" readonly
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
                <?php $currentCat = old('income_category', $application['income_category'] ?? 'Central Govt Employee'); ?>
                <?php
                    $soldierCategories = ['Serving Soldier', 'Ex-Serviceman', 'Soldier Widow/Dependent', 'Soldier Category'];
                    $soldierSelected   = in_array($currentCat, $soldierCategories, true);
                ?>
                <select id="app-category" name="income_category"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    <option value="Central Govt Employee" <?= $currentCat === 'Central Govt Employee' ? 'selected' : '' ?>>Central Govt Employee</option>
                    <option value="State Govt Employee" <?= $currentCat === 'State Govt Employee' ? 'selected' : '' ?>>State Govt Employee</option>
                    <option value="PSU Employee" <?= $currentCat === 'PSU Employee' ? 'selected' : '' ?>>PSU Employee</option>
                    <option value="Soldier Category" <?= $soldierSelected ? 'selected' : '' ?>>Soldier (Serving / Ex-Serviceman / Widow/Dependent)</option>
                </select>
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
                       class="mt-1">
                <span><?= esc(lang('App.appDeclarationTruth')) ?></span>
            </label>
            <label class="inline-flex items-start gap-2">
                <input type="checkbox" name="declaration_cancellation" value="1" required
                       class="mt-1">
                <span><?= esc(lang('App.appDeclarationCancellation')) ?></span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                <?= esc(lang('App.appSubmitButton')) ?>
            </button>
        </div>
    </form>
</div>

<script>
    (function () {
        var categorySelect = document.getElementById("app-category");
        var box = document.getElementById("category-forms-box");
        var linksContainer = document.getElementById("category-forms-links");
        var bookletBase = "/BookLet%20Sindoor%20Vihar.pdf";

        function getFormsForCategory(cat) {
            // Map categories to scanned JPG forms under /assets/documentform
            var forms = [];
            var imgBase = "/assets/documentform/";

            if (!cat) {
                return forms;
            }

            // Base annexures (I & II) always required
            forms.push({
                label: "Annexure I – Self Declaration / Affidavit (All)",
                url: imgBase + "BookLet Sindoor Vihar_page-0015.jpg"
            });
            forms.push({
                label: "Annexure II – Income Certificate (All)",
                url: imgBase + "BookLet Sindoor Vihar_page-0016.jpg"
            });

            // Normalise category string
            var c = String(cat).trim();

            // SC/ST
            if (c === "SC" || c === "ST") {
                forms.push({
                    label: "Annexure III – SC/ST Certificate (SC/ST)",
                    url: imgBase + "BookLet Sindoor Vihar_page-0017.jpg"
                });
                return forms;
            }

            // Soldier related categories
            if (c === "Soldier Category" || c === "Serving Soldier" || c === "Ex-Serviceman" || c === "Soldier Widow/Dependent") {
                forms.push({
                    label: "Annexure IV – Soldier Certificate (Soldier)",
                    url: imgBase + "BookLet Sindoor Vihar_page-0018.jpg"
                });
                forms.push({
                    label: "Annexure V – Soldier Family Affidavit (Soldier)",
                    url: imgBase + "BookLet Sindoor Vihar_page-0019.jpg"
                });
                forms.push({
                    label: "Annexure VI – Soldier Undertaking (Soldier)",
                    url: imgBase + "BookLet Sindoor Vihar_page-0020.jpg"
                });
                return forms;
        }

            // Divyang
            if (c === "Divyang (PwD)") {
                forms.push({
                    label: "Annexure VII – Disability Certificate (Divyang)",
                    url: imgBase + "BookLet Sindoor Vihar_page-0021.jpg"
                });
                return forms;
            }

            // Other categories (General, EWS, LIG, MIG, Govt Employee, Journalist, Transgender,
            // Destitute/Landless/Single woman etc.) only need Annexure I & II for now.
            return forms;
        }

        function renderForms(cat) {
            if (!box || !linksContainer) return;
            
            var forms = getFormsForCategory(cat);
            linksContainer.innerHTML = "";

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

        if (categorySelect) {
            categorySelect.addEventListener("change", function () {
                renderForms(this.value);
            });

            // Initial render for default/loaded value
            renderForms(categorySelect.value);
        }
    })();
</script>
