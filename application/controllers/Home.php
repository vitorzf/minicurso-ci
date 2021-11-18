<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("home_model", "home");
	}

	public function index()
	{

		$dados['titulo'] = "Página Inicial";
		$dados['pagina'] = "home";
		$this->load->view('body_view', $dados);
	}

	public function listar(){
		
		$produtos = $this->home->listar_produtos();

		if(empty($produtos)){

			echo "<tr><td colspan=\"5\" class=\"text-center\">Nenhum Produto encontrado</td></tr>";

		}

		$html = "";

		foreach($produtos as $produto){

			$preco = number_format($produto->preco, 2, ",", ".");

			$html .= "
				<tr>
					<td>{$produto->id}</td>
					<td>{$produto->titulo}</td>
					<td>{$preco}</td>
					<td>{$produto->estoque}</td>
					<td class=\"text-center\">
						<a href=\"javascript:editar('{$produto->id}');\">Editar</a>
						<a href=\"javascript:remover('{$produto->id}')\" class=\"text-danger\">Remover</a>
					</td>
				</tr>
			";

		}

		echo $html;

	}

	public function dados(){

		$id = $this->input->post("id");

		$dados = (array) $this->home->dados_produto($id);

		if(empty($dados)){
			die(json_encode(["erro" => true]));
		}
		
		$dados['erro'] = false;

		echo json_encode($dados);

	}

	public function enviar_produto(){

		$id = $this->input->post("id");

		$dados = $this->input->post();

		unset($dados["id"]);

		if(empty($id)){
			$retorno = $this->home->cadastrar_produto($dados);
		}else{
			$retorno = $this->home->alterar_produto($id, $dados);
		}

		echo json_encode(["erro" => !$retorno]);

	}
	
	public function remover(){

		$id = $this->input->post("id");

		$retorno = $this->home->remover_produto($id);

		echo json_encode(["erro" => !$retorno]);

	}

}
