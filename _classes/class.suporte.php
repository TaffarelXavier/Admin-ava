<?php

class Suporte {

    private $conexao = null;

    public function __construct($connection) {
        $connection->query('use ' . DB_NAME . ';');
        $this->conexao = $connection;
    }

    public function select($sql) {
        try {
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function mostrar_aos_destinatarios($sql) {
        try {
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function upd_status($sql) {
        try {
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return (int) $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $mensagem
     * @param type $nivel
     * @param type $remetente
     * @param type $ordem
     * @param type $tipo
     * @return type
     */
    public function inserir($mensagem, $nivel, $remetente, $ordem, $tipo) {
        try {
            $sql = 'INSERT INTO `suporte` (`suporte_id`, `suporte_mensagem`, `suporte_data`, `suporte_nivel`,'
                    . ' `suporte_status`, `suporte_remetente`,`suporte_ordem`,`suporte_tipo`) VALUES (NULL,?, "' . time() . '",?,"aberto",?,?,?);';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $mensagem, PDO::PARAM_STR);
            $sth->bindParam(2, $nivel, PDO::PARAM_STR);
            $sth->bindParam(3, $remetente, PDO::PARAM_STR);
            $sth->bindParam(4, $ordem, PDO::PARAM_STR);
            $sth->bindParam(5, $tipo, PDO::PARAM_STR);
            $sth->execute();
            return (int) $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * <p>Insere a relação</p>
     * @param type $suporte_id
     * @param type $destinatarios_ids
     * @return type
     */
    public function insert_relacao($suporte_id, $destinatarios_ids) {
        try {
            $sql = 'INSERT INTO `suporte_relacao` (`sup_rel_id`, `sup_rel_suporte_id`,`sup_rel_destinatarios_id`) VALUES (NULL,?,?)';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $suporte_id, PDO::PARAM_INT);
            $sth->bindParam(2, $destinatarios_ids, PDO::PARAM_STR);
            $sth->execute();
            return (int) $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function last_id() {
        try {
            $sql = "SELECT `suporte_id` FROM `suporte` ORDER BY suporte_id DESC LIMIT 1";
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            $total = $sth->fetch();
            return (int) $total[0];
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $suporte_id
     * @return type
     */
    public function excluir_comnunicado($suporte_id) {
        try {
            $sql = 'delete FROM `suporte` WHERE suporte_id = ?;DELETE FROM `suporte_relacao` WHERE sup_rel_suporte_id =?;'
                    . 'DELETE FROM suporte_view WHERE sup_view_suporte_id = ?;';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $suporte_id, PDO::PARAM_INT);
            $sth->bindParam(2, $suporte_id, PDO::PARAM_INT);
            $sth->bindParam(3, $suporte_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $suporteID
     * @return type
     */
    public function get_alunos_visualizados($suporteID) {
        try {
            $sql = "SELECT * FROM `suporte_view` AS t1 JOIN alunos AS t2 "
                    . "ON t1.sup_view_user_id = t2.alu_id WHERE t1.sup_view_suporte_id = ? ORDER BY t1.sup_view_id DESC";
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $suporteID, PDO::PARAM_INT);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}

/*
include '../autoload.php';

$connection->query('use '.DB_NAME);

$Suporte = new Suporte($connection);

$f= $Suporte->select('SELECT * FROM `suporte` WHERE suporte_id =4');
$d= $f->fetch(PDO::FETCH_ASSOC);

echo $d['suporte_mensagem'];*/