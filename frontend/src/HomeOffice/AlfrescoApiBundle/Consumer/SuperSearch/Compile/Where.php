<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Where\Nested;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Where\Basic;

/**
 * Class Where
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement\Compile
 */
class Where implements CompileInterface
{
    /**
     * @var array
     */
    private static $factory = [
        'Basic'  => Basic::class,
        'Nested' => Nested::class
    ];

    /**
     * @param  Statement   $query
     * @return bool|string
     */
    public function getSql(Statement $query)
    {
        return self::generate($query->getWheres());
    }

    /**
     * @param  array       $wheres
     * @return bool|string
     */
    public static function generate(array $wheres)
    {
        return empty($wheres) ? '' : self::prepare(self::loopWheres($wheres));
    }

    /**
     * @param  $data
     * @return string
     */
    private static function factory($type, array $data)
    {
        if (! array_key_exists($type, self::$factory)) {
            throw new \InvalidArgumentException('Class type [' . $data['type'] . '] missing.');
        }

        return (new self::$factory[$type])->getSql($type === 'Nested' ? $data['query'] : $data);
    }

    /**
     * @param  array $wheres
     * @param  array $sql
     * @return array
     */
    private static function loopWheres(array $wheres, $sql = [])
    {
        foreach ($wheres as $where) {
            if (! isset($where['type'])) {
                throw new \InvalidArgumentException('$data[\'type\'] must be specified.');
            }
            $sql[] = $where['boolean'] . ' ' . self::factory($where['type'], $where);
        }

        return $sql;
    }

    /**
     * @param  array        $sql
     * @return bool|string
     */
    private static function prepare(array $sql)
    {
        return count($sql) > 0 ? 'WHERE' . ' ' . self::removeLeadingBoolean(implode(' ', $sql)) : false;
    }

    /**
     * @param  $value
     * @return mixed
     */
    protected static function removeLeadingBoolean($value)
    {
        return  preg_replace('/and |or /i', '', $value, 1);
    }
}
