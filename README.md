repoflow
========

A simple trait to allow fluently querying repositories with an eloquent model. Gives back the flexibility of eloquent to some extent, while remaining explicit on which methods are supported by a repository.

## Using it

Simply: 

1. Add the Pieterdev\Repoflow\FluentRepositoryTrait to your repository class.
2. Add a `protected static $filters= [...]` array to your repository class denoting which properties on your model should be filterable.
3. Have the model your repository is using as a field called $model on your repository class, or have $model be a string containing the name of the eloquent model used by the repository.
4. You can then do chain queries on your repository, for example `$repo->whereName('Jack')->whereScore(3)->all();
5. The method `all()` invokes the query.

```
<?php

class SomeEloquentRepository implements ISomeRepository {

    use Pieterdev\Repoflow\FluentRepositoryTrait;

    protected static $filters = [
        'name',
        'score'
    ];

    protected $model;

    function __construct(User $userModel) 
    {
        $this->model = $userModel;
    }
}
```

