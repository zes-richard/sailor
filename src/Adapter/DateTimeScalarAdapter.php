<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Adapter;

use DateTime;
use DateTimeInterface;
use Safe\Exceptions\DatetimeException;

class DateTimeScalarAdapter implements ScalarAdapter
{
    private string         $format;

    private ?\DateTimeZone $dateTimeZone;

    public function __construct(string $format = DateTimeInterface::ATOM, \DateTimeZone $dateTimeZone = null)
    {
        $this->format       = $format;
        $this->dateTimeZone = $dateTimeZone;
    }

    public function typeHint(): string
    {
        return DateTime::class;
    }

    /**
     * @param string $value
     *
     * @return DateTime
     * @throws DatetimeException
     */
    public function parse(string $value): ?DateTime
    {
        $dateTime = DateTime::createFromFormat($this->format, $value, $this->dateTimeZone);

        if ($dateTime === false) {
            throw DatetimeException::createFromPhpError();
        }

        return $dateTime;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public function serialize($value): ?string
    {
        if (! $value instanceof DateTimeInterface) {
            return $value;
        }

        return $value->format($this->format);
    }
}
