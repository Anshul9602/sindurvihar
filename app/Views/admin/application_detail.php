<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6" style="color: #0F1F3F;">
        Application Detail
    </h1>

    <?php if (empty($application)): ?>
        <div class="bg-white shadow-md rounded-lg p-6">
            <p style="color: #4B5563;">Application not found.</p>
        </div>
    <?php else: ?>
        <div class="bg-white shadow-md rounded-lg p-6 space-y-4">
            <div>
                <h2 class="text-xl font-semibold mb-4" style="color: #0F1F3F;">Application Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Application ID</label>
                        <p class="text-base font-semibold" style="color: #0F1F3F;"><?= esc($application['id']) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Status</label>
                        <p class="text-base">
                            <span class="px-3 py-1 rounded text-sm uppercase
                                <?php 
                                $status = $application['status'] ?? 'draft';
                                if ($status === 'submitted') echo 'bg-yellow-100 text-yellow-800';
                                elseif ($status === 'verified') echo 'bg-green-100 text-green-800';
                                elseif ($status === 'rejected') echo 'bg-red-100 text-red-800';
                                else echo 'bg-gray-100 text-gray-800';
                                ?>">
                                <?= esc(ucfirst(str_replace('_', ' ', $status))) ?>
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Full Name</label>
                        <p class="text-base" style="color: #0F1F3F;"><?= esc($application['full_name']) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Aadhaar Number</label>
                        <p class="text-base" style="color: #0F1F3F;"><?= esc($application['aadhaar']) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Address</label>
                        <p class="text-base" style="color: #0F1F3F;"><?= esc($application['address']) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">City/State</label>
                        <p class="text-base" style="color: #0F1F3F;">
                            <?= esc($application['city'] ?? 'N/A') ?>, <?= esc($application['state'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Annual Income</label>
                        <p class="text-base" style="color: #0F1F3F;">₹<?= number_format($application['income'], 2) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Income Category</label>
                        <p class="text-base">
                            <span class="px-3 py-1 rounded text-sm uppercase bg-blue-100 text-blue-800">
                                <?= esc(strtoupper($application['income_category'])) ?>
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Submitted Date</label>
                        <p class="text-base" style="color: #0F1F3F;">
                            <?= date('d M Y, h:i A', strtotime($application['created_at'])) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-4 mt-4">
                <h3 class="text-lg font-semibold mb-3" style="color: #0F1F3F;">Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <?php if (!empty($documents)): ?>
                        <button id="btn-view-docs"
                                class="px-4 py-2 rounded-md font-semibold border"
                                style="border-color:#0F1F3F; color:#0F1F3F;">
                            View Documents
                        </button>
                    <?php endif; ?>
                    <?php if ($application['status'] === 'submitted' || $application['status'] === 'under_verification'): ?>
                        <button id="btn-verify" 
                                class="px-4 py-2 rounded-md font-semibold text-white"
                                style="background-color: #16A34A;">
                            Mark as Verified
                        </button>
                    <?php endif; ?>
                    <?php if ($application['status'] !== 'rejected'): ?>
                        <button id="btn-reject" 
                                class="px-4 py-2 rounded-md font-semibold text-white"
                                style="background-color: #DC2626;">
                            Reject Application
                        </button>
                    <?php endif; ?>
                    <a href="/admin/applications" 
                       class="px-4 py-2 rounded-md font-semibold border"
                       style="border-color: #0747A6; color: #0747A6;">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php
    $identityFiles  = !empty($documents['identity_files'] ?? null) ? json_decode($documents['identity_files'], true) : [];
    $incomeFiles    = !empty($documents['income_files'] ?? null) ? json_decode($documents['income_files'], true) : [];
    $residenceFiles = !empty($documents['residence_files'] ?? null) ? json_decode($documents['residence_files'], true) : [];
    $annexureFiles  = !empty($documents['annexure_files'] ?? null) ? json_decode($documents['annexure_files'], true) : [];
?>

<?php if (!empty($documents)): ?>
    <!-- Documents modal -->
    <div id="docs-modal"
         class="fixed inset-0 z-40 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[80vh] overflow-y-auto p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold" style="color:#0F1F3F;">Documents Submitted</h2>
                <button id="btn-close-docs" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            </div>

            <div class="space-y-4 text-sm">
                <div>
                    <p class="font-medium" style="color:#4B5563;">Identity Proof</p>
                    <?php if (!empty($identityFiles)): ?>
                        <ul class="space-y-1 text-blue-700">
                            <?php foreach ($identityFiles as $path): ?>
                                <?php $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); ?>
                                <li>
                                    <a href="<?= base_url($path) ?>" target="_blank"
                                       class="flex items-center space-x-2 hover:underline">
                                        <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                            <img src="<?= base_url($path) ?>" alt="Document"
                                                 style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                        <?php else: ?>
                                            <div style="width:50px;height:50px;border-radius:4px;background:#E5E7EB;
                                                        display:flex;align-items:center;justify-content:center;
                                                        font-size:11px;color:#6B7280;">
                                                <?= strtoupper($ext) ?>
                                            </div>
                                        <?php endif; ?>
                                        <span><?= esc(basename($path)) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="color:#6B7280;">Not uploaded</p>
                    <?php endif; ?>
                </div>

                <div>
                    <p class="font-medium" style="color:#4B5563;">Income Proof</p>
                    <?php if (!empty($incomeFiles)): ?>
                        <ul class="space-y-1 text-blue-700">
                            <?php foreach ($incomeFiles as $path): ?>
                                <?php $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); ?>
                                <li>
                                    <a href="<?= base_url($path) ?>" target="_blank"
                                       class="flex items-center space-x-2 hover:underline">
                                        <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                            <img src="<?= base_url($path) ?>" alt="Document"
                                                 style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                        <?php else: ?>
                                            <div style="width:50px;height:50px;border-radius:4px;background:#E5E7EB;
                                                        display:flex;align-items:center;justify-content:center;
                                                        font-size:11px;color:#6B7280;">
                                                <?= strtoupper($ext) ?>
                                            </div>
                                        <?php endif; ?>
                                        <span><?= esc(basename($path)) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="color:#6B7280;">Not uploaded</p>
                    <?php endif; ?>
                </div>

                <div>
                    <p class="font-medium" style="color:#4B5563;">Residence Proof</p>
                    <?php if (!empty($residenceFiles)): ?>
                        <ul class="space-y-1 text-blue-700">
                            <?php foreach ($residenceFiles as $path): ?>
                                <?php $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); ?>
                                <li>
                                    <a href="<?= base_url($path) ?>" target="_blank"
                                       class="flex items-center space-x-2 hover:underline">
                                        <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                            <img src="<?= base_url($path) ?>" alt="Document"
                                                 style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                        <?php else: ?>
                                            <div style="width:50px;height:50px;border-radius:4px;background:#E5E7EB;
                                                        display:flex;align-items:center;justify-content:center;
                                                        font-size:11px;color:#6B7280;">
                                                <?= strtoupper($ext) ?>
                                            </div>
                                        <?php endif; ?>
                                        <span><?= esc(basename($path)) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="color:#6B7280;">Not uploaded</p>
                    <?php endif; ?>
                </div>

                <div>
                    <p class="font-medium" style="color:#4B5563;">Annexure Forms</p>
                    <?php if (!empty($annexureFiles)): ?>
                        <ul class="space-y-1 text-blue-700">
                            <?php foreach ($annexureFiles as $path): ?>
                                <?php $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); ?>
                                <li>
                                    <a href="<?= base_url($path) ?>" target="_blank"
                                       class="flex items-center space-x-2 hover:underline">
                                        <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                            <img src="<?= base_url($path) ?>" alt="Document"
                                                 style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                        <?php else: ?>
                                            <div style="width:50px;height:50px;border-radius:4px;background:#E5E7EB;
                                                        display:flex;align-items:center;justify-content:center;
                                                        font-size:11px;color:#6B7280;">
                                                <?= strtoupper($ext) ?>
                                            </div>
                                        <?php endif; ?>
                                        <span><?= esc(basename($path)) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="color:#6B7280;">Not uploaded</p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($documents['notes'])): ?>
                    <div>
                        <p class="font-medium" style="color:#4B5563;">Notes</p>
                        <p style="color:#4B5563;"><?= nl2br(esc($documents['notes'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

</div>

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

        // Documents modal behaviour
        var btnViewDocs = document.getElementById("btn-view-docs");
        var docsModal   = document.getElementById("docs-modal");
        var btnCloseDocs = document.getElementById("btn-close-docs");

        if (btnViewDocs && docsModal) {
            btnViewDocs.addEventListener("click", function () {
                docsModal.classList.remove("hidden");
                docsModal.classList.add("flex");
            });
        }

        if (btnCloseDocs && docsModal) {
            btnCloseDocs.addEventListener("click", function () {
                docsModal.classList.add("hidden");
                docsModal.classList.remove("flex");
            });
        }

        if (docsModal) {
            docsModal.addEventListener("click", function (e) {
                if (e.target === docsModal) {
                    docsModal.classList.add("hidden");
                    docsModal.classList.remove("flex");
                }
            });
        }
    })();
</script>


