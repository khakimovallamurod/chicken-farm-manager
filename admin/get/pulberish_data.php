<?php
    include_once '../../config.php';
    $db = new Database();
    $query = "SELECT SUM(pb.summa) AS umumiy_summa, t.fio, t.kompaniya_nomi, t.telefon, t.balans, t.id, t.manzil
    FROM pul_berish pb 
    LEFT JOIN taminotchilar t ON pb.taminotchi_id = t.id
    GROUP BY t.id   
    ORDER BY umumiy_summa DESC;";
    $pul_berishlar = $db->query($query);

?>  
<table table id="pulberishTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>№</th>
            <th>Kompaniya nomi</th>
            <th>Taminotchi nomi</th>
            <th>Telefon</th>
            <th>Taminotchi balans</th>
            <th>Taminotchi manzil</th>
            <th>Umumiy berilgan summa</th>
            <th>Ko'rish</th>
        </tr>
    </thead>
    <tbody>
        <?php $index = 1; while ($pulberish = mysqli_fetch_assoc($pul_berishlar)) { 
            $summa = (float)$pulberish['umumiy_summa'];
            $rowClass = $summa > 0 ? 'table-success' : 'table-danger';
            ?>
            <tr>
                <td><?= $index++ ?></td>
                <td><?= htmlspecialchars($pulberish['kompaniya_nomi']) ?></td>
                <td><?= htmlspecialchars($pulberish['fio']) ?></td>
                <td><?= htmlspecialchars($pulberish['telefon']) ?></td>
                <td>
                    <?php
                    $balans = (float)$pulberish['balans'];
                    echo $balans <= 0
                        ? '<span class="badge bg-danger">0 so‘m</span>'
                        : '<span class="badge bg-primary">' . number_format($balans, 0, '.', ' ') . ' so‘m</span>';
                    ?>
                </td>
                <td><?= htmlspecialchars($pulberish['manzil']) ?></td>
                <td><strong><?= number_format($summa, 0, '.', ' ') ?> so‘m</strong></td>
                <td>
                    <button class="btn btn-sm btn-outline-warning" title="Ko‘rish" onclick="viewDetailsPulBerish(<?= $pulberish['id'] ?>)">
                        👁️
                    </button>
                </td>
            </tr>
        <?php }; ?>
    </tbody>
</table>