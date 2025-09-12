<?php
include_once '../config.php';
$db = new Database();
$taminotchilar = $db->get_data_by_table_all('taminotchilar');
$mahsulotlar = $db->get_data_by_table_all('mahsulotlar');
?>  

<section id="kirim" class="content-section">
    <div class="section-header">
        <h2 class="section-title">📥 Kirimlar</h2>
        <div style="margin-top: 1rem;">
            <button id="toggleKirimViewBtn" class="btn btn-outline-success">📋 Jadval ko‘rinishini ko‘rsatish</button>
        </div>
    </div>
    <form id="kirimForm" onsubmit="addKirim(event)">
        <div class="form-grid">
            <div class="form-group">
                <label>Taminotchini tanlang:</label>    
                <select id="kirim_taminotchi" required>
                    <option value="">Taminotchini tanlang</option>
                    <?php foreach ($taminotchilar as $taminotchi): ?>
                        <option value="<?= $taminotchi['id'] ?>">
                            <?= $taminotchi['kompaniya_nomi'] ?> (<?= $taminotchi['fio'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Sana:</label>
                <input type="date" id="kirim_sana" required> 
            </div>
        </div>
        <div id="kirim-mahsulotlar-wrapper">
            <div class="product-row">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Mahsulotni tanlangan:</label>
                        <select name="kategoriya[]" required>
                            <option value="">Mahsulotni tanlangan</option>
                            <?php foreach ($mahsulotlar as $mahsulot): ?>
                                <option value="<?= $mahsulot['id'] ?>">
                                    <?= $mahsulot['nomi'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Miqdori:</label>
                        <input type="number" name="miqdor[]" placeholder="Miqdori" required min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Narxi (so'm):</label>
                        <input type="number" name="narx[]" placeholder="Narxi (so'm)" required min="0" step="1">
                    </div>                                    
                </div>
            </div>
        </div>                        
        <!-- ➕ Qo'shish tugmasi -->
        <button type="button" class="add-product-btn" id="addKirimProductBtn" onclick="addKirimProduct()">
            ➕ Mahsulot qo'shish
        </button>
        <div class="form-group">
            <label>Izoh:</label>
            <textarea id="kirim_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
        </div>
        <button type="submit" class="btn btn-success">📥 Kirim qo'shish</button>
    </form>
    <?php
        include_once '../config.php';
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
    <div class="table-container" id="kirimTableSection" style="display: none;">
        <h3>Kirimlar ro'yxati</h3>
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="min-date" class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i>Boshlanish sanasi
                    </label>
                    <input type="date"  id="startDate_kirimadd" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="max-date" class="form-label">
                        <i class="fas fa-calendar-check me-1"></i>Tugash sanasi
                    </label>
                    <input type="date" id="endDate_kirimadd"  class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <button id="filterByDate_kirimadd" class="btn-professional btn-info">🔍 Filterlash</button>
                    <button id="clearFilter_kirimadd" class="btn-professional btn-secondary">❌ Tozalash</button>
                </div>
            </div>
        </div>
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
    </div>
</section>
<div id="historyKirimModal" class="modal">
    <div class="modal-content">
        <div id="historyContent">
            
        </div>
    </div>
</div>
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
    function viewDetailsKirim(rowId) {
        const modal = document.getElementById('historyKirimModal');
        const historyContent = document.getElementById('historyContent');
        historyContent.innerHTML = '<div style="text-align: center; padding: 40px;">Yuklanmoqda...</div>';
        modal.style.display = 'flex';

        fetch('../api/get_kirim_details.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + rowId
        })
        .then(response => response.json())
        .then(records => {
            historyContent.innerHTML = `
                <div class="modal-header">
                    <h3>💰 Kirim tafsilotlari</h3>
                    <button class="close" onclick="closeModal('historyKirimModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <table id="kirimDetailsTable" class="table table-hover align-middle text-center">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Soni</th>
                                <th>Narxi</th>
                                <th>Jami</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" id="kirimJamiRow" class="text-end"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;

            const tbody = document.querySelector('#kirimDetailsTable tbody');
            let total = 0;

            if (records.length > 0) {
                records.forEach((item, index) => {
                    const narxi = parseFloat(item.narxi) || 0;
                    const summa = parseFloat(item.summa) || 0;

                    total += summa;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${item.soni}</td>
                        <td>${narxi.toLocaleString('uz-UZ')}</td>
                        <td>${summa.toLocaleString('uz-UZ')} so'm</td>
                    `;
                    tbody.appendChild(row);
                });
                document.getElementById('kirimJamiRow').innerHTML = `
                    <strong>Jami summa: ${total.toLocaleString('uz-UZ')} so'm</strong>
                `;

                setTimeout(() => {
                    $('#kirimDetailsTable').DataTable({
                        responsive: true,
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
                }, 100);

            } else {
                document.querySelector('.modal-body').innerHTML = `
                    <div class="no-products">
                        <p>Bu kirim uchun tafsilotlar topilmadi</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Xatolik:', error);
            historyContent.innerHTML = `
                <div class="error-message">
                    <p>Ma'lumotlarni yuklashda xatolik yuz berdi.</p>
                </div>
            `;
        });
    }

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
    const toggleKirimViewBtn = document.getElementById('toggleKirimViewBtn');
    const kirimForm = document.getElementById('kirimForm');
    const kirimTableSection = document.getElementById('kirimTableSection');

    toggleKirimViewBtn.addEventListener('click', () => {
        const isFormVisible = kirimForm.style.display !== 'none';

        kirimForm.style.display = isFormVisible ? 'none' : 'block';
        kirimTableSection.style.display = isFormVisible ? 'block' : 'none';

        toggleKirimViewBtn.innerHTML = isFormVisible 
            ? '➕ Forma ko‘rinishini ko‘rsatish' 
            : '📋 Jadval ko‘rinishini ko‘rsatish';
    });
    function addKirimProduct() {            
        const wrapper = document.getElementById('kirim-mahsulotlar-wrapper');
        const newRow = document.createElement('div');
        newRow.className = 'product-row';
        newRow.innerHTML = `
            <div class="form-grid">
                <div class="form-group">
                    <label>Mahsulotni tanlang:</label>
                    <select name="kategoriya[]" required>
                        <option value="">Mahsulotni tanlangan</option>
                        <?php foreach ($mahsulotlar as $mahsulot): ?>
                            <option value="<?= $mahsulot['id'] ?>">
                                <?= $mahsulot['nomi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Miqdori:</label>
                    <input type="number" name="miqdor[]" placeholder="Miqdori" required min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label>Narxi (so'm):</label>
                    <input type="number" name="narx[]" placeholder="Narxi (so'm)" required min="0" step="1">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="remove-btn" onclick="removeMahsulot(this)" title="Mahsulotni olib tashlash">
                        ❌
                    </button>
                </div>
            </div>
        `;
        wrapper.appendChild(newRow);
    }
    function removeMahsulot(button) {            
        const productRow = button.closest('.product-row');
        const wrapper = document.getElementById('kirim-mahsulotlar-wrapper');
        
        if (!productRow || !wrapper) {
            console.error('Element topilmadi');
            return;
        }
        
        if (wrapper.children.length > 1) {
            productRow.remove();
        } else {
            alert('Kamida bitta mahsulot qatori bo\'lishi kerak!');
        }
    }
    function addKirim(event) {
        event.preventDefault();
        const taminotchi_id = $('#kirim_taminotchi').val();
        const sana = $('#kirim_sana').val();
        const izoh = $('#kirim_izoh').val();

        const mahsulotlar = [];
        $('#kirim-mahsulotlar-wrapper .product-row').each(function () {
            const kategoriya = $(this).find('select[name="kategoriya[]"]').val();
            const miqdor = parseFloat($(this).find('input[name="miqdor[]"]').val());
            const narx = parseFloat($(this).find('input[name="narx[]"]').val());
            const summa = miqdor * narx;

            mahsulotlar.push({
                mahsulot_nomi: kategoriya,
                miqdor: miqdor,
                narx: narx,
                summa: summa
            });
        });

        const total_summa = mahsulotlar.reduce((acc, item) => acc + item.summa, 0);
        const kirimData = {
            taminotchi_id: taminotchi_id,
            sana: sana,
            izoh: izoh,
            umumiy_summa: total_summa,
            mahsulotlar: mahsulotlar
        };
        console.log(kirimData);
        $.ajax({
            url: '../form_insert_data/kirim_data_qoshish.php',
            type: 'POST',
            data: JSON.stringify(kirimData),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    swal("✅ Muvaffaqiyatli!", response.message, "success");
                    $('#kirimForm')[0].reset();
                    $('#kirim-mahsulotlar-wrapper').html('');
                    addKirimProduct(); 
                } else {
                    swal("❌ Xatolik!", response.message, "error");
                }
            },
            error: function (xhr, status, error) {
                swal("⚠️ Xatolik!", "Server bilan bog‘lanishda muammo yuz berdi", "error");
                console.error(error);
            }
        });
    }
</script>