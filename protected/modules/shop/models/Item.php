<?php

/**
 * This is the model class for table "cart_item".
 *
 * The followings are the available columns in table 'cart_item':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $sku
 * @property integer $qty
 * @property string $price
 * @property string $discount
 * @property string $total
 * @property string $size
 * @property string $color
 *
 * The followings are the available model relations:
 * @property CartOrderdetail[] $cartOrderdetails
 */
class Item
{
 public $id;
 public $name;
 public $image;
 public $description;
 public $sku;
 public $qty;
 public $price    = 0.00;
 public $discount = 0.00;
 public $total    = 0.00;
 public $size;
 public $color;


}
