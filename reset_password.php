<?php if ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Recuperar contraseña</title>
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
        <style>
            body {
                background: #888;
                height: 100%;
            }
            .main{
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 6rem;
            }
        </style>
    </head>
    <body>
    <main class="main">
        <div class="card">
            <form method="post" class="card-body">
                <h5 class="card-title">Ingresa tu correo para recuperar tu contraseña</h5>
                <label>
                    Correo Electronico:
                    <input name="email" class="form-control" placeholder="Email" type="email"/>
                </label>
                <br/>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="login.php">Volver al login</a>
                </div>
            </form>
        </div>
    </main>
    </body>
    </html>
<?php endif; ?>


<?php
require_once 'assets/PHPMailer/src/PHPMailer.php';
require_once 'assets/PHPMailer/src/Exception.php';
require_once 'assets/PHPMailer/src/SMTP.php';
require_once 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $query = $conn->query("SELECT * from users where email = '{$email}'");
    $user = $query->fetch_array();
    if ($user) {
        sendMail($email, $user);
        header('Location: /login.php?msg=Puede ir a su correo electronico para recuperar la contraseña');
    } else {
        echo 'Usuario no encontrado';
    }
}

function sendMail(string $toAddr, array $user)
{
    $mail = new PHPMailer(true);
    $smtpAddr = 'richardjimenez176@gmail.com';
    $smtpPassword = getenv('SMTP_PASSWORD');
    try {
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;

        $mail->Username = $smtpAddr;

        $mail->Password = $smtpPassword;
        $mail->setFrom($smtpAddr, 'Super sistema de nomina');

        $mail->addAddress($toAddr, $user['name']);
        $mail->Subject = 'Solicitud de recuperacion de contraseña';

        $mail->msgHTML("Su contraseña es: {$user['password']}");

        $mail->send();

    } catch (Exception $e) {
        echo 'Ha ocurrido un error al momento de enviar el correo';
    }
}