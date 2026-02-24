<div class="container mx-auto px-4 py-12 max-w-md">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-2 text-center" style="color: #0F1F3F;">
            <?= esc(lang('App.registerTitle')) ?>
        </h1>
        <p class="text-center text-sm text-gray-500 mb-6">Step <span id="reg-step-num">1</span> of 2</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 rounded-md text-sm" style="background-color: #FEE2E2; color: #DC2626;">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-3 rounded-md text-sm" style="background-color: #D1FAE5; color: #059669;">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form id="register-form" action="<?= site_url('auth/register') ?>" method="POST" class="space-y-4">
            <!-- Hidden fields for name and aadhaar (filled after step 1 verification) -->
            <input type="hidden" id="reg-name" name="name" value="<?= esc(old('name', '')) ?>">
            <input type="hidden" id="reg-aadhaar" name="aadhaar" value="<?= esc(old('aadhaar', '')) ?>">

            <!-- STEP 1: Aadhaar Verification – embed standalone page -->
            <div id="reg-step1" class="space-y-4">
                <h2 class="text-xl font-semibold mb-3" style="color: #0F1F3F;"><?= esc(lang('App.registerAadhaarTitle') ?? 'Aadhaar Verification') ?></h2>
                <p class="text-sm text-gray-600 mb-3">
                    Step 1: Complete Aadhaar DigiLocker verification in the panel below. When it shows verified details,
                    click <strong>Use Aadhaar details &amp; continue</strong>.
                </p>

                <div class="border rounded-md overflow-hidden" style="height:300px ;">
                    <iframe
                        id="aadhaar-standalone-frame"
                        src="<?= base_url('aadhaar-standalone-complete.html') ?>"
                        style="width: 100%; height: 100%; border: 0;"
                        title="Aadhaar DigiLocker Verification">
                    </iframe>
                </div>

                <button type="button" id="reg-use-aadhaar-btn"
                        class="w-full mt-4 px-4 py-3 rounded-md font-semibold text-white"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    Use Aadhaar details &amp; continue
                </button>
                <div id="reg-step1-status" class="mt-2 text-sm text-gray-600"></div>
            </div>

            <!-- STEP 2: Registration form (email, language, category, mobile, password) -->
            <div id="reg-step2" class="hidden space-y-4">
                <!-- Verified info summary (readonly display) -->
                <div class="p-3 rounded-md bg-green-50 border border-green-200 flex items-center gap-2 flex-wrap">
                    <span class="text-green-700 font-medium">Verified:</span>
                    <span id="reg-step2-name" class="font-medium text-gray-800"></span>
                    <span class="text-gray-500">|</span>
                    <span id="reg-step2-aadhaar" class="font-medium text-gray-800"></span>
                    <span class="w-6 h-6 rounded-full bg-green-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </span>
                </div>

                <div>
                    <label for="reg-aadhaar-full" class="block text-sm font-medium mb-1">Aadhaar Number</label>
                    <input id="reg-aadhaar-full" type="text" maxlength="12" pattern="[0-9]{12}" required
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                           placeholder="Enter 12-digit Aadhaar number used for DigiLocker verification">
                    <p class="mt-1 text-xs text-gray-500">
                        This must be the same Aadhaar number you used in DigiLocker. We will verify the last 4 digits match.
                    </p>
                </div>
                <div>
                    <label for="reg-email" class="block text-sm font-medium mb-1">Email</label>
                    <input id="reg-email" name="email" type="email" required
                           value="<?= esc(old('email', '')) ?>"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                           placeholder="Enter your email address">
                </div>
                <div>
                    <label for="reg-language" class="block text-sm font-medium mb-1">Preferred Language</label>
                    <select id="reg-language" name="language" required
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                        <option value="">Select language</option>
                        <option value="en" <?= old('language') === 'en' ? 'selected' : '' ?>>English</option>
                        <option value="hi" <?= old('language') === 'hi' ? 'selected' : '' ?>>Hindi</option>
                    </select>
                </div>
                <div>
                    <label for="reg-category" class="block text-sm font-medium mb-1">Category</label>
                    <select id="reg-category" name="category" required
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                        <option value="">Select category</option>
                        <option value="General" <?= old('category') === 'General' ? 'selected' : '' ?>>General</option>
                        <option value="SC" <?= old('category') === 'SC' ? 'selected' : '' ?>>SC</option>
                        <option value="ST" <?= old('category') === 'ST' ? 'selected' : '' ?>>ST</option>
                        <option value="OBC" <?= old('category') === 'OBC' ? 'selected' : '' ?>>OBC</option>
                    </select>
                </div>
                <div>
                    <label for="reg-mobile" class="block text-sm font-medium mb-1"><?= esc(lang('App.registerMobileLabel')) ?></label>
                    <input id="reg-mobile" name="mobile" type="text" required maxlength="10" pattern="[0-9]{10}"
                           value="<?= esc(old('mobile', '')) ?>"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                           placeholder="<?= esc(lang('App.registerMobilePlaceholder')) ?>">
                </div>
                <div>
                    <label for="reg-password" class="block text-sm font-medium mb-1"><?= esc(lang('App.registerPasswordLabel')) ?></label>
                    <input id="reg-password" name="password" type="password" required minlength="6"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                           placeholder="<?= esc(lang('App.registerPasswordPlaceholder')) ?>">
                </div>
                <button type="submit" id="reg-submit-btn"
                        class="w-full px-4 py-2 rounded-md font-semibold text-white"
                        style="background-color: #0747A6;">
                    <?= esc(lang('App.registerButton')) ?>
                </button>
                <button type="button" id="reg-back-btn" class="w-full px-4 py-2 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50">
                    Back to Step 1
                </button>
            </div>
        </form>

        <p class="text-center text-sm mt-4" style="color: #4B5563;">
            <?= esc(lang('App.registerHaveAccount')) ?>
            <a href="<?= site_url('auth/login') ?>" class="text-blue-600 hover:underline"><?= esc(lang('App.registerLoginLink')) ?></a>
        </p>
    </div>
</div>

<script>
(function () {
    var step1 = document.getElementById('reg-step1');
    var step2 = document.getElementById('reg-step2');
    var stepNum = document.getElementById('reg-step-num');

    var regName = document.getElementById('reg-name');
    var regAadhaar = document.getElementById('reg-aadhaar');
    var step2Name = document.getElementById('reg-step2-name');
    var step2Aadhaar = document.getElementById('reg-step2-aadhaar');

    var useAadhaarBtn = document.getElementById('reg-use-aadhaar-btn');
    var step1Status = document.getElementById('reg-step1-status');
    var backBtn = document.getElementById('reg-back-btn');
    var aadhaarFullInput = document.getElementById('reg-aadhaar-full');

    function showStep(step) {
        if (step === 1) {
            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            if (stepNum) stepNum.textContent = '1';
        } else {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            if (stepNum) stepNum.textContent = '2';
            if (step2Name) step2Name.textContent = regName.value || '';
            if (step2Aadhaar) {
                var a = regAadhaar.value || '';
                step2Aadhaar.textContent = a && a.length >= 4 ? '****-****-' + a.slice(-4) : '';
            }
        }
    }

    if (backBtn) {
        backBtn.addEventListener('click', function () {
            showStep(1);
        });
    }

    if (useAadhaarBtn) {
        useAadhaarBtn.addEventListener('click', function () {
            if (step1Status) {
                step1Status.textContent = 'Checking local Aadhaar verification results...';
                step1Status.className = 'mt-2 text-sm text-gray-600';
            }

            var raw = null;
            try {
                raw = localStorage.getItem('aadhaar_kyc');
            } catch (e) {
                if (step1Status) {
                    step1Status.textContent = 'Unable to read browser storage. Please allow storage/cookies.';
                    step1Status.className = 'mt-2 text-sm text-red-600';
                }
                return;
            }

            if (!raw) {
                if (step1Status) {
                    step1Status.textContent = 'No Aadhaar KYC found. Please complete verification in the panel above first.';
                    step1Status.className = 'mt-2 text-sm text-red-600';
                }
                return;
            }

            var kyc;
            try {
                kyc = JSON.parse(raw);
            } catch (e) {
                if (step1Status) {
                    step1Status.textContent = 'Saved Aadhaar data is invalid. Please verify again.';
                    step1Status.className = 'mt-2 text-sm text-red-600';
                }
                return;
            }

            var name = kyc.name || '';
            var aadhaarNum = (kyc.aadhaarNumber || kyc.aadhar_number || '').toString().replace(/\D/g, '');

            if (!name) {
                if (step1Status) {
                    step1Status.textContent = 'Could not find name in verification result. Please verify again.';
                    step1Status.className = 'mt-2 text-sm text-red-600';
                }
                return;
            }

            if (!aadhaarNum || aadhaarNum.length < 4) {
                if (step1Status) {
                    step1Status.textContent = 'Could not find valid Aadhaar digits in verification result. Please verify again.';
                    step1Status.className = 'mt-2 text-sm text-red-600';
                }
                return;
            }

            // Save only the last 4 digits from verified Aadhaar into the hidden field.
            // The full number will be entered by the user in Step 2 and must match these 4 digits.
            var kycLast4 = aadhaarNum.slice(-4);

            if (regName) regName.value = name;
            if (regAadhaar) regAadhaar.value = kycLast4;

            // Also try to pre-fill the full Aadhaar in Step 2 from what user typed in the standalone page
            var aadhaarInputFull = '';
            try {
                aadhaarInputFull = localStorage.getItem('aadhaar_input') || '';
            } catch (e) {
                aadhaarInputFull = '';
            }
            if (aadhaarFullInput && /^[0-9]{12}$/.test(aadhaarInputFull)) {
                aadhaarFullInput.value = aadhaarInputFull;
            }

            // Save KYC to backend so registration can see a verified Aadhaar
            var payload = {
                aadhaarNumber: aadhaarInputFull || aadhaarNum,
                name: name,
                fatherName: kyc.fatherName || kyc['Father Name'] || '',
                dob: kyc.dateOfBirth || kyc.dob || '',
                gender: kyc.gender || '',
                address: kyc.address || '',
                pincode: kyc.pincode || '',
                verifiedAt: kyc.verifiedAt || ''
            };

            fetch('<?= site_url("auth/aadhaar/save-kyc-standalone") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (!res.success) {
                    if (step1Status) {
                        step1Status.textContent = res.message || 'Unable to save Aadhaar verification. Please try again.';
                        step1Status.className = 'mt-2 text-sm text-red-600';
                    }
                    return;
                }

                if (step1Status) {
                    step1Status.textContent = '✓ Aadhaar verified and saved. Name and Aadhaar are now locked for Step 2.';
                    step1Status.className = 'mt-2 text-sm text-green-600';
                }

                showStep(2);
            })
            .catch(function (err) {
                if (step1Status) {
                    step1Status.textContent = 'Error saving Aadhaar verification: ' + err.message;
                    step1Status.className = 'mt-2 text-sm text-red-600';
                }
            });
        });
    }

    var form = document.getElementById('register-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!regName.value.trim() || !regAadhaar.value.trim()) {
                e.preventDefault();
                alert('<?= esc(lang('App.registerAadhaarRequired') ?? 'Please complete Aadhaar verification in Step 1 and use the details before registering.') ?>');
                showStep(1);
                return false;
            }

            var email = document.getElementById('reg-email').value.trim();
            var language = document.getElementById('reg-language').value;
            var category = document.getElementById('reg-category').value;
            var mobile = document.getElementById('reg-mobile').value;
            var password = document.getElementById('reg-password').value;
            var fullAadhaar = aadhaarFullInput ? aadhaarFullInput.value.replace(/\D/g, '') : '';
            var verifiedLast4 = regAadhaar.value ? regAadhaar.value.slice(-4) : '';

            if (!/^[0-9]{12}$/.test(fullAadhaar)) {
                e.preventDefault();
                alert('Please enter a valid 12-digit Aadhaar number (Step 2).');
                return false;
            }

            if (!verifiedLast4 || fullAadhaar.slice(-4) !== verifiedLast4) {
                e.preventDefault();
                alert('The Aadhaar number you entered does not match the Aadhaar verified via DigiLocker. Please check and try again.');
                return false;
            }

            // If match, store full Aadhaar in the hidden field so backend receives full number.
            regAadhaar.value = fullAadhaar;

            if (!email) { e.preventDefault(); alert('Please enter your email'); return false; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { e.preventDefault(); alert('Please enter a valid email'); return false; }
            if (!language) { e.preventDefault(); alert('Please select your preferred language'); return false; }
            if (!category) { e.preventDefault(); alert('Please select your category'); return false; }
            if (!/^[0-9]{10}$/.test(mobile)) { e.preventDefault(); alert('Please enter a valid 10-digit mobile number'); return false; }
            if (password.length < 6) { e.preventDefault(); alert('Password must be at least 6 characters long'); return false; }
        });
    }
})();
</script>
