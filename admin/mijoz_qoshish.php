
<section id="mijozqoshish" class="content-section">
    <div class="section-header">
        <h2 class="section-title">👨‍💼 Mijoz qo'shish</h2>
        <div style="margin-top: 1rem;">
            <button id="toggleMijozViewBtn" class="btn btn-outline-success">📋 Jadval ko‘rinishini ko‘rsatish</button>
        </div>
    </div>
    <div id="mijozFormSection">
        <form id="mijozQoshishForm" onsubmit="addMijozQoshish(event)">
            <div class="form-grid">
                <div class="form-group">
                    <label>Mijoz ismi:</label>
                    <input type="text" id="mijoz_ismi" name="mijoz_ismi" required placeholder="Masalan: Rustam Abdullayev">
                </div>
                <div class="form-group">
                    <label>Telefon raqami:</label>
                    <input type="tel" id="mijoz_telefon" name="mijoz_telefon" required placeholder="+998 90 123 45 67">
                </div>
                <div class="form-group">
                    <label>Manzil:</label>
                    <input type="text" id="mijoz_manzil" name="mijoz_manzil" placeholder="Masalan: Toshkent, Chilonzor tumani">
                </div>
            </div>
            <div class="form-group">
                <label>Izoh:</label>
                <textarea id="mijoz_izoh" name="mijoz_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
            </div>
            <button type="submit" class="btn btn-success">👨‍💼 Mijoz qo'shish</button>
        </form>
    </div>
    
    <?php
        include_once '../config.php';
        $db = new Database();
        $kataklar = $db->get_data_by_table_all('mijozlar', "ORDER BY created_at DESC");        
    ?>
    <div class="table-container" id="mijozTableSection" style="display: none;">
        <h3>Mijozlar ro'yxati</h3>
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="min-date" class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i>Boshlanish sanasi
                    </label>
                    <input type="date"  id="startDate" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="max-date" class="form-label">
                        <i class="fas fa-calendar-check me-1"></i>Tugash sanasi
                    </label>
                    <input type="date" id="endDate"  class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <button id="filterByDate" class="btn-professional btn-info me-2">🔍 Filterlash</button>
                    <button id="clearFilter" class="btn-professional btn-secondary">❌ Tozalash</button>
                </div>
            </div>
        </div>
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
    </div>
</section>
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        const toggleMijoznBtn = $('#toggleMijozViewBtn');
        const mijozFormSection = $('#mijozFormSection');
        const mijozTableSection = $('#mijozTableSection');

        toggleMijoznBtn.on('click', function () {
            const isFormVisible = mijozFormSection.is(':visible');
            mijozFormSection.toggle(!isFormVisible);
            mijozTableSection.toggle(isFormVisible);
            toggleMijoznBtn.html(isFormVisible 
                ? '➕ Forma ko‘rinishini ko‘rsatish' 
                : '📋 Jadval ko‘rinishini ko‘rsatish');
        });

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

        $('#mijozQoshishForm').on('submit', function (event) {
            event.preventDefault();
            const data = {
                ismi: $('#mijoz_ismi').val(),
                telefon: $('#mijoz_telefon').val(),
                manzil: $('#mijoz_manzil').val(),
                izoh: $('#mijoz_izoh').val()
            };
            
            $.ajax({
                url: '../form_insert_data/add_mijoz.php',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                dataType: 'json',
                success: function (result) {
                    if (result.success) {
                        showAlert(result.message, 'success');
                    } else {
                        showAlert(result.message, 'error');
                    }
                    $('#mijozQoshishForm')[0].reset();
                },
                error: function () {
                    swal({
                        title: "Xatolik!",
                        text: "Server bilan bog'lanishda xatolik yuz berdi.",
                        icon: "error",
                        button: "OK",
                    });
                }
            });
        });
    });

</script>
