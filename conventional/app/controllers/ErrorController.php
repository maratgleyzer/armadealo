<?php

class ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {
        $this->getResponse()
            ->setHeader('HTTP/1.0 404 Not Found');
    }
}
