<?php
class Zend_View_Helper_Shorturl {

    public function shorturl() {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        return "/$controller/$action";
    }
}  