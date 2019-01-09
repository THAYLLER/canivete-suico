<?php

class caniveteSuico
{
    private $string;
    private $conteudo;
    private $tipo;
    private $qt;

    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }

    public function tiraAcentos() {

        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$this->string);
   }

    public function ChecaVariavel() {
        if ($this->qt) {
            $this->conteudo = $this->left($this->conteudo, $this->qt);
        }
        //limita qt de caracteres
        //$conteudo = utf8_encode($conteudo); //coloca no formato UTF-8
        $this->conteudo = trim($this->conteudo); //Retira espaço no ínicio e final da string
        if ($this->tipo == 'inverte') {
            $this->conteudo = stripslashes($this->conteudo);
        } else {
            //funcao nao esta ativada
            if (!get_magic_quotes_gpc()) {
                $this->conteudo = addslashes($this->conteudo);
            }
            //Retorna a string com escapes (\)
        }
        if ($this->tipo != "texto") {
            $this->conteudo = strip_tags($this->conteudo);
        }
        //Retira as tags HTML e PHP de uma string
        if ($this->tipo == "padrao") {
        } elseif ($this->tipo == "decimal") {
            $this->conteudo = strtr($this->conteudo, ".", "");
            $this->conteudo = strtr($this->conteudo, ",", ".");
        } elseif ($this->tipo == "numero") {
            if ($this->conteudo != "") {
                $this->conteudo = intval($this->conteudo);
            }

        } elseif ($this->tipo == "data") {
            $this->conteudo = $this->cData2($this->conteudo);
        } elseif ($tipo == "data1") {
            $this->conteudo = $this->cData1($this->conteudo);
        } elseif ($tipo == "dataHora") {
            $this->conteudo = $this->cDataHora2($this->conteudo);
        } elseif ($this->tipo == "forma_data") {
            $this->conteudo = $this->right($this->conteudo, 4) . "-" . $this->left($this->right($this->conteudo, 6), 2) . "-" . $this->left($this->conteudo, 2);
        } elseif ($tipo == "forma_data2") {
            $this->conteudo = $this->right($this->conteudo, 2) . "-" . $this->left($this->right($this->conteudo, 6), 2) . "-" . $this->left($this->conteudo, 4);
            // coloca pontos e traços no CPF
        } elseif ($this->tipo == "cpf") {
            $this->conteudo = $this->left($this->conteudo, 3) . "." . substr($this->conteudo, 3, 3) . "." . substr($this->conteudo, 6, 3) . "-" . $this->right($this->conteudo, 2);
        } elseif ($this->tipo == "email") {
            //testa email
            if (eregi("^([a-zA-Z0-9.-_])*([@])([a-z0-9]).([a-z]{2,3})", $this->conteudo)) {
                $this->conteudo = 1;
            }

            //retira qualquer caracter que não seja numero
        } elseif ($this->tipo == "digitos") {
            $this->conteudo = str_replace("/", "", $this->conteudo);
            $this->conteudo = str_replace(",", "", $this->conteudo);
            $this->conteudo = str_replace(".", "", $this->conteudo);
            $this->conteudo = str_replace("_", "", $this->conteudo); //retira caractere - (traços)
            $this->conteudo = str_replace("-", "", $this->conteudo); //retira caractere - (traços)
            $this->conteudo = str_replace("(", "", $this->conteudo); //retira abre parenteses
            $this->conteudo = str_replace(")", "", $this->conteudo); //retira fecha parenteses
            $this->conteudo = str_replace(" ", "", $this->conteudo); //retira espaços
        }
        return $this->conteudo;
    }
    private function cData1($strData) {
        if (preg_match("#-#", $strData) == 1) {
            $strData = implode('/', array_reverse(explode('-', $strData)));
        }
        return $strData;
    }
    // recebe a data no formato aaaa-mm-dd e converte a para dd/mm/aaaa
    private function cData2($strData) {
        if (preg_match("#/#", $strData) == 1) {
            $strData = implode('-', array_reverse(explode('/', $strData)));
        }
        return $strData;
    }
    // recebe dataHora no formato do banco de dados mysql e converte no formato normal
    private function cDataHora1($strData) {
        if (preg_match("#-#", $strData) == 1) {
            $strData2 = implode('/', array_reverse(explode('-', left($strData, 10))));
            $strData = $strData2 . $this->right($strData, 9);
        }
        return $strData;
    }
    // converte data e hora em formato para o banco de dados mysql
    private function cDataHora2($strData) {
        if (preg_match("#/#", $strData) == 1) {
            $strData2 = implode('-', array_reverse(explode('/', $this->left($strData, 10))));
            $strData = $strData2 . $this->right($strData, 9);
        }
        return $strData;
    }

    ///////////////////////////////////////
    private function right($value, $count) {
        $value = substr($value, (strlen($value) - $count), strlen($value));
        return $value;
    }
    private function left($string, $count) {
        return substr($string, 0, $count);
    }
}
