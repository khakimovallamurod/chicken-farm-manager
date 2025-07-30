<?php
include_once '../config.php';
$db = new Database();
$kataklar = $db->get_data_by_table_all('kataklar');
$mahsulotlar = $db->get_data_by_table_all('mahsulotlar', "WHERE categoriya_id = 2"); 
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Professional Table Styling */
.table-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 25px;
    margin-top: 30px;
}

.table-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 25px;
    font-size: 1.4rem;
}

.filter-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid #007bff;
}

.filter-section label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 5px;
}

.filter-section .form-control {
    border-radius: 6px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.filter-section .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.btn-clear {
    background: linear-gradient(45deg, #6c757d, #5a6268);
    border: none;
    color: white;
    border-radius: 6px;
    padding: 8px 16px;
    transition: all 0.3s ease;
}

.btn-clear:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(108,117,125,0.3);
    color: white;
}

/* DataTable Custom Styling */
#yemBerishTable {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

#yemBerishTable thead th {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border: none;
    padding: 15px 12px;
}

#yemBerishTable tbody td {
    padding: 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
    transition: all 0.2s ease;
}

#yemBerishTable tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
    transform: translateY(-1px);
}

.badge-katak {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.miqdor-badge {
    background: linear-gradient(45deg, #ffc107, #ff9800);
    color: #333;
    padding: 5px 10px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.85rem;
}

/* DataTables Controls Styling */
.dataTables_wrapper .dataTables_length select {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 5px 10px;
    color: #495057;
}

.dataTables_wrapper .dataTables_filter input {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 8px 12px;
    margin-left: 10px;
    transition: all 0.3s ease;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    outline: none;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 6px;
    margin: 0 2px;
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white !important;
    border-color: #007bff;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f8f9fa;
    color: #007bff !important;
    border-color: #007bff;
    transform: translateY(-1px);
}

.dataTables_wrapper .dataTables_info {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .filter-section {
        padding: 15px;
    }
    
    .table-container {
        padding: 15px;
    }
    
    #yemBerishTable thead th,
    #yemBerishTable tbody td {
        padding: 8px 6px;
        font-size: 0.85rem;
    }
}

/* Loading Animation */
.dataTables_processing {
    background: rgba(255,255,255,0.9);
    color: #007bff;
    font-weight: 600;
    border-radius: 8px;
    padding: 20px;
}
</style>

<section id="yem" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🌾 Yem berish</h2>
    </div>
    <form id="yemForm" onsubmit="addYem(event)">
        <div class="form-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 20px;">
            <div class="form-group">
                <label>Katak tanlang:</label>
                <select id="yem_katak_id" required>
                    <option value="">Katakni tanlang</option>
                    <?php foreach ($kataklar as $katak): ?>
                        <option value="<?= $katak['id'] ?>">
                            <?= $katak['katak_nomi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Sana:</label>
                <input type="date" id="yem_sana" required>
            </div>
            <div class="form-group">
                <label>Yem turi:</label>
                <select id="yem_turi" required>
                    <option value="">Yem turini tanlang</option>
                    <?php foreach ($mahsulotlar as $mahsulot): ?>
                        <option value="<?= $mahsulot['id'] ?>">
                            <?= $mahsulot['nomi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Yem miqdori (kg):</label>
                <input type="number" id="yem_miqdori" required min="1" placeholder="Masalan: 50">
            </div>
        </div>                   
        <div class="form-group">
            <label>Izoh:</label>
            <textarea id="yem_izoh" rows="3" placeholder="Yem turi, sifati haqida..."></textarea>
        </div>
        <button type="submit" class="btn btn-success">🌾 Yem berish</button>
    </form>
    
    <?php
        include_once '../config.php';
        $db = new Database();
        $query_for_oj = "
        SELECT yb.id AS yem_id, yb.sana, yb.miqdori, yb.izoh, k.katak_nomi, m.nomi AS mahsulot_nomi 
        FROM yem_berish yb INNER JOIN kataklar k ON yb.katak_id = k.id 
        INNER JOIN mahsulotlar m ON yb.mahsulot_id = m.id ORDER BY yb.sana DESC;
        ";
        $fetch = $db->query($query_for_oj);
    ?>
    
    <div class="table-container">
        <h3 class="table-title">
            <i class="fas fa-list-alt me-2"></i>Yem berish ro'yxati
        </h3>
        
        <!-- Professional Filter Section -->
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="min-date" class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i>Boshlanish sanasi
                    </label>
                    <input type="date" id="min-date" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="max-date" class="form-label">
                        <i class="fas fa-calendar-check me-1"></i>Tugash sanasi
                    </label>
                    <input type="date" id="max-date" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <button id="clear-dates" type="button" class="btn btn-clear">
                        <i class="fas fa-eraser me-1"></i>Tozalash
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Professional Table -->
        <div class="table-responsive">
            <table id="yemBerishTable" class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="fas fa-home me-1"></i>Katak</th>
                        <th><i class="fas fa-seedling me-1"></i>Yem turi</th>
                        <th><i class="fas fa-weight-hanging me-1"></i>Miqdori</th>
                        <th><i class="fas fa-calendar me-1"></i>Sana</th>
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
                                <span class="miqdor-badge">
                                    <?= htmlspecialchars($joja_row['miqdori']) ?> kg
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

<!-- Scripts -->
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Set today's date
    document.getElementById('yem_sana').valueAsDate = new Date();
    
    // Initialize DataTable with professional settings
    var table = $('#yemBerishTable').DataTable({
        "language": {
            "decimal": "",
            "emptyTable": "Jadvalda ma'lumot yo'q",
            "info": "_TOTAL_ ta yozuvdan _START_ dan _END_ gacha ko'rsatilmoqda",
            "infoEmpty": "0 ta yozuvdan 0 dan 0 gacha ko'rsatilmoqda",
            "infoFiltered": "(_MAX_ ta yozuvdan filtrlangan)",
            "lengthMenu": "Har sahifada _MENU_ ta yozuv ko'rsatish",
            "loadingRecords": "Yuklanmoqda...",
            "processing": "Ma'lumotlar ishlanmoqda...",
            "search": "Qidirish:",
            "searchPlaceholder": "Qidirish uchun yozing...",
            "zeroRecords": "Hech qanday mos yozuv topilmadi",
            "paginate": {
                "first": "Birinchi",
                "last": "Oxirgi", 
                "next": "Keyingi",
                "previous": "Oldingi"
            },
            "aria": {
                "sortAscending": ": o'sish tartibida saralash",
                "sortDescending": ": kamayish tartibida saralash"
            }
        },
        "pageLength": 10,
        "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
        "order": [[3, 'desc']], // Sort by date column (newest first)
        "responsive": true,
        "processing": true,
        "columnDefs": [
            {
                "targets": [3], // Date column
                "type": "date"
            },
            {
                "targets": [2], // Amount column
                "className": "text-center"
            },
            {
                "targets": [0], // Katak column
                "className": "text-center"
            }
        ],
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "paging": true,
        "info": true
    });

    // Custom date range filter function
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            // Only apply this filter to our specific table
            if (settings.nTable.id !== 'yemBerishTable') {
                return true;
            }
            
            var min = $('#min-date').val();
            var max = $('#max-date').val();
            
            // If no date filters are set, show all rows
            if (!min && !max) {
                return true;
            }
            
            // Get the date from the data-order attribute of the date cell
            var $row = $(settings.nTable).find('tbody tr').eq(dataIndex);
            var dateStr = $row.find('td').eq(3).attr('data-order');
            
            if (!dateStr) {
                return true;
            }
            
            // Convert strings to Date objects for comparison
            var rowDate = new Date(dateStr);
            var minDate = min ? new Date(min) : null;
            var maxDate = max ? new Date(max) : null;
            
            // Apply the date range filter
            if (minDate && maxDate) {
                return rowDate >= minDate && rowDate <= maxDate;
            } else if (minDate) {
                return rowDate >= minDate;
            } else if (maxDate) {
                return rowDate <= maxDate;
            }
            
            return true;
        }
    );

    // Event handlers for date filters
    $('#min-date, #max-date').on('change', function() {
        table.draw();
    });

    // Clear date filters
    $('#clear-dates').on('click', function() {
        $('#min-date, #max-date').val('');
        table.draw();
    });
});

function addYem(event) {
    event.preventDefault();
    
    const katakId = $('#yem_katak_id').val();
    const sana = $('#yem_sana').val();
    const yemTuri = $('#yem_turi').val();
    const miqdori = $('#yem_miqdori').val();
    const izoh = $('#yem_izoh').val();

    // Frontend validation
    if (!katakId || !sana || !yemTuri || !miqdori) {
        swal({
            title: "Xatolik!",
            text: "Barcha majburiy maydonlarni to'ldiring!",
            icon: "warning",
            button: "OK"
        });
        return;
    }

    if (miqdori <= 0) {
        swal({
            title: "Xatolik!",
            text: "Yem miqdori 0 dan katta bo'lishi kerak!",
            icon: "warning",
            button: "OK"
        });
        return;
    }

    const data = {
        katak_id: katakId,
        sana: sana,
        yem_turi: yemTuri,
        miqdori: miqdori,
        izoh: izoh
    };

    // Show loading
    swal({
        title: "Yuklanmoqda...",
        text: "Ma'lumotlar saqlanmoqda",
        icon: "info",
        buttons: false,
        closeOnClickOutside: false,
        closeOnEsc: false
    });

    $.ajax({
        url: '../form_insert_data/yem_berish.php',
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: 'json',
        timeout: 10000, // 10 second timeout
        success: function(result) {
            if (result.success) {
                swal({
                    title: "Muvaffaqiyat!",
                    text: result.message || "Yem berish muvaffaqiyatli qo'shildi!",
                    icon: "success",
                    button: "OK"
                }).then(() => {
                    // Reset form
                    $('#yemForm')[0].reset();
                    document.getElementById('yem_sana').valueAsDate = new Date();
                    
                    // Reload page to show new data
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                });
            } else {
                swal({
                    title: "Xatolik!",
                    text: result.message || "Ma'lumot qo'shishda xatolik yuz berdi!",
                    icon: "error",
                    button: "OK"
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {xhr, status, error});
            
            let errorMessage = "Ma'lumotlar bazasiga qo'shishda xatolik yuz berdi!";
            
            if (status === 'timeout') {
                errorMessage = "Vaqt tugadi! Iltimos qaytadan urinib ko'ring.";
            } else if (xhr.status === 404) {
                errorMessage = "Server fayli topilmadi!";
            } else if (xhr.status === 500) {
                errorMessage = "Server xatosi yuz berdi!";
            }
            
            swal({
                title: "Xatolik!",
                text: errorMessage,
                icon: "error",
                button: "OK"
            });
        }
    });
}

function showAlert(message, type) {
    const iconType = type === 'success' ? 'success' : type === 'error' ? 'error' : 'info';
    const title = type === 'success' ? 'Muvaffaqiyat!' : type === 'error' ? 'Xatolik!' : 'Ma\'lumot';
    
    swal({
        title: title,
        text: message,
        icon: iconType,
        button: "OK"
    });
}
</script>