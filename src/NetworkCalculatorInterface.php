<?php

namespace Rpodwika\NetworkCalculator;

/**
 * Interface NetworkCalculatorInterface
 * @package Rpodwika\NetworkCalculator
 */
interface NetworkCalculatorInterface
{
    /**
     * Calculates the first and last ip in the network and returns it as array
     * [ 'firstIp' => 'x.x.x.x', 'lastIp' => 'x.x.x.x' ]
     * @return array
     */
    public function calculateIpRange();

    /**
     * Calculates broadcast address
     *
     * @return string
     */
    public function calculateBroadcastIp();

    /**
     * Calculates network address
     *
     * @return string
     */
    public function calculateNetworkAddress();

    /**
     * Calculates CIDR(Classless Inter-Domain Routing) prefix
     *
     * @see https://en.wikipedia.org/wiki/Hamming_weight
     *
     * @return int
     */
    public function calculateNetworkMaskLength();
}