<?php
    namespace App\Logic\Query;

// Simple query builder for simple applications
class QueryBuilder
{

    protected $table;

    protected $selects = array();

    protected $wheres = array();

    protected $joins = array();

    protected $groupBy;

    protected $orderBy;

    protected $offset;

    protected $limit;

    public function __construct($table)
    {
        $this->table = trim($table);
    }

    public function select($mixed)
    {
        $this->selects = array_merge($this->selects, (array) $mixed);
    }

    public function join($joinClause)
    {
        $this->joins[] = trim($joinClause);
    }

    public function where($whereClause)
    {
        $this->wheres[] = trim($whereClause);
    }

    public function orderBy($orderByClause)
    {
        $this->orderBy = trim($orderByClause);
    }

    public function groupBy($groupByClause)
    {
        $this->groupBy = trim($groupByClause);
    }

    public function offset($offset)
    {
        $this->offset = intval($offset);
    }

    public function limit($limit)
    {
        $this->limit = intval($limit);
    }

    public function build()
    {
        $query = "SELECT ";
        $query .= implode(', ', $this->selects);
        $query .= " FROM ".$this->table;
        foreach ($this->joins as $join) {
            $query .= " ".$join;
        }
        $query .= " WHERE 1";
        foreach ($this->wheres as $where) {
            $query .= " AND ".$where;
        }
        if (!empty($this->groupBy)) {
            $query .= " GROUP BY ".$this->groupBy;
        }
        if (!empty($this->orderBy)) {
            $query .= " ORDER BY ".$this->orderBy;
        }
        if (!empty($this->limit)) {
            $query .= " LIMIT ";
            if (!empty($this->offset)) {
                $query .= $this->offset.",";
            }
            $query .= $this->limit;
        }
        return $query;
    }

}
