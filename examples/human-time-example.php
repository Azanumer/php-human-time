<?php
require __DIR__ . '/../src/HumanTime.php';

$now = time();
echo HumanTime::diffForHumans($now - 3 * 3600) . PHP_EOL;          // 3 hours ago
echo HumanTime::diffForHumans($now + 86400) . PHP_EOL;             // in 1 day
echo HumanTime::diffForHumans($now - 90, null, true) . PHP_EOL;    // 1m ago
echo HumanTime::diffDetailed($now - 90000) . PHP_EOL;              // 1 day, 1 hour ago
echo HumanTime::diffForHumans(new DateTime('-2 weeks')) . PHP_EOL; // 2 weeks ago
