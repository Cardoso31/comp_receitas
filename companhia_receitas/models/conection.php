<?php
session_start();


class Conection{
    
     public function conection(){
        $con = new PDO('mysql:host=localhost;dbname=receitas', 'root', '');
        return $con;
    }

}


class Query{

    public $dados;
    //public $query

    public function conection(){
        $item = new Conection();
        $con = $item->conection();
        return $con;
    }

    public function select($query){
        try {

            $conn = $this->conection();
            $stmt = $conn->prepare($query);
            $stmt->execute();


            while($conteudo =$stmt->fetch(PDO::FETCH_ASSOC)){
                $this->dados[]=$conteudo;
            }
        }catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    public function input($query){
        try {
            $conn = $this->conection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare($query);

            $stmt->execute();
        }catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }
}

