<?php

class Ajuda {

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
     * @param type $ajuda_id
     * @param type $remetente
     * @param type $mensagem
     * @param type $data
     * @return type
     */
    public function inserir($ajuda_id, $remetente, $mensagem, $data) {
        try {
            $sql = 'INSERT INTO `ajuda_resposta` (`aju_id`, `aju_ajuda_id`, `aju_remetente`, `aju_mensagem`, `aju_data_resposta`,`aju_status`,`aju_visto`) VALUES (NULL,?,?,?,?,"branco","sim");';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $ajuda_id, PDO::PARAM_INT);
            $sth->bindParam(2, $remetente, PDO::PARAM_STR);
            $sth->bindParam(3, $mensagem, PDO::PARAM_STR);
            $sth->bindParam(4, $data, PDO::PARAM_STR);
            $sth->execute();
            return (int) $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $lido
     * @param type $aju_id
     * @param type $ajuda_id
     * @return type
     */
    public function marcar_como_lida($lido, $aju_id, $ajuda_id) {
        try {
            $sql = 'UPDATE `ajuda_resposta` SET `aju_visto` = ? WHERE `ajuda_resposta`.`aju_id` = ? AND `ajuda_resposta`.`aju_ajuda_id` = ?';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $lido, PDO::PARAM_STR);
            $sth->bindParam(2, $aju_id, PDO::PARAM_INT);
            $sth->bindParam(3, $ajuda_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->rowCount();
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
     * @param type $ajuda_id
     * @return type
     */
    public function get_ajuda_por_id($ajuda_id) {
        try {
            $sql = "SELECT ajuda_id,ajuda_caminho_pasta,ajuda_tipo,ajuda_nivel,ajuda_mensagem,ajuda_data_envio,ajuda_status,alu_nome,alu_id FROM `ajuda` AS t1 JOIN alunos AS t2 ON t1.ajuda_usuario_id = t2.alu_id WHERE t1.ajuda_id = ?";
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $ajuda_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function get_mensagens_por_id($ajuda_id) {
        try {
            $sql = 'SELECT * FROM `ajuda_resposta` AS t1 WHERE t1.aju_ajuda_id = ? ORDER BY t1.aju_id DESC';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $ajuda_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function update_view_ajuda($lido, $ajuda_id) {
        try {
            $sql = "UPDATE `ajuda` SET `ajuda_visto` = ? WHERE `ajuda`.`ajuda_id` = ?";
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $lido, PDO::PARAM_STR);
            $sth->bindParam(2, $ajuda_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $mensagem
     * @param type $post_id
     * @return type
     */
    public function update_post_por_id($mensagem, $post_id) {
        try {
            $sql = "UPDATE `ajuda_resposta` SET `aju_mensagem` = ? WHERE `ajuda_resposta`.`aju_id` = ?;";
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $mensagem, PDO::PARAM_STR);
            $sth->bindParam(2, $post_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $aju_id
     * @param type $ajuda_id
     * @param type $usuario_id
     * @return type
     */
    public function delete_topico($aju_id, $ajuda_id, $usuario_id) {
        try {
            $sql = "DELETE FROM `ajuda_resposta` WHERE aju_id = ? AND aju_ajuda_id = ? AND aju_usuario = ?";
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $aju_id, PDO::PARAM_INT);
            $sth->bindParam(2, $ajuda_id, PDO::PARAM_INT);
            $sth->bindParam(3, $usuario_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * @param type $ajuda_id
     * @param type $usuario_id
     * @return type
     */
    public function delete_inteiramente_ajuda_por_id($ajuda_id, $usuario_id) {
        try {

            $sql = "DELETE FROM `ajuda` WHERE ajuda_id = ?  AND ajuda_usuario_id = ?;";
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $ajuda_id, PDO::PARAM_INT);
            $sth->bindParam(2, $usuario_id, PDO::PARAM_INT);
            $sth->execute();

            $sql2 = "DELETE FROM `ajuda_resposta` WHERE aju_ajuda_id = ?;";
            $sth2 = $this->conexao->prepare($sql2);
            $sth2->bindParam(1, $ajuda_id, PDO::PARAM_INT);
            $sth2->execute();
            $sth2->rowCount();

            return $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * <p>Retorna o número de mensagens não lidas pelo administrador. Essas mensagens são enviadas pelos alunos.</p>
     * @param type $ajuda_id
     * @return type
     */
    public function contar_mensagens_nao_lidas($ajuda_id) {
        try {
            $sql = "SELECT COUNT(*) FROM `ajuda_resposta` AS t1 WHERE t1.aju_ajuda_id = ? AND t1.aju_visto = 'nao';";
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $ajuda_id, PDO::PARAM_INT);
            $sth->execute();
            $total = $sth->fetch();
            return $total[0];
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function sse_msg_nao_lidas() {

        try {
            $sql = "SELECT COUNT(*) FROM `ajuda` where ajuda_visto = 'nao'";
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            $total = $sth->fetch();
            return $total[0];
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function get_msg_nao_lidas() {

        try {
            $sql = "SELECT * FROM `ajuda` where ajuda_visto = 'nao'";
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @return type
     */
    public function MSG_ajuda_respostas_nao_lidas() {
        try {
            $sql = "SELECT * FROM `ajuda_resposta` AS t1 WHERE aju_usuario <> 0 AND aju_visto <> 'sim' ";
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
