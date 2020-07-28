<?php

use \Rain\Tpl;

class Page {

    //objeto do template
    private $tpl;
    //array de configuracoes da pagina
    private $options = [];
    //aray que recebe configs 
    private $defaults = [
        "data"=>[]
    ];

    //construtor recebe algumas configs como parametro
    public function __construct($opts = array()) {

        //
        $this->options = array_merge($this->defaults, $opts);

        $config = array(
            "tpl_dir"   =>  "views/",
            "cache_dir" => "views_cache/",
            "debug"     => false
        );

        Tpl::configure( $config );

        $this->tpl = new Tpl();

        $this->setData($this->options['data']);

        $this->tpl->draw("header");
    }

    private function setData($data = array()) 
    {
        foreach($data as $key => $value)
        {
            $this->tpl->assign($key,$value);
        }
    }

    public function setTpl($name, $data = array(), $returnHTML = false)
    {
        $this->setData($data);

        return $this->tpl->draw($name, $returnHTML);
    }

    public function __destruct() {

        $this->tpl->draw("footer");
    }
}

?>