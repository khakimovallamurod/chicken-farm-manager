<?php

class Database{
    private $host = 'localhost';
    private $db_name = 'chicken_farm_manager';
    private $username = 'root';
    private $password = '';
    private $link;
    function __construct() {
        $this->link = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        if (!$this->link) {
            exit("Bazaga ulanmadi!");
        }
    }
    public function query($query) {
        
        return mysqli_query($this->link, $query);
    }
    public function get_data_by_table($table, $arr, $con = 'no'){
            $sql = "SELECT * FROM ".$table. " WHERE ";
            $t = '';
            $i=0;
            $n = count($arr);
            foreach($arr as $key=>$val){
                $i++;
                if($i==$n){
                    $t .= "$key = '$val'";
                }else{
                    $t .= "$key = '$val' AND ";
                }
            }
            $sql .= $t;
            if ($con != 'no'){
                $sql .= $con;
            }
            $fetch = mysqli_fetch_assoc($this->query($sql));
            return $fetch;
        }
    public function get_data_by_table_all($table, $con = 'no'){
            $sql = "SELECT * FROM ".$table;
            if ($con != 'no'){
                $sql .= " ".$con;
            }
            $result = $this->query($sql);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        }
    public function insert($table, $arr){
            $sql = "INSERT INTO ".$table. " ";
            $t1 = '';
            $t2 = '';
            $i = 0;
            $n = count($arr);
            foreach($arr as $key=>$val){
                $i++;
                if($i==$n){
                    $t1 .= $key;
                    $t2 .= "'".$val."'";
                }else{
                    $t1 .= $key.', ';
                    $t2 .= "'".$val."', ";
                }
            }
            $sql .= "($t1) VALUES ($t2);";
            return $this -> query($sql);
        }
    public function update($table, $arr, $con = 'no'){
            $sql = "UPDATE ".$table. " SET ";
            $t = '';
            $i=0;
            $n = count($arr);
            foreach($arr as $key=>$val){
                $i++;
                if($i==$n){
                    $t .= "$key = '$val'";
                }else{
                    $t .= "$key = '$val', ";
                }
            }
            $sql .= $t;
            if ($con != 'no'){
                $sql .= " WHERE ".$con;
            }
            return $this -> query($sql);
        }
    public function delete($table, $con = 'no'){
            $sql = "DELETE FROM ".$table;
            if ($con != 'no'){
                $sql .= " WHERE ".$con;
            }
            return $this -> query($sql);
        }
    public function dashboard_view_data(){
        $kataklar = count($this->get_data_by_table_all('kataklar'));

        $jami_joja_soni = mysqli_fetch_assoc($this->query("
            SELECT SUM(soni) as jami_joja_soni FROM joja
        "))['jami_joja_soni'];
        $jami_olgan_joja = mysqli_fetch_assoc($this->query("
            SELECT SUM(soni) as jami_olgan_joja FROM olgan_jojalar
        "))['jami_olgan_joja'];
        $jojalar_data = mysqli_fetch_assoc($this->query("
            SELECT 
                (SELECT SUM(summa) FROM joja) AS jami_summa,
                (SELECT narxi FROM joja ORDER BY id DESC LIMIT 1) AS oxirgi_narx;
        "));
        $jami_qoshilgan_joja_summa = $jojalar_data['jami_summa'];
        $jami_qoshilgan_oxirgi_narx = $jojalar_data['oxirgi_narx'];
        $jami_mahsulot_summasi = mysqli_fetch_assoc($this->query("
            SELECT SUM(z.soni * m.narxi) AS jami_zahira_summa 
            FROM mahsulot_zahirasi z 
            JOIN mahsulotlar m ON z.mahsulot_id = m.id
        "))['jami_zahira_summa'];
       
        $taminotchilar_balans = mysqli_fetch_assoc($this->query("
            SELECT SUM(balans) as jami_balans FROM taminotchilar
        "))['jami_balans'];

        $mijozlar_balans = mysqli_fetch_assoc(result: $this->query("
            SELECT SUM(balans) as jami_balans FROM mijozlar
        "))['jami_balans'];
        $jami_joja_summa = $jami_qoshilgan_joja_summa - $jami_qoshilgan_oxirgi_narx * $jami_olgan_joja;
        $kapital_summa = $jami_mahsulot_summasi + $mijozlar_balans + $jami_joja_summa - $taminotchilar_balans;
        return [
            'kataklar_soni' => number_format($kataklar, 0, '.', ' '),
            'jami_joja_soni' => number_format($jami_joja_soni - $jami_olgan_joja ?? 0, 0, '.', ' '),
            'jami_mahsulot_summasi' => number_format($jami_mahsulot_summasi ?? 0, 0, '.', ' '),
            'taminotchilar_balans' => number_format($taminotchilar_balans ?? 0, 0, '.', ' '),
            'mijozlar_balans' => number_format($mijozlar_balans ?? 0, 0, '.', ' '),
            'jami_joja_summa' => number_format($jami_joja_summa ?? 0, 0, '.', ' '),
            'kapital_summa' => number_format($kapital_summa ?? 0, 0, '.', ' ')
        ];
    }

}
$obj = new Database();
?>