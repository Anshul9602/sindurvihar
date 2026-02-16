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
                
                // Form will submit normally to backend
            });
        }
    })();
</script>


