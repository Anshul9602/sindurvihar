<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-4 rounded-md border border-green-300 bg-green-50 px-4 py-3 text-sm" style="color:#166534;">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-sm" style="color:#B91C1C;">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>
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

        <!-- Generate Chalan for Final Payment -->
        <div class="border-t pt-6 mt-6">
            <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminGenerateChalan') ?? 'Generate Chalan for Final Payment') ?>
            </h2>
            <?php if (!empty($chalan)): ?>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm" style="color:#111827;">
                        <strong><?= esc(lang('App.chalanNumber') ?? 'Chalan No') ?>:</strong> <?= esc($chalan['chalan_number']) ?><br>
                        <strong><?= esc(lang('App.amount') ?? 'Amount') ?>:</strong> ₹<?= number_format($chalan['amount']) ?><br>
                        <strong><?= esc(lang('App.adminStatus') ?? 'Status') ?>:</strong>
                        <span class="px-2 py-0.5 rounded text-xs font-semibold <?= ($chalan['status'] === 'paid') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                            <?= esc(ucfirst($chalan['status'])) ?>
                        </span>
                        <?php if (($chalan['status'] ?? '') === 'paid' && !empty($chalan['paid_at'])): ?>
                            <br><strong><?= esc(lang('App.chalanPaidDate') ?? 'Paid Date') ?>:</strong> <?= esc(date('d M Y, h:i A', strtotime($chalan['paid_at']))) ?>
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <form action="<?= site_url('admin/allotments/' . $allotment['id'] . '/generate-chalan') ?>" method="post" class="flex flex-wrap items-end gap-3">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #6B7280;"><?= esc(lang('App.chalanAmount') ?? 'Chalan Amount (₹)') ?></label>
                        <input type="number" name="amount" min="1" required
                               class="border border-gray-300 rounded-md px-3 py-2 w-40"
                               placeholder="e.g. 50000">
                    </div>
                    <button type="submit" class="px-4 py-2 rounded-md font-semibold text-white" style="background-color:#10B981;">
                        <?= esc(lang('App.adminGenerateChalan') ?? 'Generate Chalan') ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Actions Section -->
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminActions') ?? 'Actions') ?>
            </h3>
            <div class="flex flex-wrap gap-3 items-center">
                <a href="<?= site_url('admin/allotments') ?>" 
                   class="px-6 py-2 rounded-md font-semibold border-2 border-blue-600 text-blue-600 hover:bg-blue-50 transition">
                    <?= esc(lang('App.adminBackToList') ?? 'Back to List') ?>
                </a>
                <a href="<?= site_url('admin/applications/' . $allotment['application_id']) ?>" 
                   class="px-6 py-2 rounded-md font-semibold bg-cyan-600 text-white hover:bg-cyan-700 transition">
                    <?= esc(lang('App.adminViewApplication') ?? 'View Application') ?>
                </a>
                <?php
                $chalanPaid = !empty($chalan) && ($chalan['status'] ?? '') === 'paid';
                $chalanVerified = !empty($chalan) && !empty($chalan['verified_at']);
                $allotmentStatus = strtolower((string)($allotment['status'] ?? 'provisional'));
                $canMarkAllotted = $chalanPaid && $chalanVerified && in_array($allotmentStatus, ['provisional', 'final'], true);
                $needsVerification = $chalanPaid && !$chalanVerified && in_array($allotmentStatus, ['provisional', 'final'], true);
                ?>
                <?php if ($needsVerification): ?>
                    <a href="<?= site_url('admin/chalans/' . ($chalan['id'] ?? '')) ?>" class="px-6 py-2 rounded-md font-semibold bg-amber-600 text-white hover:bg-amber-700 transition shadow-sm inline-flex items-center gap-2">
                        <?= esc(lang('App.adminVerifyChalan') ?? 'Verify Chalan') ?> →
                    </a>
                <?php elseif ($canMarkAllotted): ?>
                    <form action="<?= site_url('admin/allotments/' . $allotment['id'] . '/mark-allotted') ?>" method="post" class="inline" onsubmit="return confirm('<?= esc(lang('App.adminConfirmMarkAllotted') ?? 'Mark this allotment as Allotted?') ?>');">
                        <?= csrf_field() ?>
                        <button type="submit" class="px-6 py-2 rounded-md font-semibold bg-green-600 text-white hover:bg-green-700 transition shadow-sm inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <?= esc(lang('App.adminMarkAllotted') ?? 'Mark as Allotted') ?>
                        </button>
                    </form>
                <?php elseif (in_array($allotmentStatus, ['allotted', 'alloted'], true)): ?>
                    <span class="px-4 py-2 rounded-md font-semibold bg-green-100 text-green-800 border border-green-200">
                        <?= esc(lang('App.adminAllottedStatus') ?? 'Allotted') ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

