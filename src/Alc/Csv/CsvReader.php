<?php

namespace Alc\Csv;

/**
 * CsvReader
 */
class CsvReader {

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

		if( !file_exists($filename) )
			throw new \Exception('File doesnt exist', 1);

		$this->filename = $filename;

		$this->setDelimiter($delimiter);
		$this->setEnclosure($enclosure);
		$this->setEscape($escape);

		$this->hasHeader = $hasHeader;
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
	protected function open() {

		if( $this->handle == null) {

			if (($this->handle = fopen($this->filename, 'r')) == false) {

				$this->handle = null;

				throw new \Exception('Unable to open file in read mode', 1);
			}
		}

		return $this->handle;		
	}

	/**
	 * read
	 */
	public function read() {

		if ( $handle = $this->open() ) {

			if( ($cells = fgetcsv($handle, 0, $this->delimiter, $this->enclosure, $this->escape)) !== false) {

				if( $cells[0] == null )
					return false;

				if( $this->hasHeader() ) {

					if( $this->index == 0 ) {

						$this->setHeader( $cells );

						$this->index++;

						return $this->read();
					}

					$cells = $this->combineWithHeader($cells);
				}

				$this->index++;

				return $cells;
			}
			else {

				$this->close();
			}
		}

		return false;		
	}

	public function readLine() {

		return $this->read();
	}

	/**
	 * readAll
	 */
	public function readAll() {

		$this->index = 0;

		$csv = array();

		while( $row = $this->read() ) {

			$csv[] = $row;
		}

		$this->close();

		return $csv;
	}

	/**
	 * combineWithHeader
	 */
	protected function combineWithHeader( $cells ) {

		if( $header = $this->getHeader() ) {

			if( count($header) == count($cells) ) {

				return array_combine($header, $cells);
			}
			else {

				throw new \Exception('Bad number of arguments, CSV line '.$this->index.'. '.count($header).','.count($cells), 1);
			}
		}
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

	/**
	 * analyse
	 */
	public function analyse() {

		$info = $this->analyseFile();

		// Search for delimiter value
		if( isset($info['delimiter']['value']) ) {

			$this->setDelimiter($info['delimiter']['value']);
		}
		
		return true;;
	}

	/**
	 * analyseFile
	 *
	 * Source: http://www.php.net/manual/en/function.fgetcsv.php#101238
	 */
	public function analyseFile( $capture_limit_in_kb = 10 ) {

	    // capture starting memory usage
	    $output['peak_mem']['start'] = memory_get_peak_usage(true);

	    // log the limit how much of the file was sampled (in Kb)
	    $output['read_kb'] = $capture_limit_in_kb;
	   
	    // read in file
	    $fh = fopen($this->filename, 'r');
	        $contents = fread($fh, ($capture_limit_in_kb * 1024)); // in KB
	    fclose($fh);
	   
	    // specify allowed field delimiters
	    $delimiters = array(
	        'comma'     => ',',
	        'semicolon' => ';',
	        'tab'       => "\t",
	        'pipe'      => '|',
	        'colon'     => ':'
	    );
	   
	    // specify allowed line endings
	    $line_endings = array(
	        'rn'        => "\r\n",
	        'n'         => "\n",
	        'r'         => "\r",
	        'nr'        => "\n\r"
	    );
	   
	    // loop and count each line ending instance
	    foreach ($line_endings as $key => $value) {
	        $line_result[$key] = substr_count($contents, $value);
	    }
	   
	    // sort by largest array value
	    asort($line_result);
	   
	    // log to output array
	    $output['line_ending']['results']     = $line_result;
	    $output['line_ending']['count']     = end($line_result);
	    $output['line_ending']['key']         = key($line_result);
	    $output['line_ending']['value']     = $line_endings[$output['line_ending']['key']];
	    $lines = explode($output['line_ending']['value'], $contents);
	   
	    // remove last line of array, as this maybe incomplete?
	    array_pop($lines);
	   
	    // create a string from the legal lines
	    $complete_lines = implode(' ', $lines);
	   
	    // log statistics to output array
	    $output['lines']['count']     = count($lines);
	    $output['lines']['length']     = strlen($complete_lines);
	   
	    // loop and count each delimiter instance
	    foreach ($delimiters as $delimiter_key => $delimiter) {
	        $delimiter_result[$delimiter_key] = substr_count($complete_lines, $delimiter);
	    }
	   
	    // sort by largest array value
	    asort($delimiter_result);
	   
	    // log statistics to output array with largest counts as the value
	    $output['delimiter']['results']     = $delimiter_result;
	    $output['delimiter']['count']         = end($delimiter_result);
	    $output['delimiter']['key']         = key($delimiter_result);
	    $output['delimiter']['value']         = $delimiters[$output['delimiter']['key']];
	   
	    // capture ending memory usage
	    $output['peak_mem']['end'] = memory_get_peak_usage(true);
	    return $output;
	}
}
