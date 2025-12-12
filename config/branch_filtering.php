<?php
// branch_filtering.php
// Reusable branch dropdown for super_admin

require_once  'db.php';
require_once  'helpers.php';

// Only display dropdown if user is super_admin
if (currentRole() === 'super_admin'):
?>
    <div>
        <label for="branchFilter" class="form-label me-2">Filter by Branch:</label>
        <select id="branchFilter" class="form-select d-inline-block w-auto">
            <option value="all">All Branches</option>
            <?php
            $query = "SELECT id, branch_name FROM branches ORDER BY branch_name ASC";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $branches = $stmt->get_result();

            while ($branch = $branches->fetch_assoc()):
            ?>
                <option value="<?= $branch['id']; ?>">
                    <?= htmlspecialchars($branch['branch_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
<?php endif; ?>
