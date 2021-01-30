<?php
require_once("../vendor/autoload.php");
use CoinbaseCommerce\Webhook;

$secret = '2be9c6a8-f6ef-4e76-8cb4-7170e3a0ff2f';
$headerName = 'X-Cc-Webhook-Signature';
$headers = getallheaders();
$signraturHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;
$payload = trim(file_get_contents('php://input'));
$date = new DateTime();
$date = $date->format("y:m:d h:i:s")." - ";

try {
    $event = Webhook::buildEvent($payload, $signraturHeader, $secret);
    http_response_code(200);

    file_put_contents('coinbase.txt',$date.sprintf('Successfully verified event with id %s and type %s.', $event->id, $event->type).PHP_EOL, FILE_APPEND);

    $payment_id = $event->data->metadata['payment_id'];
    $event_type = $event->type;
    $event_type = explode(':', $event_type)[1];
    if (!empty($payment_id)) {
        include("../config.php");
        $sql = "UPDATE `payments` SET `status` = '".$event_type."' WHERE `id` = ".$payment_id;

        if (mysqli_query($link, $sql) === TRUE && $event_type == "confirmed") {
            $sql = "SELECT `user_id`, `subscription`, `time` FROM `payments` WHERE `id` = ".$payment_id;
            $result = mysqli_query($link, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $subscription = $row['subscription'];
                $time = $row['time']*60*60*24*31;
                $user_id = $row['user_id'];
                $max_bots = 100000;
                if ($subscription == "Standard")
                    $max_bots = 20;
                elseif ($subscription == "Enterprise")
                    $max_bots = 100;

                $sql = "UPDATE `users` SET `subscription_name` = '".$subscription."', `subscription_end_timestamp` = `subscription_end_timestamp`+".$time.", subscription_end_is_notified = 0, `max_bots` = '".$max_bots."' WHERE `id` = ".$user_id;
                if (mysqli_query($link, $sql) === TRUE)
                    file_put_contents('coinbase.txt',$date.'Successfully set subscription: '.$subscription.' and added: '.$row['time'].' months to user ID: '.$user_id.PHP_EOL, FILE_APPEND);
                else file_put_contents('coinbase.txt',$date.'Could not update the subscription to user ID:'.$user_id.PHP_EOL, FILE_APPEND);
            } else file_put_contents('coinbase.txt',$date.'Could not find payment ID'.PHP_EOL, FILE_APPEND);
        } else file_put_contents('coinbase.txt',$date.'Could not set payment status'.PHP_EOL, FILE_APPEND);
        mysqli_close($link);
    } else file_put_contents('coinbase.txt',$date.'[payment_id] is empty'.PHP_EOL, FILE_APPEND);

    echo sprintf("Successfully updated your payment info.");
} catch (\Exception $exception) {
    http_response_code(400);
    file_put_contents('coinbase.txt',$date.'Error occured. ' . $exception->getMessage().PHP_EOL, FILE_APPEND);
}
?>
