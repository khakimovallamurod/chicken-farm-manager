<?php
    include_once '../../config.php';
    $db = new Database();
    $gosht_soyishlar = $db->get_data_by_table_all('gosht_soyish');
?>
<table id="topshirishlarTable" class="table table-hover align-middle text-center">
    <thead>
        <tr>
            <th><i class="fas fa-home me-1"></i>Katak</th>
            <th><i class="fas fa-dove me-1"></i>Jo'jalar massasi</th>
            <th><i class="fas fa-dove me-1"></i>Jo'jalar soni</th>
            <th><i class="fas fa-calendar-alt me-1"></i>Sana</th>
            <th><i class="fas fa-comment me-1"></i>Izoh</th>
            <th><i class="fas fa-eye me-1"></i>Ko'rish</th>
            <th><i class="fas fa-plus me-1"></i>Qo'shish</th>
        </tr>
    </thead>
    <tbody id="goshtTopshirishTable">
        <?php foreach ($gosht_soyishlar as $soyish): 
            $katak_id = $soyish['katak_id'];
            $kataklar = $db->get_data_by_table('kataklar', ['id' => $katak_id]);
            $katak_name = $kataklar['katak_nomi'];
        ?>
        <tr id="<?= $soyish['id'] ?>">
            <td>
                <span class="badge-katak">
                    <?= htmlspecialchars($katak_name) ?>
                </span>
            </td>
            <td>
                <span class="badge bg-success">
                    <?= htmlspecialchars($soyish['massasi']) ?> kg
                </span>
            </td>
            <td>
                <span class="badge bg-success">
                    <?= htmlspecialchars($soyish['joja_soni']) ?> dona
                </span>
            </td>
            <td data-order="<?= $soyish['sana'] ?>">
                <?= date('d.m.Y', strtotime($soyish['sana'])) ?>
            </td>
            <td><?= htmlspecialchars($soyish['izoh']) ?></td>
            <td>
                <button class="btn btn-sm btn-outline-warning" title="Ko'rish" onclick="viewDetails(<?= $soyish['id'] ?>)">
                    👁️
                </button>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary" title="Qo'shish" onclick="showAddProductForRow(<?= $soyish['id'] ?>)">
                    ➕
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function () {
            var table_meal = $('#topshirishlarTable').DataTable({
                responsive: true,
                order: [[2, 'desc']], 
                language: {
                    search: "Qidiruv:",
                    lengthMenu: "Har sahifada _MENU_ ta yozuv",
                    info: "Jami _TOTAL_ ta yozuvdan _START_–_END_ ko‘rsatilmoqda",
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
            function filterByDateRangeMeal(settings, data, dataIndex) {
                var start = $('#startDate_meal').val();
                var end = $('#endDate_meal').val();
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

            $('#filterByDate_meal').on('click', function () {
                $.fn.dataTable.ext.search = []; 
                $.fn.dataTable.ext.search.push(filterByDateRangeMeal);
                table_meal.draw();
            });

            $('#clearFilter_meal').on('click', function () {
                $('#startDate_meal').val('');
                $('#endDate_meal').val('');
                $.fn.dataTable.ext.search = []; 
                table_meal.draw();
            });        
        });
</script>