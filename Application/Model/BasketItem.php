<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Model;

class BasketItem extends BasketItem_parent {

    protected $_fPreciseUnitPrice;

    public function setPrice($oPrice)
    {
        parent::setPrice($oPrice);
        $article = $this->getArticle();
        $fee = 0;

        if ($article && $article->oxarticles__oxvarselect->value) {
            $fee = $article->getAdditionalVariantHandlingFees();
        }

        $this->_oPrice->add($fee);

    }

    public function getPreciseUnitPrice() {
        $article = $this->getArticle();
        $fee = 0;

        if ($article && $article->oxarticles__oxvarselect->value) {
            $fee = $article->getAdditionalVariantHandlingFees();
        }

        return $this->_oUnitPrice->getPrice() + $fee / $this->getAmount();
    }

}