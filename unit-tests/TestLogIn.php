<?php
require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../classes/User.php";
require_once __DIR__."/../classes/UserTools.php";

use PHPUnit\Framework\TestCase;

class TestLogIn extends TestCase
{

    /**
     * @dataProvider providerData
     */

    public function test_log_in($name, $password, $resp)
    {
        $user = new User();
        $user->name = $name;
        $user->password = $password;

        $tools = new UserTools();
        $this->assertEquals($resp, $tools->logIn($user));

    }

    public function providerData()
    {
        return array(
            array("max", 1234, ["status"=>"OK"]),
            array("max", 1253, ["status"=>"ERROR","error"=>"Неверный пароль"]),
            array("", 1234, ["status"=>"ERROR", "error"=>"Имя или пароль не введены"]),
            array("lena", null, ["status"=>"ERROR", "error"=>"Имя или пароль не введены"]),
            array("lena", 1234, ["status"=>"ERROR", "error"=>"Пользователя с таким именем нет"]),
            array("лена", 1234, ["status"=>"ERROR",  "error" => "Имя содержит запрещенные символы"])
        );

    }
}
