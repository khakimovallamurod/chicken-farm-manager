<?php
    include_once '../config.php';
    $db = new Database();
    $taminotchilar = $db->get_data_by_table_all('taminotchilar');
    $tolov_birliklari = $db->get_data_by_table_all('tolov_birligi');
?>  
<section id="pulberish" class="content-section">
    <div class="section-header">
        <h2 class="section-title">💸 Taminotchiga pul berish</h2>
        <div style="margin-top: 1rem;">
            <button id="togglePulBerishViewBtn" class="btn btn-outline-success">📋 Jadval ko‘rinishini ko‘rsatish</button>
        </div>
    </div>
    
    <form id="pulBerishForm">
        <div class="form-grid">
            <div class="form-group">
                <label>Taminotchini tanlang:</label>
                <select id="ber_mijoz" required>
                    <option value="">Taminotchini tanlang</option>
                    <?php foreach ($taminotchilar as $taminotchi): ?>
                        <option value="<?= $taminotchi['id'] ?>">
                            <?= $taminotchi['fio'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Berilgan miqdor (so'm):</label>
                <input type="number" id="ber_summasi" required min="0" step="0.01" placeholder="Masalan: 3000000">
            </div>
            <div class="form-group">
                <label>Berilgan sana:</label>
                <input type="date" id="ber_sana" required>
            </div>
            <div class="form-group">
                <label>To'lov usuli:</label>
                <select id="ber_tolov" required>
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
            <textarea id="ber_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
        </div>
        <button type="submit" class="btn btn-danger">💸 Pul berishni qayd qilish</button>
    </form>
    <?php
        $query = "SELECT SUM(pb.summa) AS umumiy_summa, t.fio, t.kompaniya_nomi, t.telefon, t.balans, t.id, t.manzil
        FROM pul_berish pb 
        LEFT JOIN taminotchilar t ON pb.taminotchi_id = t.id
        GROUP BY t.id   
        ORDER BY umumiy_summa DESC;";
        $pul_berishlar = $db->query($query);

    ?>  
    <div class="table-container" id="pulberishTableSection" style="display: none;">
        <h3>Pul berishlar ro'yxati</h3>
        <table table id="pulberishTable" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Kompaniya nomi</th>
                    <th>Taminotchi nomi</th>
                    <th>Telefon</th>
                    <th>Taminotchi balans</th>
                    <th>Taminotchi manzil</th>
                    <th>Umumiy berilgan summa</th>
                    <th>Ko'rish</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 1; while ($pulberish = mysqli_fetch_assoc($pul_berishlar)) { 
                    $summa = (float)$pulberish['umumiy_summa'];
                    $rowClass = $summa > 0 ? 'table-success' : 'table-danger';
                    ?>
                    <tr>
                        <td><?= $index++ ?></td>
                        <td><?= htmlspecialchars($pulberish['kompaniya_nomi']) ?></td>
                        <td><?= htmlspecialchars($pulberish['fio']) ?></td>
                        <td><?= htmlspecialchars($pulberish['telefon']) ?></td>
                        <td>
                            <?php
                            $balans = (float)$pulberish['balans'];
                            echo $balans <= 0
                                ? '<span class="badge bg-danger">0 so‘m</span>'
                                : '<span class="badge bg-primary">' . number_format($balans, 0, '.', ' ') . ' so‘m</span>';
                            ?>
                        </td>
                        <td><?= htmlspecialchars($pulberish['manzil']) ?></td>
                        <td><strong><?= number_format($summa, 0, '.', ' ') ?> so‘m</strong></td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning" title="Ko‘rish" onclick="viewDetailsPulBerish(<?= $pulberish['id'] ?>)">
                                👁️
                            </button>
                        </td>
                    </tr>
                <?php }; ?>
            </tbody>
        </table>
    </div>
</section>
<div id="historyPulBerishModal" class="modal">
    <div class="modal-content modal-large">
        <div id="PulBerishContent"></div>
    </div>
</div>
<script>
    function viewDetailsPulBerish(id) {
        const modal = document.getElementById('historyPulBerishModal');
        const content = document.getElementById('PulBerishContent');
        content.innerHTML = '<div style="text-align: center; padding: 40px;">Yuklanmoqda...</div>';
        modal.style.display = 'flex';

        fetch('../api/get_pul_berish_details.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(records => {
            if (records.length === 0) {
                content.innerHTML = `
                    <div class="modal-header">
                        <h3>💸 Pul berish tafsilotlari</h3>
                        <button class="close" onclick="closeModal('historyPulBerishModal')">&times;</button>
                    </div>
                    <div class="modal-body" style="text-align:center; padding:20px;">
                        <p>Bu kompaniya uchun pul berish tafsilotlari topilmadi</p>
                    </div>
                `;
                return;
            }

            content.innerHTML = `
                <div class="modal-header">
                    <h3>💸 Pul berish tafsilotlari</h3>
                    <button class="close" onclick="closeModal('historyPulBerishModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <table id="pulBerishDetailsTable" class="table table-hover align-middle text-center">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Sana</th>
                                <th>Berilgan summa</th>
                                <th>To'lov turi</th>
                                <th>Izoh</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" id="pulBerishJamiRow" class="text-end"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;

            const tbody = content.querySelector('#pulBerishDetailsTable tbody');
            let total = 0;

            records.forEach((item, index) => {
                const summa = parseFloat(item.summa || 0);
                total += summa;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.sana}</td>
                    <td>${summa.toLocaleString('uz-UZ')} so‘m</td>
                    <td>${item.tolov_turi}</td>
                    <td>${item.izoh ?? '-'}</td>
                `;
                tbody.appendChild(row);
            });

            document.getElementById('pulBerishJamiRow').innerHTML = `
                <strong>Jami summa: ${total.toLocaleString('uz-UZ')} so‘m</strong>
            `;

            setTimeout(() => {
                if ($.fn.DataTable.isDataTable('#pulBerishDetailsTable')) {
                    $('#pulBerishDetailsTable').DataTable().destroy();
                }
                $('#pulBerishDetailsTable').DataTable({
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
        $('#pulberishTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/uz.json' 
            }
        });
    });
    const togglePulBerishViewBtn = document.getElementById('togglePulBerishViewBtn');
    const pulBerishForm = document.getElementById('pulBerishForm');
    const pulberishTableSection = document.getElementById('pulberishTableSection');

    togglePulBerishViewBtn.addEventListener('click', () => {
        const isFormVisible = pulBerishForm.style.display !== 'none';

        pulBerishForm.style.display = isFormVisible ? 'none' : 'block';
        pulberishTableSection.style.display = isFormVisible ? 'block' : 'none';

        togglePulBerishViewBtn.innerHTML = isFormVisible 
            ? '➕ Forma ko‘rinishini ko‘rsatish' 
            : '📋 Jadval ko‘rinishini ko‘rsatish';
    });
    $('#pulBerishForm').on('submit', function (event) {
        event.preventDefault();

        const formData = {
            taminotchi_id: $('#ber_mijoz').val(),
            summa: $('#ber_summasi').val(),
            sana: $('#ber_sana').val(),
            tolov_usuli: $('#ber_tolov').val(),
            izoh: $('#ber_izoh').val().trim()
        };

        $.ajax({
            url: '../form_insert_data/pul_berish_insert.php', 
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    showAlert(result.message, 'success');
                    $('#pulBerishForm')[0].reset();
                } else {
                    showAlert(result.message, 'error');
                    $('#pulBerishForm')[0].reset();
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