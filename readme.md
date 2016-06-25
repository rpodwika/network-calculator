#Network calculator in PHP

[![Build Status](https://travis-ci.org/rpodwika/network-calculator.svg)](https://travis-ci.org/rpodwika/network-calculator)
[![Coverage Status](https://coveralls.io/repos/github/rpodwika/network-calculator/badge.svg?branch=master)](https://coveralls.io/github/rpodwika/network-calculator?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/576e45c27bc6810042bf26aa/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/576e45c27bc6810042bf26aa)
[![Latest Stable Version](https://poser.pugx.org/rpodwika/network-calculator/v/stable)](https://packagist.org/packages/rpodwika/network-calculator) [![Total Downloads](https://poser.pugx.org/rpodwika/network-calculator/downloads)](https://packagist.org/packages/rpodwika/network-calculator) [![Latest Unstable Version](https://poser.pugx.org/rpodwika/network-calculator/v/unstable)](https://packagist.org/packages/rpodwika/network-calculator) [![License](https://poser.pugx.org/rpodwika/network-calculator/license)](https://packagist.org/packages/rpodwika/network-calculator)

During searching for good network calculator I couldn't find a good one written in PHP. That's why I've decided to write it using only bitmask operations and math. 

#What can I calculate?

* Network address
* Broadcast address
* CIDR prefix
* First and last IP in the network
* Number of possible hosts in the network

#How to use?

```
$networkCalculator = new NetworkCalculator("192.168.1.14, "255.255.255.0");

echo $networkCalculator->calculateNetworkAddress(); // 192.168.1.0
echo $networkCalculator->calculateBroadcastIp(); // 192.168.1.255
echo $networkCalculator->calculateNetworkMaskLength(); // 24

list($first, $last) = $networkCalculator->calculateIpRange();

echo $first; // 192.168.1.1
echo $last; // 192.168.1.254

echo $networkCalculator; // will print all the information in pretty way
```


#Found a bug?

Just make pull request or report issue