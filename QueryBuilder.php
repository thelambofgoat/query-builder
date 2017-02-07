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

    protected $page;

    protected $tokenShowAll = 'all';

    // As not to hang browser or app on showing of thousands of items
    protected $limitShowAll = 100;

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

    // TODO: several GROUP BY conditions
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

    // TODO: design as trait
    public function paginate($show = 20)
    {
        $p = $_GET['page'];
        if ($p === $this->tokenShowAll) {
            $this->limit = $this->limitShowAll;
        } else {
            // TODO: is this right?
            $p = $p || 1;
            $this->limit = $p <= $this->limitShowAll ? $p : $this->limitShowAll;
            $this->offset = ($p - 1) * $this->limit;
        }



    }

}
