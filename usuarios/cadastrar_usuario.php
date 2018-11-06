<?php
if (!class_exists('Tokens')) {
    header('location: ../');
}
if (!defined('_LOGADO_')) {
    header('location: ../');
}
?>
<div class="row-fluid">
    <fieldset>
        <form id="formCadastrarUsuario" class="" method="post" autocomplete="off">
            <label>Nome de usuário:</label>
            <input name="username" class="span12" autocomplete="off" type="text"  id="id1" autofocus="" /><br/>
            <label>Senha:</label>
            <input name="senha" class="span12" autocomplete="off"  type="password" id="id2" /><br/>
            <label>Nível:</label>
            <select name="nivel" class="m-wrap span12">
                <option value="basico">Básico</option>
                <option value="medio">Médio</option>
                <option value="avancado">Avançado</option>
            </select><br/>
            <?php
            $chave = Tokens::inserir_nova();
            ?>
            <input type="hidden" name="key" value="<?php echo $chave[0]; ?>" />
            <input type="hidden" name="token" value="<?php echo $chave[1]; ?>" />
            <button class="btn btn-success" type="submit">Cadastrar</button>
            <a href="./" class="btn btn-danger">Cancelar</a>
        </form> 
    </fieldset>
</div>
<hr style="border:0;border-top:1px solid #ccc" />
<script>
    $(document).ready(function() {
        $('#formCadastrarUsuario').ajaxForm({
            url: 'usuarios/models/insert.php',
            success: function(responseText) {
                alert(responseText);
            }
        });
    });
</script>

