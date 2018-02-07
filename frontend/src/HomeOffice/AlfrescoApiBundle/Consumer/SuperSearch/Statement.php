<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch;

/**
 * Class Statement
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch
 */
class Statement
{
    /**
     * @var array
     */
    protected $statement = [];

    /**
     * @var array
     */
    private $columns = [];
    private $wheres = [];
    private $from = [];

    protected $bindings = [
        'select' => [],
        'join'   => [],
        'where'  => []
    ];

    /**
     * @return array
     */
    public function getWheres()
    {
        return $this->wheres;
    }

    /**
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param  string $field
     * @return $this
     */
    public function select($field)
    {
        $this->columns = is_array($field) ? $field : func_get_args();
        return $this;
    }

    /**
     * @param  $table
     * @return $this
     */
    public function from($table)
    {
        $this->from = $table;
        return $this;
    }

    /**
     * @param  $column
     * @param  null            $value
     * @param  string          $operator
     * @param  string          $boolean
     * @return $this|Statement
     */
    public function where($column, $value = null, $operator = 'LIKE', $boolean = 'AND')
    {
        if ($column instanceof \Closure) {
            return $this->whereNested($column, $boolean);
        }

        $type = 'Basic';
        $this->wheres[] = compact('type', 'column', 'operator', 'value', 'boolean');
        return $this;
    }

    /**
     * @param  $column
     * @return $this
     */
    public function addSelect($column)
    {
        $column = is_array($column) ? $column : func_get_args();
        $this->columns = array_merge((array) $this->columns, $column);
        return $this;
    }

    /**
     * @param  string $field
     * @param  string $value
     * @param  string $boolean
     * @return $this
     */
    public function like($field, $value, $boolean = 'AND')
    {
        $this->where($field, "%" . $value . "%", 'LIKE', $boolean);
        return $this;
    }

    /**
     * @param  $field
     * @param  $value
     * @return Statement
     */
    public function orLike($field, $value)
    {
        return $this->like($field, $value, 'OR');
    }

    /**
     * @param  $column
     * @param  null      $value
     * @param  string    $operator
     * @return Statement
     */
    public function orWhere($column, $value = null, $operator = 'LIKE')
    {
        return $this->where($column, $value, $operator, 'OR');
    }

    /**
     * @param  \Closure  $callback
     * @param  string    $boolean
     * @return Statement
     */
    private function whereNested(\Closure $callback, $boolean = 'AND')
    {
        $query = $this->forNestedWhere();
        call_user_func($callback, $query);

        return $this->addNestedWhereQuery($query, $boolean);
    }

    /**
     * @return mixed
     */
    private function forNestedWhere()
    {
        $query = new static;
        return $query->from($this->from);
    }

    /**
     * @param  $query
     * @param  string $boolean
     * @return $this
     */
    private function addNestedWhereQuery(Statement $query, $boolean = 'and')
    {
        if (count($query->wheres)) {
            $type = 'Nested';
            $this->wheres[] = compact('type', 'query', 'boolean');
            $this->addBinding($query->getBindings(), 'where');
        }

        return $this;
    }

    /**
     * @param  $value
     * @param  string $type
     * @return $this
     */
    protected function addBinding($value, $type = 'where')
    {
        if (! array_key_exists($type, $this->bindings)) {
            throw new \InvalidArgumentException('Invalid binding type: ' . $type . '.');
        }

        is_array($value)
            ? $this->bindings[$type] = array_values(array_merge($this->bindings[$type], $value))
            : $this->bindings[$type][] = $value;

        return $this;
    }


    /**
     * @return mixed
     */
    protected function getBindings()
    {
        return array_reduce($this->bindings, function ($result, $item) {
            return array_merge($result, [$item]);
        }, []);
    }

    /**
     * @return array|string
     * @throws \Exception
     */
    public function generate()
    {
        if (! $this->from) {
            throw new \RuntimeException('From must be set to execute a query.');
        }

        return Compile::build($this);
    }

}
