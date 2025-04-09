<?php session_start(); ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Css Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Arı Bilgi Login</title>
</head>

<body>

    <section id="login">
        <div class="container">
            <div class="row" style="height: 70vh;">
                <div class="col-4 m-auto">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="../assets/img/logo.webp" class="w-75 mb-3">
                            <form action="" method="post" class="mb-2">
                                <input type="text" name="kadi" placeholder="kullanıcı adınız" class="form-control">
                                <input type="password" name="sifre" placeholder="şifreniz" class="form-control my-2">
                                <input type="submit" value="giriş yap" class="btn btn-success w-100" name="loginBtn">
                            </form>
                            <!-- login module start -->
                            <?php
                            if (isset($_POST['loginBtn'])) {
                                $kadi = $_POST['kadi'];
                                $sifre = $_POST['sifre'];

                                if ($kadi == 'admin' && $sifre == '123') {
                                    echo '<div class="alert alert-success">kullanıcı adı ve şifreniz doğru</div>
                                    <meta http-equiv="refresh" content="2; url=dashboard.php">'; 
                                    $_SESSION['kadi'] = $kadi;
                                } else {
                                    echo '<div class="alert alert-danger">kullanıcı adı ve şifreniz yanlış</div>';
                                }
                            }
                            ?>
                            <!-- login module end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <meta http-equiv="refresh" content="2; url=dashboard.php">

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>