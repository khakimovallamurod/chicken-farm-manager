
<?php
    include_once '../config.php';
    $db = new Database();
    $kataklar = $db->get_data_by_table_all('kataklar');
    $mahsulotlar = $db->get_data_by_table_all('mahsulotlar', "WHERE categoriya_id = 1");
    
?>
<style>
  .btn-professional {
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }
    
    .btn-professional:hover {
        transform: translateY(-2px);
        color: white;
    }
    
    .btn-professional:active {
        transform: translateY(0);
    }
    .btn-professional::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-professional:hover::before {
        left: 100%;
    }
    
    .btn-professional.btn-primary {
        background: linear-gradient(145deg, #3664e4ff 0%, #764ba2 100%);
        box-shadow: 0 6px 20px rgba(44, 78, 230, 0.4);
    }
    
    .btn-professional:hover {
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        background: linear-gradient(145deg, #5a67d8 0%, #6b46c1 100%);
    }
    
    .btn-professional.btn-primary:active {
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .btn-professional.btn-secondary {
        background: linear-gradient(145deg, #718096 0%, #4a5568 100%);
        box-shadow: 0 6px 20px rgba(113, 128, 150, 0.4);
    }
    
    .btn-professional.btn-secondary:hover {
        box-shadow: 0 8px 25px rgba(113, 128, 150, 0.6);
        background: linear-gradient(145deg, #dd6b20 0%, #c05621 100%);
    }
    
    .btn-professional.btn-secondary:active {
        box-shadow: 0 4px 15px rgba(113, 128, 150, 0.4);
    }
  
</style>
<section id="joja" class="content-section">
  <div class="section-header">
    <h2 class="section-title">🐥 Jo'ja qo'shish</h2>
    <div style="margin-top: 1rem;">
      <button id="toggleJojaViewBtn" class="btn btn-outline-primary">📋 Jadval ko‘rinishini ko‘rsatish</button>
    </div>
  </div>

  <div id="jojaFormSection">
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
  </div>

  <div class="table-container" id="jojaTableSection" style="display: none;">
    <h3 class="table-title">
      <i class="fas fa-list-alt me-2"></i>Qo'shilgan jo'jalar ro'yxati
    </h3>
    <div class="filter-section">
        <div class="row align-items-end">
            <div class="col-md-4 mb-3">
                <label for="min-date" class="form-label">
                    <i class="fas fa-calendar-alt me-1"></i>Boshlanish sanasi
                </label>
                <input type="date"  id="startDate_joja" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label for="max-date" class="form-label">
                    <i class="fas fa-calendar-check me-1"></i>Tugash sanasi
                </label>
                <input type="date" id="endDate_joja"  class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <button id="filterByDate_joja" class="btn-professional btn-info">🔍 Filterlash</button>
                <button id="clearFilter_joja" class="btn-professional btn-secondary">❌ Tozalash</button>
            </div>
        </div>
    </div>
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
          <?php
          $query_for_oj = "
            SELECT 
              j.id AS joja_id, j.sana, j.soni, j.narxi, j.izoh, k.katak_nomi, m.nomi AS mahsulot_nomi 
            FROM joja j 
            INNER JOIN kataklar k ON j.katak_id = k.id 
            INNER JOIN mahsulotlar m ON j.mahsulot_id = m.id 
            ORDER BY j.sana DESC;";
          $fetch = $db->query($query_for_oj);
          while ($joja_row = mysqli_fetch_assoc($fetch)): ?>
            <tr>
              <td><span class="badge-katak"><?= htmlspecialchars($joja_row['katak_nomi']) ?></span></td>
              <td><?= htmlspecialchars($joja_row['mahsulot_nomi']) ?></td>
              <td><span class="badge bg-success"><?= htmlspecialchars($joja_row['soni']) ?> dona</span></td>
              <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($joja_row['narxi']) ?> so'm</span></td>
              <td data-order="<?= $joja_row['sana'] ?>"><?= date('d.m.Y', strtotime($joja_row['sana'])) ?></td>
              <td><?= htmlspecialchars($joja_row['izoh']) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
    const toggleBtn = document.getElementById('toggleJojaViewBtn');
    const formSection = document.getElementById('jojaFormSection');
    const tableSection = document.getElementById('jojaTableSection');

    toggleBtn.addEventListener('click', () => {
        const isFormVisible = formSection.style.display !== 'none';
        
        formSection.style.display = isFormVisible ? 'none' : 'block';
        tableSection.style.display = isFormVisible ? 'block' : 'none';
        toggleBtn.innerHTML = isFormVisible ? '➕ Forma ko‘rinishini ko‘rsatish' : '📋 Jadval ko‘rinishini ko‘rsatish';
    });
    $(document).ready(function () {
      var table_joja = $('#qoshilganJojalarTable').DataTable({
          order: [[4, 'desc']], 
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
    
      function filterByDateRangeJoja(settings, data, dataIndex) {
          var start = $('#startDate_joja').val();
          var end = $('#endDate_joja').val();
          var dateStr = data[4]; 

          if (!start && !end) {
              return true;
          }
          var parts = dateStr.split('.');
          var convertedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
          var rowDate = new Date(convertedDate);
          if (start) start = new Date(start);
          if (end) end = new Date(end);

          return (!start || rowDate >= start) && (!end || rowDate <= end);
      }

      $('#filterByDate_joja').on('click', function () {
          $.fn.dataTable.ext.search = []; 
          $.fn.dataTable.ext.search.push(filterByDateRangeJoja);
          table_joja.draw();
      });

      $('#clearFilter_joja').on('click', function () {
          $('#startDate_joja').val('');
          $('#endDate_joja').val('');
          $.fn.dataTable.ext.search = []; 
          table_joja.draw();
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

