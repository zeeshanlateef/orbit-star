<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require '../config/config.php';
require 'functions/authentication.php';

$db = new dbClass();
$auth = new AdminManager();
$auth->checkSession();

if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    header('Content-Type: application/json');
    $ids = $_POST['ids'] ?? [];
    if (!empty($ids) && is_array($ids)) {
        $clean_ids = array_filter($ids, 'is_numeric');
        if (!empty($clean_ids)) {
            $placeholders = implode(',', array_fill(0, count($clean_ids), '?'));
            $deleteQuery = "DELETE FROM contact_us WHERE id IN ($placeholders)";
            if ($db->executeStatement($deleteQuery, array_values($clean_ids))) {
                echo json_encode(['success' => true, 'message' => 'Record(s) deleted successfully!']);
                exit;
            }
        }
    }
    echo json_encode(['success' => false, 'message' => 'Failed to delete record(s).']);
    exit;
}

$allInquiries = [];
try {
    $allInquiries = $db->getAllData("SELECT * FROM contact_us ORDER BY id DESC");
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <title>Orbit Star Services | Contact Inquiries</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/favicon.webp" type="image/webp">
    <link href="libs/simple-datatables/style.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="libs/toastr/css/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- Orbit Star Theme Override -->
    <link href="css/orbitstar-theme.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include 'include/header.php'; ?>
<?php include 'include/sidebar.php'; ?>
<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col text-end">
                    <button id="deleteSelectedBtn" class="btn btn-danger btn-sm">Delete Selected</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h2 class="card-title">Contact Inquiries</h2></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle" id="datatable">
                                    <thead class="table-light">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th class="ps-3">S.No</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($allInquiries)): ?>
                                        <?php foreach ($allInquiries as $index => $item): ?>
                                        <tr id="row-<?php echo $item['id']; ?>">
                                            <td><input type="checkbox" class="row-checkbox" value="<?php echo $item['id']; ?>"></td>
                                            <td class="ps-3"><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td><?php echo htmlspecialchars($item['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($item['email']); ?></td>
                                            <td><?php echo htmlspecialchars($item['subject'] ?? ''); ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($item['message'])); ?></td>
                                            <td><?php echo htmlspecialchars($item['date'] ?? ''); ?></td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-soft-danger delete-btn" data-id="<?php echo $item['id']; ?>">
                                                    <i class="las la-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'include/footer.php'; ?>
    </div>
</div>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Confirm Deletion</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Are you sure you want to delete the selected record(s)? This action cannot be undone.</div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
        </div>
    </div></div>
</div>
<script src="js/jquery-3.6.0.js"></script>
<script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="libs/simple-datatables/umd/simple-datatables.js"></script>
<script src="libs/toastr/js/toastr.min.js"></script>
<script src="js/app.js"></script>
<script>
$(document).ready(function() {
    const tableElement = document.getElementById('datatable');
    if (tableElement && tableElement.rows.length > 1) {
        const datatable = new simpleDatatables.DataTable(tableElement, { columns: [{ select: 0, sortable: false }] });
        const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        const selectAllCheckbox = $('#selectAll');
        const updateSelectAllState = () => {
            const visibleCheckboxes = $(tableElement).find('tbody .row-checkbox');
            const checkedVisibleCheckboxes = visibleCheckboxes.filter(':checked').length;
            selectAllCheckbox.prop('checked', visibleCheckboxes.length > 0 && visibleCheckboxes.length === checkedVisibleCheckboxes);
        };
        selectAllCheckbox.on('change', function() { $(tableElement).find('tbody .row-checkbox').prop('checked', $(this).is(':checked')); });
        $(tableElement).on('change', '.row-checkbox', updateSelectAllState);
        $(document).on('click', '.delete-btn', function(event) {
            event.preventDefault(); event.stopImmediatePropagation();
            $('#confirmDeleteBtn').data('id', [$(this).data('id')]); deleteModal.show();
        });
        $('#deleteSelectedBtn').on('click', function(event) {
            event.preventDefault(); event.stopImmediatePropagation();
            const selectedIds = $(tableElement).find('.row-checkbox:checked').map(function() { return $(this).val(); }).get();
            if (!selectedIds.length) { toastr.warning('Select at least one record.'); return; }
            $('#confirmDeleteBtn').data('id', selectedIds); deleteModal.show();
        });
        $('#confirmDeleteBtn').on('click', function() {
            $.ajax({
                url: window.location.href, type: 'POST', data: { action: 'delete', ids: $(this).data('id') }, dataType: 'json',
                success: function(res) {
                    deleteModal.hide();
                    if (res.success) { toastr.success(res.message); setTimeout(() => location.reload(), 1000); } else { toastr.error(res.message); }
                },
                error: function() { deleteModal.hide(); toastr.error('Error deleting record(s).'); }
            });
        });
        updateSelectAllState();
    } else {
        $('#deleteSelectedBtn').hide();
    }
});
</script>
</body></html>
