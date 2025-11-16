<table id="yemBerishTable" class="table table-hover">
    <thead>
        <tr>
            <th><i class="fas fa-home me-1"></i>Katak</th>
            <th><i class="fas fa-seedling me-1"></i>Yem turi</th>
            <th><i class="fas fa-weight-hanging me-1"></i>Miqdori</th>
            <th><i class="fas fa-calendar me-1"></i>Sana</th>
            <th><i class="fas fa-comment me-1"></i>Izoh</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include_once '../../config.php';
        $db = new Database();
        $query_for_oj = "
            SELECT yb.id AS yem_id, yb.sana, yb.miqdori, yb.izoh, k.katak_nomi, m.nomi AS mahsulot_nomi 
            FROM yem_berish yb 
            INNER JOIN kataklar k ON yb.katak_id = k.id 
            INNER JOIN mahsulotlar m ON yb.mahsulot_id = m.id 
            ORDER BY yb.sana DESC;
        ";
        $fetch = $db->query($query_for_oj);
        while ($joja_row = mysqli_fetch_assoc($fetch)) { ?>
            <tr>
                <td><span class="badge-katak"><?= htmlspecialchars($joja_row['katak_nomi']) ?></span></td>
                <td><?= htmlspecialchars($joja_row['mahsulot_nomi']) ?></td>
                <td><span class="miqdor-badge"><?= htmlspecialchars($joja_row['miqdori']) ?> kg</span></td>
                <td data-order="<?= $joja_row['sana'] ?>"><?= date('d.m.Y', strtotime($joja_row['sana'])) ?></td>
                <td><?= htmlspecialchars($joja_row['izoh']) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        var table_yem = $('#yemBerishTable').DataTable({
            order: [[3, 'desc']],
            dom: 'Bfrtip',   // 🔥 EXPORT BUTTONLAR UCHUN SHAR
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel yuklab olish',
                    title: 'Yem berish'
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV yuklab olish',
                    title: 'Yem berish'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF yuklab olish',
                    title: 'Yem berish',
                    orientation: 'landscape',
                    pageSize: 'A4'
                },
                {
                    extend: 'print',
                    text: 'Chop etish',
                    title: 'Yem berish'
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
        function filterByDateRangeYem(settings, data, dataIndex) {
            var start = $('#startDate_yem').val();
            var end = $('#endDate_yem').val();
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

        $('#filterByDate_yem').on('click', function () {
            $.fn.dataTable.ext.search = []; 
            $.fn.dataTable.ext.search.push(filterByDateRangeYem);
            table_yem.draw();
        });

        $('#clearFilter_yem').on('click', function () {
            $('#startDate_yem').val('');
            $('#endDate_yem').val('');
            $.fn.dataTable.ext.search = []; 
            table_yem.draw();
        });        
    });

    
</script>