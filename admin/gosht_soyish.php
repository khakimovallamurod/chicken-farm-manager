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
        </div>
        <div class="form-container">
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
                        <label>Topshiriladigan jo'jalar soni:</label>
                        <input type="number" id="gosht_soni" required min="1" placeholder="Masalan: 50">
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
        <div class="table-container">
            <h3>So'nggi topshirishlar</h3>
            <table>
                <thead>
                    <tr>
                        <th>Katak</th>
                        <th>Jo'jalar soni</th>
                        <th>Sana</th>
                        <th>Izoh</th>
                        <th>Ko'rish</th>
                        <th>Qo'shish</th>
                    </tr>
                </thead>
                <tbody id="goshtTopshirishTable">
                    <?php foreach ($gosht_soyishlar as $soyish): 
                        $katak_id = $soyish['katak_id'];
                        $kataklar = $db->get_data_by_table('kataklar', ['id'=>$katak_id]);
                        $katak_name = $kataklar['katak_nomi'];
                        ?>
                        <tr id="<?= $soyish['id'] ?>">
                            <td><?= $katak_name ?></td>
                            <td><?= $soyish['joja_soni'] ?></td>
                            <td><?= $soyish['sana'] ?></td>
                            <td><?= $soyish['izoh'] ?></td>
                            <td>
                                <button class="icon-btn" onclick="viewDetails(<?= $soyish['id'] ?>)" title="Ko'rish">👁️</button>
                            </td>
                            <td>
                                <button class="icon-btn" onclick="showAddProductForRow(<?= $soyish['id'] ?>)" title="Qo'shish">➕</button>
                            </td>
                        </tr>       
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Tarix ko'rish modali -->
    <div id="historyModal" class="modal">
        <div class="modal-content">
            <div id="historyContent">
                <table>
                    <thead>
                        <tr>
                            <th>Sana</th>
                            <th>Katak</th>
                            <th>Jo'jalar soni</th>
                            <th>Mijoz</th>
                            <th>Kg narxi</th>
                            <th>Jami summa</th>
                            <th>Holati</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody">
                        <!-- Bu yerda tarix ma'lumotlari ko'rsatiladi -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
                       
    <!-- Qator uchun mahsulot qo'shish modali -->
    <div id="addProductForRowModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>➕ Qator uchun mahsulot qo'shish</h3>
                <button class="close" onclick="closeModal('addProductForRowModal')">&times;</button>
            </div>
            <!-- Tanlangan qator ma'lumotlari -->
            <div id="selectedRowInfo" style="margin-bottom: 20px; padding: 15px; background: #e3f2fd; border-radius: 8px; border-left: 4px solid #2196f3;">
                <h4>Tanlangan qator ma'lumotlari:</h4>
                <div id="selectedRowData">
                    <!-- Bu yerda tanlangan qator ma'lumotlari ko'rsatiladi -->
                </div>
            </div>
            <!-- Qo'shilgan mahsulotlar -->
            <div id="addedProductsInfoForRow" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <h4>Qo'shilgan mahsulotlar:</h4>
                <div id="addedProductsListForRow">
                    <p>Hozircha mahsulot qo'shilmagan</p>
                </div>
                <div style="margin-top: 10px;">
                    <strong>Jami: <span id="totalProductsForRow">0</span> ta mahsulot</strong>
                </div>
            </div>
            <!-- Mahsulot qo'shish formi -->
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
                        <input type="number" id="product_quantity_row" required min="1" placeholder="Miqdorni kiriting">
                    </div>
                </div>                
                <button type="submit" class="btn btn-success">➕ Mahsulot qo'shish</button>
            </form>
        </div>
    </div>
   
    <script>
        
        let addedProducts = [];
        let rowProducts = {};
        $('#goshtTopshirishForm').on('submit', function (event) {
            event.preventDefault();

            const katak_id = $('#gosht_katak_id').val();
            const gosht_soni = $('#gosht_soni').val();
            const gosht_sana = $('#gosht_sana').val();
            const gosht_izoh = $('#gosht_izoh').val();

            $.ajax({
                url: '../form_insert_data/insert_gosht_soyish.php', 
                type: 'POST',
                dataType: 'json',
                data: {
                    katak_id: katak_id,
                    soni: gosht_soni,
                    sana: gosht_sana,
                    izoh: gosht_izoh
                },
                success: function (response) {
                    if (response.status === 'success') {
                        showAlert(response.message, "success");
                        $('#goshtTopshirishForm')[0].reset();
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
        // Tarix ko'rish funksiyasi (bu endi ishlatilmaydi, lekin qoldiramiz)
        function showHistory() {
            const modal = document.getElementById('historyModal');
            const historyTableBody = document.getElementById('historyTableBody');
            
            historyTableBody.innerHTML = '';
            
            goshtTopshirishData.forEach(item => {
                const jamiSumma = item.soni * item.narx;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.sana}</td>
                    <td>${item.katak}</td>
                    <td>${item.soni}</td>
                    <td>${item.mijoz}</td>
                    <td>${item.narx.toLocaleString()}</td>
                    <td>${jamiSumma.toLocaleString()}</td>
                    <td><span class="katak-status ${item.holat === 'Topshirildi' ? 'status-active' : 'status-maintenance'}">${item.holat}</span></td>
                `;
                historyTableBody.appendChild(row);
            });
            modal.classList.add('show');
        }
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
                    'sana' => $soyish['sana'],
                    'izoh' => $soyish['izoh'],
                ];
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        ?>;
        // Yangi modal ochish funksiyasi
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
                        showAlert("✅ Mahsulot muvaffaqiyatli qo'shildi!", "success");
                        document.getElementById('addProductForRowForm').reset();
                        updateAddedProductsListForRow(rowId); 
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
            const historyContent = document.getElementById('historyContent');
            
            // Yuklash holatini ko'rsatish
            historyContent.innerHTML = '<div style="text-align: center; padding: 40px;">Yuklanmoqda...</div>';
            
            // Modalni ko'rsatish
            modal.style.display = 'flex';
            
            // Ma'lumotlarni serverdan olish
            fetch('../api/get_gosht_mahsulotlar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + rowId
            })
            .then(response => response.json())
            .then(products => {
                // Modal sarlavhasi
                historyContent.innerHTML = `
                    <div class="modal-header">
                        <h3>📊 Go'sht so'yish mahsulotlari</h3>
                        <button class="close" onclick="closeModal('historyModal')">&times;</button>
                    </div>
                    <div class="modal-body">
                `;
                
                if (products.length > 0) {
                    // Jadval yaratish
                    const table = document.createElement('table');
                    table.className = 'product-table';
                    
                    // Jadval sarlavhasi
                    const thead = document.createElement('thead');
                    thead.innerHTML = `
                        <tr>
                            <th>№</th>
                            <th>Mahsulot nomi</th>
                            <th>Miqdori</th>
                            <th>Narxi (so'm)</th>
                            <th>Jami (so'm)</th>
                            <th>Tavsif</th>
                        </tr>
                    `;
                    table.appendChild(thead);
                    
                    // Jadval tana qismi
                    const tbody = document.createElement('tbody');
                    let totalSum = 0;
                    
                    products.forEach((product, index) => {
                        const rowSum = product.soni * product.narxi;
                        totalSum += rowSum;
                        
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${product.mahsulot_nomi}</td>
                            <td>${product.soni}</td>
                            <td>${parseFloat(product.narxi).toLocaleString()}</td>
                            <td>${rowSum.toLocaleString()}</td>
                            <td>${product.tavsif || '-'}</td>
                        `;
                        tbody.appendChild(row);
                    });
                    
                    // Jami qator
                    const footerRow = document.createElement('tr');
                    footerRow.className = 'total-row';
                    footerRow.innerHTML = `
                        <td colspan="3"><strong>Jami:</strong></td>
                        <td colspan="3"><strong>${totalSum.toLocaleString()} so'm</strong></td>
                    `;
                    tbody.appendChild(footerRow);
                    
                    table.appendChild(tbody);
                    document.querySelector('.modal-body').appendChild(table);
                } else {
                    document.querySelector('.modal-body').innerHTML = `
                        <div class="no-products">
                            <p>Ushbu topshirishga mahsulotlar qo'shilmagan</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Xatolik:', error);
                historyContent.innerHTML = `
                    <div class="error-message">
                        <p>Ma'lumotlarni yuklashda xatolik yuz berdi. Iltimos, qayta urunib ko'ring.</p>
                    </div>
                `;
            });
        }
        function editRecord(id) {
            alert(`Tahrirlash funksiyasi ID: ${id} uchun ishlab chiqilmoqda...`);
        }

        // Modal tashqarisiga bosganda yopish
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

        // Sahifa yuklanganda bugungi sanani o'rnatish
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('gosht_sana').value = today;
        });
    </script>
</body>
</html>