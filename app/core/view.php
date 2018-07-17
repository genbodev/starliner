<?php

namespace Core;

class View {

  private $_template;
  private $_vars = array();
  private $_vars_array = array();
  private $_var_array_name = null;
  private $_str = '';

  public function set($name, $val) {

    if (is_array($val)) {
      if ((count($val, COUNT_RECURSIVE)) - count($val) <= 0) {
        $this->_vars_array[$name] = $val;
        $val = '[var ' . $name . ' is ARRAY]';
      }
      else {
        $val = '[var ' . $name . ' is MULTIDIMENSIONAL ARRAY]';
      }
    }
    $this->_vars['{{' . $name . '}}'] = $val;

  }

  public function glue($name, $view) {

    $str = file_get_contents('app/views/' . $view);
    $result = $this->_assignVars($str);
    $this->_vars['{{' . $name . '}}'] = $result;

  }

  public function display($template, $isAssign = true) {

    $this->_template = 'app/views/' . $template;
    if (!file_exists($this->_template)) die('Template ' . $this->_template . ' does not exist!');
    $this->_str = file_get_contents($this->_template);
    if ($isAssign === true) {
      $this->_str = $this->_assignVars($this->_str);
      if (count($this->_vars_array) > 0) {
        foreach ($this->_vars_array as $key => $val) {
          $this->_var_array_name = $key;
          $this->_str = $this->_cycleCreate($key);
        }
      }
    }

    echo $this->_str;

  }


  private function _assignVars($str) {

    $result = str_replace(array_keys($this->_vars), array_values($this->_vars), $str);
    return $result;

  }


  private function _cycleCreate($key) {

    $result = '';
    $re = '~\{\{cycle from ' . $key . '\}\}(.+?)\{\{/cycle\}\}~is';
    if (preg_match($re, $this->_str)) {
      $result = preg_replace_callback($re, array($this, '_cycleCreateReplace'), $this->_str);
    }

    return $result;

  }

  private function _cycleCreateReplace($matches) {

    $txt = '';
    foreach ($this->_vars_array[$this->_var_array_name] as $k => $v) {
      $txt .= str_replace(array('{{key}}', '{{val}}'), array($k, $v), $matches[1]);
    }

    return $txt;
    
  }

}