<?php
        function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
        {
            $levels = array(
                E_WARNING => "Warning",
                E_NOTICE => "Notice",
                E_USER_ERROR => "Error",
                E_USER_WARNING => "Warning",
                E_USER_NOTICE => "Notice",
                E_STRICT => "Strict warning",
                E_RECOVERABLE_ERROR => "Recoverable error",
                E_DEPRECATED => "Deprecated feature",
                E_USER_DEPRECATED => "Deprecated feature"
            );
            $message = date("Y-m-d H:i:s - ");
            $message .= $levels[$errno] . ": $errstr in $errfile, line $errline\n\n";
            $message .= "--->:\n";
            $message .= print_r($errcontext, true) . "\n\n";
            error_log($message, 3, "Errors.log");
            die("There was an error!" + $message);
        }
?>