<?php

namespace spec\Pieterdev\RepoFlow;

use PhpSpec\ObjectBehavior;
use Pieterdev\RepoFlow\FluentRepositoryTrait;
use Prophecy\Argument;

class FluentRepositoryTraitSpec extends ObjectBehavior {
    protected $mockModel;

    function let(StubEloquentModel $mockModel)
    {
        $this->mockModel = $mockModel;
        $this->beAnInstanceOf('spec\Pieterdev\RepoFlow\FluentTraitTest');
        $this->beConstructedWith($mockModel);

    }

    function it_should_have_an_available_filters_list()
    {
        $this->availableFilters()->shouldReturn(['name', 'age']);
    }

    function it_should_allow_dynamic_filter_methods_based_on_supplied_filters(StubQueryBuilder $builder)
    {
        $this->whereName('Pieter');

        $this->activeFilters[0]->name->shouldBe('name');
        $this->activeFilters[0]->args[0]->shouldBe('Pieter');
    }

    function it_should_allow_chaining_dynamic_filter_methods_based_on_supplied_filters(StubQueryBuilder $builder)
    {
        $this->whereName('Pieter')->whereAge(25);

        $this->activeFilters[0]->name->shouldBe('name');
        $this->activeFilters[0]->args[0]->shouldBe('Pieter');


        $this->activeFilters[1]->name->shouldBe('age');
        $this->activeFilters[1]->args[0]->shouldBe(25);
    }

    function it_should_call_through_to_eloquent_models_query_builder_to_construct_and_run_the_filters_when_calling_all(StubQueryBuilder $builder)
    {
        $this->mockModel->newQuery()->willReturn($builder);
        $builder->where('name', 'Jack')->shouldBeCalledTimes(1);
        $builder->where('age', 28)->shouldBeCalled(1);
        $builder->get()->shouldBeCalledTimes(1);
        $this->whereAge(28)->whereName('Jack')->all();
    }
}

interface StubQueryBuilder {
    function where($name, $args);
    function get();
}

interface StubEloquentModel {
    function whereName();

    function whereAge();

    function newQuery();
}

class FluentTraitTest {
    use \Pieterdev\RepoFlow\FluentRepositoryTrait;

    protected static $filters = [
      'name',
      'age'
    ];

    protected $model;

    function __construct(StubEloquentModel $model)
    {
        $this->model = $model;
    }
}
