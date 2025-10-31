<?php
include_once '../../config.php';
$db = new Database();
$taminotchilar = $db->get_data_by_table_all('taminotchilar', "ORDER BY created_at DESC");
?>
<div id="taminotchiGridView" class="kataklar-grid">
    <?php foreach ($taminotchilar as $taminotchi): ?>
    <div class="katak-card">
        <div class="katak-header">
            <div class="katak-title">🏢 <?= htmlspecialchars($taminotchi['kompaniya_nomi']) ?></div>
            <span class="katak-status status-active">Faol</span>
        </div>
        <div class="katak-info">
            <div class="info-item">
                <div class="info-value">0</div> 
                <div class="info-label">Buyurtmalar</div>
            </div>
            <div class="info-item">
                <div class="info-value"><?= rtrim(rtrim(number_format($taminotchi['balans'], 2, '.', ' '), '0'), '.') ?></div>
                <div class="info-label">balans</div>
            </div>
        </div>
        <p><strong>FIO:</strong> <?= htmlspecialchars($taminotchi['fio']) ?></p>
        <p><strong>Mahsulotlar:</strong> <?= htmlspecialchars($taminotchi['mahsulotlar']) ?></p>
        <p><strong>Telefon:</strong> <?= htmlspecialchars($taminotchi['telefon']) ?></p>
    </div>
    <?php endforeach; ?>
</div>
<div id="taminotchiTableView" style="display: none;">
    <table id="taminotchiTable" class="display">
        <thead>
            <tr>
                <th>Kompaniya nomi</th>
                <th>Buyurtmalar</th>
                <th>Balans</th>
                <th>FIO</th>
                <th>Mahsulotlar</th>
                <th>Telefon</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($taminotchilar as $taminotchi): ?>
            <tr>
                <td><?= htmlspecialchars($taminotchi['kompaniya_nomi']) ?></td>
                <td>0</td>
                <td><?= rtrim(rtrim(number_format($taminotchi['balans'], 2, '.', ' '), '0'), '.') ?></td>
                <td><?= htmlspecialchars($taminotchi['fio']) ?></td>
                <td><?= htmlspecialchars($taminotchi['mahsulotlar']) ?></td>
                <td><?= htmlspecialchars($taminotchi['telefon']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#taminotchiTable').DataTable({
            "paging": true,        
            "searching": true,     
            "ordering": true,      
            "order": [[0, "asc"]],
            "info": true,      
            "lengthMenu": [5, 10, 25, 50, 100], // ko'rsatish soni
            "language": {
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