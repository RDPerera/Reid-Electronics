<?php
class cis_template_parser
{
    var $vars = array();
    var $template;
    public function get_tpl($tpl_name)
    {
        if(empty($tpl_name) || !file_exists($tpl_name))
        {
            echo '<div><h1>ERROR</h1><p>template '. $tpl_name . ' not found</p><div>';
            return false;
        }
        else
        {
            $this->template  = file_get_contents($tpl_name);
        }
    }
    public function set_tpl($key,$var)
    {
        $this->vars[$key] = $var;
    }
    public function tpl_parse()
    {
        foreach($this->vars as $find => $replace)
        {
            $this->template = str_replace($find, $replace, $this->template);
        }
    }
}
?>