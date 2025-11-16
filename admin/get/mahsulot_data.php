<style>
    .dataTables_wrapper {
        width: 100% !important;
    }

    #mahsulotlarTable {
        width: 100% !important;
        table-layout: auto;
    }
    #mahsulotlarTable th,
    #mahsulotlarTable td {
        white-space: nowrap;  
        text-align: left;
    }
</style>
<div id="gridView" class="kataklar-grid">  
    <?php
    include_once '../../config.php';
    $db = new Database();
    $query = "SELECT
        m.id,
        m.nomi,
        k.nomi AS categoriya_nomi,
        b.nomi AS birlik_nomi,
        m.narxi,
        COALESCE(mz.soni, 0) AS soni,
        m.tavsif,
        m.created_at               
    FROM mahsulotlar m
    LEFT JOIN categoriya k ON m.categoriya_id = k.id
    LEFT JOIN birliklar b ON m.birlik_id = b.id
    LEFT JOIN mahsulot_zahirasi mz ON m.id = mz.mahsulot_id;";

    $mahsulotlar = $db->query($query);

    foreach ($mahsulotlar as $mahsulot): ?>
        <div class="katak-card">
            <div class="katak-header">
                <div class="katak-title"><?= $mahsulot['nomi'] ?></div>
                <span class="katak-status status-active"><?= $mahsulot['categoriya_nomi'] ?></span>
            </div>
            <div class="katak-info">
                <div class="info-item">
                    <div class="info-value"><?= rtrim(rtrim(number_format($mahsulot['soni'], 2, '.', ' '), '0'), '.')?></div>
                    <div class="info-label"><?= $mahsulot['birlik_nomi'] ?></div>
                </div>
                <div class="info-item">
                    <div class="info-value"><?= rtrim(rtrim(number_format($mahsulot['narxi'], 2, '.', ' '), '0'), '.')?></div>
                    <div class="info-label">so'm/<?= $mahsulot['birlik_nomi'] ?></div>
                </div>
            </div>
            <p><?= $mahsulot['tavsif'] ?></p>
            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                <button class="btn btn-primary" style="font-size: 0.8rem;" onclick="editMahsulot(<?= $mahsulot['id'] ?>)">✏️ Tahrirlash</button>
                <button class="btn btn-danger" style="font-size: 0.8rem;" onclick="updateStock(<?= $mahsulot['id'] ?>)">❌ O'chirish</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div id="tableView" style="display: none;">
    <table id="mahsulotlarTable" class="display">
        <thead>
            <tr>
                <th>Nomi</th>
                <th>Categoriya</th>
                <th>Soni</th>
                <th>Birlik</th>
                <th>Narxi</th>
                <th>Tavsif</th>
                <th>Amallar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mahsulotlar as $m): ?>
            <tr>
                <td><?= $m['nomi'] ?></td>
                <td><?= $m['categoriya_nomi'] ?></td>
                <td><?= rtrim(rtrim(number_format($m['soni'], 2, '.', ' '), '0'), '.')?></td>
                <td><?= $m['birlik_nomi'] ?></td>
                <td><?= rtrim(rtrim(number_format($m['narxi'], 2, '.', ' '), '0'), '.')?></td>
                <td><?= $m['tavsif'] ?></td>
                <td>
                    <button class="btn btn-primary" onclick="editMahsulot(<?= $m['id'] ?>)">✏️ Tahrirlash</button>
                    <button class="btn btn-danger" onclick="updateStock(<?= $m['id'] ?>)">❌ O'chirish</button>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        let dataTableInitialized = false;
        $("#toggleViewBtn").click(function() {
            const grid = $("#gridView");
            const tableView = $("#tableView");

            if (grid.is(":visible")) {
                // Card -> Table
                grid.hide();
                tableView.show();

                if (!dataTableInitialized) {
                    $('#mahsulotlarTable').DataTable({
                        destroy: true,
                        autoWidth: false,
                        responsive: true
                    });
                    dataTableInitialized = true;
                }

                $(this).text("📦 Katak ko‘rinishini ko‘rsatish");
            } else {
                // Table -> Card
                tableView.hide();
                grid.show();

                $(this).text("📋 Jadval ko‘rinishini ko‘rsatish");
            }
        });
        $('#mahsulotlarTable').DataTable({
            "paging": true,        
            "searching": true,     
            "ordering": true,      
            "order": [[0, "asc"]],
            "info": true,      
            "lengthMenu": [5, 10, 25, 50, 100], // ko'rsatish soni
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel yuklab olish',
                    title: 'Mahsulotlar'
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV yuklab olish',
                    title: 'Mahsulotlar'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF yuklab olish',
                    title: 'Mahsulotlar',
                    orientation: 'landscape',
                    pageSize: 'A4'
                },
                {
                    extend: 'print',
                    text: 'Chop etish',
                    title: 'Mahsulotlar'
                }
            ],
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
