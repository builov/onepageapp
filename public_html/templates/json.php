<?php

$out['data'] = [];
$out['pager'] = [];

if ($this->model->entries > 0)
{
    foreach ($this->data as $row) {
        $out['data'][] = array($row['date'], $row['title'], $row['quantity'], $row['dist']);
    }

    $out['pager']['pages'] = ceil($this->model->entries / $this->model->limit);
    $out['pager']['active'] = $this->model->pager_page;
}
else {
    $out['message'] = 'Ничего не найдено.';
}
print json_encode($out);