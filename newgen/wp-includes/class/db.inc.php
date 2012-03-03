<?php
     class DB
     {
	 

			var $host = '';
			var $user = '';
			var $password = '';
			var $database = '';
		
			var $persistent = false;
			var $conn=NULL;
			var $persist=NULL;
			var $result=false;
			var $fields;
			var $check_fields;
			var $tbname;
			var $addNewFlag=false;
        /////////////////////////End ////////////////////

        function addNew($table_name)
        {
           $this->fields=array();
           $this->addNewFlag=true;
           $this->tbname=$table_name;
        }

        function edit($table_name)
        {
           $this->fields=array();
           $this->check_fields=array();
           $this->addNewFlag=false;
           $this->tbname=$table_name;
        }

        function update()
        {
         foreach($this->fields as $field_name=>$value)
		 {
           if($value=='--DATE--')
			   $qry.=$field_name."=now(),";
		   else
			   $qry.=$field_name."='".$value."',";
		 }

         $qry=substr($qry,0,strlen($qry)-1);

          if($this->addNewFlag)
            $qry="INSERT INTO ".$this->tbname." SET ".$qry;
          else
          {
           $qry="UPDATE ".$this->tbname." SET ".$qry;
           if(count($this->check_fields)>0)
           {
               $qry.=" WHERE ";
               foreach($this->check_fields as $field_name=>$value)
                   $qry.=$field_name."='".$value."' AND ";
               $qry=substr($qry,0,strlen($qry)-5);
           }
          }
         return $this->query($qry);
        }



        function DB($host="",$user="",$password="",$dbname="",$open=false)
        {
			$this->setDatabaseSettings();
         if($host!="")
            $this->host=$host;
         if($user!="")
            $this->user=$user;
         if($password!="")
            $this->password=$password;
         if($dbname!="")
            $this->database=$dbname;

         if($open)
           $this->open();
        }



        function open($host="",$user="",$password="",$dbname="") //
        {
         if($host!="")
            $this->host=$host;
         if($user!="")
            $this->user=$user;
         if($password!="")
            $this->password=$password;
         if($dbname!="")
            $this->database=$dbname;

         if($this->connect()===false) return false;
         if($this->select_db()===false) return false;
		 return $this->conn;
        }


        function set_host($host,$user,$password,$dbname)
        {
         $this->host=$host;
         $this->user=$user;
         $this->password=$password;
         $this->database=$dbname;
        }


        function affectedRows() //-- Get number of affected rows in previous operation
        {
         return @mysql_affected_rows($this->conn);
        }
		function query($sql)
			{
				$this->result = @mysql_query($sql, $this->conn);
				return ($this->result != false);
			}


        function close()//Close a connection to a  Server
        {
         return @mysql_close($this->conn);
        }


        function connect() //Open a connection to a Server
        {
          if(is_resource($this->conn))
			return true;
		  // Choose the appropriate connect function
          if ($this->persist)
	          $this->conn = @mysql_pconnect($this->host, $this->user, $this->password);
          else
	          $this->conn = @mysql_connect($this->host, $this->user, $this->password);

          // Connect to the database server
          if(!is_resource($this->conn))
             return false;
		  else
			  return true;
              
        }



        function select_db($dbname="") //Select a databse
        {
          if($dbname=="")
             $dbname=$this->database;
			return  @mysql_select_db($dbname,$this->conn);
        }


        function create_db($dbname="") //Create a database
        {
          if($dbname=="")
             $dbname=$this->database;
          return $this->query("CREATE DATABASE ".$dbname);
        }


        function drop_db($dbname="") //Drop a database
        {
          if($dbname=="")
             $dbname=$this->database;
          return $this->query("DROP DATABASE ".$dbname);
        }


        function data_seek($row) ///Move internal result pointer
        {
         return mysql_data_seek($this->result,$row);
        }


        function error() //Get last error
        {
            return (mysql_error());
        }


        function errorno() //Get error number
        {
            return mysql_errno();
        }
       
        function numRows() //Return number of rows in selected table
        {
            return (@mysql_num_rows($this->result));
        }


    	  function fieldName($field)
        {
           return (@mysql_field_name($this->result,$field));
        }


    	  function insertID()
        {
            return (@mysql_insert_id($this->conn));
        }


        function fetchObject()
        {
            return (@mysql_fetch_object($this->result));
        }


        function fetchArray($mode=MYSQL_BOTH)
        {
            return (@mysql_fetch_array($this->result,$mode));
        }


        function fetchAssoc()
        {
            return (@mysql_fetch_assoc($this->result));
        }


        function freeResult()
        {
            return (@mysql_free_result($this->result));
        }



		function getSingleResult($sql)
		{
			$this->query($sql);
			$row=$this->fetchArray(MYSQL_NUM);
			$return=$row[0];
			return $return;
		}
		
		/*
				Set the data base settings for the current DB object
		*/
		
	 function setDatabaseSettings(){
			 $this->host = constant("HOST");
			 $this->user = constant("USER");
			 $this->password = constant("PASSWORD");
			 $this->database = constant("DATABASE");	 	
	 }
 }
	 

?>
