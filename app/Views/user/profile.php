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
            <label for="profile-language" class="block text-sm font-medium mb-1"><?= esc(lang('App.profileLanguageLabel')) ?></label>
            <?php $lang = old('language', $user['language'] ?? 'en'); ?>
            <select id="profile-language" name="language"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>><?= esc(lang('App.languageEn')) ?></option>
                <option value="hi" <?= $lang === 'hi' ? 'selected' : '' ?>><?= esc(lang('App.languageHi')) ?></option>
            </select>
        </div>

        <button type="submit"
                class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                style="background-color: #0747A6;">
            <?= esc(lang('App.profileSaveButton')) ?>
        </button>
    </form>
</div>