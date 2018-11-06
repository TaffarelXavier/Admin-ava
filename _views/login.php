<?php
if (!class_exists('Tokens')) {
    header('location: ../');
    exit();
}
if (!isset($_SESSION['security'])) {
    $_SESSION['security'] = null;
}

$arr = Tokens::inserir_nova();

?>

<form class="form-signin" enctype="multipart/form-data" method="post" id="formLogin" autocomplete="false">
    <h2 class="form-signin-heading">Login</h2>
    <input type="text" class="input-block-level" name="usuario" placeholder="Usuário" required=""  autocomplete=false>

    <input type="password" class="input-block-level" name="password" placeholder="Senha" required=""  autocomplete=false>

    <input type="text" name='captcha' class="input-block-level" required=""  autocomplete="off" 
           placeholder="Preencha com os caracteres abaixo" />

    <input type="hidden" name="key" value="<?php echo $arr[0]; ?>" />

    <input type="hidden" name="token" value="<?php echo $arr[1]; ?>" />

    <img alt="captcha" class='captcha' id="captcha" />
    <button class="btn" id="atualizarCaptcha" type="button" ><i class="icon-refresh"></i></button><div class="clearfix"></div><br/>
    <button class="btn btn-large btn-block btn-primary" type="submit">Entrar</button>
    <div class="percent"></div>
    <div class="progress">
        <div  class="bar"></div>
    </div>

</form>
<script src="js/scripts.js"></script>