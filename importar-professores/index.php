<?php
include '../autoload.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Alterar Senha de Professores</title>
	<script src="../../js/jquery-1.10.1.min.js"></script>
	<link rel="stylesheet" href="css.css" />
    </head>
    <body>
	<?php
	if (isset($_SESSION['LOGADO_SEN_PROF']) && isset($_SESSION['LOGADO_1']) && isset($_SESSION['LOGADO_2'])) {
	  ?>
  	<div class="menu-topo">
  	    <button type="submit" form="form2" id="btnSair" class="buttonLogado">Sair</button>
  	    <a id="voltar" href="../">Voltar</a>
  	    <h1>Neste seção, você poderá alterar as senhas dos professores.</h1>
  	</div>
	  <?php
	}
	/* $senha = 'chkdskP123#$%*('; */
	$Professores = new Professores($connection);
	$f = $Professores->get_professores_por_tipo('PROFESSOR');
	if (!isset($_SESSION['LOGADO_SEN_PROF'])) {
	  ?>
  	<br/>
  	<fieldset>
  	    <legend>
  		Autenticação
  	    </legend>
  	    <form method="post" class="formulario">
  		<label>Usuário:</label><br/>
  		<input type="password" id="usuario" name="usuario" value="TaffarelXAvier147" required="" /><br/>
  		<label>Senha:</label><br/>
  		<input type="password" id="senha" name="senha" required="" /><br/>
  		<button id="btnFazerLogin">Entrar</button>
		<a href="../" title="" style="text-align: center;display: block;position:relative;top:2px;" autofocus="">Voltar</a>
  	    </form>
	      <?php
	      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$usuario = val_input::sani_string('usuario');
		$senha = val_input::sani_string('senha');
		if (hash('ripemd160', $usuario) !== 'e2862f1799a0a36d6d6baf8113103c571670999f') {
		  exit('<span class="usPasIncor">Usuário ou senha incorretos.</span>');
		}
		if (password_verify($senha, '$2y$14$ifcDPIE0/a03QsKBGaidiejkfsBaBd4qVay0g0osqCgC7bMPX7qMO')) {
		  $_SESSION['LOGADO_SEN_PROF'] = true;
		  $_SESSION['LOGADO_1'] = true;
		  $_SESSION['LOGADO_2'] = true;
		} else {
		  echo '<hr/><span class="usPasIncor">Usuário ou senha incorretos.</span>';
		}
	      }
	      ?>
  	</fieldset>
	  <?php
	}
	if (isset($_SESSION['LOGADO_SEN_PROF']) && isset($_SESSION['LOGADO_1']) && isset($_SESSION['LOGADO_2'])) {
	  ?>
  	<form method="post">
	      <?php Tokens_Dois::inserir('hidden'); ?>
  	    <div class="layer1">
  		<table border="1">
  		    <thead>
  			<tr>
  			    <th style="text-align: center;">
  				<input type="checkbox" id="selecionarTudo" />
  			    </th>
  			    <th>ID</th>
  			    <th>NOME</th>
  			    <th>CPF</th>
  			</tr>
  		    </thead>
		      <?php
		      while ($rows = $f->fetch(PDO::FETCH_ASSOC)) {
			$cpf = preg_replace('/\.|\-/', '', $rows['prof_cpf']);
			?> 
    		    <tbody>
    			<tr>
    			    <td class="checkbox">
    				<input type="checkbox" name="check[<?php echo $rows['prof_id']; ?>]" 
    				       value="<?php echo $cpf; ?>" class="check" />
    				<input type="hidden" id="" name="nomes[<?php echo $rows['prof_id']; ?>]" value="<?php echo $rows['prof_nome']; ?>" />
    			    </td>
    			    <td class="id"><?php echo $rows['prof_id']; ?></td>
    			    <td><?php echo $rows['prof_nome']; ?></td>
    			    <td><input type="text" name="cpf[<?php echo $rows['prof_id']; ?>]" value="<?php echo $cpf; ?>" size="10" /></td>
    			</tr>
    		    </tbody>
			<?php
		      }
		      ?>
  		</table>
		<script>
		     window.location.href="?taffarel=true#";
		</script>
  	    </div>
  	    <div class="layer2">
  		<input type="hidden" id="" name="alterar" value="1" />
  		<button type="submit" class="buttonLogado">Alterar Senha</button><br/>
		  <?php
		  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $key = val_input::sani_string('key');
		    $token = val_input::sani_string('token');
		    if (Tokens_Dois::validar_sessao($key, $token)) {
		      $alterar = val_input::sani_string('alterar');
		      if ($alterar == '1') {
			$dados = val_input::neo_str_array('check', FILTER_SANITIZE_STRING);
			$nomes = val_input::neo_str_array('nomes', FILTER_SANITIZE_STRING);
			$cpf = val_input::neo_str_array('cpf', FILTER_SANITIZE_STRING);
			$total = 0;
			foreach ($dados as $key => $value) {
			  $total += 1;
			  echo $Professores->atualizar_senha_professor($key, $cpf[$key]) > 0 ? "<span class='sucesso'>A senha de <b>" . $nomes[$key] . '</b> (' . ' CPF: <b>' . $value . "</b>) Nova senha: <b>" . $cpf[$key] . "</b> - foi alterada com sucesso!</span><br/>" : "A alteração falhou.<br/>";
			}
			echo '<hr />';
			if ($total == 1) {
			  echo 'Uma senha foi alterada';
			} else if ($total > 1) {
			  echo $total, ' senhas foram alteradas.';
			} else {
			  echo 'Nenhuma senha foi alterada.';
			}
		      }
		    } else {
		      echo 'Perâmentro inválido';
		    }
		  }
		  ?>
  	    </div>
  	</form>
  	<form  id="form2" method="post">
  	    <input type="hidden" name="sair" value="ok" />
  	</form>
	  <?php
	  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    if (isset($_POST['sair'])) {
	      unset($_SESSION['LOGADO_SEN_PROF']);
	      unset($_SESSION['LOGADO_1']);
	      unset($_SESSION['LOGADO_2']);
	      header('location: ./');
	    }
	  }
	  ?>
  	<script>
            $(document).ready(function () {
                var checkboxs = $('.check');
                $('#selecionarTudo').click(function () {
                    var _t = $(this);
                    if (_t.prop('checked')) {
                        checkboxs.prop("checked", true);
                    } else {
                        checkboxs.prop("checked", false);
                    }
                });
                $('fieldset').hide();
            });
  	</script>
	  <?php
	}
	?>
    </body>
</html>
