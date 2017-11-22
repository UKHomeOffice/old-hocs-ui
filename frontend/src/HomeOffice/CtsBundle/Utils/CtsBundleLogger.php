<?php
namespace HomeOffice\CtsBundle\Utils;

use Symfony\Component\HttpKernel\Log\LoggerInterface as Logger;

class CtsBundleLogger
{
    static private $logger;

    public static function init(Logger $logger)
    {
        self::$logger = $logger;
    }

    public static function getLogger()
    {
        return self::$logger;
    }
}
