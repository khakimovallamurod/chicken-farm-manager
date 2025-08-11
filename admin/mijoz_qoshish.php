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
                        <td><?=$katak['created_at']?></td>                        
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
    const toggleMijoznBtn = document.getElementById('toggleMijozViewBtn');
    const mijozFormSection = document.getElementById('mijozFormSection');
    const mijozTableSection = document.getElementById('mijozTableSection');

    toggleMijoznBtn.addEventListener('click', () => {
        const isFormVisible = mijozFormSection.style.display !== 'none';

        mijozFormSection.style.display = isFormVisible ? 'none' : 'block';
        mijozTableSection.style.display = isFormVisible ? 'block' : 'none';

        toggleMijoznBtn.innerHTML = isFormVisible 
            ? '➕ Forma ko‘rinishini ko‘rsatish' 
            : '📋 Jadval ko‘rinishini ko‘rsatish';
    });
    $(document).ready(function() {
        $('#mijozlarTable').DataTable({
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
    });
    function addMijozQoshish(event) {
        event.preventDefault();
        const ismi = $('#mijoz_ismi').val();
        const telefon = $('#mijoz_telefon').val();
        const manzil = $('#mijoz_manzil').val();
        const izoh = $('#mijoz_izoh').val();

        const data = {
            ismi: ismi,
            telefon: telefon,
            manzil: manzil,
            izoh: izoh
        };
        console.log(data);
        $.ajax({
            url: '../form_insert_data/add_mijoz.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(result) {
                if (result.success) {
                    showAlert(result.message, 'success');
                    $('#mijozQoshishForm')[0].reset();
                } else {
                    showAlert(result.message, 'error');
                    $('#mijozQoshishForm')[0].reset();
                }
            },
            error: function(xhr, status, error) {
                swal({
                    title: "Xatolik!",
                    text: "Server bilan bog'lanishda xatolik yuz berdi.",
                    icon: "error",
                    button: "OK",
                });
            }
        });
    }
</script>