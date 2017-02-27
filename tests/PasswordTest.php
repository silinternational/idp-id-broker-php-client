<?php
namespace Sil\Idp\IdBroker\Client\tests;

use Sil\Idp\IdBroker\Client\PasswordClient;

class PasswordTest extends \PHPUnit_Framework_TestCase
{
    private function getPasswordClient($extra = [])
    {
        $testConfig = include __DIR__ . '/config-test.php';
        $config = array_replace_recursive($testConfig, $extra);
        
        return new PasswordClient($config);
    }
    
    public function testSet()
    {
        // Arrange:
        $userClient = $this->getPasswordClient();
        
        // Act:
        $userClient->set();
        
        // Assert:
        $this->fail('Test not yet written.');
    }
}
