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
    }

    .navbar-brand {
        font-size: 2rem;
        font-weight: bold;
    }

    .navb {
        color: white !important;
        transition: background-color 0.3s, color 0.3s;
        /* Adding transition for smooth hover effect */
    }
<<<<<<< Updated upstream
</style>

<nav class="navbar navbar-expand-lg navbar-dark">
<<<<<<< Updated upstream
    <div class="container-fluid w-75 d-flex justify-content-between align-items-center">
        <a class="navbar-brand text-white" href="index.php">Quiz Management</a>
        <?php if (isset($_SESSION['user_authenticated'])): ?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav nav-underline ms-auto text-end">
                <?php if ($_SESSION['user_role'] == 'admin'): ?>
=======

    .sidebar {
        height: 100%;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #111;
    }

    .sidebar .navbar-nav {
        flex-direction: column;
        width: 220px;
    }

    .sidebar .nav-item {
        margin-bottom: 10px;
    }

    .navb:hover {
        color: #fff;
        background-color: #000;
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
        padding: 20px;
    }

    .navb.nav-link.active {
        background-color: #fff;
        color: #000 !important;
        border-radius: 5px;
        font-weight: bold;
    }

    .navbar-dark .navbar-nav .nav-link:focus,
    .navbar-dark .navbar-nav .nav-link:hover {
        background-color: #f9fafb;
        color: #333 !important;
        border-radius: 5px;
    }

    h1 {
        font-size: 30px;
    }
</style>
<div class="sidebar">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <img src="../assets/images/PG.png" alt="P&G Logo" style="width: 150px; height: auto; margin: 60px 10px 10px 10px;  ">
        <div class="collapse navbar-collapse" id="sidebarNav">
            <ul class="navbar-nav flex-column">
                <?php if (isset($_SESSION['user_authenticated'])) : ?>
=======
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <img src="../assets/images/PG.png" alt="P&G Logo" style="height: 30px; margin: 10px;">
        <?php if (isset($_SESSION['user_authenticated'])) : ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav nav-underline ms-auto text-end">
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
>>>>>>> Stashed changes
                    <li class="nav-item">
                        <a class="navb nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'product.php' || basename($_SERVER['PHP_SELF']) == 'categories.php') ? 'active' : 'inactive'; ?>" href="product.php">Products</a>                   
                    </li>
<<<<<<< Updated upstream
                    <li class="nav-item">
                        <a class="navb nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'question.php' || basename($_SERVER['PHP_SELF']) == 'conditional-question.php') ? 'active' : 'inactive'; ?>" href="question.php">Questions</a>            
                    </li>
                    <li class="nav-item">
                        <a class="navb nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'voucher.php' || basename($_SERVER['PHP_SELF']) == 'conditional-voucher.php') ? 'active' : 'inactive'; ?>" href="voucher.php">Vouchers</a>            
                    </li>
                    <li class="nav-item">
                        <a class="navb nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'analytics.php') ? 'active' : 'inactive'; ?>" href="analytics.php">Analytics</a>            
=======
                    <li class="nav-item">
                        <a class="navb nav-link" href="logout.php">Logout</a>
>>>>>>> Stashed changes
                    </li>
                <?php endif; ?>
                </ul>
            </div>
    </div>
</nav>
=======
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>

>>>>>>> Stashed changes


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>