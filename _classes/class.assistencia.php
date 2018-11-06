<?php

class Assistencia
{

    private $conexao = null;

    public function __construct($connection)
    {
        $connection->query("use " . DB_NAME);
        $this->conexao = $connection;
    }

    /**
     * 
     * @param type $status
     * @param type $suporte_id
     * @param type $user_id
     * @return type
     */
    public function update_status_suporte($status, $suporte_id, $user_id)
    {
        try
        {
            $sql = 'UPDATE `assistencia` T1 SET `assistencia_status` = ? WHERE `T1`.`assistencia_id` = ? AND T1.assistencia_user_id = ?';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $status, PDO::PARAM_STR);
            $sth->bindParam(2, $suporte_id, PDO::PARAM_INT);
            $sth->bindParam(3, $user_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function get_assistencia_fechadas($user_id)
    {
        try
        {
            $sql = 'SELECT * FROM `assistencia` AS t1 WHERE t1.assistencia_user_id = ? AND assistencia_status= "fechado" ORDER BY t1.assistencia_id DESC';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $user_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth;
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    public function deletar()
    {
        
    }

    public function atualiazar()
    {
        
    }

    /**
     * 
     * @param type $user_id
     * @param type $nivel
     * @param type $assunto
     * @return type
     */
    public function insert($user_id, $nivel, $assunto)
    {
        try
        {
            $sql = 'INSERT INTO `assistencia` (`assistencia_id`, `assistencia_user_id`, `assistencia_data`, '
                    . '`assistencia_status`, `assistencia_nivel`, `assistencia_assunto`) VALUES (NULL,?,' . time() . ',\'aberto\',?,?)';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $user_id, PDO::PARAM_INT);
            $sth->bindParam(2, $nivel, PDO::PARAM_INT);
            $sth->bindParam(3, $assunto, PDO::PARAM_INT);
            $sth->execute();
            return (int) $sth->rowCount();
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function assistencia_existe($user_id)
    {
        try
        {
            $sql = 'SELECT * FROM `assistencia` AS t1 WHERE t1.assistencia_user_id= ?';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $user_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    public function assistencia_em_aberto($user_id)
    {
        try
        {
            $sql = 'SELECT * FROM `assistencia` AS t1 WHERE t1.assistencia_user_id = ? AND t1.`assistencia_status` = \'aberto\' LIMIT 1';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $user_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    public function get_last_id()
    {
        try
        {
            $sql = "SELECT `assistencia_id` FROM `assistencia` AS t1 ORDER BY t1.assistencia_id DESC LIMIT 1";
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            $total = $sth->fetch();
            return (int) $total[0];
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $user_id
     * @param type $assistencia_id
     * @param type $mensagem
     * @param type $remetente
     * @param type $arquivo
     * @return type
     */
    public function insert_mensagem($user_id, $assistencia_id, $mensagem, $remetente, $arquivo)
    {
        try
        {
            $sql = 'INSERT INTO `assistencia_mensagens` (`assis__id`, `assis__user_id`, `assis__foreignkey_assistencia_id`, `assis__mensagem`, `assis__data`, `assis__remetente`, `assis__arquivo_md5`) VALUES (NULL,?,?,?, ' . time() . ',?,?);';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $user_id, PDO::PARAM_INT);
            $sth->bindParam(2, $assistencia_id, PDO::PARAM_INT);
            $sth->bindParam(3, $mensagem, PDO::PARAM_STR);
            $sth->bindParam(4, $remetente, PDO::PARAM_STR);
            $sth->bindParam(5, $arquivo, PDO::PARAM_STR);
            $sth->execute();
            return (int) $sth->rowCount();
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $assistencia_id
     * @return type
     */
    public function select_mensagens($assistencia_id)
    {
        try
        {
            $sql = 'SELECT * FROM `assistencia_mensagens` AS t1 WHERE t1.assis__foreignkey_assistencia_id = ? ORDER BY t1.assis__id DESC, t1.assis__data DESC';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $assistencia_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth;
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $assitencia_id
     */
    public function show_mensagens($assitencia_id)
    {
        $mensagens = $this->select_mensagens($assitencia_id);

        while ($linhas = $mensagens->fetch(PDO::FETCH_ASSOC))
        {

            if ($linhas['assis__remetente'] == 'sistema')
            {
                ?>
                <div class="ftxb-msg-body ftxb-msg-admin">
                    <img src="../img/certo.png" alt="alt" /><span class="ftxb-msg-admin-arrow"></span><time class="ftxb-time-admin">Enviado em <?php echo date('d \d\e m \d\e Y \à\s H:i:s', $linhas['assis__data']); ?></time>
                    <?php echo $linhas['assis__mensagem']; ?></div>
                <?php
            } else
            {
                ?>
                <div class="ftxb-msg-body ftxb-msg-user"><img src="../img/avatar.png" alt="alt" /><span class="ftxb-msg-user-arrow"></span>
                    <time class="ftxb-time-user">Enviado em <?php echo date('d \d\e m \d\e Y \à\s H:i:s', $linhas['assis__data']); ?></time>
                    <?php echo $linhas['assis__mensagem']; ?></div>
                <?php
            }
        }
    }

    /**
     * 
     * @param type $sql
     * @return type
     */
    public function select($sql)
    {
        try
        {
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return $sth;
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

}
