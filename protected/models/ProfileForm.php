<?php

/**
 * ProfileForm class.
 * ProfileForm is the data structure for keeping
 * contact form data. It is used by the 'profile' action of 'UserController'.
 */
class ProfileForm extends User
{
  public $name;
  public $lastname;
  public $address;
  public $country;
  public $state;
  public $city;
  public $rfc;
  public $phone_number;
  public $email;
  public $fiscal_data;

  /**
   * Declares the validation rules.
   */
  public function rules()
  {
    return array(
      // name, email, subject and body are required
      array('name, address, country, state, city, rfc, phone_number, email', 'required'),
      array('name, country, state, city', 'length', 'max'=>50),
      array('email', 'length', 'max'=>128),
      array('rfc', 'length', 'max'=>28),
      // email has to be a valid email address
      array('email', 'email'),
    );
  }

  /**
   * Declares customized attribute labels.
   * If not declared here, an attribute would have a label that is
   * the same as its name with the first letter in upper case.
   */
  public function attributeLabels()
  {
    return array(
      'id'=>'Id',
      'name'=>'Nombre',
      'lastname'=>'Apellidos',
      'address'=>'Dirección',
      'country'=>'País',
      'state'=>'Estado',
      'city'=>'Ciudad',
      'rfc'=>'RFC',
      'phone_number'=>'Teléfono',
      'fiscal_data'=>'Datos Fiscales',
      'email'=>'Correo',
    );
  }

  protected function beforeValidate()
  {
    if ($this->fiscal_data == 'on'){
      $this->fiscal_data = 1;
    }else{
      $this->fiscal_data = 0;
    }

    return true;
  }   

}