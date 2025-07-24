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
    public function insert_table(){
        // $query = "CREATE TABLE kataklar (
        //     id INT AUTO_INCREMENT PRIMARY KEY,
        //     nomi VARCHAR(100) NOT NULL,
        //     izoh TEXT,
        //     date DATE DEFAULT (CURRENT_DATE),
        //     time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        // );";
        // $query = 
        // return $this->query($query);
    }

}
// $obj = new Database();
// $obj->insert_table();

?>