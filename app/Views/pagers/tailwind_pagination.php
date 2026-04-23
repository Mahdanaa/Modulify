<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation">
    <ul class="flex items-center justify-center space-x-2 mt-8">

        <?php if ($pager->getPreviousPage()) : ?>
            <li>
                <a href="<?= $pager->getPreviousPage() ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition shadow-sm font-bold flex items-center">
                    &laquo; Prev
                </a>
            </li>
        <?php else: ?>
            <li>
                <span class="px-4 py-2 bg-gray-100 border border-gray-200 text-gray-400 rounded-lg shadow-sm font-bold flex items-center cursor-not-allowed">
                    &laquo; Prev
                </span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="px-4 py-2 border rounded-lg transition shadow-sm font-bold flex items-center justify-center min-w-[40px]
                    <?= $link['active'] ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->getNextPage()) : ?>
            <li>
                <a href="<?= $pager->getNextPage() ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition shadow-sm font-bold flex items-center">
                    Next &raquo;
                </a>
            </li>
        <?php else: ?>
            <li>
                <span class="px-4 py-2 bg-gray-100 border border-gray-200 text-gray-400 rounded-lg shadow-sm font-bold flex items-center cursor-not-allowed">
                    Next &raquo;
                </span>
            </li>
        <?php endif ?>

    </ul>
</nav
