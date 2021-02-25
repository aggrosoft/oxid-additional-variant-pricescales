<?php

namespace  Aggrosoft\AdditionalVariantPriceScales\Application\Model;

class Article extends Article_parent {

    public function getBasePrice($amount = 1)
    {
        $price = parent::getBasePrice($amount);
        $price += $this->getAdditionalVariantAmountPrice($amount);
        return $price;
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