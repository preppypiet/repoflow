<?php

namespace Pieterdev\RepoFlow;


class FluentFilter {

    public $name;
    public $args;

    function __construct($name, $args)
    {
        $this->name = $name;
        $this->args = $args;
    }
}