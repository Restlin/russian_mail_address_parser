<?php

namespace app\helpers;

/**
 * Хелпер по работе с адресами
 *
 * @author restlin
 */
class AddressHelper {
    /**
     * Выделение адреса в строке сырых данных по словарику адресных частей
     * @param string $content сырой контент
     * @param array $addressParts адресные части
     * @return string
     */
    public static function findAddress(string $content, array $addressParts): string {
        foreach([";", "\t", ","] as $delimiter) {
            $columns = explode($delimiter, mb_strtolower($content, 'UTF-8'));
            $count = count($columns);
            if($count > 1 && $count < 100) {
                break;
            }
        }
        $result = [];
        foreach($columns as $column) {
            $elements = preg_split('/\s/ui', $column);
            foreach($elements as $element) {
                if(in_array($element, $addressParts)) {
                    $result[] = $column;
                    break;
                }
            }
        }
        return implode(' ', $result);
    }
}
