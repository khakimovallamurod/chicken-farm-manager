<div class="kataklar-grid">
    <?php 
        include_once '../../config.php';
        $db = new Database();
        $query = "SELECT 
            k.*,
            COALESCE(j.umumiy_joja, 0) AS umumiy_joja,
            COALESCE(o.olgan_joja, 0) AS olgan_joja,
            COALESCE(y.jami_miqdori, 0) AS yem_miqdori,
            COALESCE(y.jami_summa, 0) AS yem_jami_summa,
            COALESCE(gs_joja.gosht_joja_soni, 0) AS gosht_joja_soni,
            COALESCE(gs_mahsulot.jami_mahsulot_soni, 0) AS gosht_mahsulot_soni,
            COALESCE(gs_mahsulot.jami_summa, 0) AS gosht_jami_summa
        FROM kataklar k
        LEFT JOIN (
            SELECT katak_id, SUM(soni) AS umumiy_joja
            FROM joja
            GROUP BY katak_id
        ) j ON k.id = j.katak_id
        LEFT JOIN (
            SELECT katak_id, SUM(soni) AS olgan_joja
            FROM olgan_jojalar
            GROUP BY katak_id
        ) o ON k.id = o.katak_id
        LEFT JOIN (
            SELECT 
                y.katak_id,
                SUM(y.miqdori) AS jami_miqdori,
                SUM(y.miqdori * m.narxi) AS jami_summa
            FROM yem_berish y
            LEFT JOIN mahsulotlar m ON y.mahsulot_id = m.id
            GROUP BY y.katak_id
        ) y ON k.id = y.katak_id
        LEFT JOIN (
            SELECT katak_id, SUM(joja_soni) AS gosht_joja_soni
            FROM gosht_soyish
            GROUP BY katak_id
        ) gs_joja ON k.id = gs_joja.katak_id
        LEFT JOIN (
            SELECT 
                gs.katak_id,
                SUM(gsm.soni) AS jami_mahsulot_soni,
                SUM(gsm.soni * m.narxi) AS jami_summa
            FROM gosht_soyish gs
            LEFT JOIN gosht_soyish_mahsulot gsm ON gsm.gosht_soyish_id = gs.id
            LEFT JOIN mahsulotlar m ON gsm.mahsulot_id = m.id
            GROUP BY gs.katak_id
        ) gs_mahsulot ON k.id = gs_mahsulot.katak_id
        ORDER BY 
            CASE WHEN k.status = 'active' THEN 0 ELSE 1 END,
            k.created_at DESC;";
        $query_last_price = "SELECT narxi FROM joja ORDER BY sana DESC LIMIT 1;";
        $price = floatval(mysqli_fetch_assoc($db->query($query_last_price))['narxi']);   
        $kataklar = $db->query($query);
        function money($value) {
            return number_format($value, 0, ',', ' ') . " so'm";
        }
        function numberf($value) {
            return number_format($value, 0, ',', ' ');
        }
        while ($katak = mysqli_fetch_assoc($kataklar)) {
            $today = new DateTime();
            $old_date = new DateTime($katak['created_at']);
            $interval = $today->diff($old_date);
            $days = $interval->days;
            $total_joja = (int)$katak['umumiy_joja'];
            $sold_joja = (int)$katak['olgan_joja'];
            $alive_joja = $total_joja - $sold_joja;
            $yem_summa = (float)$katak['yem_jami_summa'];
            $sum_total_joja = $total_joja * $price;
            $sum_sold_joja = $sold_joja * $price;
            $grand_total_sum =  (float)$katak['gosht_jami_summa'] - ($yem_summa + $sum_total_joja + $sum_sold_joja);
            if ($katak['status'] == 'active') {
    ?>
        <div class="katak-card" data-id="<?=$katak['id']?>">
            <div class="katak-header">
                <div class="katak-title"><?=$katak['katak_nomi']?>/<?=$katak['sigimi']?></div>
                <span class="katak-status status-active">Faol</span>
            </div>
            <div class="katak-info">
                <div class="info-item accent">
                    <div class="info-value"><?= numberf($total_joja) ?></div>
                    <div class="info-label">Jo'jalar</div>
                </div>
                <div class="info-item <?= $alive_joja > 0 ? 'accent' : 'neutral' ?>">
                    <div class="info-value"><?= numberf($alive_joja) ?></div>
                    <div class="info-label">Qolgan jo'jalar</div>
                </div>
                <div class="info-item negative">
                    <div class="info-value"><?= numberf($sold_joja) ?></div>
                    <div class="info-label">O'lgan jojalar</div>
                </div>
                <div class="info-item neutral">
                    <div class="info-value"><?= numberf($days) ?></div>
                    <div class="info-label">Kun</div>
                </div>
            </div>
            <div class="money-info">
                <div class="info-item positive">
                    <div class="info-value"><?= money($sum_total_joja) ?></div>
                    <div class="info-label">Jami joja summasi</div>
                </div>
                <div class="info-item negative">
                    <div class="info-value"><?= money($sum_sold_joja) ?></div>
                    <div class="info-label">Jami olgan joja summasi</div>
                </div>
                <div class="info-item neutral">
                    <div class="info-value"><?= money($yem_summa) ?></div>
                    <div class="info-label">Jami yem summasi</div>
                </div>
                <div class="info-item accent">
                    <div class="info-value"><?= money($katak['gosht_jami_summa'])?></div>
                    <div class="info-label">Jami go'sh so'yish summasi</div>
                </div>
                <div class="info-item positive">
                    <div class="info-value"><?= money( $grand_total_sum)?></div>
                    <div class="info-label">Jami foyda</div>
                </div>
            </div>
            <p><?=$katak['izoh']?></p>
            <div class="katak-views">
                <button class="btn btn-info btn-small" onclick="showJojaTarixi(<?=$katak['id']?>, '<?=$katak['katak_nomi']?>')">
                    📊 Jo'ja tarixi
                </button>
                <button class="btn btn-success btn-small" onclick="showYemTarixi(<?=$katak['id']?>, '<?=$katak['katak_nomi']?>')">
                    🌾 Yem berish
                </button>
                <button class="btn btn-danger btn-small" onclick="showOlganTarixi(<?=$katak['id']?>, '<?=$katak['katak_nomi']?>')">
                    💀 O'lgan jo'jalar
                </button>
            </div>
            
            <div class="katak-actions">
                <button class="btn btn-danger" onclick="deleteKatak(this)">O'chirish</button>
                <button class="btn btn-warning" onclick="editKatak(this)">Tahrirlash</button>
            </div>
        </div>
        <?php } else { ?>
            <div class="katak-card inactive" data-id="<?=$katak['id']?>">
                <div class="katak-header">
                    <div class="katak-title"><?=$katak['katak_nomi']?></div>
                    <span class="katak-status status-inactive">Faol emas</span>
                </div>
                <div class="katak-info">
                    <div class="info-item">
                        <div class="info-value"><?=$katak['umumiy_joja']?></div>
                        <div class="info-label">Jo'jalar</div>
                    </div>  
                    <div class="info-item">
                        <div class="info-value"><?=$days?></div>
                        <div class="info-label">Kun</div>
                    </div>          
                    <div class="info-item">
                        <div class="info-value"><?=$katak['sigimi']?></div>
                        <div class="info-label">Sig'im</div>
                    </div>
                </div>
                <p><?=$katak['izoh']?></p>
                
                <div class="katak-actions">
                    <button class="btn btn-danger" onclick="deleteKatak(this)">O'chirish</button>
                    <button class="btn btn-warning" onclick="editKatak(this)">Tahrirlash</button>
                </div>
            </div>
    <?php } }?>
</div>