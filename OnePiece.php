<?php

# -----------------------------------------
# Define chartset da header
# ----------------------------------------- 
ini_set('default_charset', 'UTF-8');

/**
 * Classe para verificar lançamentos de anime ou manga
 * de One Piece.
 * 
 * Caso tenha uma hospedagem, você pode usar o agendador de tarefa para o script ser
 * executado a cada intervalo de tempo que você desejar.
 * 
 * @todo Pegar link do manga e anime
 * @author offboard
 */
class OnePiece {

    /**
     * Site que será procurado
     * Nota: A barra no final é obrigatória
     * 
     * @access public
     * @var string
     */
    var $site = "http://onepiecex.com.br/";

    /**
     * Você procura o lançamento do manga ? se sim digite o capitulo
     * se não digite como false
     * 
     * @access public
     * @var int
     */
    var $manga = false;

    /**
     * Você procura o lançamento do anime ? se sim digite o capitulo
     * se não digite como false
     * 
     * @access public
     * @var int
     */
    var $anime = false;

    /**
     * Digite o email que será enviado após o lançamento
     * caso não queira que envie digite false
     * 
     * @access public
     * @var string
     */
    var $email = false;

    /**
     * Método Mágico
     * 
     * @access public
     * @return void
     */
    public function __construct() {
        if (!$this->manga && !$this->anime) {
            die('Você precisa escolher qual lançamento você deseja receber !');
        } else {
            // Procura novo manga
            if (!!$this->manga) {
                if ($this->ChecarManga()) {
                    $msg = sprintf("\n Saiu o novo capítulo %d de One Piece \n", $this->manga);
                    if (!$this->email) {
                        echo "\n" . $msg . "\n";
                    } else {
                        mail($this->email, "Novo capítulo {$this->anime} de One Piece", $msg);
                    }
                }
            }
            // Procurar novo anime
            if (!!$this->anime) {
                if ($this->ChecarAnime()) {
                    $msg = sprintf("Saiu o novo episódio %d de One Piece", $this->anime);
                    if (!$this->email) {
                        echo "\n" . $msg . "\n";
                    } else {
                        mail($this->email, "Novo episódio {$this->anime} de One Piece", $msg);
                    }
                }
            }
        }
    }

    /**
     * Verifica se saiu um capitulo novo do manga
     * 
     * @return boolean
     */
    private function ChecarManga() {
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $this->site . 'manga-' . $this->manga);
        curl_setopt($cURL, CURLOPT_HEADER, 0);
        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($cURL, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:33.0) Gecko/20100101 Firefox/33.0");
        $resultado = curl_exec($cURL);
        curl_close($cURL);
        if (eregi(sprintf("Mangá %d", $this->manga), $resultado) && !eregi(sprintf("Spoiler %d", $this->manga), $resultado)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica se saiu o novo episodio do anime
     * 
     * @return boolean
     */
    private function ChecarAnime() {
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $this->site . 'episodio-' . $this->anime);
        curl_setopt($cURL, CURLOPT_HEADER, 0);
        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($cURL, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:33.0) Gecko/20100101 Firefox/33.0");
        $resultado = curl_exec($cURL);
        curl_close($cURL);
        if (eregi(sprintf("Episódio %d", $this->anime), $resultado)) {
            return true;
        } else {
            return false;
        }
    }

}

// Inicia nossa classe caçadora de piratas ^^ entendeu a piada ?
new OnePiece();
