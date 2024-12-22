<?php
    namespace App\Enums;

    enum ApiResponse : string{
        case NOT_FOUND = "NOT_FOUND";
        case ERROR = "ERROR";
        case OK = "OK";
    }

?>