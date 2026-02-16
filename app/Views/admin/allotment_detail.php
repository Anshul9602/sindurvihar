<!-- Title Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminAllotmentDetailTitle') ?? 'Allotment Detail') ?>
    </h1>
</div>

<?php if (empty($allotment)): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <p style="color: #4B5563;"><?= esc(lang('App.adminAllotmentNotFound') ?? 'Allotment not found') ?></p>
    </div>
<?php else: ?>
    <!-- Main Allotment Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <!-- Allotment Information Section -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminAllotmentInformation') ?? 'Allotment Information') ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminAllotmentId')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        #<?= esc($allotment['id']) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminAllotmentStatus') ?? 'Status') ?>
                    </label>
                    <p class="text-base">
                        <?php 
                        $status = $allotment['status'] ?? 'provisional';
                        $statusClass = 'bg-green-100 text-green-800';
                        if ($status === 'final') {
                            $statusClass = 'bg-blue-100 text-blue-800';
                        }
                        ?>
                        <span class="px-3 py-1 rounded text-sm font-semibold uppercase <?= $statusClass ?>">
                            <?= esc(ucfirst($status)) ?>
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminAllotmentApplicationId')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <a href="/admin/applications/<?= esc($allotment['application_id']) ?>" 
                           class="text-blue-600 hover:text-blue-800 hover:underline">
                            #<?= esc($allotment['application_id']) ?>
                        </a>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminAllotmentCreatedDate') ?? 'Created Date') ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= isset($allotment['created_at']) ? esc(date('d M Y, h:i A', strtotime($allotment['created_at']))) : '—' ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Plot Information Section -->
        <div class="border-t pt-6 mt-6">
            <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminPlotInformation') ?? 'Plot Information') ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminAllotmentPlot')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        <?= esc($allotment['plot_number'] ?? 'N/A') ?>
                    </p>
                </div>
                <?php if (!empty($allotment['block_name'])): ?>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminBlockName') ?? 'Block Name') ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($allotment['block_name']) ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php if (!empty($plot)): ?>
                    <?php if (!empty($plot['plot_name'])): ?>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.adminPlotName') ?? 'Plot Name') ?>
                        </label>
                        <p class="text-base" style="color: #111827;">
                            <?= esc($plot['plot_name']) ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($plot['category'])): ?>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.adminPlotCategory') ?? 'Category') ?>
                        </label>
                        <p class="text-base">
                            <span class="px-3 py-1 rounded text-sm font-semibold uppercase bg-blue-100 text-blue-800">
                                <?= esc(strtoupper($plot['category'])) ?>
                            </span>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($plot['dimensions'])): ?>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.adminPlotDimensions') ?? 'Dimensions') ?>
                        </label>
                        <p class="text-base" style="color: #111827;">
                            <?= esc($plot['dimensions']) ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($plot['area'])): ?>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.adminPlotArea') ?? 'Area') ?>
                        </label>
                        <p class="text-base" style="color: #111827;">
                            <?= esc($plot['area']) ?> sq ft
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($plot['location'])): ?>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.adminPlotLocation') ?? 'Location') ?>
                        </label>
                        <p class="text-base" style="color: #111827;">
                            <?= esc($plot['location']) ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($plot['price'])): ?>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.adminPlotPrice') ?? 'Price') ?>
                        </label>
                        <p class="text-base font-semibold" style="color: #111827;">
                            ₹<?= number_format($plot['price'], 2) ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($plot['plot_image'])): ?>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                            <?= esc(lang('App.adminPlotImage') ?? 'Plot Image') ?>
                        </label>
                        <div class="mt-2">
                            <img src="/<?= esc($plot['plot_image']) ?>" 
                                 alt="Plot Image" 
                                 class="max-w-md h-auto rounded-lg border border-gray-200">
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Application Information Section -->
        <div class="border-t pt-6 mt-6">
            <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminApplicationInformation')) ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminAllotmentName')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        <?= esc($allotment['full_name'] ?? $allotment['user_name'] ?? 'N/A') ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationStatus')) ?>
                    </label>
                    <p class="text-base">
                        <?php 
                        $appStatus = $allotment['application_status'] ?? 'draft';
                        $appStatusClass = 'bg-gray-100 text-gray-800';
                        if ($appStatus === 'submitted') $appStatusClass = 'bg-yellow-100 text-yellow-800';
                        elseif ($appStatus === 'verified') $appStatusClass = 'bg-green-100 text-green-800';
                        elseif ($appStatus === 'rejected') $appStatusClass = 'bg-red-100 text-red-800';
                        elseif ($appStatus === 'selected') $appStatusClass = 'bg-blue-100 text-blue-800';
                        ?>
                        <span class="px-3 py-1 rounded text-sm font-semibold uppercase <?= $appStatusClass ?>">
                            <?= esc(ucfirst(str_replace('_', ' ', $appStatus))) ?>
                        </span>
                    </p>
                </div>
                <?php if (!empty($allotment['mobile']) || !empty($allotment['user_mobile'])): ?>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminMobile') ?? 'Mobile') ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($allotment['mobile'] ?? $allotment['user_mobile'] ?? 'N/A') ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php if (!empty($allotment['aadhaar'])): ?>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationAadhaar')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($allotment['aadhaar']) ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php if (!empty($allotment['address'])): ?>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationAddress')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($allotment['address']) ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php if (!empty($allotment['tehsil']) || !empty($allotment['district'])): ?>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminTehsilDistrict') ?? 'Tehsil/District') ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($allotment['tehsil'] ?? 'N/A') ?>, <?= esc($allotment['district'] ?? 'N/A') ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php if (!empty($allotment['income'])): ?>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationAnnualIncome')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        ₹<?= number_format($allotment['income'], 2) ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php if (!empty($allotment['income_category'])): ?>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationIncomeCategory')) ?>
                    </label>
                    <p class="text-base">
                        <span class="px-3 py-1 rounded text-sm font-semibold uppercase bg-blue-100 text-blue-800">
                            <?= esc(strtoupper($allotment['income_category'])) ?>
                        </span>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actions Section -->
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminActions') ?? 'Actions') ?>
            </h3>
            <div class="flex flex-wrap gap-3">
                <a href="/admin/allotments" 
                   class="px-6 py-2 rounded-md font-semibold border-2 border-blue-600 text-blue-600 hover:bg-blue-50 transition">
                    <?= esc(lang('App.adminBackToList') ?? 'Back to List') ?>
                </a>
                <a href="/admin/applications/<?= esc($allotment['application_id']) ?>" 
                   class="px-6 py-2 rounded-md font-semibold bg-cyan-600 text-white hover:bg-cyan-700 transition">
                    <?= esc(lang('App.adminViewApplication') ?? 'View Application') ?>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

