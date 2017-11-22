<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Columns;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\From;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Where;

class Compile
{
    /**
     * @var array
     */
    protected $components = [
        'columns' => Columns::class,
        'from'    => From::class,
        'wheres'  => Where::class
    ];

    /**
     * @param  Statement $query
     * @return string
     */
    public static function build(Statement $query)
    {
        empty($query->getColumns()) ? $query->select('*') : null;

        $that = new static;

        return trim($that->concatenate($that->components($query)));
    }

    /**
     * @param  $component
     * @return Columns|From|Where
     */
    protected function factory($component)
    {
        if (! array_key_exists($component, $this->components)) {
            throw new \InvalidArgumentException('Component [' . $component . '] not found.');
        }

        return new $this->components[$component];
    }

    /**
     * @param  Statement $query
     * @param  array     $sql
     * @return array
     */
    protected function components(Statement $query, $sql = [])
    {
        foreach($this->components as $component => $class) {
            $method = 'get' . ucfirst($component);
            if (! is_null($query->$method())) {
                $sql[$component] = $this->factory($component)->getSql($query);
            }
        }

        return $sql;
    }

    /**
     * @param  $segments
     * @return string
     */
    protected function concatenate($segments)
    {
        return implode(' ', array_filter($segments, function ($value) {
            return (string) $value !== '';
        }));
    }
}
