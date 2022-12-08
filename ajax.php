<?php
require 'functions.php';

if (isset($_POST['function'])) {
    $_POST['function']();
}

function email()
{
    try {
        $fileName = $_POST['fileName'] . '.pdf';

        //Recipients
        Functions::initPHPMailer();
        Functions::$mail->setFrom($_POST['email'], $_POST['name']);
        Functions::$mail->addAddress($_POST['customerEmail'], $_POST['customer']);

        //Attachments
        Functions::$mail->addStringAttachment(base64_decode($_POST['pdfURI'], true), $fileName, 'base64', 'application/pdf');

        //Content
        Functions::$mail->Subject = $fileName;
        Functions::$mail->Body    = '<p><b>Hi ' . $_POST['customer'] . ',<br />' . $_POST['emailBody'] . '</b></p>';

        //Response
        echo $_POST['emailSent'] = Functions::$mail->send();
        Functions::addToJson($_POST, 'data.json');
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: " . Functions::$mail->ErrorInfo;
    }
}

function add()
{
    echo Functions::addToJson($_POST, 'data.json');
}

function updateData()
{
    $fileName = 'data.json';
    Functions::checkIfDataFileExists($fileName);
    $data = json_decode(file_get_contents($fileName), true);
    $data['data'] = $_POST['data'];

    echo file_put_contents($fileName, json_encode($data, true)) !== false;
}
