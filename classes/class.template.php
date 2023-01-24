<?php
/*
 * File: class.template.php
 * File Created: Sunday, 7th August 2022 10:22:11 pm
 * Author: hiimcody1
 * 
 * Last Modified: Tuesday, 24th January 2023 1:28:11 pm
 * Modified By: hiimcody1
 * 
 * License: MIT License http://www.opensource.org/licenses/MIT
 */

class Template {
    public $viewVars = Array();

    public function render($viewPage) {
        if(file_exists(Config::TemplatesPath . $viewPage)) {
            ob_start();
            $TemplateVars = $this->viewVars;
            include(Config::TemplatesPath . $viewPage);
            return ob_get_clean();
        } else {
            return null;
        }
    }
}

?>