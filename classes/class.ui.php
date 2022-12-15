<?php
/*
 * File: class.ui.php
 * File Created: Sunday, 7th August 2022 12:06:13 am
 * Author: hiimcody1
 * 
 * Last Modified: Tuesday, 1st November 2022 11:22:24 pm
 * Modified By: hiimcody1
 * 
 * License: MIT License http://www.opensource.org/licenses/MIT
 */

class UI {
    private Template $Template;
    private string $RenderedView;

    public function __construct() {
        $this->Template = new Template();
    }

    public function Render($View) {
        $this->Template->viewVars["Title"] = "ALttPDoorRandomizer Web - " . $View;
        $this->Template->viewVars["Navigation"] = $this->BuildNav($View);   //Build our navbar
        $this->Template->viewVars["Content"] = $this->Template->render($View);  //Populate Content
        $this->RenderedView = $this->Template->render("base.php");  //Build the final render
        echo $this->RenderedView;
    }

    public function GetRoute($Request,$isAPI=false) {
        $Routes = json_decode(file_get_contents(Config::RoutesPath."web/routes.json"), true);
        $RequestFull = explode("/",$Request);
        foreach($Routes as $Route) {
            if(array_key_exists($RequestFull[1],$Route)) {
                //Hit
                return $Route[$RequestFull[1]];
            }
        }
        return preg_replace("/[^\w-]/", '', ($RequestFull[0] != "" ? $RequestFull[0] : "home")).".php";
    }

    private function BuildNav($CurrentView) {
        $views = $this->FetchAvailableViews();
        foreach($views as $view)
            $navItems[] =  '<li class="nav-item"><a href="/'.strtolower(str_replace(".php","",$view)).'" class="nav-link'.($view == $CurrentView ? ' active' : '').'">'.ucfirst(str_replace(".php","",$view))."</a></li>";
        return '<ul class="nav nav-pills col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">'.PHP_EOL.implode(PHP_EOL,$navItems).PHP_EOL.'</ul>';
    }

    private function FetchAvailableViews() {
        $routes = json_decode(file_get_contents(Config::RoutesPath."web/routes.json"), true);
        $viewBases = [];
        foreach($routes as $view)
            $viewBases[] = $view["_base"];
        return $viewBases;
    }
}

?>