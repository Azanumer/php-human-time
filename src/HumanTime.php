<?php
/**
 * HumanTime — dependency-free "3 hours ago" / "in 2 days" formatting.
 *
 * Usage:
 *   echo HumanTime::diffForHumans(strtotime('-3 hours')); // "3 hours ago"
 *   echo HumanTime::diffForHumans(time() + 86400);        // "in 1 day"
 *   echo HumanTime::diffForHumans($ts, null, true);       // "3h ago" (short)
 */

class HumanTime
{
    /** [seconds, singular, plural, short] — largest unit wins. */
    private static $units = [
        [31536000, 'year',   'years',   'y'],
        [2592000,  'month',  'months',  'mo'],
        [604800,   'week',   'weeks',   'w'],
        [86400,    'day',    'days',    'd'],
        [3600,     'hour',   'hours',   'h'],
        [60,       'minute', 'minutes', 'm'],
        [1,        'second', 'seconds', 's'],
    ];

    /**
     * Format the difference between $timestamp and $now (default: now).
     * Accepts a Unix timestamp, a DateTimeInterface, or a date string.
     * Returns "just now" for differences under 5 seconds.
     */
    public static function diffForHumans($timestamp, $now = null, $short = false)
    {
        $then = self::toTimestamp($timestamp);
        $now  = $now === null ? time() : self::toTimestamp($now);
        $diff = $now - $then;

        if (abs($diff) < 5) {
            return 'just now';
        }

        $future = $diff < 0;
        $diff   = abs($diff);

        foreach (self::$units as [$seconds, $singular, $plural, $abbr]) {
            if ($diff >= $seconds) {
                $value = (int) floor($diff / $seconds);
                if ($short) {
                    $text = $value . $abbr;
                } else {
                    $text = $value . ' ' . ($value === 1 ? $singular : $plural);
                }
                return $future ? "in {$text}" : "{$text} ago";
            }
        }

        return 'just now'; // Unreachable: seconds unit covers diff >= 5.
    }

    /** Full breakdown: "2 days, 3 hours ago" (up to $parts units). */
    public static function diffDetailed($timestamp, $now = null, $parts = 2)
    {
        $then = self::toTimestamp($timestamp);
        $now  = $now === null ? time() : self::toTimestamp($now);
        $diff = $now - $then;

        if (abs($diff) < 5) {
            return 'just now';
        }

        $future    = $diff < 0;
        $remaining = abs($diff);
        $chunks    = [];

        foreach (self::$units as [$seconds, $singular, $plural]) {
            if (count($chunks) >= $parts) {
                break;
            }
            $value = (int) floor($remaining / $seconds);
            if ($value > 0) {
                $chunks[]   = $value . ' ' . ($value === 1 ? $singular : $plural);
                $remaining -= $value * $seconds;
            }
        }

        $text = implode(', ', $chunks);
        return $future ? "in {$text}" : "{$text} ago";
    }

    private static function toTimestamp($value)
    {
        if ($value instanceof DateTimeInterface) {
            return $value->getTimestamp();
        }
        if (is_int($value)) {
            return $value;
        }
        $ts = strtotime((string) $value);
        if ($ts === false) {
            throw new InvalidArgumentException('Unrecognised date/time value.');
        }
        return $ts;
    }
}
