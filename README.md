# PHPTreeClass

This class it is used to manage the tree structure, stored as array, in PHP language.

## Usage

Because it was designed as a stateless class, you don't need to instantiate it.

### Include the class in your project

```php
require_once 'classes/PHPTreeClass.php';
```

### Create a nested tree from a plain array

Considering the following plain array

```php
$plain_list = array(
    [
        "id"        => 1,
        "parent_id" => 0,
        "label"     => "Level 1"
    ],
    [
        "id"        => 2,
        "parent_id" => 0,
        "label"     => "Level 2"
    ],
    [
        "id"        => 3,
        "parent_id" => 1,
        "label"     => "Level 1-1"
    ],
    [
        "id"        => 4,
        "parent_id" => 1,
        "label"     => "Level 1-2"
    ],
    [
        "id"        => 5,
        "parent_id" => 1,
        "label"     => "Level 1-3"
    ],
    [
        "id"        => 6,
        "parent_id" => 2,
        "label"     => "Level 2-1"
    ],
    [
        "id"        => 7,
        "parent_id" => 2,
        "label"     => "Level 2-2"
    ],
    [
        "id"        => 8,
        "parent_id" => 5,
        "label"     => "Level 1-3-1"
    ],
    [
        "id"        => 9,
        "parent_id" => 8,
        "label"     => "Level 1-3-1-1"
    ]
);
```

To create a nested array, use the method `createTree`

```php
$nested = PHPTreeClass::createTree( $plain_list );
```

The resulting array is

```php
array(
    [
        "id"        => 1,
        "parent_id" => 0,
        "label"     => "Level 1",
        "children"  => array(
            [
                "id"        => 3,
                "parent_id" => 1,
                "label"     => "Level 1-1"
            ],
            [
                "id"        => 4,
                "parent_id" => 1,
                "label"     => "Level 1-2"
            ],
            [
                "id"        => 5,
                "parent_id" => 1,
                "label"     => "Level 1-3",
                "children"  => array(
                    [
                        "id"        => 8,
                        "parent_id" => 5,
                        "label"     => "Level 1-3-1",
                        "children" => array(
                            [
                                "id"        => 9,
                                "parent_id" => 8,
                                "label"     => "Level 1-3-1-1"
                            ]
                        )
                    ]
                )
            ],
        )
    ],
    [
        "id"        => 2,
        "parent_id" => 0,
        "label"     => "Level 2",
        "children"  => array(
            [
                "id"        => 6,
                "parent_id" => 2,
                "label"     => "Level 2-1"
            ],
            [
                "id"        => 7,
                "parent_id" => 2,
                "label"     => "Level 2-2"
            ]
        )
    ]
);
```

#### Create a nested tree from an intermediate item

You can get the nested array from an intermediate item

```php
$nested_from_5 = PHPTreeClass::createTree( $plain_list, 5 );
```

The resulting array is

```php
array(
    "id"        => 5,
    "parent_id" => 1,
    "label"     => "Level 1-3",
    "children"  => array(
        [
            "id"        => 8,
            "parent_id" => 5,
            "label"     => "Level 1-3-1",
            "children" => array(
                [
                    "id"        => 9,
                    "parent_id" => 8,
                    "label"     => "Level 1-3-1-1"
                ]
            )
        ]
    )
);
```

### Get the max depth of a tree

To get the max depth of a tree, use the method `getCategoriesDepth`

```php
$depth = PHPTreeClass::getCategoriesDepth( $nested ); // 4
$depth_from_5 = PHPTreeClass::getCategoriesDepth( $nested_from_5 ); // 3
```
