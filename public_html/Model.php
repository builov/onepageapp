<?php

class Model
{
    private $pdo;
    private $fields = [];
    private $operators = array('equal' => '=',
                                'contains' => 'like',
                                'larger' => '>',
                                'less' => '<');
    private $orders = ['asc', 'desc'];
    public $limit = 5;
    public $entries;
    public $no_entries_message = 'Ничего не найдено.';
    public $incorrect_value_message = 'Некорректное значение фильтра.';

    function __construct()
    {
        $this->pdo = DbConn::getConnection()->pdo;

        $stmt = $this->pdo->prepare('SHOW COLUMNS FROM table1');
        $stmt->execute();
        while ($row = $stmt->fetch())
        {
            $this->fields[$row['Field']] = $row;
        }
    }

    function getAll()
    {
        $stmt = $this->pdo->query('SELECT * FROM table1');
        while ($row = $stmt->fetch())
        {
            $data[] = $row;
        }
        return (isset($data)) ? $data : false;
    }

    private function isValidStr($str)
    {
        return true;
    }

    function getFiltered($page, $sort, $sort_by_field, $filter, $filter_by_field, $operator)
    {
        if (!$this->isValidStr($filter)) return $this->incorrect_value_message;

        $query = 'SELECT * FROM table1 WHERE TRUE';

        if ($filter != NULL && array_key_exists($filter_by_field, $this->fields) && array_key_exists($operator, $this->operators))
        {
            $query .= ' AND ' . $filter_by_field . ' ' . $this->operators[$operator] . ' :filter';
            $bind_values[] = array($filter, $operator);
        }

        if (in_array($sort, $this->orders) && array_key_exists($sort_by_field, $this->fields))
        {
            $query .= ' ORDER BY ' . $sort_by_field . ' ' . $sort;
        }

        // entries count
        $query_count = str_replace('*', 'COUNT(*)', $query);
        $stmt = $this->pdo->prepare($query_count);

        if (isset($bind_values)) foreach ($bind_values as $bind_value)
        {
            $val = ($bind_value[1] == 'contains') ? '%' . $bind_value[0] . '%' : $bind_value[0];
            $stmt->bindValue(':filter', $val, PDO::PARAM_STR);
        }

        $stmt->execute();
        $this->entries = $stmt->fetchColumn();

        if ($this->entries < 1) return $this->no_entries_message;
        // ============================

        $query .= ' LIMIT :limit';
        $query .= ' OFFSET :offset';

//        echo $query;

        $stmt = $this->pdo->prepare($query);

        if (isset($bind_values)) foreach ($bind_values as $bind_value)
        {
            $val = ($bind_value[1] == 'contains') ? '%' . $bind_value[0] . '%' : $bind_value[0];
            $stmt->bindValue(':filter', $val, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);

        $offset = ((bool) $page) ? $this->limit * ((int) $page - 1) : 0;
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        while ($row = $stmt->fetch())
        {
            $data[] = $row;
        }
        return (isset($data)) ? $data : $this->no_entries_message;
    }
}