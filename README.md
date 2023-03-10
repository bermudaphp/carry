# Install
```bash
composer require bermudaphp/carry
```

# Usage
```php
$add = new Carry(static fn(int $a, $int $b) => $a+$b, 10);

$add(5); // 15
$add(-5) // 5
```
