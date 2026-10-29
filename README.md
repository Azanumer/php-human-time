# PHP Human Time

Dependency-free "time ago" formatting for PHP — no Carbon, no intl extension needed.

```php
require 'src/HumanTime.php';

echo HumanTime::diffForHumans(strtotime('-3 hours')); // 3 hours ago
echo HumanTime::diffForHumans(time() + 86400);        // in 1 day
echo HumanTime::diffForHumans($ts, null, true);       // 3h ago (short form)
echo HumanTime::diffDetailed($ts);                    // 1 day, 1 hour ago
```

- Accepts Unix timestamps, `DateTimeInterface` objects, or date strings.
- Under 5 seconds → "just now" (avoids "0 seconds ago" flicker).
- Units: years, months, weeks, days, hours, minutes, seconds. Singular/plural handled.

Run `php examples/human-time-example.php` for a demo.

## License

MIT
