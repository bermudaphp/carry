# Install
```bash
composer require bermudaphp/carry
```

# Usage
```php
$add = new Carry(static fn(int $a, $int $b) => $a+$b, 10); 
// alternative
$add = carray(static fn(int $a, $int $b) => $a+$b, 10);

$add(5); // 15
// alternative
$add->call(5);

// add new arguments 
$decrement = $add->add(-5);
$decrement(); // 5

// Allow default argument values
$add = carray(static fn(int $a, $int $b = 5) => $a + $b, 10)->useDefaultValues(true);
// alternative
$add = Carry::use(static fn(int $a, $int $b = 5) => $a + $b, 10)

$add() // 15
```
