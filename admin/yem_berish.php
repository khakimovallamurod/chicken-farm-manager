<?php
include_once '../config.php';
$db = new Database();
$kataklar = $db->get_data_by_table_all('kataklar');
$mahsulotlar = $db->get_data_by_table_all('mahsulotlar', "WHERE categoriya_id = 2"); 
?>

<section id="yem" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🌾 Yem berish</h2>
        <div style="margin-top: 1rem;">
            <button id="toggleYemViewBtn" class="btn btn-outline-primary">📋 Jadval ko‘rinishini ko‘rsatish</button>
        </div>
    </div>

    <div id="yemFormSection">
        <form id="yemForm" onsubmit="addYem(event)">
            <div class="form-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 20px;">
                <div class="form-group">
                    <label>Katak tanlang:</label>
                    <select id="yem_katak_id" required>
                        <option value="">Katakni tanlang</option>
                        <?php foreach ($kataklar as $katak): ?>
                            <option value="<?= $katak['id'] ?>">
                                <?= $katak['katak_nomi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sana:</label>
                    <input type="date" id="yem_sana" required>
                </div>
                <div class="form-group">
                    <label>Yem turi:</label>
                    <select id="yem_turi" required>
                        <option value="">Yem turini tanlang</option>
                        <?php foreach ($mahsulotlar as $mahsulot): ?>
                            <option value="<?= $mahsulot['id'] ?>">
                                <?= $mahsulot['nomi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Yem miqdori (kg):</label>
                    <input type="number" id="yem_miqdori" required min="1" placeholder="Masalan: 50">
                </div>
            </div>
            <div class="form-group">
                <label>Izoh:</label>
                <textarea id="yem_izoh" rows="3" placeholder="Yem turi, sifati haqida..."></textarea>
            </div>
            <button type="submit" class="btn btn-success">🌾 Yem berish</button>
        </form>
    </div>

    <!-- JADVAL KO‘RINISHI -->
    <div class="table-container" id="yemTableSection" style="display: none;"style="display: none;">
        <h3 class="table-title">
            <i class="fas fa-list-alt me-2"></i>Yem berish ro'yxati
        </h3>
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="min-date" class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i>Boshlanish sanasi
                    </label>
                    <input type="date"  id="startDate_yem" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="max-date" class="form-label">
                        <i class="fas fa-calendar-check me-1"></i>Tugash sanasi
                    </label>
                    <input type="date" id="endDate_yem"  class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <button id="filterByDate_yem" class="btn-professional btn-info">🔍 Filterlash</button>
                    <button id="clearFilter_yem" class="btn-professional btn-secondary">❌ Tozalash</button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
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
        </div>
    </div>
</section>

<script>
    const toggleYemBtn = document.getElementById('toggleYemViewBtn');
    const yemFormSection = document.getElementById('yemFormSection');
    const yemTableSection = document.getElementById('yemTableSection');

    toggleYemBtn.addEventListener('click', () => {
        const isFormVisible = yemFormSection.style.display !== 'none';

        yemFormSection.style.display = isFormVisible ? 'none' : 'block';
        yemTableSection.style.display = isFormVisible ? 'block' : 'none';

        toggleYemBtn.innerHTML = isFormVisible 
            ? '➕ Forma ko‘rinishini ko‘rsatish' 
            : '📋 Jadval ko‘rinishini ko‘rsatish';
    });
    $(document).ready(function() {
        var table_yem = $('#yemBerishTable').DataTable({
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

    var jq = $.noConflict();
    jq(document).ready(function($) {
        $('#yemBerishTable').DataTable();
    });
    function addYem(event) {
        event.preventDefault();
        const katakId = $('#yem_katak_id').val();
        const sana = $('#yem_sana').val();
        const yemTuri = $('#yem_turi').val();
        const miqdori = $('#yem_miqdori').val();
        const izoh = $('#yem_izoh').val();

        if (!katakId || !sana || !yemTuri || !miqdori) {
            swal({
                title: "Xatolik!",
                text: "Barcha majburiy maydonlarni to'ldiring!",
                icon: "warning",
                button: "OK"
            });
            return;
        }
        if (miqdori <= 0) {
            swal({
                title: "Xatolik!",
                text: "Yem miqdori 0 dan katta bo'lishi kerak!",
                icon: "warning",
                button: "OK"
            });
            return;
        }

        const data = {
            katak_id: katakId,
            sana: sana,
            yem_turi: yemTuri,
            miqdori: miqdori,
            izoh: izoh
        };
        swal({
            title: "Yuklanmoqda...",
            text: "Ma'lumotlar saqlanmoqda",
            icon: "info",
            buttons: false,
            closeOnClickOutside: false,
            closeOnEsc: false
        });
        $.ajax({
            url: '../form_insert_data/yem_berish.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(result) {
                if (result.success) {
                    swal({
                        title: "Muvaffaqiyat!",
                        text: result.message || "Yem berish muvaffaqiyatli qo'shildi!",
                        icon: "success",
                        button: "OK"
                    }).then(() => {
                        $('#yemForm')[0].reset();
                        document.getElementById('yem_sana').valueAsDate = new Date();
                    });
                } else {
                    swal({
                        title: "Xatolik!",
                        text: result.message || "Ma'lumot qo'shishda xatolik yuz berdi!",
                        icon: "error",
                        button: "OK"
                    });
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = "Ma'lumotlar bazasiga qo'shishda xatolik yuz berdi!";
                swal({
                    title: "Xatolik!",
                    text: errorMessage,
                    icon: "error",
                    button: "OK"
                });
            }
        });
    }
    function showAlert(message, type) {
        const iconType = type === 'success' ? 'success' : type === 'error' ? 'error' : 'info';
        const title = type === 'success' ? 'Muvaffaqiyat!' : type === 'error' ? 'Xatolik!' : 'Ma\'lumot';
        
        swal({
            title: title,
            text: message,
            icon: iconType,
            button: "OK"
        });
    }
</script>