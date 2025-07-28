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
    public function insert_table(){
        $query = "CREATE TABLE IF NOT EXISTS `yem_berish` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `katak_id` INT NOT NULL,
            `mahsulot_id` INT NOT NULL,
            `sana` DATE NOT NULL,
            `miqdori` DECIMAL(10, 2) NOT NULL,
            `izoh` TEXT
        );";
        return $this->query($query);
    }

}
// $obj = new Database();
// $obj->insert_table();

?>