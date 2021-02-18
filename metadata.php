<?php

$sMetadataVersion = '2.0';

$aModule = array(
    'id'           => 'agadditionalvariantpricescales',
    'title'        => 'Aggrosoft Additional variant price scales',
    'description'  => [
        'de' => 'Erlaubt zusätzliche Aufschläge für bestimmte Varianten',
        'en' => 'Add additional price scales for certain variants'
    ],
    'thumbnail'    => '',
    'version'      => '1.0.0',
    'author'       => 'Aggrosoft GmbH',
    'extend'      => [
        \OxidEsales\Eshop\Application\Model\Article::class => \Aggrosoft\AdditionalVariantPriceScales\Application\Model\Article::class,
        \OxidEsales\Eshop\Application\Model\BasketItem::class => \Aggrosoft\AdditionalVariantPriceScales\Application\Model\BasketItem::class
    ],
    'settings' => [
        ['group' => 'agadditionalvariantpricescales_main', 'name' => 'aAdditionalVariantPriceScales','type' => 'aarr',   'value' => ''],
        ['group' => 'agadditionalvariantpricescales_main', 'name' => 'aAdditionalVariantHandlingFees','type' => 'aarr',   'value' => ''],
    ]
);
