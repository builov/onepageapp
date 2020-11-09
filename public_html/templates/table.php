<? if ($this->model->entries > 0): ?>
    <tbody data-entries="<?= $this->model->entries ?>">
    <? foreach ($this->data as $row): ?>
        <tr>
            <td><?= $row['date'] ?></td>
            <td><?= $row['title'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['dist'] ?></td>
        </tr>
    <? endforeach; ?>
    </tbody>
<? else: ?>
    <tbody data-entries="0">
        <tr>
            <td colspan="4" class="bg-white"><?= $this->data ?></td>
        </tr>
    </tbody>
<? endif; ?>
