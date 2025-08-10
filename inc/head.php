<head>
    <title><?php echo isset($page_title) ? $page_title : "Trang quản trị"; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js"></script>
    <script src="js/ajax_table.js"></script>
    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>
        <input type="hidden" value="<?php echo $_SESSION['status_code']; ?>">
        <script>
            Swal.fire({
                icon: '<?php echo $_SESSION['status_code']; ?>',
                title: '<?php echo $_SESSION['status']; ?>',
                showConfirmButton: true
            });
        </script>
    <?php
        unset($_SESSION['status']);
    }
    ?>
</head>

<?php
function render_sort_th($label, $field, $sort, $order, $search = '', $show_modal = '')
{
    $next_order = ($sort == $field && $order == 'asc') ? 'desc' : 'asc';
    $icon = 'fa fa-sort';
    if ($sort == $field) {
        $icon = 'fa ' . ($order == 'asc' ? 'fa-sort-up' : 'fa-sort-down');
    }
    $params = [];
    if (isset($_GET['id'])) {
        $params[] = 'id=' . urlencode($_GET['id']);
    }
    if ($search !== '') {
        $params[] = 'search=' . urlencode($search);
    }
    $params[] = 'sort=' . $field;
    $params[] = 'order=' . $next_order;
    // Chỉ thêm tab nếu đang có tab trên URL
    if (isset($_GET['tab'])) {
        $params[] = 'tab=' . urlencode($_GET['tab']);
    }
    if ($show_modal) {
        $params[] = 'show_modal=1';
    }
    $query_string = implode('&', $params);
    echo '<th>
        <a href="?' . $query_string . '">' . $label . ' <i class="' . $icon . '"></i></a>
    </th>';
}
?>

<script type="text/javascript">
    // setInterval(function() {
    //     fetch('app/notify_due_tasks.php');
    // }, 12 * 60 * 60 * 1000); // 12 tiếng

    function notifyDueTasks() {
        fetch('app/notify_due_tasks.php');
    }

    // Chạy ngay khi trang load
    notifyDueTasks();

    // Lặp lại mỗi 12 tiếng
    setInterval(notifyDueTasks, 12 * 60 * 60 * 1000);
</script>