EdFi-PHP-API Wrapper
==============

A PHP wrapper for the EdFi API as implemented by the Wisonsin Department of Public Instruction (DPI).

Please see these links for additional resources on Wisconsin's requirements:

- [DPI Ed-Fi Integration](http://dpi.wi.gov/wisedata/ed-fi-integration)
- [JSON Resource Mapping for Wisconsin DPI](http://dpi.wi.gov/sites/default/files/imce/wisedata/pdf/WISEdata_Ed-Fi_Integration.pdf)
- [Swagger Interactive API Documentation](https://uawisedataapi.dpi.wi.gov/EdFiSwagger/)
- [Wonderful HTTP Requester Add-on for Firefox](https://github.com/tommut/HttpRequester)

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