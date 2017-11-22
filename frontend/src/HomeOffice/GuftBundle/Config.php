<?php

namespace HomeOffice\GuftBundle;

/**
 * Class Config
 *
 * @author  Adam Lewis <adam.lewis@digital.homeoffice.gov.uk>
 * @since   2016-04-21
 * @package HomeOffice\GuftBundle
 */
class Config
{
    /**
     * @var array
     */
    private $params;

    /**
     * Config constructor.
     *
     * @param $title
     * @param $theme
     * @param $breadcrumb
     * @param $sidebar
     * @param $hide_breadcrumb
     * @param $hide_sidebar
     */
    public function __construct(
        $title,
        $theme,
        $breadcrumb,
        $sidebar,
        $hide_breadcrumb,
        $hide_sidebar
    ) {
        $this->params = [
            'title' => $title,
            'theme' => $theme,
            'breadcrumb' => $breadcrumb,
            'sidebar' => $sidebar,
            'hide_breadcrumb' => $hide_breadcrumb,
            'hide_sidebar' => $hide_sidebar
        ];
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
