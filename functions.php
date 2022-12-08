<?php
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/carbon.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;

class Functions
{
    public static $mail = null;
    public static $unsetTypes = [
        '' => ['address', 'postcode', 'to', 'from', 'isReturn', 'milage'],
        'taxi' => ['address', 'postcode'],
        'home' => ['to', 'from', 'isReturn', 'milage'],
    ];

    public static function initPHPMailer()
    {
        self::$mail = new PHPMailer();
        // self::$mail->SMTPDebug = 1;
        self::$mail->isSMTP();
        self::$mail->isHTML(true);
        self::$mail->Host = 'smtp.gmail.com';
        self::$mail->SMTPAuth = true;
        self::$mail->Username = 'samouieservices@gmail.com';
        self::$mail->Password = '$^r8NA%tCkOLnQomG^';
        self::$mail->SMTPSecure = 'tls';
        self::$mail->Port = 587;
    }
    private static function createJSON($fileName)
    {
        $file = fopen($fileName, "a+");
        if ($file) {
            fclose($file);
            return true;
        }
    }
    public static function checkIfDataFileExists($fileName)
    {
        if (!file_exists($fileName)) {
            self::createJSON($fileName);
        }
    }
    public static function addToJson(&$post, $fileName)
    {
        self::formatData($post);
        $json = [];

        self::checkIfDataFileExists($fileName);

        $data = file_get_contents($fileName);
        if ($data === "") {
            $post['id'] = 1;
            $json['data'] = [$post];
        } else {
            $data = json_decode($data, true);
            $post['id'] = end($data['data'])['id'] + 1;
            array_push($data['data'], $post);
            $json = $data;
        }

        $json = json_encode($json, true);
        return file_put_contents($fileName, $json) !== false ? $post['id'] : false;
    }
    public static function deleteFromJson(&$jsonData, $id, $fileName)
    {
        $jsonData = json_decode($jsonData, true);
        unset($jsonData['data'][self::searchMultiArray($jsonData['data'], "id", $id)]);
        $jsonData['data'] = array_values($jsonData['data']);
        file_put_contents($fileName, "");
        file_put_contents($fileName, json_encode($jsonData));
    }
    public static function editLine($lineId, $fileName, $field, $value)
    {
        $data = json_decode(file_get_contents($fileName), true);
        $data['data'][self::searchMultiArray($data['data'], "id", $lineId)][$field] = $value;
        file_put_contents($fileName, json_encode($data));
    }
    private static function searchMultiArray($array, $field, $value)
    {
        foreach ($array as $key => $data) {
            if ($data[$field] == $value)
                return $key;
        }
        return false;
    }
    public static function formatData(&$data)
    {
        $data['timestamp'] = Carbon::now()->setTimezone("Europe/London")->format("d/m/Y H:i:sa");
        $data['invoiceDate'] = Carbon::parse($data['invoiceDate'])->setTimezone("Europe/London")->format("d/m/Y");
        self::unsetData($data);
    }
    private static function unsetData(&$data)
    {
        $unsets = self::$unsetTypes[$data['invoiceType']] + ['invoiceTypes','edit','function','response','name','email','telephone','emails','pdfURI'];
        foreach ($unsets as $type) unset($data[$type]);
    }
}
