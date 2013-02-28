<?php
require_once 'excel_reader.php';
require_once 'excel_query.php';
require_once 'fields/exceptions/nstp_exception.php';
require_once 'fields/exceptions/pe_exception.php';

class Excel_Parser extends CI_Model {
	private $query;
	private $field_parsers = array();
	private $spreadsheet, $rows, $cols;
	private $successcount = 0;
	private $errorcount = 0;
		
	function __construct() {
        parent::__construct();
    }
	
	public function getErrorCount() {
		return $this->errorcount;
	}
	
	public function getSuccessCount() {
		return $this->successcount;
	}
	
	/** 
		$excelfile - the filename of the input excel file to be parsed
	*/
	public function initialize($excelfile) {
		$this->spreadsheet = new Spreadsheet_Excel_Reader($excelfile, false);
		$this->load->model('excel_query', 'query');
		$this->rows = $this->spreadsheet->rowcount();
		$this->cols = $this->spreadsheet->colcount();
		
		$this->load->model("Field_factory", "field_factory");
		for ($col = 1; $col <= $this->cols - 2; $col++) // last 3 columns (grades) are parsed at the same time
			$this->field_parsers[$col] = $this->field_factory->createFieldByNum($col);
	}
	
	/** Start parsing $this->spreadsheet. */
	public function parse() {
		$output = "<table class='databasetable'>";
		// If 1st row is not a header, change to $i = 1
		$output .= "<tr><th>row</th>";
		for ($col = 1; $col <= $this->cols - 2; $col++) {
			$header = $this->spreadsheet->val(1, $col);
			$output .= "<th>$header</th>";
		}
		$output .= "</tr>";
		for ($row = 2; $row <= $this->rows; $row++) {
			$this->query = new Excel_query;
			$output .= $this->parseRow($row);
			$this->query->execute();
		}
		$output .= "</table>";
		return $output;
	}
	
	private function parseRow($row) {
		$success = true;
		$error = true;
		$output = "<tr><th>".$row."</th>";
		for ($col = 1; $col <= $this->cols - 2; $col++) { // last 3 columns (grades) are parsed at the same time
			$value = $this->spreadsheet->val($row, $col);
			$orig_value = $this->spreadsheet->val($row, $col);
			try {
				$field = $this->field_parsers[$col];
				if ($col == $this->cols - 2) { // grades, include comp and secondcomp
					$compgrade = $this->spreadsheet->val($row, $col + 1);
					$secondcompgrade = $this->spreadsheet->val($row, $col + 2);
					$field->parse($value, $compgrade, $secondcompgrade);
				}
				else
					$field->parse($value);
				$field->insertToQuery($this->query);
				$output .= "<td class='databasecell'>$value</td>";
			} catch (NstpException $e) {
				$this->query->doNotExecute();
				$success = false;
				$error = false;
			} catch (PeException $e) {
				$this->query->doNotExecute();
				$success = false;
				$error = false;
			}
			catch (Exception $e) {
				$this->query->doNotExecute();
				$message = $e->getMessage(); // store for tooltip message
				$output .= "<td title='$message'><div class='databasecell upload_error'>$orig_value</div></td>";
				$success = false;
			}
		}
		$output .= "</tr>";
		if ($success) {
			$this->successcount++;
			return ''; // don't print the row
		}
		else if ($error) {
			$this->errorcount++;
			return $output; // add row for printing;
		}
		else { // neither success nor error (NSTP/PE)
			return '';
		}
	}
}
?>
