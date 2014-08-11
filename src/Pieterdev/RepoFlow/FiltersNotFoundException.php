<?php namespace Pieterdev\RepoFlow;

use \Exception;

class FiltersNotFoundException extends Exception {

    function __construct()
    {
        $this->message = "Could not find the filters field. Please add it and specify which fields can be filtered on in the repository.";
    }
}