<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Controller;

class AjaxPrice extends AjaxPrice_parent {
    protected function getAdditionalAjaxPriceData ($article, $amount) {
        $data = parent::getAdditionalAjaxPriceData($article, $amount);
        $addPrice = $article->getAdditionalVariantAmountPrice($amount);
        $oConfig = oxRegistry::getConfig();
        $oCurrency = $oConfig->getActShopCurrencyObject();
        $data['additionalVariantAmountPrice'] = oxRegistry::getLang()->formatCurrency( $addPrice ) . ' ' . $oCurrency->sign;
        return $data;
    }
}