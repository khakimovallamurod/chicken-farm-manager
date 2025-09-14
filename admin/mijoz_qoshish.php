
<section id="mijozqoshish" class="content-section">
    <div class="section-header">
        <h2 class="section-title">👨‍💼 Mijoz qo'shish</h2>
        <div style="margin-top: 1rem;">
            <button id="toggleMijozViewBtn" class="expense-btn">📋 Jadval ko‘rinishini ko‘rsatish</button>
        </div>
    </div>
    <div id="mijozFormSection">
        <form id="mijozQoshishForm">
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
    
    
    <div class="table-container" id="mijozTableSection" style="display: none;">
        <h3>Mijozlar ro'yxati</h3>
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="min-date" class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i>Boshlanish sanasi
                    </label>
                    <input type="date"  id="startDate" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="max-date" class="form-label">
                        <i class="fas fa-calendar-check me-1"></i>Tugash sanasi
                    </label>
                    <input type="date" id="endDate"  class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <button id="filterByDate" class="btn-professional btn-info me-2">🔍 Filterlash</button>
                    <button id="clearFilter" class="btn-professional btn-secondary">❌ Tozalash</button>
                </div>
            </div>
        </div>
        <div id="mijozqoshishcn">

        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        const toggleMijoznBtn = $('#toggleMijozViewBtn');
        const mijozFormSection = $('#mijozFormSection');
        const mijozTableSection = $('#mijozTableSection');

        toggleMijoznBtn.on('click', function () {
            const isFormVisible = mijozFormSection.is(':visible');
            mijozFormSection.toggle(!isFormVisible);
            mijozTableSection.toggle(isFormVisible);
            toggleMijoznBtn.html(isFormVisible 
                ? '➕ Forma ko‘rinishini ko‘rsatish' 
                : '📋 Jadval ko‘rinishini ko‘rsatish');
        });

        $('#mijozQoshishForm').on('submit', function (event) {
            event.preventDefault();
            const data = {
                ismi: $('#mijoz_ismi').val(),
                telefon: $('#mijoz_telefon').val(),
                manzil: $('#mijoz_manzil').val(),
                izoh: $('#mijoz_izoh').val()
            };
            
            $.ajax({
                url: '../form_insert_data/add_mijoz.php',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                dataType: 'json',
                success: function (result) {
                    if (result.success) {
                        showAlert(result.message, 'success');
                        loadMijozQoshish();
                    } else {
                        showAlert(result.message, 'error');
                    }
                    $('#mijozQoshishForm')[0].reset();
                },
                error: function () {
                    swal({
                        title: "Xatolik!",
                        text: "Server bilan bog'lanishda xatolik yuz berdi.",
                        icon: "error",
                        button: "OK",
                    });
                }
            });
        });
    });

</script>
