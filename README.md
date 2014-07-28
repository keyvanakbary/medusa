# Medusa

[![Build Status](https://secure.travis-ci.org/keyvanakbary/medusa.svg?branch=master)](http://travis-ci.org/keyvanakbary/medusa)

*Immutable* and *persistent* data structures for PHP.

Life would be a lot simpler if we had *immutable* data structures. Code would be easier to understand, easy to test and free of side-effects. Being *immutable* is not all, these data structures must be efficient. By making them *persistent*, collections reuse internal structure to minimize the number of operations needed to represent altered versions of an instance of a collection.

## Setup and Configuration
Add the following to your `composer.json` file
```json
{
    "require": {
        "keyvanakbary/medusa": "*"
    }
}
```

Update the vendor libraries

    curl -s http://getcomposer.org/installer | php
    php composer.phar install

## Usage

### Stack

```php
<?php

include 'vendor/autoload.php';

$s = Medusa\Stack\Stack::createEmpty();

$s1 = $s->push(1);
$s2 = $s1->pop();
echo $s1->peek();//1
echo $s2->peek();//StackIsEmpty exception
```

#### Complexity
operation | big-O
----------|------
push      | O(1)
peek      | O(1)
pop       | O(1)
isEmpty   | O(1)
reverse   | O(N)

### Queue

```php
<?php
include 'vendor/autoload.php';

$q = Medusa\Queue\Queue::createEmpty();

$q1 = $q->enqueue(1);
$q2 = $q1->dequeue();
echo $q1->peek();//1
echo $q2->peek();//QueueIsEmpty exception
```

#### Complexity
operation | big-O
----------|------
isEmpty   | O(1)
peek      | O(1)
enqueue   | O(1)
dequeue   | O(1) in average, O(N) in some cases
