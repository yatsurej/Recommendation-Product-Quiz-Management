<?php
session_start();

if (!isset($_SESSION['user_authenticated'])) {
    header('Location: login.php');
    exit();
}

include '../db.php';
$username   = $_SESSION['username'];
$userQuery  = "SELECT * FROM user WHERE username = '$username'";
$userResult = mysqli_query($conn, $userQuery);

while ($userRow = mysqli_fetch_assoc($userResult)) {
    $userID        = $userRow['userID'];
    $userFirstName = $userRow['firstName'];
    $userLastName  = $userRow['lastName'];
}
$pageTitle = "Quiz Management";
include 'header.php';
// include 'navbar.php';
?>
<div class="index-page">
    <div class="background-container"></div>
    <div class="content-container">
        <div class="logocontainer">
            <img src="../assets/images/ADA.png" alt="ADALogo" class="ADALogo">
        </div>
        <div class="container desktop-height d-flex justify-content-center align-items-center vh-100 flex-column">
            <div class="row w-100 index-spacer">
                <div class="col-md-4">
                    <a href="categories.php" class="card text-center index-card py-3">
                        <div class="card-body">
                            <i class="fa-solid fa-tag index-icon" style="color: #636363;"></i>
                            <h5>Products</h5>
                            <button type="button" class="info-btn" aria-label="Information">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip">Manage products.</span>
                            </button>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="question.php" class="card text-center index-card py-3">
                        <div class="card-body">
                            <i class="fa-solid fa-circle-question index-icon" style="color:#636363"></i>
                            <h5>Questions</h5>
                            <button type="button" class="info-btn" aria-label="Information">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip">Manage questions.</span>
                            </button>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="voucher.php" class="card text-center index-card  py-3">
                        <div class="card-body">
                            <i class="fa-solid fa-ticket index-icon" style="color: #636363"></i>
                            <h5>Vouchers</h5>
                            <button type="button" class="info-btn" aria-label="Information">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip">Manage vouchers.</span>
                            </button>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="analytics.php" class="card text-center index-card  py-3">
                        <div class="card-body">
                            <i class="fa-solid fa-chart-column index-icon" style="color: #636363"></i>
                            <h5>Analytics</h5>
                            <button type="button" class="info-btn" aria-label="Information">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip">View reports.</span>
                            </button>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="customize.php" class="card text-center index-card  py-3">
                        <div class="card-body">
                            <i class="fas fa-cogs index-icon" style="color: #636363;"></i>
                            <h5>Customization</h5>
                            <button type="button" class="info-btn" aria-label="Information">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip">Customize the user interface of the quiz.</span>
                            </button>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="logout.php" class="card text-center logout-card  py-3">
                        <div class="card-body color-white">
                            <i class="fa-solid fa-arrow-right-from-bracket index-icon"></i>
                            <h5>Logout </h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const infoBtns = document.querySelectorAll('.info-btn');
        const isMobile = window.matchMedia("only screen and (max-width: 768px)").matches;

        infoBtns.forEach(function(infoBtn) {
            let tooltipShown = false;

            // Toggle tooltip visibility on button click
            infoBtn.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevents the click event from propagating to document
                tooltipShown = !tooltipShown;
                this.classList.toggle('show-tooltip', tooltipShown);
            });

            if (!isMobile) {
                // Show tooltip on hover if not on mobile
                infoBtn.addEventListener('mouseenter', function() {
                    if (!tooltipShown) {
                        this.classList.add('show-tooltip');
                    }
                });

                // Hide tooltip when mouse leaves button if not on mobile
                infoBtn.addEventListener('mouseleave', function() {
                    if (!tooltipShown) {
                        this.classList.remove('show-tooltip');
                    }
                });
            }
        });

        // Close tooltip when page is hidden
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden') {
                infoBtns.forEach(function(infoBtn) {
                    infoBtn.classList.remove('show-tooltip');
                });
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const infoBtns = document.querySelectorAll('.info-btn');
        const isMobile = window.matchMedia("only screen and (max-width: 768px)").matches;

        infoBtns.forEach(function(infoBtn) {
            let tooltipShown = false;

            // Toggle tooltip visibility on button click
            infoBtn.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevents the click event from propagating to document
                tooltipShown = !tooltipShown;
                this.classList.toggle('show-tooltip', tooltipShown);
            });

            if (!isMobile) {
                // Show tooltip on hover if not on mobile
                infoBtn.addEventListener('mouseenter', function() {
                    if (!tooltipShown) {
                        this.classList.add('show-tooltip');
                    }
                });

                // Hide tooltip when mouse leaves button if not on mobile
                infoBtn.addEventListener('mouseleave', function() {
                    if (!tooltipShown) {
                        this.classList.remove('show-tooltip');
                    }
                });
            }
        });

        // Close tooltip when clicking anywhere on the document
        document.addEventListener('click', function() {
            infoBtns.forEach(function(infoBtn) {
                infoBtn.classList.remove('show-tooltip');
            });
        });
    });
</script>