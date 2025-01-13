<?php
namespace PH7\Learnphp;

class AllowCors{


private const Allow_Cors_Origin_Key = 'Access-Control-Allow-Origin';
private const Allow_Cors_Method_Key = 'Access-Control-Allow-Methods';

private const Allow_Core_Origin_Value = '*';
private const Allow_Core_Method_Value = 'GET, POST, PUT, DELETE, OPTIONS';

public function init(): void {
$this->set(self::Allow_Cors_Origin_Key,self::Allow_Core_Origin_Value);
$this->set(self::Allow_Cors_Method_Key,self::Allow_Core_Method_Value);
}

private function set(string $key, string $value): void {
header($key .":". $value);
}}