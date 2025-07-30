
<?php
    include_once '../config.php';
    $db = new Database();
    $kataklar = $db->get_data_by_table_all('kataklar');
    $mahsulotlar = $db->get_data_by_table_all('mahsulotlar', "WHERE categoriya_id = 1");
    
?>
<section id="joja" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🐥 Jo'ja qo'shish</h2>
    </div>
    <form id="jojaForm" onsubmit="addJoja(event)">
        <div class="form-grid">
            <div class="form-group">
                <label>Katak tanlang:</label>
                <select id="joja_katak_id" required>
                    <option value="">Katakni tanlang</option>
                    <?php foreach ($kataklar as $katak): ?>
                        <option value="<?= $katak['id'] ?>">
                            <?= $katak['katak_nomi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mahsulotni tanlang:</label>
                <select id="joja_kategoriya" required>
                    <option value="">Tanlang</option>
                    <?php foreach ($mahsulotlar as $mahsulot): ?>
                        <option value="<?= $mahsulot['id'] ?>">
                            <?= $mahsulot['nomi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Jo'jalar soni:</label>
                <input type="number" id="joja_soni" required min="1" placeholder="Masalan: 100">
            </div>
            <div class="form-group">
                <label>Sana:</label>
                <input type="date" id="joja_sana" required>
            </div>
        </div>
        <div class="form-group">
            <label>Izoh:</label>
            <textarea id="joja_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
        </div>
        <button type="submit" class="btn btn-success">🐥 Jo'ja qo'shish</button>
    </form>
    <?php
        include_once '../config.php';
        $db = new Database();
        $query_for_oj = "
        SELECT 
        j.id AS joja_id, j.sana, j.soni, j.narxi, j.izoh, k.katak_nomi, m.nomi AS mahsulot_nomi 
        FROM joja j INNER JOIN kataklar k ON j.katak_id = k.id 
        INNER JOIN mahsulotlar m ON j.mahsulot_id = m.id ORDER BY j.sana DESC;
        ";
        $fetch = $db->query($query_for_oj);
        
    ?>
    <div class="table-container">
        <h3 class="table-title">
            <i class="fas fa-list-alt me-2"></i>Qo'shilgan jo'jalar ro'yxati
        </h3>
        
        <div class="table-responsive">
            <table id="qoshilganJojalarTable" class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="fas fa-home me-1"></i>Katak nomi</th>
                        <th><i class="fas fa-drumstick-bite me-1"></i>Mahsulot nomi</th>
                        <th><i class="fas fa-plus-square me-1"></i>Soni</th>
                        <th><i class="fas fa-money-bill-wave me-1"></i>Narxi</th>
                        <th><i class="fas fa-calendar-alt me-1"></i>Sana</th>
                        <th><i class="fas fa-comment me-1"></i>Izoh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($joja_row = mysqli_fetch_assoc($fetch)) {?>                    
                        <tr>
                            <td>
                                <span class="badge-katak">
                                    <?= htmlspecialchars($joja_row['katak_nomi']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($joja_row['mahsulot_nomi']) ?></td>
                            <td>
                                <span class="badge bg-success">
                                    <?= htmlspecialchars($joja_row['soni']) ?> dona
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    <?= htmlspecialchars($joja_row['narxi']) ?> so'm
                                </span>
                            </td>
                            <td data-order="<?= $joja_row['sana'] ?>">
                                <?= date('d.m.Y', strtotime($joja_row['sana'])) ?>
                            </td>
                            <td><?= htmlspecialchars($joja_row['izoh']) ?></td>                        
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
$(document).ready(function () {
        $('#qoshilganJojalarTable').DataTable({
            order: [[4, 'desc']], // Sana bo‘yicha kamayish tartibida default sort
            language: {
                search: "Qidiruv:",
                lengthMenu: "Har sahifada _MENU_ ta yozuv ko‘rsatilsin",
                info: "_TOTAL_ tadan _START_ dan _END_ gacha ko‘rsatilmoqda",
                paginate: {
                    first: "Birinchi",
                    last: "Oxirgi",
                    next: "Keyingi",
                    previous: "Oldingi"
                },
                zeroRecords: "Hech narsa topilmadi",
                infoEmpty: "Ma’lumot mavjud emas",
                infoFiltered: "(umumiy _MAX_ yozuvdan filtrlandi)"
            }
        });
    });
    function addJoja(event) {
        event.preventDefault();
        const katakId = $('#joja_katak_id').val();
        const kategoriya = $('#joja_kategoriya').val();
        const soni = $('#joja_soni').val();
        const sana = $('#joja_sana').val();
        const izoh = $('#joja_izoh').val();

        const data = {
            katak_id: katakId,
            kategoriya: kategoriya,
            soni: soni,
            sana: sana,
            izoh: izoh
        };
        $.ajax({
            url: '../form_insert_data/joja_add.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json',
            
            success: function(result) {
                if (result.success) {
                    showAlert(result.message, 'success');
                    $('#jojaForm')[0].reset();
                } else {
                    showAlert(result.message, 'error');
                    $('#jojaForm')[0].reset();
                }
            },
            error: function(xhr, status, error) {
                swal({
                    title: "Xatolik",
                    text: "Ma'lumotlar bazasiga qo'shishda xatolik yuz berdi!",
                    icon: "error"
                });
            }
        });
    }
</script>

