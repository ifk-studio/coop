<?php

namespace Coop\Utils;

class Inflector {
	/**
	 * Returns the given lower_case_and_underscored_word as a CamelCased word.
	 *
	 * @param string $lowerCaseAndUnderscoredWord Word to camelize
	 *
	 * @return string Camelized word. LikeThis.
	 *
	 */
	public static function camelize($lowerCaseAndUnderscoredWord) {
		return str_replace(' ', '', Inflector::humanize($lowerCaseAndUnderscoredWord));
	}


	/**
	 * Returns the given camelCasedWord as an underscored_word.
	 *
	 * @param string $camelCasedWord Camel-cased word to be "underscorized"
	 *
	 * @return string Underscore-syntaxed version of the $camelCasedWord
	 *
	 */
	public static function underscore($camelCasedWord) {
		return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $camelCasedWord));
	}


	/**
	 * Returns the given underscored_word_group as a Human Readable Word Group.
	 * (Underscores are replaced by spaces and capitalized following words.)
	 *
	 * @param string $lowerCaseAndUnderscoredWord String to be made more readable
	 *
	 * @return string Human-readable string
	 *
	 */
	public static function humanize($lowerCaseAndUnderscoredWord) {
		return ucwords(str_replace('_', ' ', $lowerCaseAndUnderscoredWord));
	}
}