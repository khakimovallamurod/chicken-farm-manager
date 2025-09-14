<?php
    include_once '../../config.php';
    $db = new Database();
    
    $harajatlar = $db->get_data_by_table_all('harajatlar', 'ORDER BY sana DESC')
?>
<table id="harajatlarTable" class="display table table-bordered table-hover align-middle text-center">
    <thead>
        <tr>
            <th>Harajat turi</th>
            <th>To'lov turi</th>
            <th>Miqdor</th>
            <th>Sana</th>
            <th>Izoh</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($harajatlar as $harajat): 
            $harajat_nomi = $db->get_data_by_table('harajat_turlari', ['id'=>$harajat['harajat_turi_id']])['nomi'];
            $tolov_nomi = $db->get_data_by_table('tolov_birligi', ['id'=>$harajat['tolov_birlik_id']])['nomi'];
        ?>
        <tr>
            <td><?= htmlspecialchars($harajat_nomi) ?></td>
            <td><?= htmlspecialchars($tolov_nomi) ?></td>
            <td><?= rtrim(rtrim(number_format($harajat['miqdori'], 2, '.', ' '), '0'), '.') ?></td>
            <td><?= htmlspecialchars($harajat['sana']) ?></td>
            <td><?= htmlspecialchars($harajat['izoh']) ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        var table_xarajat = $('#harajatlarTable').DataTable({
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
        function filterByDateRangeXarajat(settings, data, dataIndex) {
            var start = $('#startDate_xarajat').val();
            var end = $('#endDate_xarajat').val();
            var dateStr = data[3]; 

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

        $('#filterByDate_xarajat').on('click', function () {
            $.fn.dataTable.ext.search = []; 
            $.fn.dataTable.ext.search.push(filterByDateRangeXarajat);
            table_xarajat.draw();
        });
        $('#clearFilter_xarajat').on('click', function () {
            $('#startDate_xarajat').val('');
            $('#endDate_xarajat').val('');
            $.fn.dataTable.ext.search = []; 
            table_xarajat.draw();
        });        
    });
</script>