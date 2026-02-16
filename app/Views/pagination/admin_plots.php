<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Page navigation">
    <ul class="flex items-center gap-1">
        <?php if ($pager->hasPrevious()): ?>
            <li>
                <a href="<?= $pager->getFirst() ?>" 
                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 transition"
                   aria-label="First">
                    <span aria-hidden="true">««</span>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getPrevious() ?>" 
                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition"
                   aria-label="Previous">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
        <?php else: ?>
            <li>
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-l-md cursor-not-allowed">
                    ««
                </span>
            </li>
            <li>
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed">
                    «
                </span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link): ?>
            <li>
                <?php if ($link['active']): ?>
                    <span class="px-3 py-2 text-sm font-semibold text-white bg-blue-600 border border-blue-600">
                        <?= $link['title'] ?>
                    </span>
                <?php else: ?>
                    <a href="<?= $link['uri'] ?>" 
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition">
                        <?= $link['title'] ?>
                    </a>
                <?php endif ?>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()): ?>
            <li>
                <a href="<?= $pager->getNext() ?>" 
                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition"
                   aria-label="Next">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getLast() ?>" 
                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 transition"
                   aria-label="Last">
                    <span aria-hidden="true">»»</span>
                </a>
            </li>
        <?php else: ?>
            <li>
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed">
                    »
                </span>
            </li>
            <li>
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-r-md cursor-not-allowed">
                    »»
                </span>
            </li>
        <?php endif ?>
    </ul>
</nav>

