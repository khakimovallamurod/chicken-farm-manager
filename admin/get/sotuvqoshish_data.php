<?php
include_once '../../config.php';
$db = new Database();
$query = "SELECT 
    s.id,
    s.sana,
    s.summa,
    s.izoh,
    m.mijoz_nomi,
    m.mijoz_tel,
    m.balans
    FROM sotuvlar s
    LEFT JOIN mijozlar m ON s.mijoz_id=m.id ORDER BY s.sana DESC;";
$sotuvlar = $db->query($query);
?>                   
<table id="sotuvTable" class="table table-bordered table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>№</th>
            <th>Sana</th>
            <th>Mijoz</th>
            <th>Telefon</th>
            <th>Balans</th>
            <th>Jami summa</th>
            <th>Izoh</th>
            <th>Ko'rish</th>
        </tr>
    </thead>
    <tbody>
        <?php $index = 1; while ($sotuv = mysqli_fetch_assoc($sotuvlar)) { ?>
            <tr>
                <td><?= $index++ ?></td>
                <td><?= htmlspecialchars($sotuv['sana']) ?></td>
                <td><?= htmlspecialchars($sotuv['mijoz_nomi']) ?></td>
                <td><?= htmlspecialchars($sotuv['mijoz_tel']) ?></td>
                <td><?= rtrim(rtrim(number_format($sotuv['balans'], 2, '.', ' '), '0'), '.') ?></td>
                <td><?= rtrim(rtrim(number_format($sotuv['summa'], 2, '.', ' '), '0'), '.') ?></td>
                <td><?= htmlspecialchars($sotuv['izoh']) ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-warning" title="Ko‘rish" onclick="viewDetailsSotuv(<?= $sotuv['id'] ?>)">
                        👁️
                    </button>
                </td>
            </tr>
        <?php }; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        var table_sotuv = $('#sotuvTable').DataTable({
            order: [[1, 'desc']],
            dom: 'Bfrtip',   // 🔥 EXPORT BUTTONLAR UCHU
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel yuklab olish',
                    title: 'Sotuvlar'
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV yuklab olish',
                    title: 'Sotuvlar'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF yuklab olish',
                    title: 'Sotuvlar',
                    orientation: 'landscape',
                    pageSize: 'A4'
                },
                {
                    extend: 'print',
                    text: 'Chop etish',
                    title: 'Sotuvlar'
                }
            ],
            language: {
                "lengthMenu": "Har sahifada _MENU_ ta yozuv ko‘rsatilsin",
                "zeroRecords": "Hech qanday ma'lumot topilmadi",
                "info": "Jami _TOTAL_ ta yozuvdan _START_–_END_ ko‘rsatilmoqda",
                "infoEmpty": "Ma'lumot yo‘q",
                "infoFiltered": "(_MAX_ ta umumiy yozuvdan filtrlandi)",
                "search": "Qidiruv:",
                "paginate": {
                    "first": "Birinchi",
                    "last": "Oxirgi",
                    "next": "Keyingi",
                    "previous": "Oldingi"
                }
            }
        });
        function filterByDateRangeSotuv(settings, data, dataIndex) {
            var start = $('#startDate_sotuv').val();
            var end = $('#endDate_sotuv').val();
            var dateStr = data[1]; 

            if (!start && !end) {
                return true;
            }
            var parts = dateStr.split('.');
            var convertedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            var rowDate = new Date(convertedDate);
            if (start) start = new Date(start);
            if (end) end = new Date(end);

            return (!start || rowDate >= start) && (!end || rowDate <= end);
        }

        $('#filterByDate_sotuv').on('click', function () {
            $.fn.dataTable.ext.search = []; 
            $.fn.dataTable.ext.search.push(filterByDateRangeSotuv);
            table_sotuv.draw();
        });

        $('#clearFilter_sotuv').on('click', function () {
            $('#startDate_sotuv').val('');
            $('#endDate_sotuv').val('');
            $.fn.dataTable.ext.search = []; 
            table_sotuv.draw();
        }); 
    });
</script>