<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="text-center" style="margin-bottom:15px;">
			<div class="button-group">
				<button class="btn btn-primary" onclick="mostrar('tabela')">
					Tabela
				</button>
				<button class="btn btn-primary" onclick="mostrar('cadastro')">
					Cadastro
				</button>
			</div>
		</div>

		<div id="tabela">
			<table class="table" id="tblProdutos">
				<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Título</th>
						<th scope="col">Preço</th>
						<th scope="col">Estoque</th>
						<th scope="col" class="text-center">Ações</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="5" class="text-center">Aguarde...</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="cadastro" style="display:none;">
			<div class="p-3 mb-2 bg-dark text-white" id="tituloForm">Criando Produto</div>
			<div>
				<div class="row">
					<input type="hidden" id="txtID">
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtTitulo">Título</label>
							<input type="text" class="form-control" id="txtTitulo" placeholder="Titulo">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="txtPreco">Preço</label>
							<input type="text" class="form-control" id="txtPreco" placeholder="Preço">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="txtEstoque">Estoque</label>
							<input type="number" class="form-control" id="txtEstoque" placeholder="Estoque">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="txtDescricao">Descrição</label>
					<textarea id="txtDescricao" class="form-control" style="min-height:50px;"></textarea>
				</div>
				<button class="btn btn-danger" onclick="limpar_cadastro();">Limpar</button>
				<button class="btn btn-success float-right" id="btnCadastrar">Cadastrar</button>
			</div>
		</div>
	</div>
</div>

<script>
	var url = "<?= base_url('home') ?>";

	$(document).ready(function() {

		$("#txtPreco").maskMoney({
			prefix: 'R$ ',
			allowNegative: true,
			thousands: '.',
			decimal: ','
		});

		tabela();

		$('#btnCadastrar').click(function() {

			var id = $('#txtID').val();
			var titulo = $('#txtTitulo').val();
			var descricao = $('#txtDescricao').val();
			var preco = $('#txtPreco').maskMoney('unmasked')[0];
			var estoque = $('#txtEstoque').val();

			var faltando = [];

			if (titulo.length == 0) faltando.push("Titulo");
			if (descricao.length == 0) faltando.push("Descricao");
			if (estoque.length == 0) faltando.push("Estoque");

			if (faltando.length != 0) {

				alert("Faltando os seguintes campos: " + faltando.join(", "));
				return;

			}

			$.post(`${url}/enviar_produto`, {
				id,
				titulo,
				descricao,
				preco,
				estoque
			}, function(data) {

				if (data.erro) {
					alert("Erro ao salvar produto");
				} else {
					alert('Produto salvo com sucesso');
					mostrar("tabela");
				}

			}, 'json');

		})

	});

	function editar(id) {

		$.post(`${url}/dados`, {
			id
		}, function(data) {

			if (data.erro) {

				alert("Erro ao buscar dados do produto");
				return;

			}

			mostrar("cadastro");

			$('#txtID').val(data.id);
			$('#txtTitulo').val(data.titulo);
			$('#txtPreco').val(data.preco);
			$('#txtEstoque').val(data.estoque);
			$('#txtDescricao').val(data.descricao);
			$('#btnCadastrar').html("Alterar");
			$('#tituloForm').html("Alteração de Produto");

		}, 'json');

	}

	function remover(id) {

		$.post(`${url}/remover`, {
			id
		}, function(data) {

			if(confirm("Deseja remover o produto?")){
				
				if (data.erro) {
					
					alert("Erro ao remover produto");
					return;
					
				}
				
				tabela();

			}

		}, 'json');

	}

	function mostrar(oque) {

		limpar_cadastro();

		if (oque == "cadastro") {
			$('#tabela').hide();
			$('#cadastro').show();
			$('#btnCadastrar').html("Cadastrar");
			$('#tituloForm').html("Cadastro de Produto");
		}

		if (oque == "tabela") {
			$('#cadastro').hide();
			$('#tabela').show();
			tabela();
		}

	}

	function limpar_cadastro() {

		$('#txtID').val('');
		$('#txtTitulo').val('');
		$('#txtPreco').val('');
		$('#txtEstoque').val('');
		$('#txtDescricao').val('');

	}

	function tabela() {

		$.post(`${url}/listar`, function(data) {
			$('#tblProdutos > tbody').html(data);
		});

	}
</script>
