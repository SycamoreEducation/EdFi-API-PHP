EdFi-PHP-API Wrapper
==============

A PHP wrapper for the EdFi API


This is still under heavy development and is not recommended for production use. Many methods are missing as this is just scaffolding for now.


Usage
======

Instantiate the client
```php
$client = new \EdFi\Client($Client_id, $Client_secret);
```

Get students
```php
$member_obj = new \EdFi\Model\Students($client);
$member_obj->setId('userid');
$orgs = $member_obj->getOrganizations();
```

Get a board
```php
$board = $client->getBoard($board_id);
```

Another way to get a board (or any object)
```php
$board = new \Trello\Model\Board($client);
$board->setId($board_id);
$board = $board->get();
```

Get all cards for a board
```php
$cards = $board->getCards();
```

Get a specific card for this board
```php
$card = $board->getCard($card_id);
```

Update the card
```php
$card->name = 'some new name';
$card->save();
```

Create a new card (or any object)
```php
$card = new \Trello\Model\Card($client);
$card->name = 'some card name';
$card->desc = 'some card desc';
$card->idList = $list_id;
$card->save();
```