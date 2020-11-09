<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/templates/img/favicon.ico">
    <title><?= $this->title ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">-->
    <!-- Custom styles for this template -->
    <link href="/templates/css/style.css" rel="stylesheet">
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <main role="main" class="col-md-12 ml-sm-auto col-lg-12 pt-3 px-4">
            <h1><?= $this->title ?></h1>

            <form class="form-inline mt-2 mt-md-0">
                <fieldset>
                    <legend class="mr-sm-2">Фильтр: </legend>
                    <select class="filter-field form-control mr-sm-2">
                        <option value="">Поле</option>
                        <option value="title">Название</option>
                        <option value="quantity">Количество</option>
                        <option value="dist">Расстояние</option>
                    </select>
                    <select class="filter-condition form-control mr-sm-2">
                        <option value="">Условие</option>
                        <option value="equal">Равно</option>
                        <option value="contains">Содержит</option>
                        <option value="larger">Больше</option>
                        <option value="less">Меньше</option>
                    </select>
                    <input class="filter-value form-control mr-sm-2" type="text" placeholder="Значение" aria-label="Значение">
                    <button class="filter btn btn-outline-success my-2 my-sm-0" type="submit">Применить фильтр</button>
                    <button class="clear btn btn-outline-success my-2 my-sm-0" type="submit">Отменить фильтр</button>
                </fieldset>
            </form>

            <div class="table-responsive">

                <table id="data-area" class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col"><span>Дата</span></th>
                            <th scope="col" data-sort="none" data-field="title"><a href="">Название</a></th>
                            <th scope="col" data-sort="none" data-field="quantity"><a href="">Количество</a></th>
                            <th scope="col" data-sort="none" data-field="dist"><a href="">Расстояние</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <? foreach ($this->data as $row): ?>
                            <tr>
                                <td><?= $row['date'] ?></td>
                                <td><?= $row['title'] ?></td>
                                <td><?= $row['quantity'] ?></td>
                                <td><?= $row['dist'] ?></td>
                            </tr>
                        <? endforeach; ?>
                    </tbody>
                </table>

                <? if ($this->model->entries > $this->model->limit): ?>
                    <nav>
                        <ul class="pagination">
                            <? for ($i=0; $i < ceil($this->model->entries / $this->model->limit); $i++): ?>
                                <li class="page-item page<?= $i+1 ?>">
                                    <a class="page-link page-num" data-page="<?= $i+1 ?>" href=""><?= $i+1 ?></a>
                                </li>
                            <? endfor; ?>
                        </ul>
                    </nav>
                <? endif ?>


            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>-->
<script>
    $(document).ready(function()
    {
        let params = {'page' : 1, 'sort' : 0, 'sort_by' : 0, 'filter_value' : 0, 'filter_by' : 0, 'filter_condition' : 0};
        let pagerLinks = $("ul.pagination li");

        function showMessage(msg, after_el)
        {
            $(after_el).after($('<p>', {
                class: 'message',
                text: msg
            }));
        }

        function updateTable()
        {
            let done = false;
            $.ajax({
                type: "POST",
                // contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                context: document.body,
                async: false,
                data: params,
                dataType: "html"
            }).done(function(msg) {

                let answ = JSON.parse(msg);

                $("p.message").remove();

                $("table#data-area tbody tr").remove();
                $.each(answ.data, function (index, row)
                {
                    $("table#data-area tbody").append("<tr><td>"+row[0]+"</td><td>"+row[1]+"</td><td>"+row[2]+"</td><td>"+row[3]+"</td></tr>");
                });

                pagerLinks.detach();
                if (answ.pager.pages > 1) for (var i = 0; i < answ.pager.pages; i++)
                {
                    $("ul.pagination").append(pagerLinks[i]);
                }

                if (typeof answ.message !== "undefined") showMessage(answ.message, 'table#data-area');

                if (params.filter_by === '' || params.filter_condition === '' || params.filter_value === '') showMessage('Укажите значения для поиска.', 'form');

                console.log(pagerLinks);
                done = true;
            }).fail(function(msg) {
                alert('Произошла ошибка.')
            });
            return done;
        }

        $(".page-link").click(function(e)
        {
            e.preventDefault();
            if ($(this).hasClass('page-num')) params.page = $(this).data('page');
            console.log(params);
            updateTable();
        });

        $("form button.filter").click(function(e)
        {
            e.preventDefault();
            params.filter_by = $("select.filter-field").val();
            params.filter_condition = $("select.filter-condition").val();
            params.filter_value = $("input.filter-value").val();
            params.page = 1;
            console.log(params);
            updateTable();
        });

        $("form button.clear").click(function(e)
        {
            e.preventDefault();
            params = {'page' : 1, 'sort' : 0, 'sort_by' : 0, 'filter_value' : 0, 'filter_by' : 0, 'filter_condition' : 0};
            $("th a").removeClass('asc').removeClass('desc');
            $('form')[0].reset();
            console.log(params);
            updateTable();
        });

        $("th a").click(function(e)
        {
            e.preventDefault();
            params.sort = ($(this).hasClass('asc')) ? 'desc' : 'asc';
            params.sort_by = $(this).parent().data('field');
            if (updateTable())
            {
                $("th a").not(this).removeClass('asc').removeClass('desc');
                if ($(this).hasClass('asc')) $(this).removeClass('asc').addClass('desc');
                else $(this).removeClass('desc').addClass('asc');
                console.log(params);
            }
        });
    });
</script>
</body>
</html>
