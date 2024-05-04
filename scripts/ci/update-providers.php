<?php

declare(strict_types=1);

$file = 'bootstrap/providers.php';
$current = include $file;

// ? ============================
// ? Appending Service Providers
// ? ==========================

$newContent = "<?php\n\ndeclare(strict_types=1);\n\nreturn [\n";

$current[] = 'VPremiss\Crafty\CraftyServiceProvider::class';
$current[] = 'VPremiss\Arabicable\ArabicableServiceProvider::class';

foreach ($current as $provider) {
    $newContent .= "    $provider,\n";
}

$newContent .= "];\n";

file_put_contents($file, $newContent);
