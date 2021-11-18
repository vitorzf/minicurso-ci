<?php
class Home_model extends CI_Model
{

	public function __construct()
	{

		parent::__construct();
	}

	public function listar_produtos(){

		return $this->db->query("SELECT * FROM produtos")->result();

	}

	public function cadastrar_produto($dados){

		return $this->db->insert("produtos", $dados);

	}

	public function alterar_produto($id, $dados){

		return $this->db->update("produtos", $dados, ["id" => $id]);

	}

	public function dados_produto($id){

		return $this->db->query("SELECT * FROM produtos WHERE id = ?", [$id])->row();

	}

	public function remover_produto($id){

		return $this->db->delete("produtos", ["id" => $id]);

	}
}
