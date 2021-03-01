<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Model;

class Article extends Article_parent {

    public function getBasePrice($amount = 1)
    {
        $price = parent::getBasePrice($amount);
        $price += $this->getAdditionalVariantAmountPrice($amount);
        $price += $this->getAdditionalVariantSetupFees();
        return $price;
    }

    public function getRawBasePrice ($amount = 1) {
        return parent::getBasePrice($amount);
    }

    public function getAdditionalVariantPriceScales () {
        $result = [];
        $scales = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar('aAdditionalVariantPriceScales', null, 'module:agadditionalvariantpricescales');
        if (count($scales)) {
            foreach($scales as $variant => $scale) {
                $result[$variant] = $this->parseAdditionalVariantPriceScale($scale);
            }
        }
        return $result;
    }

    public function getAdditionalVariantHandlingFees () {
        $fees = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar('aAdditionalVariantHandlingFees', null, 'module:agadditionalvariantpricescales');
        $fee = 0;
        if (count($fees)) {
            $varNames = explode('|', $this->oxarticles__oxvarselect->value);
            foreach ($varNames as $varName) {
                if (isset($fees[trim($varName)])) {
                    $fee += $fees[trim($varName)];
                }
            }
        }
        return $fee;
    }

    public function getAdditionalVariantSetupFees () {
        if ($this->oxarticles__oxvarselect->value) {
            $excludedNames = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar('aExcludeSetupFees', null, 'module:agadditionalvariantpricescales');
            $varNames = array_map('trim', explode('|', $this->oxarticles__oxvarselect->value));
            if (count(array_intersect($excludedNames, $varNames))) {
                return 0;
            }
        }
        return $this->oxarticles__agsetupfees->value;
    }

    public function getAdditionalVariantAmountPrice ($amount) {
        $addPrice = 0;

        if ($this->oxarticles__oxvarselect->value) {
            $scales = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar('aAdditionalVariantPriceScales', null, 'module:agadditionalvariantpricescales');
            if (count($scales)) {
                $varNames = explode('|', $this->oxarticles__oxvarselect->value);
                foreach ($varNames as $varName) {
                    if (isset($scales[trim($varName)])) {
                        $varScales = $this->parseAdditionalVariantPriceScale($scales[trim($varName)]);
                        $addPrice += $this->getMatchingAdditionalVariantPriceForAmount($varScales, $amount);
                    }
                }
            }
        }

        return $addPrice;
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

    protected function getMatchingAdditionalVariantPriceForAmount ($varScales, $amount) {
        foreach($varScales as $sc) {
            if ($sc['from'] <= $amount && $sc['to'] >= $amount) {
                return $sc['price'];
            }
        }
    }

}