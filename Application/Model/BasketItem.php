<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Model;

class BasketItem extends BasketItem_parent {

    public function setPrice($oPrice)
    {
        parent::setPrice($oPrice);
        $article = $this->getArticle();
        $fee = 0;

        if ($article && $article->oxarticles__oxvarselect->value) {
            $fee = $article->getAdditionalVariantHandlingFees();
        }

        $this->_oUnitPrice->add($fee / $this->getAmount());
        $this->_oPrice->add($fee);

    }

}