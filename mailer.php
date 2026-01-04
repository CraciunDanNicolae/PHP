<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

function trimiteEmailBunVenit($emailDestinatar, $numeUtilizator)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = defined('SMTP_HOST_CFG') ? SMTP_HOST_CFG : 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = defined('SMTP_USER_CFG') ? SMTP_USER_CFG : '';
        $mail->Password = defined('SMTP_PASS_CFG') ? SMTP_PASS_CFG : '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = defined('SMTP_PORT_CFG') ? SMTP_PORT_CFG : 587;

        $mail->setFrom(defined('SMTP_USER_CFG') ? SMTP_USER_CFG : '', 'Echipa Magazin Online');
        $mail->addAddress($emailDestinatar, $numeUtilizator);

        $mail->isHTML(true);
        $mail->Subject = 'Bine ai venit in comunitatea noastra!';

        $bodyContent = "<h1>Salut, " . htmlspecialchars($numeUtilizator) . "!</h1>";
        $bodyContent .= "<p>Îți mulțumim că ți-ai creat un cont pe platforma noastră.</p>";
        $bodyContent .= "<p>Acum poți adăuga produse în coș și poți plasa comenzi.</p>";
        $bodyContent .= "<br><p>Cu drag,<br>Echipa Magazin Online</p>";

        $mail->Body = $bodyContent;
        $mail->AltBody = "Salut, $numeUtilizator! Îți mulțumim că te-ai înregistrat. Acum poți plasa comenzi.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function trimiteEmailNewsletter($emailDestinatar, $numeUtilizator)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = defined('SMTP_HOST_CFG') ? SMTP_HOST_CFG : 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = defined('SMTP_USER_CFG') ? SMTP_USER_CFG : '';
        $mail->Password = defined('SMTP_PASS_CFG') ? SMTP_PASS_CFG : '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = defined('SMTP_PORT_CFG') ? SMTP_PORT_CFG : 587;

        $mail->setFrom(defined('SMTP_USER_CFG') ? SMTP_USER_CFG : '', 'Echipa Magazin Online');
        $mail->addAddress($emailDestinatar, $numeUtilizator);

        $mail->isHTML(true);
        $mail->Subject = 'Abonare Newsletter - Magazin Online';

        $bodyContent = "<h1>Salut, " . htmlspecialchars($numeUtilizator) . "!</h1>";
        $bodyContent .= "<p>Îți mulțumim că te-ai abonat la newsletter-ul nostru!</p>";
        $bodyContent .= "<p>Vei primi cele mai noi oferte pe această adresă de email.</p>";
        $bodyContent .= "<br><p>Cu drag,<br>Echipa Magazin Online</p>";

        $mail->Body = $bodyContent;
        $mail->AltBody = "Salut, $numeUtilizator! Te-ai abonat cu succes la newsletter.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}