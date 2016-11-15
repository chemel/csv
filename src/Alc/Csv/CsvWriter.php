<?php

namespace Alc\Csv;

/**
 * CsvWriter
*/
class CsvWriter extends Csv {

	/**
	 * __construct
	 */
	public function __construct( $filename = 'php://output', $delimiter = ';', $enclosure = '"', $escape = '\\', $hasHeader = true ) {

		parent::__construct($filename, $delimiter, $enclosure, $escape, $hasHeader);
	}

	/**
	 * write
	 */
	public function write( $fields ) {

		if ( $handle = $this->open('w') ) {

			if( $this->hasHeader() && $this->index == 0 ) {

				$this->fputcsv($handle, array_keys($fields));

				$this->index++;
			}

			$this->fputcsv($handle, $fields);

			$this->index++;
		}		
	}

	/**
	 * fputcsv
	 */
	protected function fputcsv( $handle, $fields ) {

		return fputcsv($handle, $fields, $this->delimiter, $this->enclosure, $this->escape);
	}
}