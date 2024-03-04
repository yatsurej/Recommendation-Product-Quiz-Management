    <?php
        session_start();

        if (isset($_SESSION['user_authenticated'])) {
            header('Location: index.php');
            exit();
        }

        $pageTitle = "Login Page";
        require '../db.php';
        include '../header.php';
    ?>

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    </head>

    <div class="logocontainer">
            <img src="../assets/images/ada.png" alt="ADALogo" class="ADALogo">
        </div>

        <div class="login">

            <div class="title">
                <h1>Quiz Dashboard</h1>
            </div>

            <div class="login-form">
                <form action="functions.php" method="post" autocomplete="off">
                    <div class="container">
        
                        <input type="text" placeholder="Your Mail" name="username" id="username" required><br>
        
                        <div class="password-container">
                            <input type="password" id="password" placeholder="Your Password" name="password" required>
                            <button type="button" id="togglePassword">
                                <i class="fas fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                        
                
                        <button type="submit" class="loginpage-button" name="login" style="background-color: rgb(97, 119, 196);">Login</button>
                    </div>
                </form>
            </div>


            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const passwordInput = document.getElementById('password');
                    const togglePasswordButton = document.getElementById('togglePassword'); // Update the id here
                    const eyeIcon = document.getElementById('eye-icon');

                    togglePasswordButton.addEventListener('click', () => {
                        const type = passwordInput.type === 'password' ? 'text' : 'password';
                        passwordInput.type = type;

                        eyeIcon.classList.toggle('fa-eye-slash');
                    });
                });
            </script>


        </div>

    <style>
        body {
            background: url('../assets/images/background.png');
            background-size: cover;
            min-height: 100vh;
            text-align: center;
            font-family: 'Abel';
        }

        .ADALogo {
            width: clamp(40px, 40%, 80px);
            height: auto;
            position: absolute;
            top: clamp(1%, 3%, 5%);
            right: clamp(1%, 3%, 5%);
        }

        .title {
            margin-bottom: 20px;
        }

        .title h1 {
            color: white;
            font-size: 32px;
        }

        .login {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .login-form {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 5px;
            width: 50vw;
        }

        .container input {
            width: 100%;
            height: 48px;
            padding: 10px;
            margin-bottom: 30px;
            box-sizing: border-box;
            border-radius: 4px;
            border: none;
        }

        .container input:focus {
            border: none;
        }

        *:focus {
            outline: none;
        }

        .loginpage-button {
            background-color: rgb(97, 119, 196);
            color: #ffffff;
            width: 100%;
            height: 48px;
            border-radius: 4px;
            border-color: none;
            border: none;
        }

        .password-container {
            position: relative;
        }

        .password-container input {
            width: 100%;
            height: 48px;
            padding: 10px;
            margin-bottom: 30px;
            box-sizing: border-box;
            border-radius: 4px;
            border: none;
        }

        .password-container button {
            position: absolute;
            right: 10px;
            top: 33%;
            transform: translateY(-50%);
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        #eye-icon {
            color: #9BA3BE; /* Change this to your desired icon color */
        }

    </style>