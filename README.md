# Medusa

[![Build Status](https://secure.travis-ci.org/keyvanakbary/medusa.svg?branch=master)](http://travis-ci.org/keyvanakbary/medusa)

*Immutable* and *persistent* data structures for PHP.

Life would be a lot simpler if we had *immutable* data structures. Code would be easier to understand, easy to test and free of side-effects. Being *immutable* is not all, these data structures must be efficient. By making them *persistent*, collections reuse internal structure to minimize the number of operations needed to represent altered versions of an instance of a collection.

## Setup and Configuration

Use composer:

    composer.phar require 'keyvanakbary/medusa:*'

**Note**: Composer isn't installed yet? THen run the following command:

    curl -s http://getcomposer.org/installer | php

## Usage

### Stack

```php
<?php

include 'vendor/autoload.php';

$s = Medusa\Stack::createEmpty();

$s1 = $s->push(1);
$s2 = $s1->pop();
echo $s1->peek();//1
echo $s2->peek();//Runtime exception
```

#### Complexity
operation | big-O
----------|------
push      | O(1)
peek      | O(1)
pop       | O(1)
isEmpty   | O(1)
reverse   | O(N)
count     | O(1)

### Queue

```php
<?php
include 'vendor/autoload.php';

$q = Medusa\Queue::createEmpty();

$q1 = $q->enqueue(1);
$q2 = $q1->dequeue();
echo $q1->peek();//1
echo $q2->peek();//Runtime exception
```

#### Complexity
operation | big-O
----------|------
isEmpty   | O(1)
peek      | O(1)
enqueue   | O(1)
dequeue   | O(1) in average, O(N) in some cases
count     | O(1)
