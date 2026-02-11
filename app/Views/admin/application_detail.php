<!-- Title Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminApplicationDetailTitle')) ?>
    </h1>
</div>

<?php if (empty($application)): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <p style="color: #4B5563;"><?= esc(lang('App.adminApplicationNotFound')) ?></p>
    </div>
<?php else: ?>
    <!-- Main Application Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <!-- Application Information Section -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminApplicationInformation')) ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationId')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        <?= esc($application['id']) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationStatus')) ?>
                    </label>
                    <p class="text-base">
                        <?php 
                        $status = $application['status'] ?? 'draft';
                        $statusClass = 'bg-gray-100 text-gray-800';
                        if ($status === 'submitted') $statusClass = 'bg-yellow-100 text-yellow-800';
                        elseif ($status === 'verified') $statusClass = 'bg-green-100 text-green-800';
                        elseif ($status === 'rejected') $statusClass = 'bg-red-100 text-red-800';
                        ?>
                        <span class="px-3 py-1 rounded text-sm font-semibold uppercase <?= $statusClass ?>">
                            <?= esc(ucfirst(str_replace('_', ' ', $status))) ?>
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationFullName')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($application['full_name']) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationAadhaar')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($application['aadhaar'] ?? 'N/A') ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationAddress')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($application['address'] ?? 'N/A') ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationCityState')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($application['city'] ?? 'N/A') ?>, <?= esc($application['state'] ?? 'N/A') ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationAnnualIncome')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        ₹<?= number_format($application['income'] ?? 0, 2) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationIncomeCategory')) ?>
                    </label>
                    <p class="text-base">
                        <span class="px-3 py-1 rounded text-sm font-semibold uppercase bg-blue-100 text-blue-800">
                            <?= esc(strtoupper($application['income_category'] ?? 'General')) ?>
                        </span>
                    </p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationSubmittedDate')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= date('d M Y, h:i A', strtotime($application['created_at'] ?? 'now')) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <?php if (!empty($documents)): ?>
        <div class="border-t pt-6 mt-6">
            <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminDocuments')) ?>
            </h2>
            <div class="space-y-4">
                <?php
                // Parse document files from JSON
                $identityFiles = !empty($documents['identity_files']) ? json_decode($documents['identity_files'], true) : [];
                $incomeFiles = !empty($documents['income_files']) ? json_decode($documents['income_files'], true) : [];
                $residenceFiles = !empty($documents['residence_files']) ? json_decode($documents['residence_files'], true) : [];
                $annexureFiles = !empty($documents['annexure_files']) ? json_decode($documents['annexure_files'], true) : [];
                ?>
                
                <?php if (!empty($identityFiles)): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                        <?= esc(lang('App.adminIdentityProof')) ?>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($identityFiles as $file): ?>
                            <a href="/<?= esc($file) ?>" target="_blank" 
                               class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <?= esc(basename($file)) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($incomeFiles)): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                        <?= esc(lang('App.adminIncomeProof')) ?>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($incomeFiles as $file): ?>
                            <a href="/<?= esc($file) ?>" target="_blank" 
                               class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <?= esc(basename($file)) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($residenceFiles)): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                        <?= esc(lang('App.adminResidenceProof')) ?>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($residenceFiles as $file): ?>
                            <a href="/<?= esc($file) ?>" target="_blank" 
                               class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <?= esc(basename($file)) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($annexureFiles)): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                        <?= esc(lang('App.adminAnnexureFiles')) ?>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($annexureFiles as $file): ?>
                            <a href="/<?= esc($file) ?>" target="_blank" 
                               class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <?= esc(basename($file)) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($documents['notes'])): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                        <?= esc(lang('App.adminNotes')) ?>
                    </label>
                    <p class="text-sm p-3 bg-gray-50 rounded-md" style="color: #374151;">
                        <?= esc($documents['notes']) ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Payment Details Section -->
        <?php if (!empty($payment)): ?>
        <div class="border-t pt-6 mt-6">
            <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminPaymentDetails')) ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminPaymentId')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        <?= esc($payment['id']) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminPaymentAmount')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        ₹<?= number_format($payment['amount'] ?? 0, 2) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminPaymentStatus')) ?>
                    </label>
                    <p class="text-base">
                        <?php 
                        $paymentStatus = $payment['status'] ?? 'pending';
                        $paymentStatusClass = 'bg-gray-100 text-gray-800';
                        if ($paymentStatus === 'completed' || $paymentStatus === 'success') {
                            $paymentStatusClass = 'bg-green-100 text-green-800';
                            $paymentStatus = lang('App.adminPaymentCompleted');
                        } elseif ($paymentStatus === 'pending') {
                            $paymentStatusClass = 'bg-yellow-100 text-yellow-800';
                            $paymentStatus = lang('App.adminPaymentPending');
                        } elseif ($paymentStatus === 'failed') {
                            $paymentStatusClass = 'bg-red-100 text-red-800';
                            $paymentStatus = lang('App.adminPaymentFailed');
                        }
                        ?>
                        <span class="px-3 py-1 rounded text-sm font-semibold uppercase <?= $paymentStatusClass ?>">
                            <?= esc($paymentStatus) ?>
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminPaymentDate')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= date('d M Y, h:i A', strtotime($payment['created_at'] ?? 'now')) ?>
                    </p>
                </div>
                <?php if (!empty($payment['transaction_ref'])): ?>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminPaymentTransactionRef')) ?>
                    </label>
                    <p class="text-base font-mono" style="color: #111827;">
                        <?= esc($payment['transaction_ref']) ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Actions Section -->
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminApplicationActions')) ?>
            </h3>
            <div class="flex flex-wrap gap-3">
                <?php if ($application['status'] === 'submitted' || $application['status'] === 'under_verification'): ?>
                    <button id="btn-verify" 
                            class="px-6 py-2 rounded-md font-semibold text-white bg-green-600 hover:bg-green-700 transition">
                        <?= esc(lang('App.adminMarkAsVerified')) ?>
                    </button>
                <?php endif; ?>
                <?php if ($application['status'] !== 'rejected'): ?>
                    <button id="btn-reject" 
                            class="px-6 py-2 rounded-md font-semibold text-white bg-red-600 hover:bg-red-700 transition">
                        <?= esc(lang('App.adminRejectApplication')) ?>
                    </button>
                <?php endif; ?>
                <a href="/admin/applications" 
                   class="px-6 py-2 rounded-md font-semibold border-2 border-blue-600 text-blue-600 hover:bg-blue-50 transition">
                    <?= esc(lang('App.adminBackToList')) ?>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
(function () {
    var appId = <?= isset($application) ? $application['id'] : 'null' ?>;
    
    function updateStatus(newStatus) {
        if (!appId) return;
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/admin/applications/update-status", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Application status updated successfully!");
                            window.location.reload();
                        } else {
                            alert("Error: " + (response.message || "Failed to update status"));
                        }
                    } catch (e) {
                        alert("Error parsing response");
                    }
                } else {
                    alert("Server error: " + xhr.status);
                }
            }
        };
        
        xhr.send(JSON.stringify({
            application_id: appId,
            status: newStatus
        }));
    }
    
    var btnVerify = document.getElementById("btn-verify");
    if (btnVerify) {
        btnVerify.addEventListener("click", function() {
            if (confirm("Are you sure you want to mark this application as verified?")) {
                updateStatus("verified");
            }
        });
    }
    
    var btnReject = document.getElementById("btn-reject");
    if (btnReject) {
        btnReject.addEventListener("click", function() {
            if (confirm("Are you sure you want to reject this application?")) {
                updateStatus("rejected");
            }
        });
    }
})();
</script>
