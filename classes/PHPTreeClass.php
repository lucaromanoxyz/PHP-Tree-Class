<?php
/**
 * This class it is used to manage the tree structure, stored as array, in PHP language.
 *
 * @author Luca Romano <romano.luca@hotmail.it>
 * @package PHPTreeClass
 * @version 1.0.1
 * @access public
 * @license Creative Commons Zero v1.0 Universal
 * @see https://github.com/lucaromanoxyz/php-tree-class
 */

class PHPTreeClass {
	/**
	 * Create a tree structure from plain array of items.
	 *
	 * @param array $plain_items tree array of items_list
	 * @param int|null $root_id the id of the first element to return
	 * @param string $id_key the key of the index that represent the id
	 * @param string $parent_key the key of the index that represent the parent_id
	 * @param string $children_key the key of the index that represent the list of children
	 *
	 * @return array[][]
	 */
	public static function createTree( array $plain_items, int $root_id = null, string $id_key = "id", string $parent_key = "parent_id", string $children_key = "children" ): array {
		$nested_items    = [];
		$pendings = [];
		foreach ( $plain_items as $plain_item ) {
			// converti $plain_item to array in order to use array's syntax
			$item = (array) $plain_item;
			// add children index
			$item[ $children_key ] = [];
			// add row to items
			$item_id           = $item[ $id_key ];
			$nested_items[ $item_id ] = $item;

			$parent_id = $item[ $parent_key ];
			// current element has a parent
			if ( $parent_id != null ) {
				// parent id not still retrieved from resultset
				if ( ! isset( $nested_items[ $parent_id ] ) ) {

					if ( ! isset( $pendings[ $parent_id ] ) ) {
						$pendings[ $parent_id ] = [];
					}

					$pendings[ $parent_id ][] = $item_id;
				} else {
					// add item to his parent
					$nested_items[ $parent_id ][ $children_key ][] = &$nested_items[ $item_id ];
				}
			}

			// check if current item is in pendings
			if ( isset( $pendings[ $item_id ] ) ) {
				foreach ( $pendings[ $item_id ] as $child_id ) {
					$nested_items[ $item_id ][ $children_key ][] = &$nested_items[ $child_id ];
				}
				unset( $pendings[ $item_id ] );
			}
		}
		// clone only first level items
		$filtered = [];
		foreach ( $nested_items as &$item ) {
			if ( ( $root_id !== null && $item[ $id_key ] == $root_id ) || ( $root_id === null && $item[ $parent_key ] == null ) ) {
				$filtered[] = &$item;
			}
		}

		return $filtered;
	}

	/**
	 * Get the max depth of items' list.
	 *
	 * @param array $items_list tree array of items_list
	 * @param string $children_key the key of the index that represent the list of children
	 * @param int $initial_depth initial depth
	 *
	 * @return int
	 */
	public static function getCategoriesDepth( array $items_list, string $children_key = "children", int $initial_depth = 1 ): int {
		// return 0 if $items_list is an empty array
		if(count($items_list) === 0) {
			return 0;
		}

		// set the depth of the current level
		$level_depth = 1;
		// browse all items_list
		foreach ( $items_list as $category ) {
			// if category has children, browse all children
			if ( isset( $category[ $children_key ] ) && count( $category[ $children_key ] ) ) {
				$level_depth = max( $level_depth, PHPTreeClass::getCategoriesDepth( $category[ $children_key ], $children_key, $initial_depth + 1 ) );
			}
		}
		// set new depth
		$initial_depth = max( $initial_depth, $level_depth );

		return $initial_depth;
	}
}