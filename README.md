Pizza Time ![PizzaTime!](http://i.imgur.com/OG7pgRg.gif)
========================================================

Programming delicious pizza, straight to your door.

This is a pretty basic library to interface with multiple Pizza APIs.

## Supported Chains

* Dominos

## Usage

    $address = new \PizzaTime\Address('1 Sewers St', 'Big Apple', 'The State', '55555-555');
    $customer = new \PizzaTime\Customer('First', 'Last', '555-555-5555', $address);

    $chain = new \PizzaTime\Providers\Dominos\Chain();

    $store = $chain->getDeliveryStore($customer);

    $menuItems = $store->getMenuItems();

    $order = new \PizzaTime\Order($customer);

    $order->addMenuItem(array_rand($menuItems));

    $store->order($order, \PizzaTime\Payment::CASH);
    # here comes the whatever


## Why

Because I couldn't find anything quite like it.

![Cowabunga!](http://i.imgur.com/kEuhjYY.gif)
