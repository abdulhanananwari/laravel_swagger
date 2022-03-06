<?php

namespace App\Helpers\Managers;

class ManagerBase {
    
    public function managerCaller($name, $arguments, $constructor, $namespace, $folder, $function) {
        
        $class =  $namespace . '\\' . $folder . '\\' . ucfirst($name);
        
        $assigner = new $class($constructor);
        return call_user_func_array([$assigner, $function], $arguments);
    }
}
