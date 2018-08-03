<?php
/*
$criteria = array(
	0 => 'string1 string2',
	'param1' => array(
		0 => 'string1',
		1 => 'string2'
	),
	'param2' => array(
		0 => 'string3'
	)
);
$section_id > 0 - pouze potomci zadane sekce
*/
class Search {

	protected $result = array();

    public function __construct($criteria, $section_id=0) {
		$this->criteria = $criteria;
		$this->section_id = $section_id;
		$this->db = Database::connect();
		$this->session = Session::getInstance(MODE);
    }

	public function getResult($from=0, $limit=0, $sortby="", $order="ASC") {
		if($this->result && $sortby) {
			$this->sortBy($sortby, $order);
		}
		if($limit) {
			return array_slice($this->result, $from, $limit);
		} else {
			return array_slice($this->result, $from);
		}
	}

	public function process() {
		if(is_array($this->criteria)) {
			$i = 1;
			foreach($this->criteria as $key => $criteria) {
				//fulltext
				if(is_numeric($key)) {
					foreach(explode(" ", $criteria) as $word) {
						if($word != "") {
							$sections = $this->getFulltextSections(trim($word));
							$this->mergeSections($sections, $i);
							$i++;
						}
					}
				//parametr
				} else {
					foreach($criteria as $value) {
						$sections = $this->getParamSections($key, trim($value));
						$this->mergeSections($sections, $i);
						$i++;
					}
				}
			}
		}
	}

	protected function sortBy($sortby, $order) {	
		$sectionList = new SectionList();
		$this->result = $sectionList->sortSectionsBy($sortby, $order, $this->result);
	}

	protected function mergeSections($sections, $i) {
		if($i == 1) {
			$this->result = $sections;
		} else {
			$this->result = array_intersect($this->result, $sections);
		}
	}

	protected function getFulltextSections($param) {
		$return = array();
		
		$where = "";
		if($this->section_id) {
			$where.= " AND t1.parent_id = ".$this->section_id;
		}
		
		$query = "SELECT DISTINCT(st1.section_id) AS section_id
				  FROM ".Config::db_table_section_text()." st1
				  LEFT JOIN ".Config::db_table_section()." s1 ON (st1.section_id = s1.section_id)
				  LEFT JOIN ".Config::db_table_section_text_value()." stv1 ON (st1.section_text_id = stv1.section_text_id)
				  LEFT JOIN ".Config::db_table_tree()." t1 ON (s1.section_id = t1.section_id)
				  WHERE (st1.name LIKE '%".$param."%' OR
				  		 stv1.int_val LIKE '%".$param."%' OR
				  		 stv1.varchar_val LIKE '%".$param."%' OR
						 stv1.text_val LIKE '%".$param."%' OR
						 stv1.datetime_val LIKE '%".$param."%') AND
						st1.section_text_id = (
					        SELECT MAX(st2.section_text_id)
					        FROM ".Config::db_table_section_text()." st2
					        WHERE st2.section_id = st1.section_id AND
					        	  st2.lang_id = st1.lang_id) AND
						s1.domain_id = ".$this->session->domain_id." AND
                        st1.lang_id = ".$this->session->lang_id.$where;
		$res = $this->db->select($query);
		foreach($res as $row) {
			$return[] = $row['section_id'];
		}
		return $return;
	}

	protected function getParamSections($code, $param) {
		$return = array();

		$where = "";
		if($this->section_id) {
			$where.= " AND t1.parent_id = ".$this->section_id;
		}

		if(MODE != 'CMS') {
			$where.= " AND st1.status = '1'";
		}

		$query = "SELECT DISTINCT(st1.section_id) AS section_id
				  FROM ".Config::db_table_section_text_value()." stv1
				  LEFT JOIN ".Config::db_table_section_text()." st1 ON (st1.section_text_id = stv1.section_text_id)
				  LEFT JOIN ".Config::db_table_section()." s1 ON (st1.section_id = s1.section_id)
				  LEFT JOIN ".Config::db_table_tree()." t1 ON (s1.section_id = t1.section_id)
				  WHERE stv1.code = '".$code."' AND
				  		(stv1.int_val LIKE '%".$param."%' OR
				  		 stv1.varchar_val LIKE '%".$param."%' OR
						 stv1.text_val LIKE '%".$param."%' OR
						 stv1.datetime_val LIKE '%".$param."%') AND
						st1.section_text_id = (
					        SELECT MAX(st2.section_text_id)
					        FROM ".Config::db_table_section_text()." st2
					        WHERE st2.section_id = st1.section_id AND
					        	  st2.lang_id = st1.lang_id) AND
						s1.domain_id = ".$this->session->domain_id." AND
                        st1.lang_id = ".$this->session->lang_id.$where;
		$res = $this->db->select($query);
		foreach($res as $row) {
			$return[] = $row['section_id'];
		}
		return $return;
	}

}
