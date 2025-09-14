<?php
    include_once '../../config.php';
    $db = new Database();
    $kataklar = $db->get_data_by_table_all('mijozlar', "ORDER BY created_at DESC");        
?>
<table id="mijozlarTable" class="display table table-bordered table-hover align-middle text-center">
    <thead>
        <tr>
            <th>FIO</th>
            <th>Balans</th>
            <th>Telafon raqam</th>
            <th>Manzil</th>
            <th>Ro'yxatdan o'tdi</th>
            <th>Izoh</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($kataklar as $katak): ?>
            <tr>
                <td><?=$katak['mijoz_nomi']?></td>
                <td><?=rtrim(rtrim(number_format($katak['balans'], 2, '.', ' '), '0'), '.')?></td>
                <td><?=$katak['mijoz_tel']?></td>
                <td><?=$katak['mijoz_address']?></td>
                <td><?= date('Y-m-d', strtotime($katak['created_at'])) ?></td>                        
                <td><?=$katak['mijoz_izoh']?></td>                        
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    var table = $('#mijozlarTable').DataTable({
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

    function filterByDateRange(settings, data, dataIndex) {
        var start = $('#startDate').val();
        var end = $('#endDate').val();
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

    $('#filterByDate').on('click', function () {
        $.fn.dataTable.ext.search = []; 
        $.fn.dataTable.ext.search.push(filterByDateRange);
        table.draw();
    });

    $('#clearFilter').on('click', function () {
        $('#startDate').val('');
        $('#endDate').val('');
        $.fn.dataTable.ext.search = []; 
        table.draw();
    });
</script>