 - [Overview](00-index.md)
 - [Chapter 1: list comprehension](01-list-comprehension.md)
 - [Chapter 2: partial application](02-partial-application.md)
 - [Chapter 3: access functions](03-access-functions.md)
 - Chapter 4: function functions
 - [Chapter 5: mathematical functions](05-mathematical-functions.md)
 - [Chapter 6: transformation functions](06-transformation-functions.md)

# Function functions

Function functions take a function and return a new, modified version of the function.


## retry()
Retry a callback until the number of retries are reached or the callback does no longer throw an exception

```php
use function Functional\retry;
use function Functional\sequence_exponential;

assert_options(ASSERT_CALLBACK, function () {throw new Exception('Assertion failed');});

// Assert that a file exists 10 times with an exponential back-off
retry(
    function() {assert(file_exists('/tmp/lockfile'));},
    10,
    sequence_exponential(1, 100)
);
```


## poll()
Retry a callback until it returns a truthy value or the timeout (in microseconds) is reached

```php
use function Functional\poll;
use function Functional\sequence_linear;

// Poll if a file exists for 10,000 microseconds with a linearly growing back-off starting at 100 milliseconds
poll(
    function() {
        return file_exists('/tmp/lockfile');
    },
    10000,
    sequence_linear(100, 1)
);
```

You can pass any `Traversable` as a sequence for the delay but Functional comes with `Functional\sequence_constant()`, `Functional\sequence_linear()` and `Functional\sequence_exponential()`.

## memoize_func()

Given a target ```callable```, ```memoize_func``` creates a new ```callable``` able to cache every invocation. Really useful for expensive function calls that should be performed potentially several times.

```php
use function Functional\memoize_func;

// Caches preg_replace calls
$preg_replace = memoize_func('preg_replace');

// Caches select resultsets
$select = memoize_func(function($selectClause, array $parameters = []) use ($pdo) {
    $stmt = $pdo->prepare($selectClause);
    $stmt->execute($parameters);
    
    return $stmt->fetchAll();
});
```

## Other

`mixed Functional\memoize(callable $callback[, array $arguments = []], [mixed $key = null]])`  
Returns and stores the result of the function call. Second call to the same function will return the same result without calling the function again
