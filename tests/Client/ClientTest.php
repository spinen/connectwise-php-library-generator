<?php

namespace Tests\Spinen\ConnectWise\Client;

use InvalidArgumentException;
use Spinen\ConnectWise\Client\Client;
use Tests\Spinen\ConnectWise\BaseTest;

/**
 * Class ClientTest
 *
 * @package Tests\Spinen\ConnectWise\Client
 * @group   client
 */
class ClientTest extends BaseTest
{

    private function buildConfig($options = [])
    {
        return array_replace_recursive([
            'connectwise' => [
                'company'  => env_value('COMPANY', 'Company'),
                'host'     => env_value('CONNECTWISE_HOST', 'http://some.host'),
                'password' => env_value('PASSWORD', 'Password'),
                'username' => env_value('USERNAME', 'Username'),
            ],
        ], $options);
    }

    /**
     * @test
     */
    public function it_can_be_constructed_with_good_values()
    {
        $client = new Client($this->buildConfig());

        $this->assertInstanceOf('Spinen\\ConnectWise\\Client\\Client', $client);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_raises_exception_when_constructed_without_a_company()
    {
        $config = $this->buildConfig();

        unset($config['connectwise']['company']);

        new Client($config);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_raises_exception_when_constructed_without_a_host()
    {
        $config = $this->buildConfig();

        unset($config['connectwise']['host']);

        new Client($config);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_raises_exception_when_constructed_with_a_bad_host()
    {
        new Client($this->buildConfig(['connectwise' => ['host' => 'some.host']]));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_raises_exception_when_constructed_without_a_password()
    {
        $config = $this->buildConfig();

        unset($config['connectwise']['password']);

        new Client($config);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_raises_exception_when_constructed_without_a_username()
    {
        $config = $this->buildConfig();

        unset($config['connectwise']['username']);

        new Client($config);
    }

    /**
     * @test
     */
    public function it_returns_the_expected_results()
    {
        $client = (new Client($this->buildConfig()))->setApiNamespace('Tests\\Spinen\\ConnectWise\\Client\\Stubs');

        $this->assertInstanceOf('Tests\\Spinen\\ConnectWise\\Client\\Stubs\\FunctionCallResponse',
            $client->execute('SomeApi', 'FunctionCall', ['key' => 'value']));
    }

    /**
     * @test
     */
    public function it_returns_the_default_api_namespace()
    {
        $client = new Client($this->buildConfig());

        $this->assertEquals('Spinen\\ConnectWise\\Library\\Api\\Generated', $client->getApiNamespace());
    }

    /**
     * @test
     */
    public function it_returns_the_api_namespace_with_a_class_prepended_if_passed_in()
    {
        $client = new Client($this->buildConfig());

        $this->assertEquals('Spinen\\ConnectWise\\Library\\Api\\Generated\\Class', $client->getApiNamespace('Class'));
    }

    /**
     * @test
     */
    public function it_returns_a_static_instance_of_itself()
    {
        $client = new Client($this->buildConfig());

        $this->assertInstanceOf('Spinen\\ConnectWise\\Client\\Client', Client::getClient());
        $this->assertEquals($client, Client::getClient());
    }

    /**
     * @test
     */
    public function it_returns_the_host_name()
    {
        $expected = "http://someknown.host";

        $client = new Client($this->buildConfig(['connectwise' => ['host' => $expected]]));

        $this->assertEquals($expected, $client->getHost());
    }

    /**
     * @test
     */
    public function it_returns_the_host_name_with_passed_in_uri()
    {

        $expected = "http://someknown.host";

        $client = new Client($this->buildConfig(['connectwise' => ['host' => $expected]]));

        $this->assertEquals($expected, $client->getHost('/'));

        $expected .= '/some.uri';

        $this->assertEquals($expected, $client->getHost('some.uri'));

        $this->assertEquals($expected, $client->getHost('/some.uri'));
    }

    /**
     * @test
     */
    public function it_returns_an_empty_array_when_there_are_no_soap_options()
    {
        $config = $this->buildConfig();

        // Make 100% sure that we don't have any set soap options
        if (array_key_exists('soap_options', $config)) {
            unset($config['soap_options']);
        }

        $client = new Client($config);

        $this->assertEquals([], $client->getSoapOptions());
    }

    /**
     * @test
     */
    public function it_returns_the_soap_options()
    {
        $expected = [
            'key 1' => 'value',
        ];

        $client = new Client($this->buildConfig(['soap_options' => $expected]));

        $this->assertEquals($expected, $client->getSoapOptions());
    }

}
