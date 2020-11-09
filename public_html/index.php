<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$model = new Model();

//$pager_page = (int) $_GET['page'];
$pager_page = (isset($_POST['page'])) ? (int) $_POST['page'] : 1;
$sort = (isset($_POST['sort'])) ? filter_input(INPUT_POST, "sort", FILTER_SANITIZE_STRING) : 'asc';
$sort_by_field = (isset($_POST['sort_by'])) ? filter_input(INPUT_POST, "sort_by", FILTER_SANITIZE_STRING) : NULL;
$filter = (isset($_POST['filter_value'])) ? filter_input(INPUT_POST, "filter_value", FILTER_SANITIZE_STRING) : NULL;
$filter_by_field = (isset($_POST['filter_by'])) ? filter_input(INPUT_POST, "filter_by", FILTER_SANITIZE_STRING) : NULL;
$filter_condition = (isset($_POST['filter_condition'])) ? filter_input(INPUT_POST, "filter_condition", FILTER_SANITIZE_STRING) : NULL;

if ($data = $model->getFiltered($pager_page, $sort, $sort_by_field, $filter, $filter_by_field, $filter_condition))
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
    {
        $out = new View('templates/json.php', $data, $model);
        print $out->render();
        return;
    }
    $page = new View('templates/page.php', $data, $model);
    print $page->render();
    return;
}
$page = new View('templates/error.php','Ошибка сервера.', $model);
print $page->render();
