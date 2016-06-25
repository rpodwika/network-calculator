<?php

namespace Rpodwika\NetworkCalculator\Tests;

use Rpodwika\NetworkCalculator\NetworkCalculator;

/**
 * Class NetworkCalculatorTest
 * @package Rpodwika\NetworkCalculator\Tests
 */
class NetworkCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function broadcastDataProvider()
    {
        return [
            ['192.168.1.25', '255.255.255.0', '192.168.1.255'],
            ['10.0.2.34', '255.0.0.0', '10.255.255.255'],
        ];
    }

    /**
     * @return array
     */
    public function networkDataProvider()
    {
        return [
            ['192.168.1.25', '255.255.255.0', '192.168.1.0'],
            ['10.0.4.15', '255.0.0.0', '10.0.0.0'],
        ];
    }

    /**
     * @return array
     */
    public function ipRangeDataProvider()
    {
        return [
            ['192.168.1.25', '255.255.255.0', ['192.168.1.1', '192.168.1.254']],
            ['10.3.5.34', '255.0.0.0', ['10.0.0.1', '10.255.255.254']],
        ];
    }

    /**
     * @param $ip
     * @param $mask
     * @param $expected
     *
     * @dataProvider broadcastDataProvider
     */
    public function testCalculateBroadcastAddress($ip, $mask, $expected)
    {
        $networkCalculator = new NetworkCalculator($ip ,$mask);

        $this->assertSame($expected, $networkCalculator->calculateBroadcastIp());
    }

    /**
     * @param $ip
     * @param $mask
     * @param $expected
     *
     * @dataProvider networkDataProvider
     */
    public function testCalculateNetworkAddress($ip, $mask, $expected)
    {
        $networkCalculator = new NetworkCalculator($ip ,$mask);

        $this->assertSame($expected, $networkCalculator->calculateNetworkAddress());
    }

    public function testCalculateNetworkMaskLength()
    {
        $networkCalculator = new NetworkCalculator("192.168.0.1", "255.255.255.0");

        $this->assertSame(24, $networkCalculator->calculateNetworkMaskLength());
    }

    /**
     * @param $ip
     * @param $mask
     * @param $expectedRange
     *
     * @dataProvider ipRangeDataProvider
     */
    public function testCalculateIpRange($ip, $mask, $expectedRange)
    {
        $networkCalculator = new NetworkCalculator($ip, $mask);

        $calculatedRange = $networkCalculator->calculateIpRange();

        $this->assertEquals($expectedRange[0], $calculatedRange['firstIp']);
        $this->assertEquals($expectedRange[1], $calculatedRange['lastIp']);
    }


    public function testToString()
    {
        $networkCalculator = new NetworkCalculator("192.168.1.1", "255.255.255.0");

        echo $networkCalculator;

        $this->expectOutputRegex('#IP: \d{3}.\d{3}.\d.\d#');
        $this->expectOutputRegex('#Subnet mask: \d{3}.\d{3}.\d{3}.\d#');
        $this->expectOutputRegex('#Broadcast IP: 192.168.1.255#');
        $this->expectOutputRegex('#Network IP: 192.168.1.0#');
        $this->expectOutputRegex('#First IP: 192.168.1.1, Last IP: 192.168.1.254#');
    }

}
