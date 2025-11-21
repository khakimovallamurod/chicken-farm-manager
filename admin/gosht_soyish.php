<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go'sht topshirish</title>
    <link rel="stylesheet" href="../assets/css/gosht_soyish_style.css">
   
</head>
<?php
    include_once '../config.php';
    $db = new Database();
    $kataklar = $db->get_data_by_table_all('kataklar');
    $gosht_soyishlar = $db->get_data_by_table_all('gosht_soyish');
    $mahsulotlar = $db->get_data_by_table_all('mahsulotlar', ' WHERE categoriya_id=3');
?>
<body>
    <section id="goshttopshirish" class="content-section">
        <div class="section-header">
            <h2 class="section-title">🥩 Go'sht so'yish</h2>
            <div style="margin-top: 1rem;">
                <button id="toggleGoshtViewBtn"  class="expense-btn">📋 Jadval ko‘rinishini ko‘rsatish</button>
            </div>
        </div>
        <div class="form-container" id="goshtFormSection">
            <form id="goshtTopshirishForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Katak tanlang:</label>
                        <select id="gosht_katak_id" required>
                            <option value="">Katakni tanlang</option>
                            <?php foreach ($kataklar as $katak): ?>
                                <option value="<?= $katak['id'] ?>">
                                    <?= $katak['katak_nomi'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Topshiriladigan jo'jalar massasi:</label>
                        <input type="text" id="gosht_massasi" required min="1" placeholder="Masalan: 500kg">
                    </div>
                    <div class="form-group">
                        <label>Topshiriladigan jo'jalar soni:</label>
                        <input type="text" id="gosht_soni" required min="1" placeholder="Masalan: 50">
                    </div>
                    <div class="form-group">
                        <label>Topshirish sanasi:</label>
                        <input type="date" id="gosht_sana" required>
                    </div>                    
                </div>
                <div class="form-group">
                    <label>Izoh:</label>
                    <textarea id="gosht_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">🥩 Go'sht so'yish</button>
            </form>
        </div>
        <div class="table-container" id="goshtTableSection" style="display: none;">
            <h3 class="table-title">
                <i class="fas fa-list-alt me-2"></i>So'nggi topshirishlar
            </h3>
            <div class="filter-section">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label for="min-date" class="form-label">
                            <i class="fas fa-calendar-alt me-1"></i>Boshlanish sanasi
                        </label>
                        <input type="date"  id="startDate_meal" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="max-date" class="form-label">
                            <i class="fas fa-calendar-check me-1"></i>Tugash sanasi
                        </label>
                        <input type="date" id="endDate_meal"  class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <button id="filterByDate_meal" class="btn-professional btn-info">🔍 Filterlash</button>
                        <button id="clearFilter_meal" class="btn-professional btn-secondary">❌ Tozalash</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <div class="col-md-4 mb-3">
                    <button id="deleteAllGoshtSoyishBtn" class="btn-professional btn-danger">🗑️ Barcha go'sht so'yishlarni o'chirish</button>
                </div>
                <div id='goshtsoyishcn'>
                </div>
            </div>
        </div>
    </section>
    <div id="deleteAllGoshtSoyishModal" class="modal">
        <div class="modal-content" style="width: 500px; max-width: 90%; margin-top: 20%; ">
            <h3>Barcha go'sht so'yishlarni o'chirish</h3>
            <div id="deleteAllGoshtSoyishContent">
                <p>Diqqat! Bu amal barcha go'sht so'yish yozuvlarini o'chiradi va qaytarib bo'lmaydi. Davom etmoqchimisiz?</p>
                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-danger" id="confirmDeleteAllGoshtSoyishBtn">Ha, o'chirish</button>
                    <button class="btn btn-secondary" onclick="closeModal('deleteAllGoshtSoyishModal')">Bekor qilish</button>
                </div>
            </div>
        </div>
    </div>

    <div id="historyModal" class="modal">
        <div class="modal-content">
            <h3 style="text-align:center; margin-bottom:10px;">Gosht soyish mahsulotlari</h3>
            <div id="historyContent">
                <table id="historyTable" class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>Mahsulot nomi</th>
                            <th>Umumiy miqdori</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="addProductForRowModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>➕ Qator uchun mahsulot qo'shish</h3>
                <button class="close" onclick="closeModal('addProductForRowModal')">&times;</button>
            </div>
            <div id="selectedRowInfo" style="margin-bottom: 20px; padding: 15px; background: #e3f2fd; border-radius: 8px; border-left: 4px solid #2196f3;">
                <h4>Tanlangan qator ma'lumotlari:</h4>
                <div id="selectedRowData">
                </div>
            </div>
            <div id="addedProductsInfoForRow" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <h4>Qo'shilgan mahsulotlar:</h4>
                <div id="addedProductsListForRow">
                    <p>Hozircha mahsulot qo'shilmagan</p>
                </div>
                <div style="margin-top: 10px;">
                    <strong>Jami: <span id="totalProductsForRow">0</span> ta mahsulot</strong>
                </div>
            </div>
            <form id="addProductForRowForm" onsubmit="addProductForRow(event)">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Mahsulotni tanlang:</label>
                        <select id="product_name_row" required>
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
                        <input type="text" id="product_quantity_row" required min="1" placeholder="Miqdorni kiriting">
                    </div>
                </div>                
                <button type="submit" class="btn btn-success">➕ Mahsulot qo'shish</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- 2. DataTables JS va CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script>
        document.getElementById("deleteAllGoshtSoyishBtn").addEventListener("click", function() {
            document.getElementById("deleteAllGoshtSoyishModal").style.display = "block";
        });
        document.getElementById("confirmDeleteAllGoshtSoyishBtn").addEventListener("click", function () {
            deleteAllGoshtSoyish();
            closeModal("deleteAllGoshtSoyishModal");
        });

        const capacityInput_gosht_soni = document.getElementById('gosht_soni');
        IMask(capacityInput_gosht_soni, {
            mask: Number,
            min: 0,
            max: 100000000,
            thousandsSeparator: ' '
        });
        const capacityInput_gosht_massasi = document.getElementById('gosht_massasi');
        IMask(capacityInput_gosht_massasi, {
            mask: Number,
            min: 0,
            max: 100000000,
            thousandsSeparator: ' '
        });
        const capacityInput_product_quantity_row = document.getElementById('product_quantity_row');
        IMask(capacityInput_product_quantity_row, {
            mask: Number,
            min: 0,
            max: 100000000,
            thousandsSeparator: ' '
        });
        const toggleGoshtBtn = document.getElementById('toggleGoshtViewBtn');
        const goshtFormSection = document.getElementById('goshtFormSection');
        const goshtTableSection = document.getElementById('goshtTableSection');

        toggleGoshtBtn.addEventListener('click', () => {
            const isFormVisible = goshtFormSection.style.display !== 'none';

            goshtFormSection.style.display = isFormVisible ? 'none' : 'block';
            goshtTableSection.style.display = isFormVisible ? 'block' : 'none';

            toggleGoshtBtn.innerHTML = isFormVisible 
                ? '➕ Forma ko‘rinishini ko‘rsatish' 
                : '📋 Jadval ko‘rinishini ko‘rsatish';
        });
        
        let addedProducts = [];
        let rowProducts = {};
        $('#goshtTopshirishForm').on('submit', function (event) {
            event.preventDefault();

            const katak_id = $('#gosht_katak_id').val();
            const gosht_soni = $('#gosht_soni').val();
            const gosht_massasi = $('#gosht_massasi').val();
            const gosht_sana = $('#gosht_sana').val();
            const gosht_izoh = $('#gosht_izoh').val();

            $.ajax({
                url: '../form_insert_data/insert_gosht_soyish.php', 
                type: 'POST',
                dataType: 'json',
                data: {
                    katak_id: katak_id,
                    soni: gosht_soni,
                    massasi: gosht_massasi,
                    sana: gosht_sana,
                    izoh: gosht_izoh
                },
                success: function (response) {
                    if (response.status === 'success') {
                        showAlert(response.message, "success");
                        $('#goshtTopshirishForm')[0].reset();
                        loadGoshtSoyish();
                    } else {
                        showAlert(response.message, "error");
                        $('#goshtTopshirishForm')[0].reset();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX xatosi:", error);
                    swal("❌ Serverda xatolik", "Iltimos, qaytadan urinib ko'ring", "error");
                }
            });
        });
        
        const goshtTopshirishData = <?php
            $data = [];
            foreach ($gosht_soyishlar as $soyish) {
                $katak_id = $soyish['katak_id'];
                $kataklar = $db->get_data_by_table('kataklar', ['id' => $katak_id]);
                $katak_nomi = $kataklar['katak_nomi'] ?? 'Noma’lum';
                
                $data[] = [
                    'id' => $soyish['id'],
                    'katak' => $katak_nomi,
                    'soni' => $soyish['joja_soni'],
                    'massasi' => $soyish['massasi'],
                    'sana' => $soyish['sana'],
                    'izoh' => $soyish['izoh'],
                ];
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        ?>;
        function showAddProductForRow(rowId) {
            const modal = document.getElementById('addProductForRowModal');
            document.getElementById('selectedRowData').innerHTML = '';
            document.getElementById('addedProductsListForRow').innerHTML = '<p>Hozircha mahsulot qo\'shilmagan</p>';
            document.getElementById('totalProductsForRow').textContent = '0';
            const selectedRow = goshtTopshirishData.find(item => item.id == rowId); // == (not ===) raqam va string bo'lishi mumkin

            if (selectedRow) {
                document.getElementById('selectedRowData').innerHTML = `
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                        <div><strong>Katak:</strong> ${selectedRow.katak}</div>
                        <div><strong>Jo'jalar massasi:</strong> ${selectedRow.massasi}</div>
                        <div><strong>Jo'jalar soni:</strong> ${selectedRow.soni}</div>
                        <div><strong>Sana:</strong> ${selectedRow.sana}</div>
                        <div><strong>Izoh:</strong> ${selectedRow.izoh}</div>
                    </div>
                `;
                modal.dataset.rowId = rowId;
                modal.style.display = 'flex';
                updateAddedProductsListForRow(rowId);
            }
        }
        // Qator uchun mahsulot qo'shish funksiyasi
        function addProductForRow(event) {
            event.preventDefault();
            const rowId = document.getElementById('addProductForRowModal').dataset.rowId;
            const mahsulotId = document.getElementById('product_name_row').value;
            const miqdor = document.getElementById('product_quantity_row').value;
            console.log(miqdor, mahsulotId);
            
            $.ajax({
                url: '../form_insert_data/get_gosht_soyish.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    gosht_topshirish_id: rowId,
                    mahsulot_id: mahsulotId,
                    miqdor: miqdor
                },
                success: function(response) {
                    if (response.status === 'success') {
                        console.log(response);
                        
                        showAlert("✅ Mahsulot muvaffaqiyatli qo'shildi!", "success");
                        document.getElementById('addProductForRowForm').reset();
                        updateAddedProductsListForRow(rowId); 
                        loadGoshtSoyish();
                    } else {
                        showAlert("❗ Xatolik: " + (response.message || "Ma'lumotlar qo'shilmadi"), "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX xatosi:", error);
                    swal("❌ Server xatosi", "Iltimos qayta urinib ko‘ring", "error");
                }
            });
        }
        // Qator uchun qo'shilgan mahsulotlar ro'yxatini yangilash
        function updateAddedProductsListForRow(rowId) {
            const listContainer = document.getElementById('addedProductsListForRow');
            const totalProducts = document.getElementById('totalProductsForRow');
            
            const products = rowProducts[rowId] || [];
            
            if (products.length === 0) {
                listContainer.innerHTML = '<p>Hozircha mahsulot qo\'shilmagan</p>';
                totalProducts.textContent = '0';
                return;
            }
            
            let html = '<div style="max-height: 200px; overflow-y: auto;">';
            products.forEach(product => {
                html += `
                    <div style="padding: 8px; margin: 5px 0; background: white; border-radius: 4px; border-left: 3px solid #28a745;">
                        <strong>${product.name}</strong> - ${product.quantity} ${product.unit} 
                        <span style="color: #666;">(${product.price.toLocaleString()} so'm)</span>
                        ${product.comment ? `<br><small style="color: #888;">${product.comment}</small>` : ''}
                    </div>
                `;
            });
            html += '</div>';
            
            listContainer.innerHTML = html;
            totalProducts.textContent = products.length;
        }
        
        // Mahsulot qo'shish funksiyasi
        function addProduct(event) {
            event.preventDefault();
            
            const name = document.getElementById('product_name').value;
            const quantity = document.getElementById('product_quantity').value;
            const unit = document.getElementById('product_unit').value;
            const price = document.getElementById('product_price').value;
            const comment = document.getElementById('product_comment').value;
            
            const newProduct = {
                id: addedProducts.length + 1,
                name: name,
                quantity: parseInt(quantity),
                unit: unit,
                price: parseInt(price),
                comment: comment,
                sana: new Date().toISOString().split('T')[0]
            };
            
            addedProducts.push(newProduct);
            updateAddedProductsList();
            
            // Formani tozalash
            document.getElementById('addProductForm').reset();
            
            alert('Mahsulot muvaffaqiyatli qo\'shildi!');
        }

        // Qo'shilgan mahsulotlar ro'yxatini yangilash
        function updateAddedProductsList() {
            const listContainer = document.getElementById('addedProductsList');
            const totalProducts = document.getElementById('totalProducts');
            
            if (addedProducts.length === 0) {
                listContainer.innerHTML = '<p>Hozircha mahsulot qo\'shilmagan</p>';
                totalProducts.textContent = '0';
                return;
            }
            
            let html = '<div style="max-height: 200px; overflow-y: auto;">';
            addedProducts.forEach(product => {
                html += `
                    <div style="padding: 8px; margin: 5px 0; background: white; border-radius: 4px; border-left: 3px solid #28a745;">
                        <strong>${product.name}</strong> - ${product.quantity} ${product.unit} 
                        <span style="color: #666;">(${product.price.toLocaleString()} so'm)</span>
                        ${product.comment ? `<br><small style="color: #888;">${product.comment}</small>` : ''}
                    </div>
                `;
            });
            html += '</div>';
            
            listContainer.innerHTML = html;
            totalProducts.textContent = addedProducts.length;
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'none';
        }

        function viewDetails(rowId) {
            const modal = document.getElementById('historyModal');
            const tbody = document.getElementById('historyTableBody');
            const historyTable = $('#historyTable');

            if ($.fn.DataTable.isDataTable('#historyTable')) {
                historyTable.DataTable().destroy();
            }

            modal.style.display = 'flex';
            tbody.innerHTML = `
                <tr><td colspan="2" style="text-align:center; padding:15px;">
                    ⏳ Ma'lumotlar yuklanmoqda...
                </td></tr>
            `;

            fetch('../api/get_gosht_mahsulotlar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + rowId
            })
            .then(res => res.json())
            .then(data => {
                console.log(data);
                
                if (!Array.isArray(data) || data.length === 0) {
                    tbody.innerHTML = `
                        <tr><td colspan="2" style="text-align:center; color:gray;">
                            ❗ Ma'lumot topilmadi
                        </td></tr>`;
                    return;
                }
                const grouped = {};
                data.forEach(item => {
                    const id = item.mahsulot_id;
                    if (!grouped[id]) {
                        grouped[id] = {
                            mahsulot_nomi: item.mahsulot_nomi,
                            jami_soni: 0,
                            details: []
                        };
                    }
                    grouped[id].jami_soni += parseFloat(item.soni);
                    grouped[id].details.push({
                        sana: item.created_at ? item.created_at.split(' ')[0] : '-',
                        soni: item.soni
                    });
                });

                tbody.innerHTML = '';
                let totalAll = 0;

                // Har bir mahsulot uchun asosiy qator
                Object.keys(grouped).forEach(mahsulot_id => {
                    const g = grouped[mahsulot_id];
                    totalAll += g.jami_soni;

                    const mainRow = document.createElement('tr');
                    mainRow.innerHTML = `
                        <td style="cursor:pointer;" class="expandable" data-id="${mahsulot_id}">
                            ▶️ ${g.mahsulot_nomi}
                        </td>
                        <td>${g.jami_soni}</td>
                    `;
                    tbody.appendChild(mainRow);
                });

                // Qatorni bosganda detallarni ochish
                tbody.querySelectorAll('.expandable').forEach(cell => {
                    cell.addEventListener('click', function() {
                        const mahsulot_id = this.dataset.id;
                        const existing = this.parentElement.nextElementSibling;
                        if (existing && existing.classList.contains('details-row')) {
                            existing.remove(); // agar ochilgan bo‘lsa yopamiz
                            return;
                        }

                        const details = grouped[mahsulot_id].details;
                        let rows = `<tr class="details-row"><td colspan="2">
                            <table class="table table-sm table-bordered">
                                <thead><tr><th>Sana</th><th>Miqdori</th></tr></thead><tbody>`;

                        details.forEach(d => {
                            rows += `<tr><td>${d.sana}</td><td>${d.soni}</td></tr>`;
                        });

                        rows += `</tbody></table></td></tr>`;
                        this.parentElement.insertAdjacentHTML('afterend', rows);
                    });
                });

                historyTable.DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    language: {
                        search: "Qidiruv:",
                        lengthMenu: "Har sahifada _MENU_ ta yozuv",
                        info: "Jami _TOTAL_ yozuvdan _START_–_END_ ko‘rsatilmoqda",
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
            })
            .catch(err => {
                console.error('Xatolik:', err);
                tbody.innerHTML = `<tr><td colspan="2" style="color:red;">❌ Ma'lumot yuklashda xatolik</td></tr>`;
            });
        }



        function editRecord(id) {
            alert(`Tahrirlash funksiyasi ID: ${id} uchun ishlab chiqilmoqda...`);
        }

        window.onclick = function(event) {
            const historyModal = document.getElementById('historyModal');
            const addProductForRowModal = document.getElementById('addProductForRowModal');
            
            if (event.target === historyModal) {
                historyModal.classList.remove('show');
            }
            
            if (event.target === addProductForRowModal) {
                addProductForRowModal.classList.remove('show');
            }
        }
        function deleteGoshtSoyish(id) {
            swal({
                title: "❗ Tasdiqlash",
                text: "Bu yozuvni o'chirmoqchimisiz?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: 'delete/delete_gosht_soyish.php',
                        type: 'POST',
                        dataType: 'json',
                        data: { id: id },
                        success: function(response) {
                            if (response.success) {
                                showAlert("✅ Yozuv muvaffaqiyatli o'chirildi!", "success");
                                loadGoshtSoyish();
                            } else {
                                showAlert("❗ Xatolik: " + (response.message || "Yozuv o'chirilmadi"), "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            swal("❌ Server xatosi", "Iltimos qayta urinib ko‘ring", "error");
                        }
                    });
                }
            });
        }
        function deleteAllGoshtSoyish() {
            $.ajax({
                url: 'delete/delete_all_gosht_soyish.php',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert("✅ Barcha yozuvlar muvaffaqiyatli o'chirildi!", "success");
                        loadGoshtSoyish();
                    } else {
                        showAlert("❗ Xatolik: " + (response.message || "Yozuvlar o'chirilmadi"), "error");
                    }
                },
                error: function(xhr, status, error) {
                    swal("❌ Server xatosi", "Iltimos qayta urinib ko‘ring", "error");
                }
            });
        }
        // Sahifa yuklanganda bugungi sanani o'rnatish
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('gosht_sana').value = today;
        });
    </script>
</body>
</html>