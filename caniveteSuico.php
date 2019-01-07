<?php


class caniveteSuico
{
    private $string;

    function __construct()
    {

        $this->string = "";
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name){
      if (isset($name) && empty($name))

        return $name;
      else

        return false;

   }

   public function tiraAcentos() {

    $this->string = preg_replace("/[ÁÀÂÃÄáàâãä]/", "a", $this->string);
    $this->string = preg_replace("/[ÉÈÊéèê]/", "e", $this->string);
    $this->string = preg_replace("/[ÍÌíì]/", "i", $this->string);
    $this->string = preg_replace("/[ÓÒÔÕÖóòôõö]/", "o", $this->string);
    $this->string = preg_replace("/[ÚÙÜúùü]/", "u", $this->string);
    $this->string = preg_replace("/[Çç]/", "c", $this->string);

    return $this->string;
   }
}
