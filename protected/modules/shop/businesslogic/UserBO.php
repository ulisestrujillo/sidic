<?php

class UserBO
{

  public function userHasAddress()
  {
    $userId = Yii::app()->user->id;

    $address=Address::model()->find(array(
                                'condition'=>'userid='.$userId
                               ));

    return $address!=null;
  }

}