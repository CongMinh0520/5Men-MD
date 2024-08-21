<?php
ob_start();
$sql_danhmuc = "SELECT * FROM `tbl_danhmuc` ORDER BY `tbl_danhmuc`.`thutu` ASC";
$query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
// Hàm đệ quy để xây dựng menu phân cấp
function buildMenu($parent_id, $mysqli) {
    $sql_danhmuc = "SELECT * FROM `tbl_danhmuc` WHERE `parent_id` " . ($parent_id === null ? "IS NULL" : "= $parent_id") . " ORDER BY `thutu` ASC";
    $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);

    if (mysqli_num_rows($query_danhmuc) > 0) {
        echo '<ul class="sub-menu">';
        while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
            echo '<li><a href="index.php?quanly=danhmuc&id=' . $row_danhmuc['id_danhmuc'] . '">' . $row_danhmuc['tendanhmuc'] . '</a>';
            buildMenu($row_danhmuc['id_danhmuc'], $mysqli); // Đệ quy để lấy các danh mục con
            echo '</li>';
        }
        echo '</ul>';
    }
}
?>
<div class="sidebar-ul" style="background-color: #feefd1; border-bottom: 1px solid #ccc;">
    <ul id="menu" style="display: flex; text-decoration: none; list-style: none; justify-content: space-between; padding: 5px 200px; font-size: 20px; margin: 0; background-color: #feefd1;">
        <li><a href="index.php">Trang chủ</a></li>
        <li><a href="index.php?quanly=shopall">Tất cả sản phẩm</a></li>
        <li><a href="index.php?quanly=sale"><span>Sale</span></a></li>

        <?php
        while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
            if ($row_danhmuc['parent_id'] === null) {
                echo '<li class="menu-item" id="menu-' . $row_danhmuc['id_danhmuc'] . '"><a href="index.php?quanly=danhmuc&id=' . $row_danhmuc['id_danhmuc'] . '">' . $row_danhmuc['tendanhmuc'] . '</a>';
                echo '<div class="sub-menu-container">';
                buildMenu($row_danhmuc['id_danhmuc'], $mysqli);
                echo '</div></li>';
            }
        }
        ?>

        <li><a href="index.php?quanly=donhang"><i class="bi bi-bag"></i> Đơn hàng</a></li>
        <?php
        if (isset($_SESSION['role_id']) && $_SESSION['role_id'] != 4) {
            echo '<li><a href="admincp/index.php"><i class="bi bi-person-circle"></i> ADMIN</a></li>';
        }
        ?>
    </ul>
</div>