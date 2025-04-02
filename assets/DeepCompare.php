<?php

declare(strict_types=1);

namespace assets;

use Symfony\Component\Uid\Uuid;
use PHPUnit\Framework\Assert;

class DeepCompare
{
    public static function compare(mixed $actual, mixed $expected): void
    {
        if ($expected === '@uuid') {
            Assert::assertTrue(Uuid::isValid($actual));
            return;
        }

        if ($expected === "@date('within 1 minute from now')") {
            $actualDate = new \DateTimeImmutable($actual);
            $now = new \DateTimeImmutable();

            Assert::assertLessThanOrEqual($now, $actualDate, 'Date should not be in the future');

            $diffSeconds = abs($now->getTimestamp() - $actualDate->getTimestamp());
            Assert::assertLessThan(60, $diffSeconds, 'Date should be within 1 minute from now');

            return;
        }

        if ($actual === $expected) {
            return;
        }

        if (!is_array($actual) || !is_array($expected)) {
            Assert::assertSame($expected, $actual);
            return;
        }

        $actualKeys = array_keys($actual);
        $expectedKeys = array_keys($expected);

        Assert::assertSameSize($expectedKeys, $actualKeys);
        foreach ($expectedKeys as $key) {
            Assert::assertArrayHasKey($key, $actual);
            self::compare($actual[$key], $expected[$key]);
        }
    }
}