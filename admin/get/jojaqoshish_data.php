<?php 
include_once '../../config.php';
$db = new Database();
?>
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

<script>
    
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
</script>