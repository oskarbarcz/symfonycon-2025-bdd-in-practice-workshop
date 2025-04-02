<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

class DeepCompare
{
    public static function compare(mixed $actual, mixed $expected): void
    {
        if ($expected === '@uuid') {
            Assert::true(Uuid::isValid($actual));
            return;
        }

        if ($expected === "@date('within 1 minute from now')") {
            $actualDate = new \DateTimeImmutable($actual);
            $now = new \DateTimeImmutable();

            Assert::lessThanEq($actualDate, $now, 'Date should not be in the future');

            $diffSeconds = abs($now->getTimestamp() - $actualDate->getTimestamp());
            Assert::lessThanEq($diffSeconds,60, 'Date should be within 1 minute from now');

            return;
        }

        if ($actual === $expected) {
            return;
        }

        if (!is_array($actual) || !is_array($expected)) {
            Assert::eq($actual, $expected);
            return;
        }

        $actualKeys = array_keys($actual);
        $expectedKeys = array_keys($expected);

        Assert::count($expectedKeys, count($actualKeys));
        foreach ($expectedKeys as $key) {
            Assert::keyExists($actual, $key);
            self::compare($actual[$key], $expected[$key]);
        }
    }
}