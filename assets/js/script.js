let data = {
    kataklar: [],
    jojalar: [],
    yemlar: [],
    olgan_jojalar: [],
    gosht_topshirish: [],
    harajatlar: [],
    gosht_sotish: [],
    pul_olish: []
};

// Sahifa yuklanganda
document.addEventListener('DOMContentLoaded', function() {
    // Bugungi sanani o'rnatish
    const today = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.value = today;
    });

    loadKataklar();
    loadStats();
});

// Bo'limlarni ko'rsatish
function showSection(sectionId) {
    // Barcha bo'limlarni yashirish
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Barcha tugmalardan active klassini olib tashlash
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Tanlangan bo'limni ko'rsatish
    document.getElementById(sectionId).classList.add('active');
    
    // Tanlangan tugmaga active klassi qo'shish
    event.target.classList.add('active');

    // Agar kataklar bo'limi tanlangan bo'lsa, kataklar ro'yxatini yangilash
    if (sectionId === 'kataklist') {
        loadKataklar();
    }
    
    // Agar hisobot bo'limi tanlangan bo'lsa, statistikalarni yangilash
    if (sectionId === 'hisobot') {
        loadStats();
    }
}

// Modal oynalarni boshqarish
function showModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Xabar ko'rsatish
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.getElementById('alertContainer');
    container.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Katak yaratish
document.getElementById('katakForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const katak = {
        id: Date.now(),
        nomi: document.getElementById('katak_nomi').value,
        izoh: document.getElementById('katak_izoh').value,
        yaratilgan_sana: new Date().toISOString().split('T')[0],
        joja_soni: 0
    };
    
    data.kataklar.push(katak);
    
    showAlert('Katak muvaffaqiyatli yaratildi!');
    closeModal('katakModal');
    document.getElementById('katakForm').reset();
    loadKataklar();
    updateSelectOptions();
});

// Kataklar ro'yxatini yuklash
function loadKataklar() {
    const container = document.getElementById('kataklar-container');
    
    if (data.kataklar.length === 0) {
        container.innerHTML = '<p>Hech qanday katak yaratilmagan.</p>';
        return;
    }
    
    let html = '';
    data.kataklar.forEach(katak => {
        const jojaCount = getKatakJojaCount(katak.id);
        html += `
            <div class="card">
                <h3>${katak.nomi}</h3>
                <p><strong>Jo'jalar soni:</strong> ${jojaCount}</p>
                <p><strong>Yaratilgan sana:</strong> ${katak.yaratilgan_sana}</p>
                <p><strong>Izoh:</strong> ${katak.izoh || 'Izoh yo\'q'}</p>
            </div>
        `;
    });
    
    container.innerHTML = html;
    updateSelectOptions();
}

// Katakdagi jo'jalar sonini hisoblash
function getKatakJojaCount(katakId) {
    let count = 0;
    
    // Qo'shilgan jo'jalar
    data.jojalar.forEach(joja => {
        if (joja.katak_id == katakId) {
            count += parseInt(joja.soni);
        }
    });
    
    // O'lgan jo'jalar
    data.olgan_jojalar.forEach(olgan => {
        if (olgan.katak_id == katakId) {
            count -= parseInt(olgan.soni);
        }
    });
    
    // Go'shtga topshirilgan
    data.gosht_topshirish.forEach(gosht => {
        if (gosht.katak_id == katakId) {
            count -= parseInt(gosht.soni);
        }
    });
    
    return Math.max(0, count);
}

// Select elementlarini yangilash
function updateSelectOptions() {
    const selects = [
        'joja_katak_id', 'yem_katak_id', 'olgan_katak_id', 'gosht_katak_id'
    ];
    
    selects.forEach(selectId => {
        const select = document.getElementById(selectId);
        const currentValue = select.value;
        
        select.innerHTML = '<option value="">Katakni tanlang</option>';
        
        data.kataklar.forEach(katak => {
            const option = document.createElement('option');
            option.value = katak.id;
            option.textContent = katak.nomi;
            select.appendChild(option);
        });
        
        select.value = currentValue;
    });
    
    // Mijozlar ro'yxatini yangilash
    updateMijozOptions();
}

// Mijozlar ro'yxatini yangilash
function updateMijozOptions() {
    const select = document.getElementById('pul_mijoz_id');
    const currentValue = select.value;
    
    select.innerHTML = '<option value="">Mijozni tanlang</option>';
    
    const mijozlar = [...new Set(data.gosht_sotish.map(item => item.mijoz_nomi))];
    
    mijozlar.forEach(mijoz => {
        const option = document.createElement('option');
        option.value = mijoz;
        option.textContent = mijoz;
        select.appendChild(option);
    });
    
    select.value = currentValue;
}

// Jo'ja qo'shish
document.getElementById('jojaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const joja = {
        id: Date.now(),
        katak_id: document.getElementById('joja_katak_id').value,
        soni: parseInt(document.getElementById('joja_soni').value),
        narxi: parseFloat(document.getElementById('joja_narxi').value),
        summa: parseInt(document.getElementById('joja_soni').value) * parseFloat(document.getElementById('joja_narxi').value),
        sana: document.getElementById('joja_sana').value,
        izoh: document.getElementById('joja_izoh').value
    };
    
    data.jojalar.push(joja);
    
    showAlert('Jo\'ja muvaffaqiyatli qo\'shildi!');
    document.getElementById('jojaForm').reset();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('joja_sana').value = today;
    loadKataklar();
});

// Yem berish
document.getElementById('yemForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const yem = {
        id: Date.now(),
        katak_id: document.getElementById('yem_katak_id').value,
        miqdori: parseFloat(document.getElementById('yem_miqdori').value),
        narxi: parseFloat(document.getElementById('yem_narxi').value),
        summa: parseFloat(document.getElementById('yem_miqdori').value) * parseFloat(document.getElementById('yem_narxi').value),
        sana: document.getElementById('yem_sana').value,
        izoh: document.getElementById('yem_izoh').value
    };
    
    data.yemlar.push(yem);
    
    showAlert('Yem berish muvaffaqiyatli qayd etildi!');
    document.getElementById('yemForm').reset();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('yem_sana').value = today;
});

// O'lgan jo'ja ayirish
document.getElementById('olganJojaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const katakId = document.getElementById('olgan_katak_id').value;
    const olganSoni = parseInt(document.getElementById('olgan_soni').value);
    const mavjudSoni = getKatakJojaCount(katakId);
    
    if (olganSoni > mavjudSoni) {
        showAlert('Xato: Katakda ' + mavjudSoni + ' ta jo\'ja bor, ' + olganSoni + ' ta o\'lgan deb belgilab bo\'lmaydi!', 'error');
        return;
    }
    
    const olgan = {
        id: Date.now(),
        katak_id: katakId,
        soni: olganSoni,
        sana: document.getElementById('olgan_sana').value,
        izoh: document.getElementById('olgan_izoh').value
    };
    
    data.olgan_jojalar.push(olgan);
    
    showAlert('O\'lgan jo\'jalar muvaffaqiyatli ayirildi!');
    document.getElementById('olganJojaForm').reset();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('olgan_sana').value = today;
    loadKataklar();
});

// Go'sht topshirish
document.getElementById('goshtForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const katakId = document.getElementById('gosht_katak_id').value;
    const goshtSoni = parseInt(document.getElementById('gosht_soni').value);
    const mavjudSoni = getKatakJojaCount(katakId);
    
    if (goshtSoni > mavjudSoni) {
        showAlert('Xato: Katakda ' + mavjudSoni + ' ta tovuq bor, ' + goshtSoni + ' ta topshirib bo\'lmaydi!', 'error');
        return;
    }
    
    const gosht = {
        id: Date.now(),
        katak_id: katakId,
        soni: goshtSoni,
        massa: parseFloat(document.getElementById('gosht_massa').value),
        narxi: parseFloat(document.getElementById('gosht_narxi').value),
        summa: parseFloat(document.getElementById('gosht_massa').value) * parseFloat(document.getElementById('gosht_narxi').value),
        sana: document.getElementById('gosht_sana').value,
        izoh: document.getElementById('gosht_izoh').value
    };
    
    data.gosht_topshirish.push(gosht);
    
    showAlert('Go\'sht muvaffaqiyatli topshirildi!');
    document.getElementById('goshtForm').reset();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('gosht_sana').value = today;
    loadKataklar();
});

// Harajat qo'shish
document.getElementById('harajatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const harajat = {
        id: Date.now(),
        kategoriya: document.getElementById('harajat_kategoriya').value,
        summa: parseFloat(document.getElementById('harajat_summa').value),
        sana: document.getElementById('harajat_sana').value,
        izoh: document.getElementById('harajat_izoh').value
    };
    
    data.harajatlar.push(harajat);
    
    showAlert('Harajat muvaffaqiyatli qo\'shildi!');
    document.getElementById('harajatForm').reset();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('harajat_sana').value = today;
});

// Go'sht sotish
document.getElementById('goshtSotishForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const sotish = {
        id: Date.now(),
        mijoz_nomi: document.getElementById('mijoz_nomi').value,
        massa: parseFloat(document.getElementById('sotish_massa').value),
        narxi: parseFloat(document.getElementById('sotish_narxi').value),
        summa: parseFloat(document.getElementById('sotish_massa').value) * parseFloat(document.getElementById('sotish_narxi').value),
        sana: document.getElementById('sotish_sana').value,
        izoh: document.getElementById('sotish_izoh').value,
        tolangan: 0
    };
    
    data.gosht_sotish.push(sotish);
    
    showAlert('Go\'sht sotilishi muvaffaqiyatli qayd etildi!');
    document.getElementById('goshtSotishForm').reset();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('sotish_sana').value = today;
    updateMijozOptions();
});

// Pul olish
document.getElementById('pulOlishForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const pulOlish = {
        id: Date.now(),
        mijoz_nomi: document.getElementById('pul_mijoz_id').value,
        summa: parseFloat(document.getElementById('olingan_summa').value),
        sana: document.getElementById('pul_sana').value,
        izoh: document.getElementById('pul_izoh').value
    };
    
    data.pul_olish.push(pulOlish);
    
    showAlert('Pul olish muvaffaqiyatli qayd etildi!');
    document.getElementById('pulOlishForm').reset();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('pul_sana').value = today;
});

// Statistikalarni yuklash
function loadStats() {
    // Umumiy statistikalar
    const jamiJojalar = data.jojalar.reduce((sum, item) => sum + item.soni, 0);
    const jamiOlganlar = data.olgan_jojalar.reduce((sum, item) => sum + item.soni, 0);
    const jamiTopshirilgan = data.gosht_topshirish.reduce((sum, item) => sum + item.soni, 0);
    const hozirgiJojalar = jamiJojalar - jamiOlganlar - jamiTopshirilgan;
    
    const jamiDaromad = data.gosht_sotish.reduce((sum, item) => sum + item.summa, 0);
    const jamiHarajat = data.harajatlar.reduce((sum, item) => sum + item.summa, 0) + 
                        data.jojalar.reduce((sum, item) => sum + item.summa, 0) +
                        data.yemlar.reduce((sum, item) => sum + item.summa, 0);
    const jamiFoyda = jamiDaromad - jamiHarajat;
    
    const jamiOlinganPul = data.pul_olish.reduce((sum, item) => sum + item.summa, 0);
    const jamiQarz = jamiDaromad - jamiOlinganPul;

    const statsHtml = `
        <div class="stat-card">
            <h3>${hozirgiJojalar}</h3>
            <p>Hozirgi jo'jalar soni</p>
        </div>
        <div class="stat-card">
            <h3>${jamiDaromad.toLocaleString()}</h3>
            <p>Jami daromad</p>
        </div>
        <div class="stat-card">
            <h3>${jamiHarajat.toLocaleString()}</h3>
            <p>Jami harajat</p>
        </div>
        <div class="stat-card">
            <h3>${jamiFoyda.toLocaleString()}</h3>
            <p>Jami foyda</p>
        </div>
        <div class="stat-card">
            <h3>${jamiQarz.toLocaleString()}</h3>
            <p>Jami qarz</p>
        </div>
        <div class="stat-card">
            <h3>${data.kataklar.length}</h3>
            <p>Jami kataklar</p>
        </div>
    `;
    
    document.getElementById('statsGrid').innerHTML = statsHtml;
    
    // Qarzdorlik jadvalini yangilash
    loadQarzdorlikTable();
}

// Qarzdorlik jadvalini yuklash
function loadQarzdorlikTable() {
    const tbody = document.querySelector('#qarzdorlikTable tbody');
    
    // Mijozlar bo'yicha guruplash
    const mijozStats = {};
    
    data.gosht_sotish.forEach(sotish => {
        if (!mijozStats[sotish.mijoz_nomi]) {
            mijozStats[sotish.mijoz_nomi] = {
                jamiSotilgan: 0,
                tolangan: 0
            };
        }
        mijozStats[sotish.mijoz_nomi].jamiSotilgan += sotish.summa;
    });
    
    data.pul_olish.forEach(pul => {
        if (mijozStats[pul.mijoz_nomi]) {
            mijozStats[pul.mijoz_nomi].tolangan += pul.summa;
        }
    });
    
    let html = '';
    Object.keys(mijozStats).forEach(mijoz => {
        const stat = mijozStats[mijoz];
        const qarz = stat.jamiSotilgan - stat.tolangan;
        
        html += `
            <tr>
                <td>${mijoz}</td>
                <td>${stat.jamiSotilgan.toLocaleString()}</td>
                <td>${stat.tolangan.toLocaleString()}</td>
                <td style="color: ${qarz > 0 ? '#e74c3c' : '#27ae60'}">${qarz.toLocaleString()}</td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html || '<tr><td colspan="4">Ma\'lumot yo\'q</td></tr>';
}

// Modal tashqarisida bosilganda yopish
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
};