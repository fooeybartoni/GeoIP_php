<?php

class CSV {
	
	/**
	 * Export
	 *
	 * Exports a CSV file from php arrays
	 * @param array $field_names
	 * @param array/object $data
	 * @param string $filename
	 * @param bool $return
	 * @return file
	 */
	public static function export($field_names, $data=array(), $filename="export", $return=FALSE)
	{
		//Generate the title row
		$title_row = self::_get_titles($field_names);
		
		//Generate the body
		$body = self::_format_data($data);
		
		$filename = $filename."_".uniqid().".csv";
		
		$output = $title_row . "\n" . $body;
		
		//Standardize newlines
		$output = strtr($output, "\r", "\n");
		$output = str_replace("\n\n", "\n", $output);
		
		if(!$return)
		{
			//Set the headers
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: private");
		    header("Content-type: text/csv");
		    header("Content-Disposition: attachment; filename=$filename");
		    header("Accept-Ranges: bytes");
			
			//Output the csv file
			echo $output;
		}
		else
		{
			return $output;
		}
		
	}
	
	/**
	 * Get Titles
	 *
	 * Generates title row of spreadsheet from array
	 * @param array $field_names
	 * @return string $title_row
	 */
	private function _get_titles($field_names)
	{
		$columns = array();
	
		$col_keys = array_keys($field_names);
		
		//Go through an arrays keys...add to columns if numeric
		foreach($col_keys as $col)
		{
			if(is_string($col))
			{
				//Add valid columns to the array, make sure to escape " characters already there
				$columns[] = '"'.strtr($col, '"', '\"').'"';
			}
		}
		
		//What? No associative keys? I guess the columns are just the values of this array then
		if(empty($columns))
		{
			foreach($field_names as $col)
			{
				//Add valid columns to the array, make sure to escape " characters already there
				$columns[] = '"'.strtr($col, '"', '\"').'"';
			}
		}
		
		$title_row = implode(",", $columns);
		
		return $title_row;
	}
	
	/**
	 * Format Data
	 *
	 * Takes data array and converts it to CSV rows
	 * @param array/object $data
	 * @return string 
	 */
	private function _format_data($data)
	{
		$body = '';
	
		foreach($data as $row)
		{
			$row_array = array();
			
			foreach($row as $col)
			{
				$row_array[] = '"'.strtr($col, '"', '\"').'"';
			}
			
			$body .= implode(",", $row_array)."\n";
		}
		
		return $body;
	}
}