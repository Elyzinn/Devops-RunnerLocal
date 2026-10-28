<?php 
class Database{
    private $host = 'database';
    private $port = '5432';
    private $db = 'crudPrateleira';
    private $user = 'postgres';
    private $pass = 'testepost';
    private $pdo;

    public function connect(){
            if(!$this->pdo){
                try{
                    $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db}";
                    $this->pdo = new PDO($dsn, $this->user, $this->pass);
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }catch(PDOException $e){
                    die("Erro ao conectar-se ao Banco de Dados: " . $e->getMessage());
                }
            }
            return $this->pdo;
        }
    }

?>