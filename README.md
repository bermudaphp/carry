# Install
```bash
composer require bermudaphp/curry
```

# Usage
```php
$add = new Curry(static fn(int $a, $int $b) => $a+$b, 10); 
// alternative
$add = curry(static fn(int $a, $int $b) => $a+$b, 10);

$add(5); // 15
// alternative
$add->call(5);

// add new arguments 
$decrement = $add->add(-5);
$decrement(); // 5

// Allow default argument values
$add = curry(static fn(int $a, $int $b = 5) => $a + $b, 10)->useDefaultValues(true);
// alternative
$add = Curry::use(static fn(int $a, $int $b = 5) => $a + $b, 10)

$add() // 15
```
