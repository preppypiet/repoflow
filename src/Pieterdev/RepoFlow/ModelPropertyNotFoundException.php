<?php namespace Pieterdev\RepoFlow;


class ModelPropertyNotFoundException extends \Exception {

    function __construct()
    {
        $this->message = "Could not find the model property, please specify it in the repository class.";
    }
}