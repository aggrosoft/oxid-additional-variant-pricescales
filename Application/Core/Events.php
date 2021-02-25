<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Core;

class Events {
    public static function onActivate()
    {
        $queries = [
            'ALTER TABLE oxarticles ADD COLUMN AGSETUPFEES DOUBLE NULL'
        ];

        foreach($queries as $query){
            try{
                \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($query);
            }catch(\Exception $e){

            }
        }

    }
}