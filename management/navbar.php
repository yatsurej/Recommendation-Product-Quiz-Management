<?php
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION)) {
    session_start();
}

$allowedPages = array('product.php', 'question.php', 'analytics.php', 'voucher.php');
?>
<style>
    .navbar {
        background-color: #000;
        display: flex;
        flex-direction: column;
        text-align: center;
        height: 100%;
    }

    .navbar-brand {
        font-size: 2rem;
        font-weight: bold;
    }

    .navb {
        color: white !important;
    }

    /* Adjustments for the sidebar */
    .sidebar {
        height: 100%;
        width: 250px;
        /* Adjust width as needed */
        position: fixed;
        top: 0;
        left: 0;
        background-color: #111;
        /* Dark background color */
    }

    .sidebar .navbar-nav {
        flex-direction: column;
    }

    .sidebar .nav-item {
        margin-bottom: 10px;
    }

    .sidebar .nav-link {
        padding: 10px;
    }

    .navbar-expand-lg .navbar-collapse {
        display: -ms-flexbox !important;
        display: flex !important;
        -ms-flex-preferred-size: auto;
        flex-basis: auto;
        flex-direction: column;
    }

    .content {
        margin-left: 250px;
        /* Adjust according to sidebar width */
        padding: 20px;
    }
</style>

<div class="sidebar">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand text-white" href="index.php">Quiz<br>Management</a>
        <?php if (isset($_SESSION['user_authenticated'])) : ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarNav" aria-controls="sidebarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="sidebarNav">
                <ul class="navbar-nav flex-column">
                    <?php if ($_SESSION['user_role'] == 'admin') : ?>
                        <li class="nav-item">
                            <a class="navb nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'product.php' || basename($_SERVER['PHP_SELF']) == 'categories.php') ? 'active' : 'inactive'; ?>" href="product.php">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="navb nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'question.php' || basename($_SERVER['PHP_SELF']) == 'conditional-question.php') ? 'active' : 'inactive'; ?>" href="question.php">Questions</a>
                        </li>
                        <li class="nav-item">
                            <a class="navb nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'voucher.php' || basename($_SERVER['PHP_SELF']) == 'conditional-voucher.php') ? 'active' : 'inactive'; ?>" href="voucher.php">Vouchers</a>
                        </li>
                        <li class="nav-item">
                            <a class="navb nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'analytics.php') ? 'active' : 'inactive'; ?>" href="analytics.php">Analytics</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="navb nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </nav>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>