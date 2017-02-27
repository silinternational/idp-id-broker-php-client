<?php
namespace Sil\Idp\IdBroker\Client\tests;

use Sil\Idp\IdBroker\Client\AuthenticationClient;

class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    private function getAuthenticationClient($extra = [])
    {
        $testConfig = include __DIR__ . '/config-test.php';
        $config = array_replace_recursive($testConfig, $extra);
        
        return new AuthenticationClient($config);
    }
    
    public function testSet()
    {
        // Arrange:
        $userClient = $this->getAuthenticationClient();
        
        // Act:
        $userClient->authenticate();
        
        // Assert:
        $this->fail('Test not yet written.');
    }
}