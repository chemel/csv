<?php

namespace Alc\Csv;

/**
 * Csv
*/
abstract class Csv {

	protected $filename;
	protected $handle;
	protected $index = 0;

	protected $hasHeader;
	protected $header = null;

	protected $delimiter;
	protected $enclosure;
	protected $escape;

	/**
	 * __construct
	 */
	public function __construct( $filename, $delimiter = ';', $enclosure = '"', $escape = '\\', $hasHeader = true ) {

		if( !function_exists('fgetcsv') )
			throw new \Exception('CsvReader require fgetcsv extension', 1);

		// if( !file_exists($filename) )
		// 	throw new \Exception('File doesnt exist', 1);

		$this->filename = $filename;

		$this->setDelimiter($delimiter);
		$this->setEnclosure($enclosure);
		$this->setEscape($escape);
		$this->setHasHeader($hasHeader);
	}

	/**
	 * setDelimiter
	 */
    public function setDelimiter( $delimiter ) {

    	$this->delimiter = $delimiter;
    }

	/**
	 * setEnclosure
	 */
    public function setEnclosure( $enclosure ) {

    	$this->enclosure = $enclosure;
    }

	/**
	 * setEscape
	 */
    public function setEscape( $escape ) {

    	$this->escape = $escape;
    }

	/**
	 * setHasHeader
	 */
	public function setHasHeader( $hasHeader ) {

		return $this->hasHeader = $hasHeader;
	}

	/**
	 * hasHeader
	 */
	protected function hasHeader() {

		return $this->hasHeader;
	}

	/**
	 * setHeader
	 */
    protected function setHeader( $header ) {

    	$this->header = $header;
    }

	/**
	 * getHeader
	 */
	protected function getHeader() {

		return $this->header;
	}

	/**
	 * open
	 */
	protected function open( $mode = 'rw' ) {

		if( $this->handle == null) {

			if (($this->handle = fopen($this->filename, $mode)) == false) {

				$this->handle = null;

				throw new \Exception('Unable to open file', 1);
			}
		}

		return $this->handle;		
	}

	/**
	 * close
	 */
	public function close() {

		$this->index = 0;

		if( $this->handle != null )
			fclose( $this->handle );

		$this->handle = null;
	}
}