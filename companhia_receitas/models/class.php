<?php
require "../models/conection.php";
/*$con=new Query;
$con->select('select * from cidade');
print_r($con);*/


class Usuario{
	private $id_usuario;
	public $nome;
	public $sobrenome;
	public $email;
	public $senha;
	public $tipo_usuario;

	public function busca_usuario($id){
		$query=new Query();
		$query->select('select * from usuario where id_usuario ='.$id);
		$user=$query->dados[0];
		return $user;
	}

	public function logar($email,$senha){
		
		$query=new Query();

		$query->select('select * from usuario where email = "'.$email.'"');

		$ab=$query->dados[0]['senha'];

		$foto=$query->dados[0]['foto_usuario'];

		if (!file_exists($foto)) {

			$query->dados[0]['imagem_perfil']='../img/perfil_user/nulo.png';

		}
		
		$senha=MD5($senha);
		

		if ($ab==$senha) {
			
			$_SESSION['logado']=array('id_usuario' => $query->dados[0]['id_usuario'],
						  				'nome' => $query->dados[0]['nome'],
						  				'sobrenome' => $query->dados[0]['sobrenome'],
						  			'email' => $query->dados[0]['email'],
						  			'restricao' => $query->dados[0]['restricao'],
						  			'senha' => $query->dados[0]['senha'],
						  			'tipo_user'=> $query->dados[0]['tipo_usuario'],
						  			'foto' => $query->dados[0]['foto_usuario']
						  			);




			header("location: ../views/index.php");
		}else{
			header("location: ../views/login.php?error=login");
		}
	}

	public function logOut(){

		session_destroy();

		echo"<h3 class='centro'>loading, please wait <h3>";

		header("Refresh: 1; url = ../views/index.php");
	}
	

	public function editar($nome,$sobrenome,$email,$senha,$data_nasc,$id,$imagem,$tipo_user){
		  if ($imagem=='vazio') {

			$senha=MD5($senha);
			$insert='update usuario set tipo_usuario = "'.$tipo_user.'", nome_usuario = "'.$nome.'",sobrenome ="'.$sobrenome.'",email_usuario ="'.$email.'",senha ="'.$senha.'",data_nascimento="'.$data_nasc.'" where id_usuario = '.$id.'';
		}else{

			$t=explode('.', $imagem['name']);
		$nome_final=time().'.'.$t[1];

		move_uploaded_file($imagem['tmp_name'],'../img/perfil_user/'.$nome_final.'');

		$caminho='../img/perfil_user/'.$nome_final.'';

			$senha=MD5($senha);
			$insert='update usuario set tipo_usuario = "'.$tipo_user.'", nome_usuario = "'.$nome.'",sobrenome ="'.$sobrenome.'",email_usuario ="'.$email.'",senha ="'.$senha.'",data_nascimento="'.$data_nasc.'",imagem_perfil ="'.$caminho.'" where id_usuario = '.$id.'';
		$this->excluir_foto($id);
		}
		
		
		
		$query=new Query();
		$query->input($insert);
		unset($query);
		if ($_SESSION['logado']['tipo_user']==3 and $tipo_user != 3) {

		header("Refresh: 0; url = ../views/lista_usuario.php");
		}else{

		$_SESSION['logado']['nome'] = $nome;
		$_SESSION['logado']['sobrenome'] = $sobrenome;
		$_SESSION['logado']['email'] = $email;
		$_SESSION['logado']['senha'] = $senha;
		$_SESSION['logado']['data_nasc'] = $data_nasc;
		$_SESSION['logado']['id_usuario'] = $id;
		$_SESSION['logado']['tipo_user'] = $tipo_user;

		if($imagem != 'vazio'){

		$_SESSION['logado']['foto'] = $caminho;

		}

		    $user13 = new Usuario();

            if(!$user13->userLogadoAdmin() ) {

                header("Refresh: 0; url = ../views/usuario.php");

            }else{

                header("Refresh: 0; url = ../views/pageAdmin.php");
            }
		}

	}

	public function cadastrar($nome,$sobrenome,$email,$senha,$tipo_usuario,$restricao,$imagem){

		if($imagem=='vazio'){

			$caminho='../img/perfil_user/nulo.png';
			$insert='insert into usuario(nome,sobrenome,email,senha,tipo_usuario,restricao,foto_usuario) values("'.$nome.'","'.$sobrenome.'","'.$email.'",MD5("'.$senha.'"),"'.$tipo_usuario.'","'.$restricao.'","'.$caminho.'")';
			
		}else{


		$t=explode('.', $imagem['name']);
		$nome_final=time().'.'.$t[1];

		move_uploaded_file($imagem['tmp_name'],'../fotos/user/'.$nome_final.'');
		$caminho='../fotos/user/'.$nome_final;

            $insert='insert into usuario(nome,sobrenome,email,senha,tipo_usuario,restricao,foto_usuario) values("'.$nome.'","'.$sobrenome.'","'.$email.'",MD5("'.$senha.'"),"'.$tipo_usuario.'","'.$restricao.'","'.$caminho.'")';

	}

        $query=new Query();
		$query->input($insert);
		unset($query);


	}
	public function excluir($id){
		$this->excluir_foto($id);
		$delete='delete from usuario where id_usuario ='.$id.'';
		$query=new Query;
		$query->input($delete);
		unset($query);
		if ($_SESSION['logado']['tipo_user']==3) {
			
		header("Refresh: 0; url = lista_usuario.php");
		}else{
		header("Refresh: 0; url = controler.php?acao=logout");
		
		}
	}
	public function valida_imagem($file){
	
		if ($file['type']=='image/png' or $file['type']=='image/jpeg') {
	
			if ($file['size'] > '40000000000') {
				return 2; //Escolha uma imagem menor
			}else{
				return 1;
			}
		}elseif($file['type']=='image/jpg'){

			if ($file['size'] > '40000000000') {//
				return 2; //Escolha uma imagem menor
			}else{
				return 1;
			}
		}elseif(empty($file['name'])){
			return 4; //sem imagem
		}else{
			$name=explode('.', $file['name']);
			return $name[1]; //Escolha uma imagem valida

		}
	}
	public function excluir_foto($id){
		$query= new Query();
		$query->select('select imagem_perfil from usuario where id_usuario ='.$id);
		if (file_exists($query->dados[0]['imagem_perfil']) and $query->dados[0]['imagem_perfil'] != '../img/perfil_user/nulo.png') {
		$nome_final=explode('.',$query->dados[0]['imagem_perfil'] );
		$nome_final=time().".".$nome_final[3];

		rename($query->dados[0]['imagem_perfil'],'../img/delet/'.$nome_final.'');
		}
	}

	public function userLogadoAdmin(){
        if (!isset($_SESSION['logado'])) {

            return false;

        } elseif ($_SESSION['logado']['tipo_user'] == 'Usuario') {

            return false;

        }else{

            return true ;
           }
        }

    public function userLogadoEdit(){
        if (!isset($_SESSION['logado'])) {

            return false;

        } elseif ($_SESSION['logado']['tipo_user'] == 3 or $_SESSION['logado']['tipo_user'] == 2) {

            return true;

        }else{

            return false ;
        }
    }

    public function userLogado(){

        if (!isset($_SESSION['logado'])) {
            return true ;
        }else{
            return false;
        }
        }

    public function verificaErro()
    {
        if (isset($_GET['error'])) {

            if ($_GET['error'] == 'login') {
                return 'Email ou senha incorretos';

            } elseif ($_GET['error'] == 'user') {
                return 'Usuario ja cadastrado';

            }elseif ($_GET['error'] == 'cur'){

                return 'Curso ja cadastrado';
            }elseif($_GET['error'] == 'uni'){

                return 'Universidade ja cadastrada';
            }

        } elseif (isset($_GET['img'])) {
            return '"' . $_GET['img'] . '" não é um arquivo válido';

        }
    }

    }



