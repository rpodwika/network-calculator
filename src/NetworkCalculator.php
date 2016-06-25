<?php

namespace Rpodwika\NetworkCalculator;

/**
 * Class NetworkCalculator
 * @package Rpodwika\NetworkCalculator
 */
class NetworkCalculator implements NetworkCalculatorInterface
{
    const M1  = 0x5555555555555555; //binary: 0101...
    const M2  = 0x3333333333333333; //binary: 00110011..
    const M3  = 0x0f0f0f0f0f0f0f0f; //binary:  4 zeros,  4 ones ...
    const H01 = 0x0101010101010101; //the sum of 256 to the power of 0,1,2,3...

    private $ip;
    private $subnetMask;

    /**
     * @param $ip
     * @param $subnetMask
     */
    public function __construct($ip, $subnetMask)
    {
        $this->setIp($ip);
        $this->setSubnetMask($subnetMask);
    }

    /**
     * @inheritdoc
     */
    public function calculateIpRange()
    {
        $firstHost = ((~$this->subnetMask) & $this->ip);
        $firstIp = ($this->ip ^ $firstHost) + 1;

        $broadcastIpInverted = ~$this->subnetMask;
        $lastIp = ($this->ip | $broadcastIpInverted) - 1;

        return [
            'firstIp' => long2ip($firstIp),
            'lastIp'  => long2ip($lastIp),
        ];
    }

    /**
     * @inheritdoc
     */
    public function calculateBroadcastIp()
    {
        return long2ip($this->ip | (~ $this->subnetMask));
    }

    /**
     * @inheritdoc
     */
    public function calculateNetworkAddress()
    {
        return long2ip($this->ip & $this->subnetMask);
    }

    /**
     * @inheritdoc
     */
    public function calculateNetworkMaskLength()
    {
        $this->subnetMask -= ($this->subnetMask >> 1) & self::M1;             //put count of each 2 bits into those 2 bits
        $this->subnetMask = ($this->subnetMask & self::M2) + (($this->subnetMask >> 2) & self::M2); //put count of each 4 bits into those 4 bits
        $this->subnetMask = ($this->subnetMask + ($this->subnetMask >> 4)) & self::M3;        //put count of each 8 bits into those 8 bits

        return ($this->subnetMask * self::H01)>>56;  //returns left 8 bits of x + (x<<8) + (x<<16) + (x<<24) + ...
    }

    /**
     * Calculates the number of possible hosts in network
     *
     * @return int
     */
    public function calculateNumberOfPossibleHosts()
    {
        return 1 << (32 - $this->calculateNetworkMaskLength());
    }

    /**
     * Converts object to a string
     *
     * @return string
     */
    public function __toString()
    {
        $networkRange = $this->calculateIpRange();
        $networkAddress = $this->calculateNetworkAddress();
        $broadcastAddress = $this->calculateBroadcastIp();
        $data = PHP_EOL;
        $data .= sprintf(
            "IP: %s, Subnet mask: %s, CIDR: %s/%s" . PHP_EOL,
            long2ip($this->ip), long2ip($this->subnetMask),
            $networkAddress,
            $this->calculateNetworkMaskLength()
        );
        $data .= sprintf(
            "Broadcast IP: %s, Network IP: %s"  . PHP_EOL,
            $broadcastAddress,
            $networkAddress
        );
        $data .= sprintf(
            "First IP: %s, Last IP: %s "  . PHP_EOL,
            $networkRange['firstIp'],  $networkRange['lastIp']
        );

        return $data;
    }


    /**
     * @param $ip
     */
    private function setIp($ip)
    {
        if (false === filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new \InvalidArgumentException(sprintf("%s does not look like valid IPv4 address"));
        }

        $this->ip = ip2long($ip);
    }

    /**
     * @param $mask
     */
    private function setSubnetMask($mask)
    {
        if (false === filter_var($mask, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new \InvalidArgumentException(sprintf("%s does not look like valid IPv4 subnet mask"));
        }

        $this->subnetMask = ip2long($mask);
    }
}