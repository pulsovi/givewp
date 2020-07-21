<?php

namespace Give\Helpers;

class ArrayDataSet {
	/**
	 * This function will return array with renamed keys.
	 *
	 * This function only support one dimensional array.
	 * You can pass a multi dimensional array but only zero level array keys will be renamed.
	 *
	 * @since 2.7.0
	 *
	 * @param array $renameTo Pass array of existing key name as key and new key name as value.
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function renameKeys( $array, $renameTo ) {
		// Rename key if property name exist for them.
		foreach ( $renameTo as $oldKey => $newKey ) {
			if ( array_key_exists( $oldKey, $array ) ) {
				$array[ $newKey ] = $array[ $oldKey ];
				unset( $array[ $oldKey ] );
			}
		}

		return $array;
	}

	/**
	 * Return whether or not array contains required keys.
	 *
	 * This function only support one dimensional array.
	 *
	 * @since 2.7.0
	 *
	 * @param array $array
	 * @param array $requiredKeys Array of required keys.
	 *
	 * @return bool
	 */
	public static function hasRequiredKeys( $array, $requiredKeys ) {
		return (bool) array_intersect_key( $array, array_flip( $requiredKeys ) );
	}

	/**
	 *  Return array with grouped under specific key.
	 *
	 * @param array  $array
	 * @param array  $itemsToMove
	 * @param string $arrayKey
	 *
	 * @return mixed
	 */
	public static function moveArrayItemsUnderArrayKey( $array, $itemsToMove, $arrayKey ) {
		foreach ( $itemsToMove as $key ) {
			if ( array_key_exists( $key, $array ) ) {
				$array[ $arrayKey ][ $key ] = $array[ $key ];
				unset( $array[ $key ] );
			}
		}

		return $array;
	}


	/**
	 * Rename array keys.
	 * Convert word first letter to uppercase which comes after dash in array key name.
	 * For example: access_token will be renamed to accessToken
	 *
	 * @since 2.8.0
	 *
	 * @param $array
	 *
	 * @return mixed
	 */
	public static function camelCaseKeys( $array ) {
		$newArray = [];

		foreach ( $array as $key => $value ) {
			$studlyKey = ucwords( str_replace( [ '-', '_' ], ' ', $key ) );
			$studlyKey = lcfirst( str_replace( ' ', '', $studlyKey ) );

			$newArray[ $studlyKey ] = $value;
		}

		return $newArray;
	}
}
