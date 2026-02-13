<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        <?= esc(lang('App.profileTitle')) ?>
    </h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 p-3 rounded-md text-sm" style="background-color: #D1FAE5; color: #059669;">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('user/profile') ?>" method="POST"
          class="bg-white shadow-md rounded-lg p-6 space-y-4">
        <div>
            <label for="profile-name" class="block text-sm font-medium mb-1"><?= esc(lang('App.profileFullNameLabel')) ?></label>
            <input id="profile-name" name="name" type="text"
                   value="<?= esc(old('name', $user['name'] ?? '')) ?>"
                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
        </div>
        <div>
            <label for="profile-email" class="block text-sm font-medium mb-1"><?= esc(lang('App.profileEmailLabel')) ?></label>
            <input id="profile-email" name="email" type="email"
                   value="<?= esc(old('email', $user['email'] ?? '')) ?>"
                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
        </div>
        <div>
            <label for="profile-category" class="block text-sm font-medium mb-1"><?= esc(lang('App.profileCategoryLabel')) ?></label>
            <?php $cat = old('category', $user['category'] ?? ''); ?>
            <select id="profile-category" name="category"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                <option value=""><?= esc(lang('App.selectOption') ?? 'Select category') ?></option>
                <option value="General" <?= $cat === 'General' ? 'selected' : '' ?>>General</option>
                <option value="EWS" <?= $cat === 'EWS' ? 'selected' : '' ?>>EWS</option>
                <option value="LIG" <?= $cat === 'LIG' ? 'selected' : '' ?>>LIG</option>
                <option value="MIG-A" <?= $cat === 'MIG-A' ? 'selected' : '' ?>>MIG-A</option>
                <option value="MIG-B" <?= $cat === 'MIG-B' ? 'selected' : '' ?>>MIG-B</option>
                <option value="HIG" <?= $cat === 'HIG' ? 'selected' : '' ?>>HIG</option>
                <option value="SC" <?= $cat === 'SC' ? 'selected' : '' ?>>SC</option>
                <option value="ST" <?= $cat === 'ST' ? 'selected' : '' ?>>ST</option>
                <option value="Central Govt Employee" <?= $cat === 'Central Govt Employee' ? 'selected' : '' ?>>Central Govt Employee</option>
                <option value="State Govt Employee" <?= $cat === 'State Govt Employee' ? 'selected' : '' ?>>State Govt Employee</option>
                <option value="PSU Employee" <?= $cat === 'PSU Employee' ? 'selected' : '' ?>>PSU Employee</option>
                <?php
                    $soldierCategories = ['Serving Soldier', 'Ex-Serviceman', 'Soldier Widow/Dependent', 'Soldier Category'];
                    $soldierSelected   = in_array($cat, $soldierCategories, true);
                ?>
                <option value="Soldier Category" <?= $soldierSelected ? 'selected' : '' ?>>Soldier (Serving / Ex-Serviceman / Widow/Dependent)</option>
                <option value="Divyang (PwD)" <?= $cat === 'Divyang (PwD)' ? 'selected' : '' ?>>Divyang (PwD)</option>
                <option value="Accredited Journalist" <?= $cat === 'Accredited Journalist' ? 'selected' : '' ?>>Accredited Journalist</option>
                <option value="Transgender" <?= $cat === 'Transgender' ? 'selected' : '' ?>>Transgender</option>
            </select>
        </div>
        <div>
            <label for="profile-language" class="block text-sm font-medium mb-1"><?= esc(lang('App.profileLanguageLabel')) ?></label>
            <?php $lang = old('language', $user['language'] ?? 'en'); ?>
            <select id="profile-language" name="language"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>><?= esc(lang('App.languageEn')) ?></option>
                <option value="hi" <?= $lang === 'hi' ? 'selected' : '' ?>><?= esc(lang('App.languageHi')) ?></option>
            </select>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
            <a href="/user/dashboard"
               class="w-full sm:w-auto px-6 py-2 rounded-md border text-sm font-semibold text-gray-700 text-center hover:bg-gray-50"
               style="border-color:#D1D5DB;">
                <?= esc(lang('App.backButton') ?? 'Back') ?>
            </a>
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                <?= esc(lang('App.profileSaveButton')) ?>
            </button>
        </div>
    </form>
</div>