<?php

require_once('include.php');

$pdo = get_pdo();
$res = $pdo->query('SELECT * FROM orders ORDER BY id ASC', PDO::FETCH_ASSOC);
$rows = $res->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.rawgit.com/TeaMeow/TocasUI/2.3.2/dist/tocas.css" rel='stylesheet'>
    <script src="https://cdn.rawgit.com/TeaMeow/TocasUI/2.3.2/dist/tocas.js"></script>
    <title>訂單列表</title>
    <?php
        require_once('frontend_lib.php')
    ?>
    <style type="text/css">
        body {
            padding: 70px 0;
        }
    </style>
    <script>
        function show_edit_btn(id) {
            let status_btn = document.getElementById('status-btn-'+id.toString());
            let edit_btn = document.getElementById('edit-btn-'+id.toString());
            status_btn.style.display = 'none';
            edit_btn.style.display = 'block';
        }

        function do_edit(id) {
            let status_btn = document.getElementById('status-btn-'+id.toString());
            let edit_btn = document.getElementById('edit-btn-'+id.toString());
            let select_btn = edit_btn.children[0];
            let selected = select_btn.selectedOptions[0];
            fetch('update.php', {
                method: 'POST',
                body: 'id=' + id + '&column=status&status=' + selected.value,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(response => {
                return response.text();
            }).then(text => {
                console.log(text);
            });
            status_btn.innerText = selected.innerText;
            status_btn.style.display = 'block';
            edit_btn.style.display = 'none';
        }

        function do_cancel(id) {
            let status_btn = document.getElementById('status-btn-'+id.toString());
            let edit_btn = document.getElementById('edit-btn-'+id.toString());
            status_btn.style.display = 'block';
            edit_btn.style.display = 'none';
        }

        function do_note(id) {
            let note = document.getElementById('note-'+id.toString());
            let original_note = document.getElementById('original-note-'+id.toString());
            original_note.value = note.value;
            fetch('update.php', {
                method: 'POST',
                body: 'id=' + id + '&column=note&note=' + encodeURIComponent(note.value),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(response => {
                return response.text();
            }).then(text => {
                console.log(text);
            });
            ts('#modal-' + id.toString()).modal('hide');
        }
    </script>
</head>
<body>
    <div class="ts top fixed inverted borderless large menu">
        <div class="ts narrow container">
            <a href="index.php" class="item">修改商品描述</a>
            <a href="orders.php" class="item">訂單列表</a>
            <a href="logout.php" class="right item">登出</a>
        </div>
    </div>

    <div class="ts narrow container">
        <div class="sixteen wide column">
            <h3 class="ts header">
                訂單列表
            </h3>
            <br />
        </div>
    </div>

    <div class="ts narrow container">
            <div class="sixteen wide column">
                <div class="ts top attached info segment">
                    <div class="ts large header">訂單列表</div>
                </div>
                <div class="ts attached segment">
                    <table class="ts very basic table">
                        <thead>
                            <tr>
                                <th>訂單編號</th>
                                <th>訂購人</th>
                                <th>電話</th>
                                <th>補充貨態資訊</th>
                                <th>貨態</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($rows as $row): ?>
                                <tr>
                                    <td>#<?=$row['id'] ?></td>
                                    <td><?=htmlspecialchars($row['id']) ?></td>
                                    <td><?=htmlspecialchars($row['id']) ?></td>
                                    <td>
                                        <button onclick="ts('#modal-<?=$row['id'] ?>').modal('show')" class="ts button">修改貨態資訊</button>
                                        <div class="ts modals dimmer">
                                            <dialog id="modal-<?=$row['id'] ?>" class="ts closable tiny modal">
                                                <div class="header">
                                                    貨態資訊
                                                </div>
                                                <div class="content">
                                                    <div class="ts fluid input">
                                                        <textarea id="note-<?=$row['id'] ?>" placeholder="無" rows="20"><?=htmlspecialchars($row['note']) ?></textarea>
                                                        <textarea id="original-note-<?=$row['id'] ?>" style="display: none"><?=htmlspecialchars($row['note']) ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="actions">
                                                    <button class="ts positive button" onclick="do_note(<?=$row['id'] ?>)">
                                                        儲存
                                                    </button>
                                                    <button class="ts negative button" onclick="document.getElementById('note-<?=$row['id'] ?>').value = document.getElementById('original-note-<?=$row['id'] ?>').value">
                                                        取消並還原
                                                    </button>
                                                </div>
                                            </dialog>
                                        </div>
                                    </td>
                                    <td>
                                        <button id="status-btn-<?=$row['id'] ?>" class="ts info button" onclick="show_edit_btn(<?=$row['id'] ?>)"><?=order_status_to_text($row['status']) ?></button>
                                        <div id="edit-btn-<?=$row['id'] ?>" style="display: none">
                                            <select class="ts basic dropdown">
                                                <?php foreach([
                                                    ORDER_STATUS_PICKING, ORDER_STATUS_PACKING, ORDER_STATUS_SENDING,
                                                    ORDER_STATUS_DELIVERING, ORDER_STATUS_ARRIVED, ORDER_STATUS_FINISH
                                                ] as $status): ?>
                                                    <option <?=$row['status'] == $status ? 'selected' : '' ?> value="<?=$status ?>"><?=order_status_to_text($status) ?></option>
                                                <?php endforeach;?>
                                            </select>
                                            <div class="ts buttons">
                                                <button class="ts positive mini button" onclick="do_edit(<?=$row['id'] ?>)"><i class="mini check icon"></i></button>
                                                <button class="ts negative mini button" onclick="do_cancel(<?=$row['id'] ?>)"><i class="mini cancel icon"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</html>