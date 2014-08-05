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

### Persistent Stack

```php
<?php

include 'vendor/autoload.php';

$s = Medusa\Stack\PersistentStack::createEmpty();

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

### Persistent Queue

```php
<?php
include 'vendor/autoload.php';

$q = Medusa\Queue\PersistentQueue::createEmpty();

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
dequeue   | O(1) in average, O(n) in some cases
count     | O(1)

### Persistent Binary Tree

```php
<?php
include 'vendor/autoload.php';

$t = Medusa\Tree\PersistentBinaryTree::createEmpty();

$t1 = $t->add(1, 'one');
$t2 = $t1->remove(1);
echo $t1->search(1)->value();//one
echo $t2->lookup(1);//Runtime exception
```

#### Complexity
operation | big-O
----------|------
isEmpty   | O(1)
value     | O(1)
key       | O(1)
search    | O(log<sub>2</sub>(n))
add       | O(1)
search    | O(log<sub>2</sub>(n))
contains  | O(log<sub>2</sub>(n))
height    | O(1)
lookup    | O(log<sub>2</sub>(n))
