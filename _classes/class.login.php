<?php

class Login {

    /**
     * <p>Link da conexao</>
     * @var type 
     */
    private $conexao = null;

    /**
     * <p>Nome da tabela</p>
     * @var type 
     */
    private $table_name = "user";

    /**
     * <p>Contrutor para chamar a conexão PDO</p>
     * @param type $connection
     */
    public function __construct($connection) {
        $this->conexao = $connection;
    }

    public function get_salt() {
        return md5(uniqid(rand(), true));
    }

    /**
     * 
     * @param type $usuario
     * @param type $userpass
     * @return boolean
     */
    public function verificar_acesso($usuario, $userpass) {

        //O tamanho máximo da senha será de 15 caracteres.
        if (strlen($userpass) > 15) {
            exit("Erro na autenticação!Cod:1.1");
        } else if (!is_string($userpass)) {
            exit("Erro na autenticação!Cod:1.2");
        }

        $sth = $this->conexao->prepare("SELECT * FROM `" . $this->table_name . "` WHERE `user_nome` = ? LIMIT 1");

        $sth->bindParam(1, $usuario, PDO::PARAM_STR, 15);

        $sth->execute();

        $query = $sth->fetch(PDO::FETCH_ASSOC);
         
        if ($query == false) {
            exit('Erro na autenticação.Código 01');
        } else {
            if (password_verify($userpass, $query['user_senha'])) {
                $_SESSION['user_id'] = $query['user_id'];
                $_SESSION['user_name'] = $query['user_nome'];
                $_SESSION['_sessao'] = array('logado' => true, 'data' => time());
                return 1;
            } else {
                return 0;
            }
        }
    }

    /**
     * <p>Atualiza senhas</p> 
     * @param type $id
     * @param type $nova_senha
     * @return type
     */
    public function atualizar_senha($id, $nova_senha) {
//Se não for uma senha
        if (!is_string($nova_senha)) {
            exit("Erro na autenticação!Cod:2.2.1");
        }

        $sth = $this->conexao->prepare("UPDATE " . $this->table_name . " SET `prof_senha` = :senha WHERE `prof_id` = :id ");
        $sth->bindParam(':id', $id, PDO::PARAM_STR, 15);
        $sth->bindParam(':senha', $nova_senha, PDO::PARAM_STR, 15);
        $sth->execute();
        $query = $sth->rowCount();
        return $query;
    }

    public function login() {
        try {
            
            $usuario = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING);
            $userpass = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_STRING);
            $key = filter_input(INPUT_POST, "key", FILTER_SANITIZE_STRING);
            $token = filter_input(INPUT_POST, "token", FILTER_SANITIZE_STRING);

            if (Tokens::validar_sessao($key, $token) === true) {
                if ($this->verificar_acesso($usuario, $userpass) > 0) {
                    $_SESSION['_sessao'] = array('logado' => true, 'data' => time());
                    return 1;
                } else {
                    exit(0);
                }
            } else {
                exit(0);
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function criar_usuario($user_pass) {

        $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);

        $hash = password_hash($user_pass, PASSWORD_BCRYPT, array("cost" => 14, "salt" => $salt));

        return $hash;
    }

}

//chkdksP`123
//cemvs2016#*