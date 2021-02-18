<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Model;

class BasketItem extends BasketItem_parent {

    public function setPrice($oPrice)
    {
        parent::setPrice($oPrice);
        $article = $this->getArticle();
        $fee = 0;

        if ($article && $article->oxarticles__oxvarselect->value) {
            $fees = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar('aAdditionalVariantHandlingFees', null, 'module:agadditionalvariantpricescales');
            if (count($fees)) {
                $varNames = explode('|', $article->oxarticles__oxvarselect->value);
                foreach ($varNames as $varName) {
                    if (isset($fees[trim($varName)])) {
                        $fee += $fees[trim($varName)];
                    }
                }
            }
        }

        $this->_oUnitPrice->add($fee / $this->getAmount());
        $this->_oPrice->add($fee);
    }

}