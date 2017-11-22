<?php

namespace HomeOffice\CtsBundle\Twig;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;

/**
 * Class DateExtension
 * @package HomeOffice\CtsBundle\Twig
 */
class DateExtension extends \Twig_Extension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'date_extension';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'toDateTime',
                [$this, 'formatToDateTime'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'toDate',
                [$this, 'formatToDate'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @param \DateTime $date
     * @param bool      $seconds
     * @param bool      $linebreak
     *
     * @return string
     */
    public function formatToDateTime(\DateTime $date, $seconds = false, $linebreak = true)
    {
        $date = DateHelper::fromNativeOrNull($date);

        if ($date != '') {
            return $date->toUkDate() . ($linebreak ? '<br>' : ' ') . $date->format($seconds ? 'H:i:s' : 'H:i');
        }

        return '';
    }

    /**
     * @param \DateTime $date
     *
     * @return string
     */
    public function formatToDate(\DateTime $date)
    {
        $date = DateHelper::fromNativeOrNull($date);

        if ($date != '') {
            return $date->toUkDate();
        }

        return '';
    }
}
