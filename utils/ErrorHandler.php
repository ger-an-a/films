<?php
class ErrorHandler
{
    function setError($code, $message = '', $details = '') // возвращает отсортированные фильмы из базы по N штук соответственно странице
    {
        if (!$message) {
            $message = isset($GLOBALS["ERROR_MESSAGE$code"]) ? $GLOBALS["ERROR_MESSAGE$code"] : $GLOBALS["ERROR_MESSAGE"];
        }

        $result = !$details ? array("message" => $message) : array("message" => $message, "details" => $details);


        http_response_code($code);
        echo json_encode($result);
        exit;
    }
}
?>