<div class="container mx-auto px-4 py-12 max-w-md">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center" style="color:#0F1F3F;">
            <?= esc(lang('App.forgotPasswordTitle') ?? 'Forgot Password') ?>
        </h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 rounded-md text-sm" style="background-color:#FEE2E2; color:#DC2626;">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-3 rounded-md text-sm" style="background-color:#D1FAE5; color:#059669;">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (($stage ?? 'mobile') === 'mobile'): ?>
            <!-- Step 1: Enter mobile -->
            <form action="<?= site_url('auth/forgot-password') ?>" method="POST" class="space-y-4">
                <input type="hidden" name="step" value="mobile">
                <div>
                    <label for="forgot-mobile" class="block text-sm font-medium mb-1">
                        <?= esc(lang('App.loginMobileLabel')) ?>
                    </label>
                    <input id="forgot-mobile" name="mobile" type="text" required
                           value="<?= esc(old('mobile', '')) ?>"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                           placeholder="<?= esc(lang('App.loginMobilePlaceholder')) ?>">
                </div>

                <button type="submit"
                        class="w-full px-4 py-2 rounded-md font-semibold text-white"
                        style="background-color:#0747A6;">
                    <?= esc(lang('App.forgotPasswordButton') ?? 'Reset Password') ?>
                </button>
            </form>
        <?php elseif (($stage ?? 'mobile') === 'otp'): ?>
            <!-- Step 2: Enter OTP -->
            <form action="<?= site_url('auth/forgot-password') ?>" method="POST" class="space-y-4 mb-4">
                <input type="hidden" name="step" value="otp">
                <div>
                    <label for="forgot-otp" class="block text-sm font-medium mb-1">
                        <?= esc(lang('App.forgotPasswordOtpLabel') ?? 'Enter OTP') ?>
                    </label>
                    <input id="forgot-otp" name="otp" type="text" required
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                           placeholder="123456">
                </div>

                <button type="submit"
                        class="w-full px-4 py-2 rounded-md font-semibold text-white"
                        style="background-color:#0747A6;">
                    <?= esc(lang('App.forgotPasswordVerifyOtp') ?? 'Verify OTP') ?>
                </button>
            </form>

            <form action="<?= site_url('auth/forgot-password') ?>" method="POST" class="text-center">
                <input type="hidden" name="step" value="resend">
                <button type="submit" class="text-sm text-blue-600 hover:underline">
                    <?= esc(lang('App.forgotPasswordResendOtp') ?? 'Resend OTP') ?>
                </button>
            </form>

            <p class="mt-4 text-xs text-gray-500 text-center">
                Demo mode: OTP is always <strong>123456</strong> and is saved in database.
            </p>
        <?php elseif (($stage ?? 'mobile') === 'reset'): ?>
            <!-- Step 3: New password -->
            <form action="<?= site_url('auth/forgot-password') ?>" method="POST" class="space-y-4">
                <input type="hidden" name="step" value="reset">

                <div>
                    <label for="forgot-new-password" class="block text-sm font-medium mb-1">
                        <?= esc(lang('App.forgotPasswordNewPassword') ?? 'New Password') ?>
                    </label>
                    <input id="forgot-new-password" name="password" type="password" required minlength="6"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                </div>

                <div>
                    <label for="forgot-confirm-password" class="block text-sm font-medium mb-1">
                        <?= esc(lang('App.forgotPasswordConfirmPassword') ?? 'Confirm New Password') ?>
                    </label>
                    <input id="forgot-confirm-password" name="password_confirm" type="password" required minlength="6"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                </div>

                <button type="submit"
                        class="w-full px-4 py-2 rounded-md font-semibold text-white"
                        style="background-color:#0747A6;">
                    <?= esc(lang('App.forgotPasswordSaveButton') ?? 'Save New Password') ?>
                </button>
            </form>
        <?php endif; ?>

        <p class="text-center text-sm mt-4" style="color:#4B5563;">
            <a href="<?= site_url('auth/login') ?>" class="text-blue-600 hover:underline">
                <?= esc(lang('App.backToLogin') ?? 'Back to login') ?>
            </a>
        </p>
    </div>
</div>


