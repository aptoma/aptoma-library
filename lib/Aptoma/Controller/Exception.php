<?php
class Aptoma_Controller_Exception extends Exception
{
    private $userMessage;

    public function __construct($userMessage, $code = 0, Exception $previous = null)
    {
        $this->userMessage = $userMessage;

        if (!is_null($previous)) {
            parent::__construct($previous->getMessage(), $code, $previous);
        } else {
            parent::__construct($userMessage, $code);
        }
    }

    public function getUserMessage()
    {
        return $this->userMessage;
    }
}
