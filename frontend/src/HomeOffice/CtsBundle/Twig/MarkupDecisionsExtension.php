<?php

namespace HomeOffice\CtsBundle\Twig;

use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;

/**
 * Class MarkupDecisionsExtension
 *
 * @package HomeOffice\CtsBundle\Twig
 */
class MarkupDecisionsExtension extends \Twig_Extension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'markup_decisions_extension';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('nextDecisions', [$this, 'getNextDecisions'])
        ];
    }

    /**
     * Get Next Decisions
     *
     * @return string[]
     */
    public function getNextDecisions()
    {

        return MarkupDecisions::getNextMarkupDecisions();
    }
}
