<?php
include $_SERVER['DOCUMENT_ROOT'] . "/../../config.php";
const TOKEN = "5382692469:AAE3qWvlFbKpLrhbt0M4u1GOILoyXJB2_fE";
const BASE_URL = "https://api.telegram.org/bot" . TOKEN . "/";
const CHAT_ID = "343005487";

// $git_cmd_res = shell_exec($git_cmd_e);
// https://api.telegram.org/bot5382692469:AAE3qWvlFbKpLrhbt0M4u1GOILoyXJB2_fE/setWebhook?url=https://ahesom.com/git_hook.php

$update = json_decode(file_get_contents('php://input'));

$t = '';
if (isset($update->message) && isset($update->message->text)) {
    $t = $update->message->text;
    switch ($t) {
        case 'status':
            $git_cmd_e = "cd /var/www/storage/ && git status";
            break;
        case 'pull':
            $git_cmd_e = "cd /var/www/storage/ && git pull 2>$1";
            break;
        case 'reset':
            $git_cmd_e = "cd /var/www/storage/ && git resetorigin";
            break;
        default:
            $git_cmd_e = "echo 'Unknown Command'";
            break;
    }

    sendTgMessage("Executing command $t . Result: " . shell_exec($git_cmd_e), CHAT_ID);
} else {
    $git_cmd_e = "cd /var/www/storage/ && git pull";
    sendTgMessage("Executing Hook. Result: " . shell_exec($git_cmd_e), CHAT_ID);
}

$url_ahesom = "https://ahesom.com/git_hook_slave.php?" . http_build_query([
    'command' =>  $t
]);

$url_alewonder = "https://alewonder.com/git_hook_slave.php?" . http_build_query([
    'command' =>  $t
]);

$url_amucor = "https://amucor.com/git_hook_slave.php?" . http_build_query([
    'command' =>  $t
]);

file_get_contents($url_ahesom);

file_get_contents($url_alewonder);

file_get_contents($url_amucor);

function sendTgMessage($message, $chat)
{
    $url = BASE_URL . "sendMessage?";
    if (!empty($message)) {
        $url .= http_build_query([
            'chat_id' => $chat,
            'text' => "[CONTENT] " . mb_strimwidth($message,0,400,"..."),
        ]);
    } else {
        $url .= http_build_query([
            'chat_id' => $chat,
            'text' => "[CONTENT] something went really wrong..."
        ]);
    }

    file_get_contents($url);
}
