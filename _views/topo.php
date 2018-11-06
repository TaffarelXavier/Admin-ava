<?php
$arr =Config_Path_Neo::functionName();
?>
<div class="topo navbar navbar-fixed-top">
    <div class="container-fluid">
        <?php
        if (defined('_LOGADO_') == true)
        {
            ?>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <?php
        }
        ?>
        <a class="brand" href="<?php echo $arr[0]; ?>">
            <img src="<?php echo SYS_BASE_NAME . 'img/' . SYS_LOGO; ?>" alt="logo" id="logo" class="img-responsive" >
            <span class="text-center text-success" style="color:black;">Administração do Sistema</span>
        </a>
        <div class="nav-collapse collapse">
            <?php
            if (defined('_LOGADO_') == true)
            {
                ?>
                <ul class="nav pull-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Acesso rápido">
                            <i class="icon-tasks"></i>
                            <span class="badge"></span>
                        </a>
                        <ul class="dropdown-menu extended tasks">
                            <li>
                                <a href="./">
                                    <span class="task">
                                        <span class="desc"><i class="icon-home"></i> Início</span>
                                        <span class="percent"></span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="task">
                                        <span class="desc"><i class="icon-book"></i>Texto</span>
                                        <span class="percent"></span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img alt="" src="<?php echo $arr[0]; ?>../ava/imagens/avatar.png">
                            <?php
                            $cripts = new Criptografia();
                            echo $cripts->decriptografar($_SESSION['usernamekey'], $_SESSION['username']);
                            ?>
                            <span class="username hidden-phone"></span>
                            <i class="icon-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="desconectar">
                                    <i class="icon-key"></i> Sair</a></li>
                        </ul>
                    </li>
                </ul>
                <?php
            }
            ?>
        </div><!--/.nav-collapse -->
    </div>
</div>
