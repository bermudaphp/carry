# Install
```bash
composer require bermudaphp/carry
```

# Usage
```php
$add = new Carry(static fn(int $a, $int $b) => $a+$b, 10);

$add(5); // 15 or $add->call(5)


$decrement = $add->add(-5);
$decrement(); // 5

```
