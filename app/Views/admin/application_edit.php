<!-- Title Section -->
<div class="mb-6">
    <div class="mb-4">
        <a href="/admin/applications/<?= esc($application['id']) ?>" 
           class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <?= esc(lang('App.backButton')) ?>
        </a>
    </div>
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminEditApplication')) ?>
    </h1>
    <p class="text-sm" style="color: #6B7280;">
        Application ID: <?= esc($application['id']) ?>
    </p>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-4 p-3 rounded-md text-sm bg-red-100 text-red-800">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-4 p-3 rounded-md text-sm bg-green-100 text-green-800">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('admin/applications/' . $application['id'] . '/update') ?>" method="POST" 
      class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
    
    <!-- Identity Section -->
    <div>
        <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
            <?= esc(lang('App.appIdentitySection')) ?>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="full_name" class="block text-sm font-medium mb-1" style="color: #374151;">
                    <?= esc(lang('App.appFullNameLabel')) ?>
                </label>
                <input id="full_name" name="full_name" type="text" required
                       value="<?= esc(old('full_name', $application['full_name'] ?? '')) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="aadhaar" class="block text-sm font-medium mb-1" style="color: #374151;">
                    <?= esc(lang('App.appAadhaarLabel')) ?>
                </label>
                <input id="aadhaar" name="aadhaar" type="text" required
                       value="<?= esc(old('aadhaar', $application['aadhaar'] ?? '')) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <label for="father_husband_name" class="block text-sm font-medium mb-1" style="color: #374151;">
                    <?= esc(lang('App.appFatherHusbandLabel')) ?>
                </label>
                <input id="father_husband_name" name="father_husband_name" type="text" required
                       value="<?= esc(old('father_husband_name', $application['father_husband_name'] ?? '')) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="age" class="block text-sm font-medium mb-1" style="color: #374151;">
                    <?= esc(lang('App.appAgeLabel')) ?>
                </label>
                <input id="age" name="age" type="number" min="18" max="70" required
                       value="<?= esc(old('age', $application['age'] ?? '')) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="mobile" class="block text-sm font-medium mb-1" style="color: #374151;">
                    <?= esc(lang('App.appMobileLabel')) ?>
                </label>
                <input id="mobile" name="mobile" type="text" required
                       value="<?= esc(old('mobile', $application['mobile'] ?? '')) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <!-- Residence Section -->
    <div>
        <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
            <?= esc(lang('App.appResidenceSection')) ?>
        </h2>
        <div class="space-y-4">
            <div>
                <label for="address" class="block text-sm font-medium mb-1" style="color: #374151;">
                    <?= esc(lang('App.appAddressLabel')) ?>
                </label>
                <input id="address" name="address" type="text" required
                       value="<?= esc(old('address', $application['address'] ?? '')) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="tehsil" class="block text-sm font-medium mb-1" style="color: #374151;">
                        <?= esc(lang('App.appTehsilLabel')) ?>
                    </label>
                    <input id="tehsil" name="tehsil" type="text" required
                           value="<?= esc(old('tehsil', $application['tehsil'] ?? '')) ?>"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="district" class="block text-sm font-medium mb-1" style="color: #374151;">
                        <?= esc(lang('App.appDistrictLabel')) ?>
                    </label>
                    <input id="district" name="district" type="text" required
                           value="<?= esc(old('district', $application['district'] ?? '')) ?>"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="state" class="block text-sm font-medium mb-1" style="color: #374151;">
                        <?= esc(lang('App.appStateLabel')) ?>
                    </label>
                    <input id="state" name="state" type="text" value="<?= esc(old('state', $application['state'] ?? 'Rajasthan')) ?>"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Income Section -->
    <div>
        <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
            <?= esc(lang('App.appIncomeSection')) ?>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="income" class="block text-sm font-medium mb-1" style="color: #374151;">
                    <?= esc(lang('App.appAnnualIncomeLabel')) ?>
                </label>
                <input id="income" name="income" type="number" required
                       value="<?= esc(old('income', $application['income'] ?? '')) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="income_category" class="block text-sm font-medium mb-1" style="color: #374151;">
                    <?= esc(lang('App.appCategoryLabel')) ?>
                </label>
                <?php $currentCat = old('income_category', $application['income_category'] ?? 'General'); ?>
                <select id="income_category" name="income_category"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="General" <?= $currentCat === 'General' ? 'selected' : '' ?>>General</option>
                    <option value="EWS" <?= $currentCat === 'EWS' ? 'selected' : '' ?>>EWS</option>
                    <option value="LIG" <?= $currentCat === 'LIG' ? 'selected' : '' ?>>LIG</option>
                    <option value="MIG-A" <?= $currentCat === 'MIG-A' ? 'selected' : '' ?>>MIG-A</option>
                    <option value="MIG-B" <?= $currentCat === 'MIG-B' ? 'selected' : '' ?>>MIG-B</option>
                    <option value="HIG" <?= $currentCat === 'HIG' ? 'selected' : '' ?>>HIG</option>
                    <option value="SC" <?= $currentCat === 'SC' ? 'selected' : '' ?>>SC</option>
                    <option value="ST" <?= $currentCat === 'ST' ? 'selected' : '' ?>>ST</option>
                    <option value="Central Govt Employee" <?= $currentCat === 'Central Govt Employee' ? 'selected' : '' ?>>Central Govt Employee</option>
                    <option value="State Govt Employee" <?= $currentCat === 'State Govt Employee' ? 'selected' : '' ?>>State Govt Employee</option>
                    <option value="PSU Employee" <?= $currentCat === 'PSU Employee' ? 'selected' : '' ?>>PSU Employee</option>
                    <option value="Serving Soldier" <?= $currentCat === 'Serving Soldier' ? 'selected' : '' ?>>Serving Soldier</option>
                    <option value="Ex-Serviceman" <?= $currentCat === 'Ex-Serviceman' ? 'selected' : '' ?>>Ex-Serviceman</option>
                    <option value="Soldier Widow/Dependent" <?= $currentCat === 'Soldier Widow/Dependent' ? 'selected' : '' ?>>Soldier Widow/Dependent</option>
                    <option value="Divyang (PwD)" <?= $currentCat === 'Divyang (PwD)' ? 'selected' : '' ?>>Divyang (PwD)</option>
                    <option value="Accredited Journalist" <?= $currentCat === 'Accredited Journalist' ? 'selected' : '' ?>>Accredited Journalist</option>
                    <option value="Transgender" <?= $currentCat === 'Transgender' ? 'selected' : '' ?>>Transgender</option>
                    <option value="Destitute Woman" <?= $currentCat === 'Destitute Woman' ? 'selected' : '' ?>>Destitute Woman</option>
                    <option value="Landless Woman" <?= $currentCat === 'Landless Woman' ? 'selected' : '' ?>>Landless Woman</option>
                    <option value="Single Woman/Widow" <?= $currentCat === 'Single Woman/Widow' ? 'selected' : '' ?>>Single Woman/Widow</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Status Section -->
    <div>
        <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
            <?= esc(lang('App.adminApplicationStatus')) ?>
        </h2>
        <div>
            <label for="status" class="block text-sm font-medium mb-1" style="color: #374151;">
                <?= esc(lang('App.adminApplicationStatus')) ?>
            </label>
            <?php $currentStatus = old('status', $application['status'] ?? 'draft'); ?>
            <select id="status" name="status"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="draft" <?= $currentStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="submitted" <?= $currentStatus === 'submitted' ? 'selected' : '' ?>>Submitted</option>
                <option value="under_verification" <?= $currentStatus === 'under_verification' ? 'selected' : '' ?>>Under Verification</option>
                <option value="verified" <?= $currentStatus === 'verified' ? 'selected' : '' ?>>Verified</option>
                <option value="rejected" <?= $currentStatus === 'rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 pt-4 border-t">
        <button type="submit"
                class="px-6 py-2 rounded-md font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
            <?= esc(lang('App.adminUpdateApplication')) ?>
        </button>
        <a href="/admin/applications/<?= esc($application['id']) ?>" 
           class="px-6 py-2 rounded-md font-semibold border-2 border-gray-300 text-gray-700 hover:bg-gray-50 transition">
            <?= esc(lang('App.backButton')) ?>
        </a>
    </div>
</form>

