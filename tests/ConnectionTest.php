<?php
/**
 * SimplySign WebService Client
 */

namespace Webinv\SimplySign\Tests;

use PHPUnit\Framework\TestCase;
use Webinv\SimplySign\Connection;

/**
 * Class ConnectionTest
 * @package Webinv\SimplySign\Tests
 */
class ConnectionTest extends TestCase
{
    /**
     * @dataProvider configDomainDataProvider
     *
     * @param array $config
     * @param       $expected
     */
    public function testConstructorDomain(array $config, $expected)
    {
        $subject = new Connection($config);

        $this->assertEquals($expected, $subject->getDomain());
    }

    /**
     * @return array
     */
    public function configDomainDataProvider()
    {
        return [
            [
                ['domain' => 'https://cloudsign.webnotarius.pl'],
                'https://cloudsign.webnotarius.pl'
            ]
        ];
    }
}
