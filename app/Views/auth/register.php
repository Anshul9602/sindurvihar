<div class="container mx-auto px-4 py-12 max-w-md">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
            <?= esc(lang('App.registerTitle')) ?>
        </h1>

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
            <div>
                <label for="reg-name" class="block text-sm font-medium mb-1">Full Name</label>
                <input id="reg-name" name="name" type="text" required
                       value="<?= esc(old('name', '')) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                       placeholder="Enter your full name">
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

            <!-- Aadhaar Verification Section -->
            <div class="border-t pt-4 mt-4">
                <h3 class="text-lg font-semibold mb-3" style="color: #0F1F3F;"><?= esc(lang('App.registerAadhaarTitle') ?? 'Aadhaar Verification') ?></h3>
                
                <div>
                    <div class="flex gap-2 items-center">
                        <input id="reg-aadhaar" name="aadhaar" type="text" required maxlength="12" pattern="[0-9]{12}"
                               value="<?= esc(old('aadhaar', '')) ?>"
                               class="flex-1 border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                               placeholder="Enter 12-digit Aadhaar number">
                        <button type="button" id="reg-generate-otp-btn" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition whitespace-nowrap">
                            <?= esc(lang('App.appAadhaarVerifyButton')) ?>
                        </button>
                        <div id="reg-aadhaar-verified-icon" class="hidden flex items-center justify-center w-10 h-10 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500"><?= esc(lang('App.appAadhaarVerificationMandatory')) ?></p>
                </div>

                <!-- Aadhaar OTP Verification Section -->
                <div id="reg-aadhaar-otp-section" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <h4 class="text-sm font-semibold mb-3" style="color:#1D4ED8;"><?= esc(lang('App.appAadhaarOtpVerification')) ?></h4>
                    <div id="reg-otp-generate-status" class="mb-3 text-sm"></div>
                    <div id="reg-otp-input-section" class="hidden">
                        <div class="flex gap-2 mb-2">
                            <input id="reg-aadhaar-otp" type="text" maxlength="6" pattern="[0-9]{6}"
                                   placeholder="<?= esc(lang('App.appAadhaarOtpPlaceholder')) ?>"
                                   class="flex-1 border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                            <button type="button" id="reg-verify-otp-btn"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 transition">
                                <?= esc(lang('App.appAadhaarVerifyOtp')) ?>
                            </button>
                        </div>
                        <button type="button" id="reg-resend-otp-btn" 
                                class="text-sm text-blue-600 hover:underline">
                            <?= esc(lang('App.appAadhaarResendOtp')) ?>
                        </button>
                    </div>
                    <div id="reg-aadhaar-verified-status" class="hidden mt-2 p-2 bg-green-100 border border-green-300 rounded text-sm text-green-800">
                        ✓ <?= esc(lang('App.appAadhaarVerifiedSuccess')) ?>
                    </div>
                </div>
            </div>

            <div>
                <label for="reg-password" class="block text-sm font-medium mb-1"><?= esc(lang('App.registerPasswordLabel')) ?></label>
                <input id="reg-password" name="password" type="password" required minlength="6"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                       placeholder="<?= esc(lang('App.registerPasswordPlaceholder')) ?>">
            </div>
            <button type="submit"
                    class="w-full px-4 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                <?= esc(lang('App.registerButton')) ?>
            </button>
            <p class="text-center text-sm mt-4" style="color: #4B5563;">
                <?= esc(lang('App.registerHaveAccount')) ?>
                <a href="<?= site_url('auth/login') ?>" class="text-blue-600 hover:underline"><?= esc(lang('App.registerLoginLink')) ?></a>
            </p>
        </form>
    </div>
</div>

<script>
    (function () {
        var form = document.getElementById("register-form");
        if (form) {
            form.addEventListener("submit", function(e) {
                var name = document.getElementById("reg-name").value.trim();
                var email = document.getElementById("reg-email").value.trim();
                var language = document.getElementById("reg-language").value;
                var category = document.getElementById("reg-category").value;
                var mobile = document.getElementById("reg-mobile").value;
                var password = document.getElementById("reg-password").value;
                
                // Basic validation for new fields
                if (name.length === 0) {
                    e.preventDefault();
                    alert("Please enter your name");
                    return false;
                }

                if (email.length === 0) {
                    e.preventDefault();
                    alert("Please enter your email");
                    return false;
                }

                // Simple email format check
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    e.preventDefault();
                    alert("Please enter a valid email address");
                    return false;
                }

                if (!language) {
                    e.preventDefault();
                    alert("Please select your preferred language");
                    return false;
                }

                if (!category) {
                    e.preventDefault();
                    alert("Please select your category");
                    return false;
                }

                // Client-side validation
                if (!/^[0-9]{10}$/.test(mobile)) {
                    e.preventDefault();
                    alert("Please enter a valid 10-digit mobile number");
                    return false;
                }
                
                if (password.length < 6) {
                    e.preventDefault();
                    alert("Password must be at least 6 characters long");
                    return false;
                }
                
                // Check Aadhaar verification before allowing form submission
                var aadhaarVerified = document.getElementById('reg-aadhaar-verified-icon').classList.contains('hidden') === false;
                if (!aadhaarVerified) {
                    e.preventDefault();
                    alert('<?= esc(lang('App.registerAadhaarRequired') ?? 'Please verify your Aadhaar number before registering') ?>');
                    return false;
                }
                
                // Form will submit normally to backend
            });
        }

        // Aadhaar Verification JavaScript for Registration
        var regAadhaarInput = document.getElementById('reg-aadhaar');
        var regGenerateOtpBtn = document.getElementById('reg-generate-otp-btn');
        var regAadhaarOtpSection = document.getElementById('reg-aadhaar-otp-section');
        var regOtpInputSection = document.getElementById('reg-otp-input-section');
        var regOtpGenerateStatus = document.getElementById('reg-otp-generate-status');
        var regAadhaarOtpInput = document.getElementById('reg-aadhaar-otp');
        var regVerifyOtpBtn = document.getElementById('reg-verify-otp-btn');
        var regResendOtpBtn = document.getElementById('reg-resend-otp-btn');
        var regAadhaarVerifiedIcon = document.getElementById('reg-aadhaar-verified-icon');
        var regAadhaarVerifiedStatus = document.getElementById('reg-aadhaar-verified-status');

        // Check existing verification on page load
        function checkRegExistingVerification() {
            var aadhaar = regAadhaarInput.value.trim().replace(/[\s\-]/g, '');
            if (aadhaar.length === 12) {
                fetch('/user/application/aadhaar/check-verification', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ aadhaar: aadhaar })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.verified) {
                        regAadhaarVerifiedIcon.classList.remove('hidden');
                        regAadhaarOtpSection.classList.add('hidden');
                        regGenerateOtpBtn.style.display = 'none';
                    }
                })
                .catch(err => console.error('Check verification error:', err));
            }
        }

        // Generate OTP
        if (regGenerateOtpBtn) {
            regGenerateOtpBtn.addEventListener('click', function() {
                var aadhaar = regAadhaarInput.value.trim().replace(/[\s\-]/g, '');
                
                if (aadhaar.length !== 12) {
                    alert('<?= esc(lang('App.appAadhaarInvalid') ?? 'Please enter a valid 12-digit Aadhaar number') ?>');
                    return;
                }

                regGenerateOtpBtn.disabled = true;
                regGenerateOtpBtn.textContent = '<?= esc(lang('App.appAadhaarOtpSending') ?? 'Sending OTP...') ?>';
                regOtpGenerateStatus.textContent = '';
                regOtpInputSection.classList.add('hidden');

                fetch('/user/application/aadhaar/generate-otp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ aadhaar: aadhaar })
                })
                .then(r => r.json())
                .then(data => {
                    regGenerateOtpBtn.disabled = false;
                    regGenerateOtpBtn.textContent = '<?= esc(lang('App.appAadhaarVerifyButton')) ?>';
                    
                    if (data.success) {
                        if (data.verified && data.same_user) {
                            regAadhaarVerifiedIcon.classList.remove('hidden');
                            regAadhaarOtpSection.classList.add('hidden');
                            regGenerateOtpBtn.style.display = 'none';
                        } else if (data.already_used) {
                            alert(data.message);
                            regAadhaarInput.value = '';
                        } else {
                            regOtpGenerateStatus.innerHTML = '<span style="color: green;">' + data.message + '</span>';
                            if (data.otp) {
                                regOtpGenerateStatus.innerHTML += '<br><strong>Demo OTP: ' + data.otp + '</strong>';
                            }
                            regOtpInputSection.classList.remove('hidden');
                            regAadhaarOtpSection.classList.remove('hidden');
                        }
                    } else {
                        regOtpGenerateStatus.innerHTML = '<span style="color: red;">' + data.message + '</span>';
                    }
                })
                .catch(err => {
                    regGenerateOtpBtn.disabled = false;
                    regGenerateOtpBtn.textContent = '<?= esc(lang('App.appAadhaarVerifyButton')) ?>';
                    regOtpGenerateStatus.innerHTML = '<span style="color: red;">Error: ' + err.message + '</span>';
                });
            });
        }

        // Verify OTP
        if (regVerifyOtpBtn) {
            regVerifyOtpBtn.addEventListener('click', function() {
                var aadhaar = regAadhaarInput.value.trim().replace(/[\s\-]/g, '');
                var otp = regAadhaarOtpInput.value.trim();

                if (otp.length !== 6) {
                    alert('<?= esc(lang('App.appAadhaarOtpInvalid') ?? 'Please enter a valid 6-digit OTP') ?>');
                    return;
                }

                regVerifyOtpBtn.disabled = true;
                regVerifyOtpBtn.textContent = '<?= esc(lang('App.appAadhaarOtpVerifying') ?? 'Verifying...') ?>';

                fetch('/user/application/aadhaar/verify-otp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ aadhaar: aadhaar, otp: otp })
                })
                .then(r => r.json())
                .then(data => {
                    regVerifyOtpBtn.disabled = false;
                    regVerifyOtpBtn.textContent = '<?= esc(lang('App.appAadhaarVerifyOtp')) ?>';
                    
                    if (data.success && data.verified) {
                        regAadhaarVerifiedIcon.classList.remove('hidden');
                        regAadhaarOtpSection.classList.add('hidden');
                        regGenerateOtpBtn.style.display = 'none';
                        regAadhaarVerifiedStatus.classList.remove('hidden');
                        regAadhaarOtpInput.value = '';
                    } else {
                        alert(data.message || 'OTP verification failed');
                    }
                })
                .catch(err => {
                    regVerifyOtpBtn.disabled = false;
                    regVerifyOtpBtn.textContent = '<?= esc(lang('App.appAadhaarVerifyOtp')) ?>';
                    alert('Error: ' + err.message);
                });
            });
        }

        // Resend OTP
        if (regResendOtpBtn) {
            regResendOtpBtn.addEventListener('click', function() {
                if (regGenerateOtpBtn) {
                    regGenerateOtpBtn.click();
                }
            });
        }

        // Check verification on Aadhaar input blur
        if (regAadhaarInput) {
            regAadhaarInput.addEventListener('blur', checkRegExistingVerification);
        }
    })();
</script>


