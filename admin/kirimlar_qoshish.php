<?php
include_once '../config.php';
$db = new Database();
$taminotchilar = $db->get_data_by_table_all('taminotchilar');
$mahsulotlar = $db->get_data_by_table_all('mahsulotlar');
?>  

<section id="kirim" class="content-section">
    <div class="section-header">
        <h2 class="section-title">📥 Kirimlar</h2>
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
</section>
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
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
        
        // Kamida bitta qator qolishi kerak
        if (wrapper.children.length > 1) {
            productRow.remove();
        } else {
            alert('Kamida bitta mahsulot qatori bo\'lishi kerak!');
        }
    }
    function addKirim(event) {
        event.preventDefault(); // Sahifa yangilanmasin
        const taminotchi_id = $('#kirim_taminotchi').val();
        const sana = $('#kirim_sana').val();
        const izoh = $('#kirim_izoh').val();

        // Mahsulotlar ro'yxati
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