<?php

namespace HomeOffice\CtsBundle\Twig;

class EmailExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('removeDomain', [$this, 'removeDomain'])
        ];
    }

    public function removeDomain($email)
    {
        if (! preg_match('/@/', $email)) {
            return $email;
        }

        $parts = explode('@', $email);

        return $parts[0];
    }

    public function getName()
    {
        return 'email_extension';
    }
}
