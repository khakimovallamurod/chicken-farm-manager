<?php
    include_once '../../config.php';
    $db = new Database();
    $query = "SELECT 
        k.id,
        k.sana,
        k.summa,
        k.izoh,
        t.kompaniya_nomi,
        t.fio,
        t.telefon,
        t.balans
    FROM kirimlar k
    LEFT JOIN taminotchilar t ON k.taminotchi_id = t.id ORDER BY k.sana DESC;";
    $kirimlar = $db->query($query);

?>
<table id="kirimTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>FIO</th>
            <th>Kompaniya Nomi</th>
            <th>Balans</th>
            <th>Telafon raqam</th>
            <th><i class="fas fa-calendar-alt me-1"></i>Sana</th>
            <th>Summa</th>
            <th>Izoh</th>
            <th>Ko'rish</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($kirim =  mysqli_fetch_assoc($kirimlar)){ ?>
            <tr>
                <td><?=$kirim['fio']?></td>
                <td><?=$kirim['kompaniya_nomi']?></td>
                <td><?= rtrim(rtrim(number_format($kirim['balans'], 2, '.', ' '), '0'), '.') ?></td>
                <td><?=$kirim['telefon']?></td>
                <td><?=$kirim['sana']?></td>                        
                <td><?= rtrim(rtrim(number_format($kirim['summa'], 2, '.', ' '), '0'), '.') ?></td>
                <td><?=$kirim['izoh']?></td> 
                <td>
                    <button class="btn btn-sm btn-outline-warning" title="Ko'rish" onclick="viewDetailsKirim(<?= $kirim['id'] ?>)">
                        👁️
                    </button>
                </td>                       
            </tr>
        <?php }; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        var table_kirimadd = $('#kirimTable').DataTable({
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
        function filterByDateRangeKirim(settings, data, dataIndex) {
            var start = $('#startDate_kirimadd').val();
            var end = $('#endDate_kirimadd').val();
            var dateStr = data[4]; 

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

        $('#filterByDate_kirimadd').on('click', function () {
            $.fn.dataTable.ext.search = []; 
            $.fn.dataTable.ext.search.push(filterByDateRangeKirim);
            table_kirimadd.draw();
        });

        $('#clearFilter_kirimadd').on('click', function () {
            $('#startDate_kirimadd').val('');
            $('#endDate_kirimadd').val('');
            $.fn.dataTable.ext.search = []; 
            table_kirimadd.draw();
        });      
    });
</script>