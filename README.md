Pizza Time ![PizzaTime!](http://i.imgur.com/OG7pgRg.gif)
========================================================

Programming delicious pizza, straight to your door.

This is a pretty basic library to interface with multiple Pizza APIs.

## Supported Chains

* Dominos

## Usage

    // Create an address and customer for ordering.
    $address = new \PizzaTime\Address('1 Sewers St', 'Big Apple', 'The State', '55555-555');
    $customer = new \PizzaTime\Customer('First', 'Last', '555-555-5555', $address);

    $chain = new \PizzaTime\Providers\Dominos\Chain();

    // Find what specific store we can order delivery from
    $store = $chain->getDeliveryStore($address);

    // Pull the menu from this store.
    $menuItems = $store->getMenuItems();

    $order = new \PizzaTime\Order($customer);

    // Set the order to be cash
    $order->setAddPaymentSource(new \PizzaTime\Payment\Cash());

    $pricedOrder = $store->price($order);

    if ($pricedOrder->getTotal() > 20) {
        die('too pricey for me!');
    }

    // Add a random item from the menu.
    $order->addMenuItem(array_rand($menuItems));

    // Here comes the whatever.
    $store->order($order);

## Why

Because I couldn't find anything quite like it.

![Cowabunga!](http://i.imgur.com/kEuhjYY.gif)
