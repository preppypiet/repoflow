<?php

namespace Pieterdev\RepoFlow;

trait FluentRepositoryTrait {
    /**
     * @var array
     */
    public $activeFilters = [];

    /**
     * @return null
     */
    function availableFilters()
    {
        return $this->getFilters();
    }

    /**
     * @return mixed
     * @throws FiltersNotFoundException
     */
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

    /**
     * Gets the Eloquent model
     * @throws ModelPropertyNotFoundException
     * @return mixed
     */
    protected function getModel()
    {
        if(property_exists($this, 'model')) {
            return $this->model;
        } else {
            throw new ModelPropertyNotFoundException('Could not find the $model property. Please create it in order to use the fluent query api.');
        }
    }

    /**
     * Executes the query with the stored active filters.
     * @return mixed
     */
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
        if(strpos($name, 'where') !== 0) return;

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
