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

    <div class="table-container">
        <h3>Qarzda mijozlar</h3>
        <table>
            <thead>
                <tr>
                    <th>Mijoz</th>
                    <th>Telefon</th>
                    <th>Umumiy qarz</th>
                    <th>To'langan</th>
                    <th>Qolgan qarz</th>
                    <th>So'nggi to'lov</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Aziz Toshev</td>
                    <td>+998 90 123 45 67</td>
                    <td>6,600,000</td>
                    <td>3,000,000</td>
                    <td style="color: #e74c3c; font-weight: bold;">3,600,000</td>
                    <td>2024-01-20</td>
                </tr>
                <tr>
                    <td>Karim Nazarov</td>
                    <td>+998 91 234 56 78</td>
                    <td>2,500,000</td>
                    <td>1,000,000</td>
                    <td style="color: #e74c3c; font-weight: bold;">1,500,000</td>
                    <td>2024-01-15</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h3>So'nggi to'lovlar</h3>
        <table>
            <thead>
                <tr>
                    <th>Sana</th>
                    <th>Mijoz</th>
                    <th>Miqdor</th>
                    <th>To'lov usuli</th>
                    <th>Qolgan qarz</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2024-01-20</td>
                    <td>Aziz Toshev</td>
                    <td>3,000,000</td>
                    <td>Bank o'tkazmasi</td>
                    <td>3,600,000</td>
                </tr>
                <tr>
                    <td>2024-01-15</td>
                    <td>Karim Nazarov</td>
                    <td>1,000,000</td>
                    <td>Naqd pul</td>
                    <td>1,500,000</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<script>
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
