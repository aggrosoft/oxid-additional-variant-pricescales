<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Model;

class Order extends Order_parent {

    // Must fix oxbprice to always carry 4 decimals
    protected function _setOrderArticles($aArticleList) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        // reset articles list
        $this->_oArticles = oxNew(\OxidEsales\Eshop\Core\Model\ListModel::class);
        $iCurrLang = $this->getOrderLanguage();

        // add all the products we have on basket to the order
        foreach ($aArticleList as $oContent) {
            //$oContent->oProduct = $oContent->getArticle();
            // #M773 Do not use article lazy loading on order save
            $oProduct = $oContent->getArticle(true, null, true);

            // copy only if object is oxarticle type
            if ($oProduct->isOrderArticle()) {
                $oOrderArticle = $oProduct;
            } else {
                // if order language does not match product language article must be reloaded in order language
                if ($iCurrLang != $oProduct->getLanguage()) {
                    $oProduct->loadInLang($iCurrLang, $oProduct->getProductId());
                }

                // set chosen select list
                $sSelList = '';
                if (count($aChosenSelList = $oContent->getChosenSelList())) {
                    foreach ($aChosenSelList as $oItem) {
                        if ($sSelList) {
                            $sSelList .= ", ";
                        }
                        $sSelList .= "{$oItem->name} : {$oItem->value}";
                    }
                    if ($sSelList !== '' && $oContent->getVarSelect() !== '') {
                        $sSelList .= ' ||';
                    }
                }

                $oOrderArticle = oxNew(\OxidEsales\Eshop\Application\Model\OrderArticle::class);
                $oOrderArticle->setIsNewOrderItem(true);
                $oOrderArticle->copyThis($oProduct);
                $oOrderArticle->setId();

                $oOrderArticle->oxorderarticles__oxartnum = clone $oProduct->oxarticles__oxartnum;
                $oOrderArticle->oxorderarticles__oxselvariant = new \OxidEsales\Eshop\Core\Field(trim($sSelList . ' ' . $oContent->getVarSelect()), \OxidEsales\Eshop\Core\Field::T_RAW);
                $oOrderArticle->oxorderarticles__oxshortdesc = new \OxidEsales\Eshop\Core\Field($oProduct->oxarticles__oxshortdesc->getRawValue(), \OxidEsales\Eshop\Core\Field::T_RAW);
                // #M974: duplicated entries for the name of variants in orders
                $oOrderArticle->oxorderarticles__oxtitle = new \OxidEsales\Eshop\Core\Field(trim($oProduct->oxarticles__oxtitle->getRawValue()), \OxidEsales\Eshop\Core\Field::T_RAW);

                // copying persistent parameters ...
                if (!is_array($aPersParams = $oProduct->getPersParams())) {
                    $aPersParams = $oContent->getPersParams();
                }
                if (is_array($aPersParams) && count($aPersParams)) {
                    $oOrderArticle->oxorderarticles__oxpersparam = new \OxidEsales\Eshop\Core\Field(serialize($aPersParams), \OxidEsales\Eshop\Core\Field::T_RAW);
                }
            }

            // ids, titles, numbers ...
            $oOrderArticle->oxorderarticles__oxorderid = new \OxidEsales\Eshop\Core\Field($this->getId());
            $oOrderArticle->oxorderarticles__oxartid = new \OxidEsales\Eshop\Core\Field($oContent->getProductId());
            $oOrderArticle->oxorderarticles__oxamount = new \OxidEsales\Eshop\Core\Field($oContent->getAmount());

            // prices
            $oPrice = $oContent->getPrice();
            $oOrderArticle->oxorderarticles__oxnetprice = new \OxidEsales\Eshop\Core\Field($oPrice->getNettoPrice(), \OxidEsales\Eshop\Core\Field::T_RAW);
            $oOrderArticle->oxorderarticles__oxvatprice = new \OxidEsales\Eshop\Core\Field($oPrice->getVatValue(), \OxidEsales\Eshop\Core\Field::T_RAW);
            $oOrderArticle->oxorderarticles__oxbrutprice = new \OxidEsales\Eshop\Core\Field($oPrice->getBruttoPrice(), \OxidEsales\Eshop\Core\Field::T_RAW);
            $oOrderArticle->oxorderarticles__oxvat = new \OxidEsales\Eshop\Core\Field($oPrice->getVat(), \OxidEsales\Eshop\Core\Field::T_RAW);

            $oUnitPrice = $oContent->getUnitPrice();
            $oOrderArticle->oxorderarticles__oxnprice = new \OxidEsales\Eshop\Core\Field($oUnitPrice->getNettoPrice(), \OxidEsales\Eshop\Core\Field::T_RAW);
            $oOrderArticle->oxorderarticles__oxbprice = new \OxidEsales\Eshop\Core\Field($oContent->getPreciseUnitPrice(), \OxidEsales\Eshop\Core\Field::T_RAW);

            // wrap id
            $oOrderArticle->oxorderarticles__oxwrapid = new \OxidEsales\Eshop\Core\Field($oContent->getWrappingId(), \OxidEsales\Eshop\Core\Field::T_RAW);

            // items shop id
            $oOrderArticle->oxorderarticles__oxordershopid = new \OxidEsales\Eshop\Core\Field($oContent->getShopId(), \OxidEsales\Eshop\Core\Field::T_RAW);

            // bundle?
            $oOrderArticle->oxorderarticles__oxisbundle = new \OxidEsales\Eshop\Core\Field($oContent->isBundle());

            // add information for eMail
            //P
            //TODO: check if this assign is needed at all
            $oOrderArticle->oProduct = $oProduct;

            $oOrderArticle->setArticle($oProduct);

            // simulation order article list
            $this->_oArticles->offsetSet($oOrderArticle->getId(), $oOrderArticle);
        }
    }


}