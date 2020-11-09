<?php

class View {
    public $title = 'One Page Application';
    public $data;
    public $template = 'templates/page.php';
    public $model;
    private $error = 'Ошибка сервера (шаблон не найден).';

    public function __construct($template, $data, $model)
    {
        $this->data = $data;
        $this->template = $template;
        $this->model = $model;
    }

    public function render()
    {
//        extract($this->data);
        ob_start();
        if (file_exists($this->template)) {
            require $this->template;
        } else {
            return $this->error;
        }
        return ob_get_clean();
    }
}