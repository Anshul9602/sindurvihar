<!-- Title Section -->
<div class="mb-6">
    <div class="mb-4">
        <a href="/admin/plots" 
           class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <?= esc(lang('App.adminBackToPlots')) ?>
        </a>
    </div>
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminAddPlotTitle')) ?>
    </h1>
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

<form action="<?= site_url('admin/plots/add') ?>" method="POST" enctype="multipart/form-data" 
      class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="plot_name" class="block text-sm font-medium mb-1" style="color: #374151;">
                <?= esc(lang('App.adminPlotName')) ?> <span class="text-red-500">*</span>
            </label>
            <input id="plot_name" name="plot_name" type="text" required
                   placeholder="<?= esc(lang('App.adminPlotNamePlaceholder')) ?>"
                   value="<?= esc(old('plot_name')) ?>"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="plot_number" class="block text-sm font-medium mb-1" style="color: #374151;">
                <?= esc(lang('App.adminPlotNumber')) ?>
            </label>
            <input id="plot_number" name="plot_number" type="text"
                   placeholder="<?= esc(lang('App.adminPlotNumberPlaceholder')) ?>"
                   value="<?= esc(old('plot_number')) ?>"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="location" class="block text-sm font-medium mb-1" style="color: #374151;">
                <?= esc(lang('App.adminPlotLocation')) ?> <span class="text-red-500">*</span>
            </label>
            <input id="location" name="location" type="text" required
                   placeholder="<?= esc(lang('App.adminPlotLocationPlaceholder')) ?>"
                   value="<?= esc(old('location')) ?>"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="dimensions" class="block text-sm font-medium mb-1" style="color: #374151;">
                <?= esc(lang('App.adminPlotDimensions')) ?>
            </label>
            <input id="dimensions" name="dimensions" type="text"
                   placeholder="<?= esc(lang('App.adminPlotDimensionsPlaceholder')) ?>"
                   value="<?= esc(old('dimensions')) ?>"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="area" class="block text-sm font-medium mb-1" style="color: #374151;">
                <?= esc(lang('App.adminPlotArea')) ?>
            </label>
            <input id="area" name="area" type="number" step="0.01"
                   placeholder="<?= esc(lang('App.adminPlotAreaPlaceholder')) ?>"
                   value="<?= esc(old('area')) ?>"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="price" class="block text-sm font-medium mb-1" style="color: #374151;">
                <?= esc(lang('App.adminPlotPrice')) ?>
            </label>
            <input id="price" name="price" type="number" step="0.01"
                   placeholder="<?= esc(lang('App.adminPlotPricePlaceholder')) ?>"
                   value="<?= esc(old('price')) ?>"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Status + Total Quantity in same row -->
        <div class="md:col-span-2 flex flex-col md:flex-row md:items-end gap-4">
            <div class="md:w-1/2">
                <label for="status" class="block text-sm font-medium mb-1" style="color: #374151;">
                    <?= esc(lang('App.adminPlotStatus')) ?>
                </label>
                <select id="status" name="status"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="available" <?= old('status', 'available') === 'available' ? 'selected' : '' ?>>
                        <?= esc(lang('App.adminPlotAvailableStatus')) ?>
                    </option>
                    <option value="allocated" <?= old('status') === 'allocated' ? 'selected' : '' ?>>
                        <?= esc(lang('App.adminPlotAllocatedStatus')) ?>
                    </option>
                    <option value="reserved" <?= old('status') === 'reserved' ? 'selected' : '' ?>>
                        <?= esc(lang('App.adminPlotReservedStatus')) ?>
                    </option>
                </select>
            </div>

            <div class="md:w-1/2">
                <label for="total_quantity" class="block text-sm font-medium mb-1" style="color:#374151;">
                    <?= esc(lang('App.adminPlotTotalQuantity')) ?>
                </label>
                <input id="total_quantity" name="total_quantity" type="number" min="1"
                       value="<?= esc(old('total_quantity', 1)) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                
            </div>
        </div>
    </div>

    <!-- Category-wise quantity rows -->
    <div class="space-y-3">
        <label class="block text-sm font-medium mb-1" style="color: #374151;">
            <?= esc(lang('App.adminPlotCategory')) ?> / <?= esc(lang('App.adminPlotQuantity')) ?>
        </label>
        <table class="w-full text-sm border border-gray-200 rounded-md">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left" style="color:#6B7280;"><?= esc(lang('App.adminPlotCategory')) ?></th>
                    <th class="px-3 py-2 text-left" style="color:#6B7280;"><?= esc(lang('App.adminPlotQuantity')) ?></th>
                    <th class="px-3 py-2 text-center" style="color:#6B7280;">+</th>
                </tr>
            </thead>
            <tbody id="category-rows">
                <tr class="category-row border-t">
                    <td class="px-3 py-2">
                        <select name="categories[0][category]"
                                class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="General">General</option>
                            <option value="EWS">EWS</option>
                            <option value="LIG">LIG</option>
                            <option value="MIG-A">MIG-A</option>
                            <option value="MIG-B">MIG-B</option>
                            <option value="HIG">HIG</option>
                            <option value="SC">SC</option>
                            <option value="ST">ST</option>
                        </select>
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" min="1" value="1"
                               name="categories[0][quantity]"
                               class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </td>
                    <td class="px-3 py-2 text-center">
                        <button type="button"
                                class="px-2 py-1 rounded-full bg-blue-600 text-white text-sm"
                                onclick="addCategoryRow()">
                            +
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="text-xs text-gray-500">
            Click "+" to add more (category, quantity) rows. Total quantity should equal sum of all row quantities.
        </p>
    </div>

    <div>
        <label for="plot_image" class="block text-sm font-medium mb-1" style="color: #374151;">
            <?= esc(lang('App.adminPlotImageLabel')) ?>
        </label>
        <input id="plot_image" name="plot_image" type="file" accept="image/*"
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <p class="text-xs text-gray-500 mt-1"><?= esc(lang('App.adminPlotImageHelp')) ?></p>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium mb-1" style="color: #374151;">
            <?= esc(lang('App.adminPlotDescription')) ?>
        </label>
        <textarea id="description" name="description" rows="4"
                  placeholder="<?= esc(lang('App.adminPlotDescriptionPlaceholder')) ?>"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= esc(old('description')) ?></textarea>
    </div>

    <div class="flex flex-wrap gap-3 pt-4 border-t">
        <button type="submit"
                class="px-6 py-2 rounded-md font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
            <?= esc(lang('App.adminPlotSaveButton')) ?>
        </button>
        <a href="/admin/plots" 
           class="px-6 py-2 rounded-md font-semibold border-2 border-gray-300 text-gray-700 hover:bg-gray-50 transition">
            <?= esc(lang('App.backButton')) ?>
        </a>
    </div>
</form>

<script>
let categoryIndex = 1;

function addCategoryRow() {
    const tbody = document.getElementById('category-rows');

    const tr = document.createElement('tr');
    tr.className = 'category-row border-t';

    tr.innerHTML = `
        <td class="px-3 py-2">
            <select name="categories[${categoryIndex}][category]"
                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="General">General</option>
                <option value="EWS">EWS</option>
                <option value="LIG">LIG</option>
                <option value="MIG-A">MIG-A</option>
                <option value="MIG-B">MIG-B</option>
                <option value="HIG">HIG</option>
                <option value="SC">SC</option>
                <option value="ST">ST</option>
            </select>
        </td>
        <td class="px-3 py-2">
            <input type="number" min="1" value="1"
                   name="categories[${categoryIndex}][quantity]"
                   class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </td>
        <td class="px-3 py-2 text-center">
            <button type="button"
                    class="px-2 py-1 rounded-full bg-red-600 text-white text-sm"
                    onclick="removeCategoryRow(this)">
                –
            </button>
        </td>
    `;

    tbody.appendChild(tr);
    categoryIndex++;
}

function removeCategoryRow(button) {
    const row = button.closest('tr');
    const tbody = document.getElementById('category-rows');
    if (tbody.querySelectorAll('.category-row').length > 1) {
        tbody.removeChild(row);
    }
}

// Optional: live summary of quantities vs total
function updateQuantitySummary() {
    const totalInput = document.getElementById('total_quantity');
    const summaryEl = document.getElementById('quantity-summary');
    if (!totalInput || !summaryEl) return;

    const total = parseInt(totalInput.value) || 0;
    let sum = 0;
    document.querySelectorAll('#category-rows .category-row input[type=\"number\"]').forEach(input => {
        sum += parseInt(input.value) || 0;
    });

    if (!total) {
        summaryEl.textContent = '';
        return;
    }

    summaryEl.textContent = `Sum of category quantities: ${sum} / Total: ${total}`;
    summaryEl.style.color = (sum === total) ? '#16A34A' : '#DC2626';
}

// Attach listeners for live summary
document.getElementById('total_quantity')?.addEventListener('input', updateQuantitySummary);
document.getElementById('category-rows')?.addEventListener('input', function(e) {
    if (e.target.matches('input[type=\"number\"]')) {
        updateQuantitySummary();
    }
});

// Initial summary
updateQuantitySummary();
</script>

