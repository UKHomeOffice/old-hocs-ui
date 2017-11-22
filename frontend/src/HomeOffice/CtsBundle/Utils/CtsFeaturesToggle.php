<?php

namespace HomeOffice\CtsBundle\Utils;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class CtsFeaturesToggle
 *
 * Use in Controllers to display new features.  Create a new method and copy the old
 * controller method code into it.  Create your new controller method for new functionality.
 * Then use $this->get() to dictate whether to use the new method or old method in the original
 * method name.  Like so:
 *
 * public function newMethod()
 * {
 *     return $this->ctsFeaturesToggle->get() ? $this->newMethod() : $this->oldMethod();
 * }
 *
 * @package HomeOffice\CtsBundle\Utils
 */
final class CtsFeaturesToggle
{
    private static $session = false;
    private $sessionSetting = 'newFeaturesOn';

    /**
     * CtsFeaturesToggle constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        if (! self::$session) {
            self::$session = $session;
        }
    }

    /**
     * @param SessionInterface $session
     */
    public static function setSession(SessionInterface $session)
    {
        self::$session = $session;
    }

    /**
     * On
     *
     * @return void
     */
    public function on()
    {
        self::$session->set($this->sessionSetting, true);
    }

    /**
     * Off
     *
     * @return void
     */
    public function off()
    {
        self::$session->set($this->sessionSetting, false);
    }

    /**
     * Set
     *
     * @param $toggle boolean
     */
    public function set($toggle)
    {
        in_array((int) $toggle, [0, 1]) ? self::$session->set($this->sessionSetting, $toggle) : null;
    }

    /**
     * On
     *
     * @param CtsCase|null $case
     *
     * @return bool
     */
    public function get(CtsCase $case = null)
    {
        if (!is_null($case)) {
            return !in_array($case->getCorrespondenceType(), ['COM', 'GEN']) || $this->isOn();
        }

        return $this->isOn();
    }

    /**
     * @return bool
     */
    protected function isOn()
    {
        $session = self::$session->get($this->sessionSetting);

        return $session && $session !== '0';
    }
}
