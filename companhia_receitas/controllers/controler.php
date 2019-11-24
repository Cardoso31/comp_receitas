<?php
include '../models/class.php';


if(isset($_GET['acao']) and !empty($_GET['acao'])){


		switch ($_GET['acao']) {


		case 'logar':

	$usuario=new Usuario();
	
	$usuario->logar($_POST['email'],$_POST['password']);
	unset($usuario);
		
	break;
			

		case 'cadastrar':

	$query=new Query();
	
	$query->select('select * from usuario where email ="'.$_POST['email'].'"');


	if (!empty($query->dados)) {

		header("location: ../views/cadastro.php?error=user");

	}else{

	$usuario=new Usuario();

        $imagem=$usuario->valida_imagem($_FILES['foto']);

	if ($imagem == 4) {


		$usuario->cadastrar($_POST['nome'],$_POST['sobrenome'],$_POST['email'],$_POST['senha'],'Usuario',$_POST['restricao'],'vazio');

	}elseif($imagem == 1){


        $usuario->cadastrar($_POST['nome'],$_POST['sobrenome'],$_POST['email'],$_POST['senha'],'Usuario',$_POST['restricao'],$_FILES['foto']);



	}else{

		header("location: ../views/cadastro.php?img=".$imagem."");

	}

	unset($usuario);
	unset($query);


	$user = new Usuario();
	$user->logar($_POST['email'],$_POST['senha']);

//	header("location: ../views/home.php");
	
}

		break;
		
		case 'editar':
	
		$usuario=new Usuario;
		$imagem=$usuario->valida_imagem($_FILES['image']);
				if ($imagem == 4) {
			$usuario->editar($_POST['nome'],$_POST['sobrenome'],$_POST['email'],$_POST['password'],$_POST['data_nasc'],$_POST['id_usuario'],'vazio',$_POST['tipo_usuario']);

		}elseif($imagem == 1){
		$usuario->editar($_POST['nome'],$_POST['sobrenome'],$_POST['email'],$_POST['password'],$_POST['data_nasc'],$_POST['id_usuario'],$_FILES['image'],$_POST['tipo_usuario']);
		
	}else{

		    if(!$usuario->userLogadoAdmin()){

                header("location: ../views/edit_usuario.php?u=".$imagem."");
            }else{
                header("location: ../views/edit_usuario.php?id=".$_POST['id_usuario']."&&u=".$imagem);
            }


		}			
		//header("location: usuario.php");

		break;

		case 'logout':

		$user = new Usuario();
		$user->logOut();
		unset($user);
		
		break;



		case 'excluir':
		
		if($_SESSION['logado']['tipo_user'] == 3){
			header("location: ../views/confirmar_excluir_usuario.php?id=".$_GET['id']);

		}else{
		header("location: ../views/confirmar_excluir_usuario.php");
		}
		break;
		
		case 'delete0':
				
				if(isset($_SESSION['logado']['nome'])){
					$usuario=new Usuario();
			
				if ($_SESSION['logado']['tipo_user']!=3) {
				
					$usuario->excluir($_GET['id']);
					session_destroy();
			
				}else{
				
					$usuario->excluir($_GET['id']);
					header("location: ../views/lista_usuario.php");
				
				}
			}else{
			
				header("location: home.php");
			}
			
				break;
		}

	
}