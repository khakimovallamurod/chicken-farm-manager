<?php
    include_once '../../config.php';
    $db = new Database();
    $qury = "SELECT m.id AS mijoz_id, m.mijoz_nomi, m.mijoz_tel, m.balans, m.mijoz_address, SUM(po.summa) AS umumiy_summa 
    FROM pul_olish po 
    LEFT JOIN mijozlar m ON po.mijoz_id = m.id 
    GROUP BY m.id, m.mijoz_nomi, m.mijoz_tel, m.balans 
    ORDER BY umumiy_summa DESC;";
    $pul_olishlar = $db->query($qury);

?>  
<table table id="pulolishTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>№</th>
            <th>Mijoz</th>
            <th>Telefon</th>
            <th>Mijoz balans</th>
            <th>Mijoz manzil</th>
            <th>Umumiy olingan summa</th>
            <th>Ko'rish</th>
        </tr>
    </thead>
    <tbody>
        <?php $index = 1; while ($pulolish = mysqli_fetch_assoc($pul_olishlar)) { 
            $summa = (float)$pulolish['umumiy_summa'];
            $rowClass = $summa > 0 ? 'table-success' : 'table-danger';
            ?>
            <tr>
                <td><?= $index++ ?></td>
                <td><?= htmlspecialchars($pulolish['mijoz_nomi']) ?></td>
                <td><?= htmlspecialchars($pulolish['mijoz_tel']) ?></td>
                <td>
                    <?php
                    $balans = (float)$pulolish['balans'];
                    echo $balans <= 0
                        ? '<span class="badge bg-danger">0 so‘m</span>'
                        : '<span class="badge bg-primary">' . number_format($balans, 0, '.', ' ') . ' so‘m</span>';
                    ?>
                </td>
                <td><?= htmlspecialchars($pulolish['mijoz_address']) ?></td>
                <td><strong><?= number_format($summa, 0, '.', ' ') ?> so‘m</strong></td>
                <td>
                    <button class="btn btn-sm btn-outline-warning" title="Ko‘rish" onclick="viewDetailsPulOlish(<?= $pulolish['mijoz_id'] ?>)">
                        👁️
                    </button>
                </td>
            </tr>
        <?php }; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        var table_pulolish = $('#pulolishTable').DataTable({
            order: [[3, 'desc']],
            dom: 'Bfrtip',   // 🔥 EXPORT BUTTONLAR UCHU
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel yuklab olish',
                    title: 'Pul olishlar'
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV yuklab olish',
                    title: 'Pul olishlar'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF yuklab olish',
                    title: 'Pul olishlar',
                    orientation: 'landscape',
                    pageSize: 'A4'
                },
                {
                    extend: 'print',
                    text: 'Chop etish',
                    title: 'Pul olishlar'
                }
            ],
            language:{
                "search": "Qidirish:",
                "lengthMenu": "Har bir sahifada _MENU_ ta",
                "info": "Jami _TOTAL_ ta taminotchidan _START_ dan _END_ gacha",
                "infoEmpty": "Ma’lumot yo‘q",
                "infoFiltered": "(filtrlangan _MAX_ ta ma’lumotdan)",
                "paginate": {
                    "first": "Birinchi",
                    "last": "Oxirgi",
                    "next": "Keyingi",
                    "previous": "Oldingi"
                },
            }
        });
    });
</script>