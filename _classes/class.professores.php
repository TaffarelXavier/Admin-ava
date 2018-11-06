<?php

class Professores {

  /**
   * <p>Link da conexao</>
   * @var type 
   */
  private $conexao = null;

  /**
   * <p>Nome da tabela</p>
   * @var type 
   */
  private $table_name = "professores";

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
   * <p>Criar uma senha para um novo usuário. Esta senha é considerada muito forte e muito difícil de decifrá-la.</p>
   * @param type $user_name
   * @param type $user_pass
   * @return type
   */
  public function incluir_novo_professor($user_name, $user_pass) {

    //Define o tamanho máximo de caracteres para o usuário
    if (strlen($user_name) > 15) {
      exit("Erro na criação de usuário.Cod:1");
    } else if (!is_string($user_pass)) {
      exit("Erro na criação de usuário.Cod:2");
    }

    $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);

    $hash = password_hash($user_pass, PASSWORD_BCRYPT, array("cost" => 14, "salt" => $salt));

    $salt_2 = $this->get_salt();

    $sth = $this->conexao->prepare("INSERT INTO `" . $this->table_name . "` (`user_id`, `user_name`, `user_pass`, `user_salt`, `user_option`)"
	    . " VALUES (NULL,:username, :hash, :salt,'1')");
    $sth->bindParam(':username', $user_name, PDO::PARAM_STR);
    $sth->bindParam(':hash', $hash, PDO::PARAM_STR);
    $sth->bindParam(':salt', $salt_2, PDO::PARAM_STR);
    $sth->execute();
    $query = $sth->fetch();
    return $query;
  }

  /**
   * 
   * @param type $senha
   * @return type
   */
  public function get_hash_password($senha) {
    $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
    return password_hash($senha, PASSWORD_BCRYPT, array("cost" => 14, "salt" => $salt));
  }

  /**
   * 
   * @param type $prof_cpf
   * @param type $prof_nome
   * @param type $prof_senha
   * @param type $prof_status
   * @return type
   */
  public function insert_novo($prof_cpf, $prof_nome, $prof_senha, $prof_status) {
    try {
      if (strlen($prof_cpf) > 15) {
	exit("Erro na criação de usuário ou senha.");
      } else if (!is_string($prof_senha)) {
	exit("Erro na criação de usuário ou senha.");
      }

      $sth = $this->conexao->prepare("INSERT INTO `" . $this->table_name . "` (`prof_id`, `prof_cpf`, `prof_nome`, `prof_senha`, `prof_status`,`prof_nivel`)VALUES (NULL,?,?,?,?,'PROFESSOR')");
      $sth->bindParam(1, $prof_cpf, PDO::PARAM_STR);
      $sth->bindParam(2, $prof_nome, PDO::PARAM_STR);
      $sth->bindParam(3, $this->get_hash_password($prof_senha), PDO::PARAM_STR);
      $sth->bindParam(4, $prof_status, PDO::PARAM_STR);
      $sth->execute();
      return (int) $sth->rowCount();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  public function cpf_existe($cpf) {
    try {
      $sql = 'SELECT COUNT(prof_cpf) FROM `professores` AS t1 WHERE t1.prof_cpf=?';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $cpf, PDO::PARAM_STR, 15);
      $sth->execute();
      $total = $sth->fetch();
      return (int) $total[0];
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  public function last_id() {
    try {
      $sth = $this->conexao->prepare("SELECT prof_id FROM `professores` AS t1 ORDER BY prof_id DESC LIMIT 1");
      $sth->execute();
      $total = $sth->fetch();
      return (int) $total['prof_id'];
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * <p>Verifica à senha</>
   * @param type $cpf
   * @param type $user_pass
   * @return boolean
   */
  public function verificar_senha_professor($cpf, $user_pass) {
    //O tamanho máximo da senha será de 15 caracteres.
    if (strlen($user_pass) > 15) {
      exit("Erro na autenticação!Cod:1.1");
    } else if (!is_string($user_pass)) {
      exit("Erro na autenticação!Cod:1.2");
    }
    $sth = $this->conexao->prepare("SELECT * FROM `" . $this->table_name . "` WHERE `prof_cpf` = :cpf");

    $sth->bindParam(':cpf', $cpf, PDO::PARAM_STR);

    $sth->execute();

    $query = $sth->fetch();

    if (password_verify($user_pass, $query[3])) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
   * <p>Atualiza senhas</p> 
   * @param type $id
   * @param type $nova_senha
   * @return type
   */
  public function atualizar_senha_professor($id, $nova_senha) {
    //Se não for uma senha
    if (!is_string($nova_senha)) {
      exit("Erro na autenticação!Cod:2.2.1");
    }
    try {
      $sth = $this->conexao->prepare("UPDATE " . $this->table_name . " SET `prof_senha` = :senha WHERE `prof_id` = :id ");
      $sth->bindParam(':id', $id, PDO::PARAM_INT);
      $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
      $hash = password_hash($nova_senha, PASSWORD_BCRYPT, array("cost" => 14, "salt" => $salt));
      $sth->bindParam(':senha', $hash, PDO::PARAM_STR);
      $sth->execute();
      return (int) $sth->rowCount();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * <p>Ativa um professor</p>
   * @param type $status Opções: ATIVO|INATIVO
   * @param type $prof_id
   * @return type
   */
  public function ativar_professor($status, $prof_id) {
    try {
      $sql = 'UPDATE `professores` SET `prof_status` = ? WHERE `professores`.`prof_id` = ?';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $status, PDO::PARAM_STR);
      $sth->bindParam(2, $prof_id, PDO::PARAM_INT);
      $sth->execute();
      return (int) $sth->rowCount();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * <p>Pega todas às senhas e atualiza todas elas.</p>
   * @param type $id
   * @param type $nova_senha
   * @return type
   */
  public function atualizar_todas_senhas_professores() {
    $sth = $this->conexao->prepare("SELECT * FROM " . $this->table_name . "");
    $sth->execute();

    while ($query = $sth->fetch()) {
      $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);

      $hash = password_hash("123", PASSWORD_BCRYPT, array("cost" => 14, "salt" => $salt));

      $this->atualizar_senha($query[0], $hash);
    }
  }

  /**
   * <p>Aqui a saída dos dados do aluno</a>
   * @param type $cpf
   */
  public function get_dados_do_professor($cpf) {
    try {
      $sth = $this->conexao->prepare("SELECT * FROM `" . $this->table_name . "` WHERE prof_cpf = :cpf");

      $sth->bindParam(':cpf', $cpf, PDO::PARAM_STR);

      $sth->execute();

      return $sth->fetch();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * 
   * @param type $id
   * @return type
   */
  public function get_dados_administrativo($id) {
    try {
      $sth = $this->conexao->prepare("SELECT * FROM `niveis_adminitrativos` AS t1 WHERE t1.niv_adm_professor_id=?");

      $sth->bindParam(1, $id, PDO::PARAM_INT);

      $sth->execute();

      return $sth->fetch();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * 
   * @param type $prof_id
   * @return type
   */
  public function prof_por_id($prof_id) {
    try {
      $sql = 'SELECT * FROM `professores` AS t1 WHERE t1.prof_id = ?';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $prof_id, PDO::PARAM_INT);
      $sth->execute();
      return $sth;
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  public function update_professor($prof_nome, $prof_cpf, $prof_id) {
    try {
      $sql = 'UPDATE `professores` SET `prof_nome` = ?,prof_cpf=? WHERE `professores`.`prof_id` = ?';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $prof_nome, PDO::PARAM_STR);
      $sth->bindParam(2, $prof_cpf, PDO::PARAM_STR);
      $sth->bindParam(3, $prof_id, PDO::PARAM_INT);
      $sth->execute();
      return $sth->rowCount();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * 
   * @param type $prof_id
   * @param type $disc_id
   * @return type
   */
  public function insert_relacao_professor_disciplinas($prof_id, $disc_id) {
    try {
      $sql = 'INSERT INTO `disciplinas_professor` (`discpro_id`, `discpro_professor_id`, `discpro_disciplina_id`) VALUES (NULL,?,?);';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $prof_id, PDO::PARAM_INT);
      $sth->bindParam(2, $disc_id, PDO::PARAM_INT);
      $sth->execute();
      return $sth->rowCount();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  public function insert_relacao_nivel_admin($prof_id, $nivel) {
    try {
      $sql = 'INSERT INTO `niveis_adminitrativos` (`niv_adm_id`, `niv_adm_professor_id`, `niv_adm_nivel`) VALUES (NULL,?,?);';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $prof_id, PDO::PARAM_INT);
      $sth->bindParam(2, $nivel, PDO::PARAM_STR);
      $sth->execute();
      return $sth->rowCount();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * <p>Desativa um professor.</p>
   */
  public function desativar_professor($professor_id) {
    try {
      $sql = "UPDATE `professores` SET `prof_status` = 'INATIVO' WHERE `professores`.`prof_id` = ?";
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $professor_id, PDO::PARAM_INT);
      $sth->execute();
      return $sth->rowCount();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * 
   * @param type $prof_id
   * @return type
   */
  public function delete_relacao_nivel_admin($prof_id) {
    try {
      $sql = 'DELETE FROM `niveis_adminitrativos` WHERE `niveis_adminitrativos`.`niv_adm_professor_id` = ?';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $prof_id, PDO::PARAM_INT);
      $sth->execute();
      return $sth->rowCount();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * 
   * @param type $disc_id
   * @return type
   */
  public function delete_disciplinas($disc_id) {
    try {
      $sql = 'DELETE FROM `disciplinas_professor` WHERE discpro_professor_id = ?';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $disc_id, PDO::PARAM_INT);
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
  public function select_todos_os_professores() {
    try {
      $sth = $this->conexao->prepare("SELECT * FROM `" . $this->table_name . "` AS t1 WHERE t1.`prof_status` = 'ATIVO' ORDER BY t1.prof_nome");
      $sth->execute();
      return $sth;
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  public function select_professores_desativados() {
    try {
      $sth = $this->conexao->prepare("SELECT * FROM `" . $this->table_name . "` AS t1 WHERE t1.`prof_status` = 'INATIVO' ");
      $sth->execute();
      return $sth;
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  public function select_disciplinas_por_professor($prof_id) {
    try {
      $sql = 'SELECT disc_id,disc_nome FROM `disciplinas_professor` AS t1 JOIN disciplinas AS t2 ON t1.discpro_disciplina_id = t2.disc_id WHERE t1.discpro_professor_id = ?';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $prof_id, PDO::PARAM_INT);
      $sth->execute();
      return $sth;
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * 
   * @param type $prof_id
   * @return type
   */
  public function get_niveis_administrativos_por_id($prof_id) {
    try {
      $sql = 'SELECT * FROM `niveis_adminitrativos` AS t1 WHERE t1.niv_adm_professor_id =?';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $prof_id, PDO::PARAM_INT);
      $sth->execute();
      $fetch = $sth->fetch(PDO::FETCH_ASSOC);
      return $fetch;
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
   * <p>Aqui a saída dos dados do aluno</a>
   * @param type $tipo
   */
  public function get_professores_por_tipo($tipo) {

    $sth = $this->conexao->prepare("SELECT * FROM `" . $this->table_name . "` WHERE prof_nivel = ?");

    $sth->bindParam(1, $tipo, PDO::PARAM_STR);

    $sth->execute();

    return $sth;
  }

  public function get_detalhes($prof_id) {
    try {
      $sql = '';
      $sth = $this->conexao->prepare($sql);
      $sth->bindParam(1, $prof_id, PDO::PARAM_INT);
      $sth->execute();
      return $sth;
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

}
