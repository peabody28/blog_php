<?php
require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../classes/User.php";
require_once __DIR__."/../classes/UserTools.php";

use PHPUnit\Framework\TestCase;

class TestSignUp extends TestCase
{

    /**
     * @dataProvider providerData
     */

    public function test_signup($name, $password, $resp)
    {
        $user = new User();
        $user->name = $name;
        $user->password = $password;

        $tools = new UserTools();
        $this->assertEquals($resp, $tools->signUp($user));

    }

    public function providerData()
    {
        return array(
            array("max", 1234, ["status"=>"OK"]),
            array("max", 1253, ["status"=>"ERROR", "error"=>"Пользователь с таким именем существует"]),
            array("", 1234, ["status"=>"ERROR", "error"=>"Имя или пароль не введены"]),
            array("lena", null, ["status"=>"ERROR", "error"=>"Имя или пароль не введены"]),
            array("лена", 1234, ["status"=>"ERROR",  "error" => "Имя содержит запрещенные символы"])
        );

    }
}
