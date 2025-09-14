<?php
    include_once '../../config.php';
    $db = new Database();
    $query_for_oj = "SELECT oj.*, k.katak_nomi AS katak_nomi
        FROM olgan_jojalar oj
        JOIN kataklar k ON oj.katak_id = k.id ORDER BY sana DESC";
    $fetch = $db->query($query_for_oj);
    
?>
<table id="olganJojalarTable" class="table table-bordered table-hover align-middle text-center">
    <thead class="table-secondary">
        <tr>
            <th><i class="fas fa-home me-1"></i>Katak nomi</th>
            <th><i class="fas fa-minus-circle me-1"></i>Soni</th>
            <th><i class="fas fa-calendar-times me-1"></i>O'lgan sana</th>
            <th><i class="fas fa-comment-dots me-1"></i>Izoh</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($olgan_joja = mysqli_fetch_assoc($fetch)) { ?>                    
            <tr>
                <td>
                    <span class="badge bg-dark"><?= htmlspecialchars($olgan_joja['katak_nomi']) ?></span>
                </td>
                <td>
                    <span class="badge bg-danger"><?= htmlspecialchars($olgan_joja['soni']) ?> dona</span>
                </td>
                <td data-order="<?= $olgan_joja['sana'] ?>">
                    <?= date('d.m.Y', strtotime($olgan_joja['sana'])) ?>
                </td>
                <td><?= htmlspecialchars($olgan_joja['izoh']) ?></td>                        
            </tr>
        <?php } ?>
    </tbody>
</table>
<script>
    $(document).ready(function () {
        var table_killed = $('#olganJojalarTable').DataTable({
            order: [[2, 'desc']], 
            language: {
                search: "Qidiruv:",
                lengthMenu: "Har sahifada _MENU_ ta yozuv ko‘rsatiladi",
                info: "Jami _TOTAL_ tadan _START_–_END_ ko‘rsatilmoqda",
                paginate: {
                    first: "Birinchi",
                    last: "Oxirgi",
                    next: "Keyingi",
                    previous: "Oldingi"
                },
                zeroRecords: "Hech narsa topilmadi",
                infoEmpty: "Ma’lumot yo‘q",
                infoFiltered: "(umumiy _MAX_ yozuvdan filtrlandi)"
            }
        });
        function filterByDateRangeKilled(settings, data, dataIndex) {
            var start = $('#startDate_kill').val();
            var end = $('#endDate_kill').val();
            var dateStr = data[2]; 

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

        $('#filterByDate_kill').on('click', function () {
            $.fn.dataTable.ext.search = []; 
            $.fn.dataTable.ext.search.push(filterByDateRangeKilled);
            table_killed.draw();
        });

        $('#clearFilter_kill').on('click', function () {
            $('#startDate_kill').val('');
            $('#endDate_kill').val('');
            $.fn.dataTable.ext.search = []; 
            table_killed.draw();
        });        
    });
</script>