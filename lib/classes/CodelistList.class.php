<?php

class CodelistList extends ItemListDomain {

    protected $item = "codelist_id";

    public function __construct() {
        parent::__construct();
        $this->table = Config::db_table_codelist();
        $this->lang_id = isset($this->session->codelist_lang) ? $this->session->codelist_lang : $this->session->lang_id;
    }

    public function load($items = array(), $limit = 0, $from = 0, $orderby = "t2.name", $sort = "ASC") {
        $query = "SELECT t1.*, t2.name
                  FROM ".$this->table." t1
                  LEFT JOIN ".Config::db_table_codelist_text()." t2 ON (t1.".$this->item." = t2.".$this->item." AND t2.lang_id = ".$this->lang_id.")
				  WHERE domain_id=".$this->session->domain_id;

        if($items) {
            $query.= " AND ".$this->item." IN (".implode(', ', $items).")";
        }

        if($orderby) {
			$query.= " ORDER BY ".$orderby." ".$sort;
		}

        if($limit) {
			$query.= " LIMIT ".$from." ".$limit;
		}

        $data = $this->db->select($query);
        foreach($data as $row) {
            $this->data[$row[$this->item]] = $row;
        }
    }

	public function getCodelist($code) {
		$query = "SELECT codelist_id
				  FROM ".$this->table."
				  WHERE code = '".mysql_real_escape_string($code)."'";
		$codelist_id = $this->db->select($query, true, "codelist_id");
		if($codelist_id) {
			return new Codelist($codelist_id);
		} else {
			return false;
		}
	}

}
