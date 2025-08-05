
<?php
    include_once '../config.php';
    $db = new Database();
    $mijozlar = $db->get_data_by_table_all('mijozlar');
    $mahsulotlar = $db->get_data_by_table_all('mahsulotlar');
?>  

<section id="goshtsotish" class="content-section">
    <div class="section-header">
        <h2 class="section-title">💰 Sotuvlar</h2>
        <div style="margin-top: 1rem;">
            <button id="toggleSotuvViewBtn" class="btn btn-outline-success">📋 Jadval ko‘rinishini ko‘rsatish</button>
        </div>
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
    <?php
        include_once '../config.php';
        $db = new Database();
        $query = "SELECT 
            s.id,
            s.sana,
            s.summa,
            s.izoh,
            m.mijoz_nomi,
            m.mijoz_tel,
            m.balans
            FROM sotuvlar s
            LEFT JOIN mijozlar m ON s.mijoz_id=m.id ORDER BY s.sana DESC;";
        $sotuvlar = $db->query($query);
    ?>                            
    <div class="table-container" id="sotuvTableSection" style="display: none;">
        <h3 class="mb-3">So'nggi sotishlar</h3>
        <table id="sotuvTable" class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>№</th>
                    <th>Sana</th>
                    <th>Mijoz</th>
                    <th>Telefon</th>
                    <th>Balans</th>
                    <th>Jami summa</th>
                    <th>Izoh</th>
                    <th>Ko'rish</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 1; while ($sotuv = mysqli_fetch_assoc($sotuvlar)) { ?>
                    <tr>
                        <td><?= $index++ ?></td>
                        <td><?= htmlspecialchars($sotuv['sana']) ?></td>
                        <td><?= htmlspecialchars($sotuv['mijoz_nomi']) ?></td>
                        <td><?= htmlspecialchars($sotuv['mijoz_tel']) ?></td>
                        <td><?= htmlspecialchars($sotuv['balans']) ?></td>
                        <td><?= htmlspecialchars($sotuv['summa']) ?></td>
                        <td><?= htmlspecialchars($sotuv['izoh']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning" title="Ko‘rish" onclick="viewDetailsSotuv(<?= $sotuv['id'] ?>)">
                                👁️
                            </button>
                        </td>
                    </tr>
                <?php }; ?>
            </tbody>
        </table>
    </div>
</section>
<div id="historySotuvModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div id="sotuvContent">
            <!-- Modal header va body JS orqali joylanadi -->
        </div>
    </div>
</div>

<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
    function viewDetailsSotuv(rowId) {
        const modal = document.getElementById('historySotuvModal');
        const content = document.getElementById('sotuvContent');
        content.innerHTML = '<div style="text-align: center; padding: 40px;">Yuklanmoqda...</div>';
        modal.style.display = 'flex';

        fetch('../api/get_sotuv_details.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + encodeURIComponent(rowId)
        })
        .then(response => response.json())
        .then(records => {
            if (records.length === 0) {
                content.innerHTML = `
                    <div class="modal-header">
                        <h3>🧾 Sotuv tafsilotlari</h3>
                        <button class="close" onclick="closeModal('historySotuvModal')">&times;</button>
                    </div>
                    <div class="modal-body" style="text-align:center; padding:20px;">
                        <p>Bu sotuv uchun tafsilotlar topilmadi</p>
                    </div>
                `;
                return;
            }
            content.innerHTML = `
                <div class="modal-header">
                    <h3>🧾 Sotuv tafsilotlari</h3>
                    <button class="close" onclick="closeModal('historySotuvModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <table id="sotuvDetailsTable" class="table table-hover align-middle text-center">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Nomi</th>
                                <th>Soni</th>
                                <th>Narxi</th>
                                <th>Jami</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" id="sotuvJamiRow" class="text-end"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
            const tbody = content.querySelector('#sotuvDetailsTable tbody');
            let total = 0;
            records.forEach((item, index) => {
                total += parseFloat(item.summa);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.nomi}</td>
                    <td>${item.soni}</td>
                    <td>${item.narxi}</td>
                    <td>${item.summa} so'm</td>
                `;
                tbody.appendChild(row);
            });

            document.getElementById('sotuvJamiRow').innerHTML = `
                <strong>Jami summa: ${total.toFixed(2)} so'm</strong>
            `;
            setTimeout(() => {
                if ($.fn.DataTable.isDataTable('#sotuvDetailsTable')) {
                    $('#sotuvDetailsTable').DataTable().destroy();
                }
                $('#sotuvDetailsTable').DataTable({
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
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
        }
    }
    $(document).ready(function() {
        $('#sotuvTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/uz.json' 
            }
        });
    });
    const toggleSotuvViewBtn = document.getElementById('toggleSotuvViewBtn');
    const goshtSotishForm = document.getElementById('goshtSotishForm');
    const sotuvTableSection = document.getElementById('sotuvTableSection');

    toggleSotuvViewBtn.addEventListener('click', () => {
        const isFormVisible = goshtSotishForm.style.display !== 'none';

        goshtSotishForm.style.display = isFormVisible ? 'none' : 'block';
        sotuvTableSection.style.display = isFormVisible ? 'block' : 'none';

        toggleSotuvViewBtn.innerHTML = isFormVisible 
            ? '➕ Forma ko‘rinishini ko‘rsatish' 
            : '📋 Jadval ko‘rinishini ko‘rsatish';
    });
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
