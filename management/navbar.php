<?php
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION)) {
    session_start();
}

<<<<<<< Updated upstream
$allowedPages = array('product.php', 'question.php', 'analytics.php');
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full rotate sm:translate-x-0" aria-label="Sidebar" style="background-image: url('../assets/images/sidebar.png');">
    <div class="h-full px-3 pb-4 mt-10 overflow-y-auto">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="index.php" class="flex items-center p-2 text-bgrey rounded-lg dark:text-white hover:bg-bgrey hover:text-white dark:hover:bg-gray-700 group">
                    <span class="flex-1 ms-3 whitespace-nowrap">Dashboard</span>
                </a>
            </li>
            <?php foreach ($allowedPages as $page) : ?>
            <li>
                <a href="<?php echo $page; ?>" class="flex items-center p-2 text-bgrey rounded-lg dark:text-white hover:bg-bgrey hover:text-white dark:hover:bg-gray-700 group <?php echo ($currentPage == $page) ? 'active' : 'inactive'; ?>">
                    <span class="ms-3"><?php echo ucfirst(str_replace('.php', '', $page)); ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</aside>


<nav class="fixed top-0 z-50 w-full bg-darkpurple dark:bg-gray-800">
    <div class="px-3 py-3 lg:px-5 lg:p  l-3">
        <div class="flex items-center ">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="#" class="flex items-center justify-center ms-16 md:me-24">
                    <img src="../assets/images/ada.png" class="h-12 md:h-16 me-3" alt="ADA Logo" />
                </a>
            </div>
            <div class="flex justify-start items-center text-white">
                <div class="flex items-center ms-3">
                    <h1 class="font-semibold text-xl">
                        <?php
                        if (isset($pageTitle)) {
                            // $pageTitle is defined
                            if ($pageTitle === "Quiz Management") {
                                echo "Dashboard";
                            } else if ($pageTitle === "Question Page") {
                                echo "Questions Page";
                            } else {
                                echo $pageTitle;
                            }
                        } else {
                            // $pageTitle is not defined, set it to blank
                            $pageTitle = "";
                            echo $pageTitle;
                        }
                        ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</nav>
=======
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

>>>>>>> Stashed changes

<script src="https://cdn.tailwindcss.com/"></script>
<script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                clifford: '#da373d',
                darkpurple: '#012044',
                bgrey: '#8C93B9'
              }
            }
          }
        }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../assets/styles.css"></script>