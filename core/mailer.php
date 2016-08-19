<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 10/08/16
 * Time: 22:01
 */

class Mail {

    /**
     * just a wrap upon phpmailer
     * @param $address - array or string
     * @param $theme
     * @param $text
     * @return string
     */
    public static function send($address, $theme, $text) {
        require_once ROOT.'lib/PHPMailer/class.phpmailer.php';
        require_once ROOT.'lib/PHPMailer/class.smtp.php';
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.yandex.ru';
        $mail->Port = 25;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->setFrom(MAIL_USERNAME, MAIL_USERNAME);
        if (!is_array($address)) {
            $mail->addAddress($address);
        } else {
            foreach ($address as $gui)
                $mail->addAddress($gui);
        }
        $mail->isHTML(true);
        $mail->Subject = $theme;
        $mail->Body = $text;
        $mail->AltBody = 'Please, switch to newer browser';
        if(!$mail->send()) {
            return $mail->ErrorInfo;
        } else {
            return 'success';
        }
    }

}