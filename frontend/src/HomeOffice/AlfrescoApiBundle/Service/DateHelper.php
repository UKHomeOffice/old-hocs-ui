<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

class DateHelper extends \DateTime
{
    /**
     * @var boolean $blank
     */
    private $blank;

    public static function forceDateTimeOrBlank($dateTimeOrString)
    {
        if (is_object($dateTimeOrString) && get_class($dateTimeOrString) === self::class) {
            return $dateTimeOrString;
        }

        if ($dateTimeOrString instanceof \DateTime) {
            return new static($dateTimeOrString->format('Y-m-d H:i:s'));
        }

        return self::fromStringOrBlank($dateTimeOrString);
    }

    /**
     * @param string           $string
     * @return DateHelper|null
     */
    public static function fromStringOrBlank($string = '')
    {
        if ($string == '') {
            return null;
        }

        return self::fromString($string);
    }

    /**
     * @param  string $string
     * @return static
     */
    public static function fromString($string)
    {
        return new static($string);
    }

    /**
     * @param  \DateTime $dateTime
     * @return static
     */
    public static function fromNative(\DateTime $dateTime)
    {
        return new static($dateTime->format('Y-m-d H:i:s'));
    }

    /**
     * @param null $dateTime
     * @return null
     */
    public static function fromNativeOrNullToIso($dateTime = null, $midnight = false, $milliseconds = false)
    {
        if ($dateTime === null) {
            return $dateTime;
        }

        $dateTime = self::fromNativeOrNull($dateTime);
        if (get_class($dateTime) === self::class) {
            $dateTime = $midnight ? $dateTime->midnight() : $dateTime;
            return $dateTime->toIso($milliseconds);
        }

        return null;
    }

    /**
     * @param  DateHelper|null $dateTime
     *
     * @return DateHelper|null
     */
    public static function fromNativeOrNull($dateTime = null)
    {
        if ($dateTime === null || (is_object($dateTime) && get_class($dateTime) === self::class)) {
            return $dateTime;
        }

        return is_object($dateTime) && $dateTime instanceof \DateTime ?
            self::fromNative($dateTime) :
            self::fromStringOrBlank('');
    }

    /**
     * @return static
     */
    public static function now()
    {
        return new static();
    }

    /**
     * @param  string          $interval
     * @return DateHelper|\DateTime
     */
    public function addInterval($interval)
    {
        return $this->blank ? $this : $this->add(new \DateInterval($interval));
    }

    /**
     * @param $interval
     * @return \DateTime|DateHelper
     */
    public function subInterval($interval)
    {
        return $this->blank ? $this : $this->sub(new \DateInterval($interval));
    }

    /**
     * @return $this
     */
    public function midnight()
    {
        return $this->setTime(0, 0, 0);
    }

    /**
     * @return \DateTime|DateHelper
     */
    public function tomorrow()
    {
        return $this->addInterval('P1D');
    }

    /**
     * @return \DateTime|DateHelper
     */
    public function yesterday()
    {
        return $this->subInterval('P1D');
    }

    /**
     * @param $format
     * @return null|string
     */
    public function toFormat($format)
    {
        return $this->blank ? null : $this->format($format);
    }

    /**
     * @param boolean            $milliseconds
     * @return mixed|null|string
     */
    public function toIso($milliseconds = false)
    {
        $string = $this->toFormat('c');
        return $string && $milliseconds ? substr_replace($string, '.000', strrpos($string, '+'), 0) : $string;
    }

    /**
     * TO UK Date
     *
     * @param bool $time
     * @param bool $fullYear
     *
     * @return string
     */
    public function toUkDate($time = false, $fullYear = true)
    {
        $this->setTimezone(new \DateTimeZone('Europe/London'));
        return $this->toFormat('d/m/' . ($fullYear ? 'Y' : 'y') . ($time ? ' H:i:s' : null));
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
       return $this->toUkdate(true, true);
    }
}
