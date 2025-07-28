
<?php
    include_once '../config.php';
    $db = new Database();
    $mijozlar = $db->get_data_by_table_all('mijozlar');
    $mahsulotlar = $db->get_data_by_table_all('mahsulotlar');
?>  

<section id="goshtsotish" class="content-section">
    <div class="section-header">
        <h2 class="section-title">💰 Sotuvlar</h2>
    </div>
    
    <form id="goshtSotishForm" onsubmit="addGoshtSotish(event)">
        <div class="form-grid">
            <div class="form-group">
                <label>Mijoz nomi:</label>
                <select id="sotish_mijoz" required>
                    <option value="">Mijozni tanlang</option>
                    <?php foreach ($mijozlar as $mijoz): ?>
                        <option value="<?= $mijoz['id'] ?>">
                            <?= $mijoz['mijoz_nomi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>                                       
            </div>
            <div class="form-group">
                <label>Sotish sanasi:</label>
                <input type="date" id="sotish_sana" required>
            </div>                                                           
        </div>
        <div id="sotish-mahsulotlar-wrapper">
            <div class="product-row">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Mahsulotni tanlang:</label>
                        <select name="kategoriya[]" required>
                            <option value="">Mahsulotni tanlang:</option>
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
        <button type="button" class="add-product-btn" id="addSotishProductBtn" onclick="addSotishMahsulotRow()">
            ➕ Mahsulot qo'shish
        </button>

        <div class="form-group">
            <label>Izoh:</label>
            <textarea id="sotish_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
        </div>
        <button type="submit" class="btn btn-success">💰 Sotishni ro'yxatga olish</button>
    </form>

    <div class="table-container">
        <h3>So'nggi sotishlar</h3>
        <table>
            <thead>
                <tr>
                    <th>Sana</th>
                    <th>Mijoz</th>
                    <th>Miqdor (kg)</th>
                    <th>Narxi</th>
                    <th>Jami</th>
                    <th>To'lov</th>
                    <th>Holati</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2024-01-22</td>
                    <td>Rustam Abdullayev</td>
                    <td>125.0</td>
                    <td>35,000</td>
                    <td>4,375,000</td>
                    <td>Naqd</td>
                    <td><span class="katak-status status-active">To'landi</span></td>
                </tr>
                <tr>
                    <td>2024-01-20</td>
                    <td>Nodir Karimov</td>
                    <td>89.5</td>
                    <td>34,500</td>
                    <td>3,087,750</td>
                    <td>Karta</td>
                    <td><span class="katak-status status-active">To'landi</span></td>
                </tr>
                <tr>
                    <td>2024-01-18</td>
                    <td>Aziz Toshev</td>
                    <td>200.0</td>
                    <td>33,000</td>
                    <td>6,600,000</td>
                    <td>Qarz</td>
                    <td><span class="katak-status status-maintenance">Qarzda</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
    // ➕ Mahsulot qatori qo‘shish
    function addSotishMahsulotRow() {
        const wrapper = document.getElementById('sotish-mahsulotlar-wrapper');
        const newRow = document.createElement('div');
        newRow.className = 'product-row';
        newRow.innerHTML = `
            <div class="form-grid">
                <div class="form-group">
                    <label>Mahsulotni tanlang:</label>
                    <select name="kategoriya[]" required>
                        <option value="">Mahsulotni tanlang</option>
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

    // ❌ Mahsulotni o‘chirish
    function removeMahsulot(button) {
        const productRow = button.closest('.product-row');
        const wrapper = document.getElementById('sotish-mahsulotlar-wrapper');

        if (!productRow || !wrapper) {
            console.error('Element topilmadi');
            return;
        }

        if (wrapper.children.length > 1) {
            productRow.remove();
        } else {
            alert("Kamida bitta mahsulot bo'lishi kerak!");
        }
    }

    function addGoshtSotish(event) {
        event.preventDefault();

        const mijoz_id = $('#sotish_mijoz').val();
        const sana = $('#sotish_sana').val();
        const izoh = $('#sotish_izoh').val();

        const mahsulotlar = [];
        $('#sotish-mahsulotlar-wrapper .product-row').each(function () {
            const mahsulot_id = $(this).find('select[name="kategoriya[]"]').val();
            const miqdor = parseFloat($(this).find('input[name="miqdor[]"]').val());
            const narx = parseFloat($(this).find('input[name="narx[]"]').val());
            const summa = miqdor * narx;

            mahsulotlar.push({
                mahsulot_id: mahsulot_id,
                miqdor: miqdor,
                narx: narx,
                summa: summa
            });
        });

        const umumiy_summa = mahsulotlar.reduce((acc, item) => acc + item.summa, 0);

        const sotishData = {
            mijoz_id: mijoz_id,
            sana: sana,
            izoh: izoh,
            umumiy_summa: umumiy_summa,
            mahsulotlar: mahsulotlar
        };

        $.ajax({
            url: '../form_insert_data/add_gosht_sotish.php',
            type: 'POST',
            data: JSON.stringify(sotishData),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    swal("✅ Muvaffaqiyatli!", response.message, "success");
                    $('#goshtSotishForm')[0].reset();
                    $('#sotish-mahsulotlar-wrapper').html('');
                    addSotishMahsulotRow();
                } else {
                    swal("❌ Xatolik!", response.message, "error");
                }
            },
            error: function (xhr, status, error) {
                swal("⚠️ Server xatosi", "Ma'lumotni yuborishda xatolik yuz berdi", "error");
                console.error(error);
            }
        });
    }
</script>
