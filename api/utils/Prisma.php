<?php

require_once(__DIR__ . '/../dao/BaseDao.class.php');

class Prisma
{
    private string $query = '';
    private array $params = [];

    public static function sql(): Prisma
    {
        return new self();
    }

    public function nested(): static
    {
        $this->query = "($this->query)";
        return $this;
    }

    public function alias(string $alias): string
    {
        return "$this->query as $alias";
    }


    public function select(...$params): static
    {
        $this->query .= "SELECT " . implode(", ", $params);
        return $this;
    }

    public function from($table): static
    {
        $this->query .= " FROM $table";
        return $this;
    }

    public function where(...$params): static
    {
        $whereClause = " WHERE ";
        $operator = 'AND';

        foreach ($params as $param) {
            if ($param === 'AND' || $param === 'OR') {
                $operator = $param;
            } else {
                $whereClause .= ($whereClause === " WHERE " ? '' : " $operator ") . $param;
            }
        }

        $this->query .= $whereClause;
        return $this;
    }


    public function join($table, $condition): static
    {
        $this->query .= " JOIN $table ON $condition";
        return $this;
    }

    public function limit($limit): static
    {
        $this->query .= " LIMIT $limit";
        return $this;
    }

    public function group(...$params): static
    {
        $this->query .= " GROUP BY " . implode(", ", $params);
        return $this;
    }

    public function order($column, $direction = 'ASC'): static
    {
        $this->query .= " ORDER BY $column $direction";
        return $this;
    }

    public function offset($offset): static
    {
        $this->query .= " OFFSET $offset";
        return $this;
    }

    public function bind($params): static
    {
        $this->params = $params;
        return $this;
    }

    public function execute(): false|array
    {
        error_log($this->query);
        $stmt = BaseDao::conn()->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute_unique()
    {
        $results = $this->execute();
        return reset($results);
    }

    public function get_query(): string
    {
        return $this->query;
    }

    public function append(Prisma $prisma): static
    {
        $this->query .= $prisma->get_query();
        $this->params = [...$this->params, ...$prisma->params];
        return $this;
    }
}
