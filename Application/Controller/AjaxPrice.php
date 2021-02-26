<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Core;

class AjaxPrice extends AjaxPrice_parent {

    protected function getAdditionalAjaxPriceData ($article, $amount) {
        return [
            'basePrice' => $article->getRawBasePrice($amount) * $amount,
            'unitBasePrice' => $article->getRawBasePrice($amount)
        ];
    }

}