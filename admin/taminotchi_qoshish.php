
<style>
    .expense-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .expense-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.35);
    }
</style>
<section id="taminotchilar" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🚛 Ta'minotchilar</h2>
        <button class="btn btn-primary" onclick="showModal('taminotchiModal')">
            ➕ Yangi ta'minotchi qo'shish
        </button>
    </div>
    <div style="margin-bottom: 1rem;">
        <button id="toggleTaminotchiBtn" class="expense-btn">📋 Jadval ko‘rinishini ko‘rsatish</button>
    </div>
    <div id="taminotchiqoshishcn">

    </div>
</section>
<div id="taminotchiModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('taminotchiModal')">&times;</span>
        <h2>🚛 Yangi ta'minotchi qo'shish</h2>
        <form id="taminotchiForm">
            <div class="form-grid">
                <div class="form-group">
                    <label>Kompaniya nomi:</label>
                    <input type="text" id="taminotchi_nomi" required placeholder="Masalan: Yem Zavodi LLC">
                </div>
                <div class="form-group">
                    <label>Kontakt shaxs:</label>
                    <input type="text" id="taminotchi_kontakt" required placeholder="Ism-familiya">
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Telefon:</label>
                    <input type="tel" id="taminotchi_telefon" required placeholder="+998 71 123 45 67">
                </div>
            </div>
            <div class="form-group">
                <label>Manzil:</label>
                <textarea id="taminotchi_manzil" rows="2" placeholder="Kompaniya manzili..."></textarea>
            </div>
            <div class="form-group">
                <label>Ta'minlaydigan mahsulotlar:</label>
                <textarea id="taminotchi_mahsulotlar" rows="2" placeholder="Masalan: Tovuq yemi, Vitaminlar, Jo'jalar..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">✅ Ta'minotchi qo'shish</button>
        </form>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    
    document.getElementById('toggleTaminotchiBtn').addEventListener('click', function() {
        const grid = document.getElementById('taminotchiGridView');
        const table = document.getElementById('taminotchiTableView');
        
        if (grid.style.display === 'none') {
            grid.style.display = 'grid';
            table.style.display = 'none';
            this.innerText = '📋 Jadval ko‘rinishini ko‘rsatish';
        } else {
            grid.style.display = 'none';
            table.style.display = 'block';
            this.innerText = '📦 Katak ko‘rinishini ko‘rsatish';
        }
    });
    $(document).ready(function() {
        $('#taminotchiTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/uz.json' 
            }
        });
    });
    $('#taminotchiForm').on('submit', function (event) {
        event.preventDefault(); 
        const nomi = $('#taminotchi_nomi').val();
        const kontakt = $('#taminotchi_kontakt').val();
        const telefon = $('#taminotchi_telefon').val();
        const manzil = $('#taminotchi_manzil').val();
        const mahsulotlar = $('#taminotchi_mahsulotlar').val();

        const data = {
            nomi: nomi,
            kontakt: kontakt,
            telefon: telefon,
            manzil: manzil,
            mahsulotlar: mahsulotlar
        };
        console.log(data);
        $.ajax({
            url: '../form_insert_data/add_taminotchi.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#taminotchiForm')[0].reset();
                    closeModal('taminotchiModal');
                    loadTaminotchiQoshish();
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                showAlert("Xatolik yuz berdi. Iltimos qayta urinib ko'ring.", 'error');
            }
        });
    });
</script>