<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Sylius Imoje Plugin</h1>

<p align="center">Sylius plugin for ING online payment.</p>

## Overview

The plugin integrates <a href="https://www.imoje.pl/">Imoje</a> payments with Sylius based applications. After the installation you should be able to create a payment method for Imoje gateway and enable its payments in your web store. Plugin also supports online refunds. 

## Installation

1. Run `composer require softify/imoje-plugin`.

2. Add plugin dependencies to your config/bundles.php file:

 ```bash
    return [
        Softify\SyliusImojePlugin\SoftifySyliusImojePlugin::class => ['all' => true],
    ]
```
3. Add plugin routing to main configuration

```bash
imoje_shop:
    resource: "@SoftifySyliusImojePlugin/Resources/config/shop_routing.yml"
```

## Configuration

Plugin has only one configuration with default values:

```bash
softify_sylius_imoje:
    ips:
        - 5.196.116.32/28
        - 51.195.95.0/28
        - 54.37.185.64/28
        - 54.37.185.80/28
        - 147.135.151.16/28
```
