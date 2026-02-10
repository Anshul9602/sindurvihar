<div class="container mx-auto px-4 py-8 max-w-5xl">
    <h1 class="text-3xl font-bold mb-6" style="color: #0F1F3F;">
        Applications List
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <table class="w-full text-left text-sm">
            <thead>
            <tr class="border-b">
                <th class="py-2">ID</th>
                <th class="py-2">Name</th>
                <th class="py-2">Mobile</th>
                <th class="py-2">Category</th>
                <th class="py-2">Status</th>
                <th class="py-2">Submitted</th>
                <th class="py-2">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($applications)): ?>
                <tr>
                    <td colspan="7" class="py-4 text-center" style="color: #4B5563;">
                        No applications found.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($applications as $app): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2"><?= esc($app['id']) ?></td>
                    <td class="py-2"><?= esc($app['full_name']) ?></td>
                    <td class="py-2"><?= esc($app['mobile'] ?? 'N/A') ?></td>
                    <td class="py-2">
                        <span class="px-2 py-1 rounded text-xs uppercase">
                            <?= esc($app['income_category']) ?>
                        </span>
                    </td>
                    <td class="py-2">
                        <span class="px-2 py-1 rounded text-xs 
                            <?php 
                            $status = $app['status'] ?? 'draft';
                            if ($status === 'submitted') echo 'bg-yellow-100 text-yellow-800';
                            elseif ($status === 'verified') echo 'bg-green-100 text-green-800';
                            elseif ($status === 'rejected') echo 'bg-red-100 text-red-800';
                            else echo 'bg-gray-100 text-gray-800';
                            ?>">
                            <?= esc(ucfirst(str_replace('_', ' ', $status))) ?>
                        </span>
                    </td>
                    <td class="py-2 text-xs" style="color: #4B5563;">
                        <?= date('d M Y', strtotime($app['created_at'])) ?>
                    </td>
                    <td class="py-2">
                        <a href="/admin/applications/<?= esc($app['id']) ?>" 
                           class="text-blue-600 hover:underline">
                            View
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


