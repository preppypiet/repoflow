<?php

namespace Pieterdev\RepoFlow;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait FluentRepositoryTrait
{
    public $activeFilters = [];

    function availableFilters()
    {
        return $this->getFilters();
    }

    protected function getFilters()
    {
        $foundFilters = null;

        if(property_exists($this, 'filters')) {
            $foundFilters = static::$filters;
        } else {
            throw new FiltersNotFoundException();
        }

        return $foundFilters;
    }

    protected function getModel()
    {
        if(property_exists($this, 'model')) {
            return $this->model;
        } else {
            throw new ModelNotFoundException('Could not find the $model property. Please create it in order to use the fluent query api.');
        }
    }

    public function all()
    {
        $builder = $this->getModel()->newQuery();

        foreach($this->activeFilters as $filter)
        {
            $argsToPass = $filter->args;
            array_unshift($argsToPass, $filter->name);
            call_user_func_array([$builder, 'where'], $argsToPass);
        }

        $this->activeFilters = [];

        return $builder->get();
    }

    function __call($name, $args)
    {
        if(!starts_with($name, 'where')) return;

        // see if our filters contains name
        // for example: 'whereAge' -> 'age'
        $propName = lcfirst(str_replace('where', '', $name));

        if(in_array($propName, $this->getFilters())) {

            // it does, so add the current filter
            $this->activeFilters[] = new FluentFilter($propName, $args);

            // return this to facilitate chaining
            return $this;
        }
    }
}
