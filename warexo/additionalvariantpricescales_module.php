<?php

class AdditionalVariantPriceScalesModule{

    public function get_additional_field_names($table)
    {
        if ($table == "oxarticle")
        {
            return array("agsetupfees" => "agsetupfees");
        }
        return array();
    }
}

$module = new AdditionalVariantPriceScalesModule;
ModuleManager::getInstance()->registerModule( $module );