<?php
    include_once '../config.php';
    $db = new Database();
    $mijozlar = $db->get_data_by_table_all('mijozlar');
    $tolov_birliklari = $db->get_data_by_table_all('tolov_birligi');
    $query_pulolish = "SELECT 
            po.id,
            m.mijoz_nomi,
            m.balans,
            m.mijoz_tel,
            po.summa,
            po.sana,
            tb.nomi AS tolov_turi,
            po.izoh
        FROM 
            pul_olish po
        INNER JOIN 
            mijozlar m ON po.mijoz_id = m.id
        INNER JOIN 
            tolov_birligi tb ON po.tolov_birlik_id = tb.id
        ORDER BY 
            po.sana DESC;
        ";
    $pul_olish_datalar = $db->query($query_pulolish);
?>  
<section id="pulolish" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🏦 Mijozdan pul olish</h2>
        <div style="margin-top: 1rem;">
            <button id="togglePulOlishViewBtn" class="btn btn-outline-success">📋 Jadval ko‘rinishini ko‘rsatish</button>
        </div>
    </div>
    
    <form id="pulOlishForm">
        <div class="form-grid">
            <div class="form-group">
                <label>Mijozni tanlang:</label>
                <select id="pul_mijoz" required>
                    <option value="">Mijozni tanlang</option>
                    <?php foreach ($mijozlar as $mijoz): ?>
                        <option value="<?= $mijoz['id'] ?>">
                            <?= $mijoz['mijoz_nomi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Olingan miqdor (so'm):</label>
                <input type="number" id="pul_olingan" required min="0" step="0.01" placeholder="Masalan: 3000000">
            </div>
            <div class="form-group">
                <label>Olish sanasi:</label>
                <input type="date" id="pul_sana" required>
            </div>
            <div class="form-group">
                <label>To'lov usuli:</label>
                <select id="pul_tolov" required>
                    <option value="">To'lov usulini tanlang</option>
                    <?php foreach ($tolov_birliklari as $tolov_birlik): ?>
                        <option value="<?= $tolov_birlik['id'] ?>">
                            <?= $tolov_birlik['nomi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Izoh:</label>
            <textarea id="pul_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">🏦 Pul olishni qayd qilish</button>
    </form>
    <?php
        $qury = "SELECT m.id AS mijoz_id, m.mijoz_nomi, m.mijoz_tel, m.balans, m.mijoz_address, SUM(po.summa) AS umumiy_summa 
        FROM pul_olish po 
        LEFT JOIN mijozlar m ON po.mijoz_id = m.id 
        GROUP BY m.id, m.mijoz_nomi, m.mijoz_tel, m.balans 
        ORDER BY umumiy_summa DESC;";
        $pul_olishlar = $db->query($qury);

    ?>  
    <div class="table-container" id="pulolishTableSection" style="display: none;">
        <h3>Pul olishlar ro'yxati</h3>
        <table table id="pulolishTable" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Mijoz</th>
                    <th>Telefon</th>
                    <th>Mijoz balans</th>
                    <th>Mijoz manzil</th>
                    <th>Umumiy olingan summa</th>
                    <th>Ko'rish</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 1; while ($pulolish = mysqli_fetch_assoc($pul_olishlar)) { 
                    $summa = (float)$pulolish['umumiy_summa'];
                    $rowClass = $summa > 0 ? 'table-success' : 'table-danger';
                    ?>
                    <tr>
                        <td><?= $index++ ?></td>
                        <td><?= htmlspecialchars($pulolish['mijoz_nomi']) ?></td>
                        <td><?= htmlspecialchars($pulolish['mijoz_tel']) ?></td>
                        <td>
                            <?php
                            $balans = (float)$pulolish['balans'];
                            echo $balans <= 0
                                ? '<span class="badge bg-danger">0 so‘m</span>'
                                : '<span class="badge bg-primary">' . number_format($balans, 0, '.', ' ') . ' so‘m</span>';
                            ?>
                        </td>
                        <td><?= htmlspecialchars($pulolish['mijoz_address']) ?></td>
                        <td><strong><?= number_format($summa, 0, '.', ' ') ?> so‘m</strong></td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning" title="Ko‘rish" onclick="viewDetailsPulOlish(<?= $pulolish['mijoz_id'] ?>)">
                                👁️
                            </button>
                        </td>
                    </tr>
                <?php }; ?>
            </tbody>
        </table>
    </div>
</section>
<div id="historyPulOlishModal" class="modal">
    <div class="modal-content modal-large">
        <div id="pulOlishContent"></div>
    </div>
</div>

<script>
    function viewDetailsPulOlish(mijozId) {
        const modal = document.getElementById('historyPulOlishModal');
        const content = document.getElementById('pulOlishContent');
        content.innerHTML = '<div style="text-align: center; padding: 40px;">Yuklanmoqda...</div>';
        modal.style.display = 'flex';

        fetch('../api/get_pul_olish_details.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'mijoz_id=' + encodeURIComponent(mijozId)
        })
        .then(response => response.json())
        .then(records => {
            if (records.length === 0) {
                content.innerHTML = `
                    <div class="modal-header">
                        <h3>💰 Pul olish tafsilotlari</h3>
                        <button class="close" onclick="closeModal('historyPulOlishModal')">&times;</button>
                    </div>
                    <div class="modal-body" style="text-align:center; padding:20px;">
                        <p>Bu mijoz uchun pul olish tafsilotlari topilmadi</p>
                    </div>
                `;
                return;
            }

            content.innerHTML = `
                <div class="modal-header">
                    <h3>💰 Pul olish tafsilotlari</h3>
                    <button class="close" onclick="closeModal('historyPulOlishModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <table id="pulOlishDetailsTable" class="table table-hover align-middle text-center">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Sana</th>
                                <th>Olingan summa</th>
                                <th>To'lov turi</th>
                                <th>Izoh</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" id="pulOlishJamiRow" class="text-end"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;

            const tbody = content.querySelector('#pulOlishDetailsTable tbody');
            let total = 0;
            records.forEach((item, index) => {
                total += parseFloat(item.summa);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.sana}</td>
                    <td>${item.summa} so'm</td>
                    <td>${item.tolov_turi}</td>
                    <td>${item.izoh ?? '-'}</td>
                `;
                tbody.appendChild(row);
            });

            document.getElementById('pulOlishJamiRow').innerHTML = `
                <strong>Jami summa: ${total.toFixed(2)} so'm</strong>
            `;

            setTimeout(() => {
                if ($.fn.DataTable.isDataTable('#pulOlishDetailsTable')) {
                    $('#pulOlishDetailsTable').DataTable().destroy();
                }
                $('#pulOlishDetailsTable').DataTable({
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
        })
        .catch(error => {
            console.error('Xatolik:', error);
            content.innerHTML = `
                <div class="error-message" style="text-align:center; padding:20px; color:red;">
                    <p>Ma'lumotlarni yuklashda xatolik yuz berdi.</p>
                </div>
            `;
        });
    }

    $(document).ready(function() {
        $('#pulolishTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/uz.json' 
            }
        });
    });
    const togglePulOlishViewBtn = document.getElementById('togglePulOlishViewBtn');
    const pulOlishForm = document.getElementById('pulOlishForm');
    const pulolishTableSection = document.getElementById('pulolishTableSection');

    togglePulOlishViewBtn.addEventListener('click', () => {
        const isFormVisible = pulOlishForm.style.display !== 'none';

        pulOlishForm.style.display = isFormVisible ? 'none' : 'block';
        pulolishTableSection.style.display = isFormVisible ? 'block' : 'none';

        togglePulOlishViewBtn.innerHTML = isFormVisible 
            ? '➕ Forma ko‘rinishini ko‘rsatish' 
            : '📋 Jadval ko‘rinishini ko‘rsatish';
    });
    $('#pulOlishForm').on('submit', function (event) {
        event.preventDefault();

        const formData = {
            mijoz_id: $('#pul_mijoz').val(),
            summa: $('#pul_olingan').val(),
            sana: $('#pul_sana').val(),
            tolov_usuli: $('#pul_tolov').val(),
            izoh: $('#pul_izoh').val().trim()
        };
        $.ajax({
            url: '../form_insert_data/pul_olish_insert.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    showAlert(result.message, 'success');
                    $('#pulOlishForm')[0].reset();
                } else {
                    showAlert(result.message, 'error');
                    $('#pulOlishForm')[0].reset();
                }
            },
            error: function (xhr, status, error) {
                console.error('Server xatosi:', error);
                swal({
                    title: "Xatolik!",
                    text: "Server bilan bog'lanishda xatolik yuz berdi.",
                    icon: "error",
                    button: "OK",
                });
            }
        });
    });

</script>
