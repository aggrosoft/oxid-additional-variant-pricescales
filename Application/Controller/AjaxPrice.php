<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Controller;

use \OxidEsales\Eshop\Core\Registry;

class AjaxPrice extends AjaxPrice_parent {

    protected function getAdditionalAjaxPriceData ($article, $amount) {

        $oPrice = $article->getBasketPrice( $amount,  Registry::getConfig()->getRequestParameter('sel'), Registry::getSession()->getBasket() );

        if($oPrice->getBruttoPrice() == 0 && $oPrice->getNettoPrice() == 0){
            $oPrice = $article->getPrice($amount);
        }

        $blNettoPrice = Registry::getConfig()->getShopConfVar('blShowNetPrice');
        $fPrice = $blNettoPrice ? $oPrice->getNettoPrice() : $oPrice->getBruttoPrice();

        $oConfig = Registry::getConfig();
        $oCurrency = $oConfig->getActShopCurrencyObject();

        if ($article && $article->oxarticles__oxvarselect->value) {
            $fee = $article->getAdditionalVariantHandlingFees();
        } else {
            $fee = 0;
        }

        $oConfig = Registry::getConfig();
        $oCurrency = $oConfig->getActShopCurrencyObject();
        $fBasePrice = $article->getRawBasePrice($amount);

        return [
            'basePrice' => Registry::getLang()->formatCurrency( $fBasePrice * $amount ) . ' ' . $oCurrency->sign,
            'unitBasePrice' =>  Registry::getLang()->formatCurrency( $fBasePrice ) . ' ' . $oCurrency->sign,
            'pricePerUnit' => Registry::getLang()->formatCurrency( $fPrice + $fee / $amount ) . ' ' . $oCurrency->sign,
            'price' => Registry::getLang()->formatCurrency( $fPrice * $amount + $fee) . ' ' . $oCurrency->sign,
        ];
    }

}