<?php
$caminho = basename(getcwd());
$p = '../';
if ($caminho == 'admin') {
    $p = '';
}
$T_User = new T_User($connection);
$priv = $T_User->get_privilegios($username);
?>
<div class="row-fluid">
    <br/>
    <div class="row-fluid">
        <a href="<?php echo $p; ?>" style="text-align: left;" class="span8 btn btn-navbar"
           ><i class="icon-home"></i>Início</a>
    </div>
    <div class="row-fluid">
        <a href="<?php echo $p; ?>comunicados/" style="text-align: left;" class="span8 btn btn-navbar"
           ><i class="icon-comment"></i>Comunicados</a>
    </div>
    <div class="row-fluid">
        <a href="<?php echo $p; ?>suporte/" style="text-align: left;"  class="span8 btn btn-navbar"><i class="icon-comment"></i>Suporte</a>
    </div>
    <div class="row-fluid">
        <a href="<?php echo $p; ?>ajuda/" style="text-align: left;" class="span8 btn btn-navbar"><i class="icon-info-sign"></i>Ajuda</a>
    </div>
    <div class="row-fluid">
        <a href="<?php echo $p; ?>erros/" style="text-align: left;" class="span8 btn btn-navbar"><i class="icon-remove"></i>Erros</a>
    </div>
    <div class="row-fluid">
        <a href="<?php echo $p; ?>printscreen/" style="text-align: left;" class="span8 btn btn-navbar">
            <i class="icon-print"></i>PrintScreen</a> 
    </div>
    <div class="row-fluid">
        <a href="<?php echo $p; ?>sessoes/" style="text-align: left;" class="span8 btn btn-navbar" ><i class="icon-print"></i>Sessões</a> 
    </div>
    <div class="row-fluid" >
        <a href="<?php echo $p; ?>uploads/" style="text-align: left;" class="span8 btn btn-navbar" ><i class="icon-print"></i>Uploads</a>
    </div>
    <?php
    if ($priv->user_execultar_query == 'sim') {
        ?>
        <div class="row-fluid">
            <a href="<?php echo $p; ?>?dbname=<?php echo $dbname; ?>&modalExecultarQuery=true" 
               class="span8 btn btn-navbar"  style="text-align: left;"  ><i class="icon-search"></i>Execultar Query</a>
        </div>
        <?php
    }
    if ($priv->user_criar_usuario == 'sim') {
        ?>
        <div class="row-fluid">
            <a href="<?php echo $p; ?>?dbname=<?php echo $dbname; ?>&criarUsuario=true" 
               class="span8 btn btn-navbar" style="text-align: left;" >
                <i class="icon-user"></i>Criar usuário</a> 
        </div><?php
    }
    if ($priv->user_backup == 'sim') {
        ?>
        <div class="row-fluid">
            <a href="<?php echo $p; ?>?dbname=<?php echo $dbname; ?>&fazerBackup=true" 
               class="span8 btn btn-navbar"  style="text-align: left;" ><i class="icon-download"></i>Backup</a>
        </div>
        <?php
    }
    ?>
    <div class="row-fluid">
        <a href="//cemvs.com/ava" target="_blank"
           class="span8 btn btn-success"  style="text-align: left;" ><i class="icon-cog"></i>AVA</a>
    </div>
</div>