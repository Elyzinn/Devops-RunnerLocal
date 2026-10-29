<?php 
require_once 'Database.php';

class Games{
    private $db;

    public function __construct() {
        $this->db=(new Database())-> connect();
    }

    public function addGames($nome,$produtora,$genero,$quantidade,$preco){
        $sql= 'INSERT INTO jogos (nome, produtora, genero, quantidade, preco) VALUES (:nome, :produtora, :genero,:quantidade, :preco)';
        $stmt = $this->db->prepare($sql);
        $stmt-> execute([
            'nome' => $nome,
            'produtora'=> $produtora,
            'genero' => $genero,
            'quantidade' => $quantidade,
            'preco' => $preco
        ]);
    }

    public function listGame(){
        $sql = "SELECT id, nome, produtora, genero, quantidade, preco FROM jogos";
        $stmt = $this->db->query($sql);
        return $stmt-> fetchAll(PDO::FETCH_ASSOC);
        
    }

    public function getGameById($id){
        $sql = "SELECT id, nome, produtora, genero, quantidade, preco FROM jogos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateGame($id, $nome, $produtora, $genero, $quantidade, $preco){
        $sql = "UPDATE jogos
                SET nome = :nome, produtora = :produtora, genero = :genero,
                    quantidade = :quantidade, preco = :preco
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nome' => $nome,
            'produtora'=> $produtora,
            'genero' => $genero,
            'quantidade' => $quantidade,
            'preco' => $preco,
            'id' => $id
        ]);
    }

    public function deleteGame($id){
        $sql = "DELETE FROM jogos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
    }
}

?>