<?php

class Sessao
{

    /**
     * 
     */
    const TEMPO_ESPIRADO = "Seu tempo Expirou!";

    /**
     * 
     */
    const SESSAO_EXPIRADA = "SESSAO_ESPIRADA";

    /**
     *
     * @var type 
     */
    private $cod = "total_de_erros";

    /**
     *
     * @var type 
     */
    private $sessiontime = "sessiontime";

    public function __construct()
    {
        if (session_status() == 1)
        {
            session_start();
            session_regenerate_id();
        }
    }

    /**
     * 
     * @param type $segundos
     */
    public function add($segundos)
    {

        $temposessao = $segundos; //em segundos

        if (isset($_SESSION[$this->sessiontime]))
        {
            if ($_SESSION[$this->sessiontime] < (time() - $temposessao))
            {
                session_unset();
                echo self::TEMPO_ESPIRADO;
            }
        } else
        {
            session_unset();
        }

        $_SESSION[$this->sessiontime] = time();
    }

    /**
     * <p>Obtém o tempo da sessão</p>
     * @param type $s
     * @return type
     */
    public function get_timestamp($s = true)
    {
        if ($s == true)
        {
            return date("h:i:s", $_SESSION[$this->sessiontime]);
        }
        return $_SESSION[$this->sessiontime];
    }

    /**
     * <p>Obtém o tempo restante</p>
     * @return type
     */
    public function tempo_restante($date_all = true)
    {
        $tempo_r = $_SESSION[$this->sessiontime] - time();

        if ($date_all == true)
        {
            return (string) date("h:i:s", $tempo_r);
        }
        return (string) date("s", $tempo_r);
    }

    public function add_sessao_fixada($nome_da_sessao, $segundos)
    {

        if (!isset($_SESSION[$nome_da_sessao]))
        {
            $_SESSION[$nome_da_sessao] = time() + $segundos;
        }

        $count = $_SESSION[$nome_da_sessao] - time();

        if ($count <= 0)
        {
            return self::SESSAO_EXPIRADA;
        } else
        {
            //A sessão foi iniciada;
        }
    }

    public function get_sessao_fixada($nome_da_sessao)
    {
        return (string) $_SESSION[$nome_da_sessao];
    }

    /**
     * <p>Essa função é para trabalhar com erros de login</p>
     * @return int
     */
    public function add_numeros_de_erros($key)
    {
        if (!isset($_SESSION[$key]))
        {
            $_SESSION[$key] = 1;
        } else
        {
            $_SESSION[$key] += 1;
        }
        return (int) $_SESSION[$key];
    }

    /**
     * <p>Obtém os dados desta sessão</p>
     * @return type
     */
    public function get_dados_da_sessao()
    {

        if (!isset($_SESSION[$this->cod]))
        {
            $_SESSION[$this->cod] = 0;
        }
        if (isset($_SESSION[$this->sessiontime]))
        {
            
        }
        return array($_SESSION[$this->cod], $_SESSION[$this->sessiontime]);
    }

    /**
     * <p>Pega o valor da sessão </p>
     * @return type
     */
    public function get_valor_sessao_por_chave($key)
    {
        if (isset($_SESSION[$key]))
        {
            return (string) $_SESSION[$key];
        }
    }

    /**
     * <p>Pega o valor da sessão </p>
     * @return type
     */
    public function get_tempo_restante($key)
    {
        if (isset($_SESSION[$key]))
        {
            return (int) $_SESSION[$key] - time();
        }
    }

    /**
     * <p>Pega o valor da sessão </p>
     * @return type
     */
    public function get_mais_tempo_restante($key)
    {
        return (int) $_SESSION[$key] - time();
    }

    /**
     * <p>Exclui uma sessão por chave</p>
     * @return type a chave 
     */
    public function excluir_sessao_por_chave($key)
    {
        if (isset($_SESSION[$key]))
        {
            unset($_SESSION[$key]);
        }
        /*
          if (!isset($_SESSION[$key]))
          {
          return true;
          }
          return false;
         * 
         */
    }

    /**
     * <p>Encerra todas as sessões</p>
     */
    public function close()
    {
        unset($_SESSION[""]);
        session_destroy();
    }

}
