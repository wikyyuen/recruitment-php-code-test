<?php

namespace App\Service;

class ProductHandler
{
    /**
     * 获取商品总金额
     * @param array $productList 商品列表
     * @return int  商品总金额
     */
    public function getTotalPrice($productList = [])
    {
        $totalPrice = 0;
        foreach ($productList as $product) {
            $price = $product['price'] ?: 0;
            $totalPrice += $price;
        }
        return $totalPrice;
    }

    /**
 * 把商品以金额排序（由大至小），並筛选商品种类是 dessert 的商品
 * @param array $productList    商品列表
 * @return array    商品总金额
 */
    public function getProductDessertBySort($productList = [])
    {
        $productList = array_filter($productList,function($item){
            return $item['type'] == 'Dessert';
        });
        array_multisort(array_column($productList, 'price'), SORT_DESC, $productList);
        return $productList;
    }

    /**
     * 把商品以金额排序（由大至小），並筛选商品种类是 dessert 的商品
     * @param array $productList    商品列表
     * @return array    商品总金额
     */
    public function convertDateFormat($productList = [])
    {
        date_default_timezone_set('Asia/Shanghai');
        return array_map(function($item){
            $item['create_at'] = strtotime($item['create_at']);
            return $item;
        },$productList);
    }


}