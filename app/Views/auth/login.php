<div class="container mx-auto px-4 py-12 max-w-md">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
            <?= esc(lang('App.loginTitle')) ?>
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

        <form id="login-form" action="<?= site_url('auth/login') ?>" method="POST" class="space-y-4">
            <div>
                <label for="login-mobile" class="block text-sm font-medium mb-1"><?= esc(lang('App.loginMobileLabel')) ?></label>
                <input id="login-mobile" name="mobile" type="text" required
                       value="<?= esc(old('mobile', '')) ?>"
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                       placeholder="<?= esc(lang('App.loginMobilePlaceholder')) ?>">
            </div>
            <div>
                <label for="login-password" class="block text-sm font-medium mb-1"><?= esc(lang('App.loginPasswordLabel')) ?></label>
                <input id="login-password" name="password" type="password" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                       placeholder="<?= esc(lang('App.loginPasswordPlaceholder')) ?>">
            </div>
            <button type="submit"
                    class="w-full px-4 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                <?= esc(lang('App.loginButton')) ?>
            </button>
            <div class="mt-4 flex flex-col gap-2 text-sm" style="color:#4B5563;">
                <a href="<?= site_url('auth/forgot-password') ?>" class="text-blue-600 hover:underline text-center">
                    <?= esc(lang('App.forgotPasswordLink') ?? 'Forgot password?') ?>
                </a>
                <p class="text-center">
                    <?= esc(lang('App.loginNoAccount')) ?>
                    <a href="<?= site_url('auth/register') ?>" class="text-blue-600 hover:underline">
                        <?= esc(lang('App.loginRegisterLink')) ?>
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        // Form will submit normally to backend
        // No JavaScript needed for form submission
    })();
</script>


