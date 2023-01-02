<?php

declare(strict_types=1);

namespace Spawnia\Sailor\Adapter;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Safe\Exceptions\DatetimeException;

class DateTimeScalarAdapter implements ScalarAdapter
{
    private string $format;

    private ?DateTimeZone $dateTimeZone;

    private bool $resetsAllFields;

    public function __construct(string $format = DateTimeInterface::ATOM, DateTimeZone $dateTimeZone = null, bool $resetsAllFields = false)
    {
        $this->format          = $format;
        $this->dateTimeZone    = $dateTimeZone;
        $this->resetsAllFields = $resetsAllFields;
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
        $format = $this->format;

        if ($this->resetsAllFields) {
            $format = '!' . $format;
        }

        $dateTime = DateTime::createFromFormat($format, $value, $this->dateTimeZone);

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
