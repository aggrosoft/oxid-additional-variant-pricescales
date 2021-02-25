<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Core;

class ViewConfig extends ViewConfig_parent {

    public function getAdditionalVariantPriceScales () {
        $result = [];

        $scales = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar('aAdditionalVariantPriceScales', null, 'module:agadditionalvariantpricescales');
        foreach($scales as $variant => $scale) {
            $result[$variant] = $this->parseAdditionalVariantPriceScale($scale);
        }

        return $result;
    }

    public function getAdditionalVariantHandlingFees () {
        return \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar('aAdditionalVariantHandlingFees', null, 'module:agadditionalvariantpricescales');
    }

    public function getAdditionalVariantHandlingFee ($selection) {
        $fees = $this->getAdditionalVariantHandlingFees();
        return $fees[$selection];
    }

    public function getAdditionalVariantPriceScale ($selection) {
        $scales = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar('aAdditionalVariantPriceScales', null, 'module:agadditionalvariantpricescales');
        if (isset($scales[$selection])) {
            return $this->parseAdditionalVariantPriceScale($scales[$selection]);
        }
    }

    protected function parseAdditionalVariantPriceScale ($scale) {
        $result = [];
        $splitted = explode(';', $scale);
        foreach($splitted as $spl) {
            list($from,$to,$price) = explode(',', $spl);
            $result[] = [
                'from' => $from,
                'to' => $to,
                'price' => $price
            ];
        }
        return $result;
    }

}