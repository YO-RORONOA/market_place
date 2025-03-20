// views/products/_pagination.php
<?php if ($totalPages > 1): ?>
<div class="mt-8 flex justify-center">
    <div class="flex space-x-1 pagination-container">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?><?= isset($category) ? '&category='.$category['id'] : '' ?><?= isset($search) ? '&search='.urlencode($search) : '' ?>" 
               class="pagination-link px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
               data-page="<?= $currentPage - 1 ?>">
                Previous
            </a>
        <?php endif; ?>
        
        <?php 
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $start + 4);
        $start = max(1, $end - 4);
        
        for ($i = $start; $i <= $end; $i++): 
        ?>
            <a href="?page=<?= $i ?><?= isset($category) ? '&category='.$category['id'] : '' ?><?= isset($search) ? '&search='.urlencode($search) : '' ?>" 
               class="pagination-link px-4 py-2 <?= $i === $currentPage ? 'bg-accent-teal text-white' : 'bg-white text-gray-700 hover:bg-gray-50' ?> border border-gray-300 rounded-md"
               data-page="<?= $i ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?= $currentPage + 1 ?><?= isset($category) ? '&category='.$category['id'] : '' ?><?= isset($search) ? '&search='.urlencode($search) : '' ?>" 
               class="pagination-link px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
               data-page="<?= $currentPage + 1 ?>">
                Next
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>