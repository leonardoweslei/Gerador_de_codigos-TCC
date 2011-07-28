<?php

class zipentry {

  const local_file_header_signature = "\x50\x4b\x03\x04";
  const data_descriptor_signature = "\x50\x4b\x07\x08";
  const central_file_header_signature = "\x50\x4b\x01\x02";
  const COMPRESSION_BZIP2 = 12;
  const COMPRESSION_DEFLATE = 8;
  const COMPRESSION_NONE = 0;
  
  //private members which can be read from outside though __get() but cannot be set from outside
  private $crc32;
  private $datasize;
  private $compressed_datasize;
  private $is_dir;
  private $zip_version;
  private $errors = array();
  
  //members which can be changed from outside but changes require special tratment, so __set() and __get() must be used
  private $data;
  private $compression_level = 6; //0 - 9 for deflate, 1 - 9 for BZIP2. It's recommened to use 0 as compressio method instead of deflating with level 0.
  private $compression_method = self::COMPRESSION_DEFLATE;
  
  //public members
  public $last_modified;
  public $filename;
  public $comment;
  public $save_memory = false; //if true this class uses less memory but needs more time (in fact currently it's only relevant for uncompressing BZIP2 compressed data)
  

  public function __construct ($is_dir = false) {
    $this->is_dir = $is_dir;
    $this->zip_version = ($is_dir)?20:10;
  }
  
  public function __toString () {
    return (string)$this->uncompress();
  }

  public function __set($name, $value) {
    switch ($name) {
      case 'data':
        if ($this->is_dir) {
          $this->errors[] = 'Zip-Entry is a directory. Directories have no content.'."<br>\n";
          return false;
        }
        $this->data = $value;
        $this->crc32 = crc32($value);
        $this->datasize = strlen($value);
        $this->data = $this->compress();
        $this->compressed_datasize = strlen($this->data);
        return true;
      case 'compression_method':
        switch ($value) {
          case self::COMPRESSION_NONE:
            $this->zip_version = ($this->is_dir)?20:10;
            break;
          case self::COMPRESSION_DEFLATE:
            $this->zip_version = 20;
            break;
          case self::COMPRESSION_BZIP2:
            $this->zip_version = 46;
            break;
          default:
            $this->errors[] = 'Compression method is not valid or not supported.'."<br>\n";
            return false;
        }
        $this->data = $this->uncompress();
        $this->compression_method = $value;
        $this->data = $this->compress();
        return true;
      case 'compression_level':
        if ($value > 9 || $value < 1) { //There are no values > 9 for all currently supportet compression methods.
          $this->errors[] = 'Compression level has to be a number greater 0 and less 9.'."<br>\n";
          return false;
        }
        $this->data = $this->uncompress();
        $this->compression_level = $value;
        $this->data = $this->compress();
        return true;
      default:
        $this->errors[] = 'There is no property "'.$name.'".'."<br>\n";
        return false;
    }
  }
  
  public function __get($name){
    switch ($name){
      case 'data':
        return $this->uncompress();
      case 'compressed_data':
        return $this->data;
      case 'compression_method':
        return $this->compression_method;
      case 'compression_level':
        return $this->compression_level;
      case 'crc32':
        return $this->crc32;
      case 'datasize':
        return $this->datasize;
      case 'compressed_datasize':
        return $this->compressed_datasize;
      case 'is_dir':
        return $this->is_dir;
      case 'errors':
        return $this->errors;
      case 'last_error':
        return end($this->errors);
      case 'file_record':
        return $this->get_file_record();
      default:
        $this->errors[] = 'There is no property "'.$name.'".'."<br>\n";
        return NULL;
    }
  }
  
  private function compress() {
    if ($this->is_dir) return '';
    switch ($this->compression_method) {
      case self::COMPRESSION_NONE:
        return $this->data;
      case self::COMPRESSION_DEFLATE:
        return gzdeflate($this->data, $this->compression_level);
      case self::COMPRESSION_BZIP2:
        return bzcompress($this->data, $this->compression_level);
      default:
        $this->errors[] = 'Compression method is not valid or not supported.'."<br>\n";
        return false;
    }
  }
  
  private function uncompress() {
    if ($this->is_dir) return '';
    switch ($this->compression_method) {
      case self::COMPRESSION_NONE:
        return $this->data;
      case self::COMPRESSION_DEFLATE:
        return gzinflate($this->data);
      case self::COMPRESSION_BZIP2:
        return bzdecompress($this->data, $this->save_memory);
      default:
        $this->errors[] = 'Compression method is not valid or not supported.'."<br>\n";
        return false;
    }
  }
  
  public function get_file_record() {
    $general_purpose_bit_flag = 0;
    $external_attributes = 0;
    $external_attributes = $external_attributes | (($this->is_dir)?16:32); //set directory- or archive-bit
    if (!$this->last_modified) $this->last_modified = time();
    return self::local_file_header_signature
          .pack('v5V3v2',
            $this->zip_version,
            $general_purpose_bit_flag,
            $this->compression_method,
            bindec(sprintf('%b05%b06%b05', date('H', $this->last_modified), date('i', $this->last_modified),
                            floor(date('s', $this->last_modified)/2))),
            bindec(sprintf('%b07%b04%b05', date('Y', $this->last_modified)-1980, date('m', $this->last_modified),
                            date('d', $this->last_modified))),
            $this->crc32,
            $this->compressed_datasize,
            $this->datasize,
            strlen($this->filename),
            0)//extra field length. not needed, so we set it to zero.
          .$this->filename
          .$this->data;
  }
  
  public function get_central_dir_record($offset) {
    $general_purpose_bit_flag = 0;
    $external_attributes = 0;
    $external_attributes = $external_attributes | (($this->is_dir)?16:32); //set directory- or archive-bit
    if (!$this->last_modified) $this->last_modified = time();
    $compressed_datasize = strlen($this->data);
    return self::central_file_header_signature
          .pack('v6V3v5V2',
            $this->zip_version,
            $this->zip_version,
            $general_purpose_bit_flag,
            $this->compression_method,
            bindec(sprintf('%b05%b06%b05', date('H', $this->last_modified), date('i', $this->last_modified),
                            floor(date('s', $this->last_modified)/2))),
            bindec(sprintf('%b07%b04%b05', date('Y', $this->last_modified)-1980, date('m', $this->last_modified),
                            date('d', $this->last_modified))),
            $this->crc32,
            $this->compressed_datasize,
            $this->datasize,
            strlen($this->filename),
            0, //extra field length. not needed, so we set it to zero.
            strlen($this->comment),
            0, //disk number start. we do not use multiple disks, so set ist to zero
            0, //internal file attributes
            $external_attributes,
            $offset)
          .$this->filename
          .$this->comment;
  }
  
  
  public function zip_input($file_record, $central_dir_record = false, $verify = true) {
    if ($central_dir_record) {
      if (strpos($central_dir_record, self::central_file_header_signature) !== 0) {
        $this->errors[] = 'Central directory record entry seems to be invalid: no Signature found.'."<br>\n";
        return false;
      }
      $central_dir_record_parts = unpack('V/v/vversion/vgeneral_purpose_bit_flag/vcompression_method/vchangetime'
                                        .'/vchangedate/Vcrc32/Vcompressed_datasize/Vdatasize/vfilename_length'
                                        .'/vextrafield_length/vcomment_length/vdisk_number_start'
                                        .'/vinternal_file_attributes/Vexternal_attributes/Voffset', $central_dir_record);
      $central_dir_record = substr($central_dir_record, 46);
    }
    if (strpos($file_record, self::local_file_header_signature) !== 0) {
      $this->errors[] = 'File record seems to be invalid: no Signature found.'."<br>\n";
      return false;
    }
    $file_record_parts = unpack('V/vversion/vgeneral_purpose_bit_flag/vcompression_method/vchangetime/vchangedate/Vcrc32'
                               .'/Vcompressed_datasize/Vdatasize/vfilename_length/vextrafield_length', $file_record);
    $file_record = substr($file_record, 30);
    $this->filename = substr($file_record, 0, $file_record_parts['filename_length']);
    if ($central_dir_record && $verify) {
      switch (false) {
      //  case $file_record_parts['version'] == $central_dir_record_parts['version']:
      //  case $file_record_parts['changetime'] == $central_dir_record_parts['changetime']:
      //  case $file_record_parts['changedate'] == $central_dir_record_parts['changedate']:
        case $file_record_parts['general_purpose_bit_flag'] == $central_dir_record_parts['general_purpose_bit_flag']:
        case $file_record_parts['compression_method'] == $central_dir_record_parts['compression_method']:
        case $file_record_parts['crc32'] == $central_dir_record_parts['crc32']:
        case $file_record_parts['compressed_datasize'] == $central_dir_record_parts['compressed_datasize']:
        case $file_record_parts['datasize'] == $central_dir_record_parts['datasize']:
        case $file_record_parts['extrafield_length'] == $central_dir_record_parts['extrafield_length']:
        #check for equal filename length is not neccessary, because filename would be different than.
        case $this->filename == substr($central_dir_record, 0, $central_dir_record_parts['filename_length']):
          $this->errors[] = 'The zip entry seems to be corrupt: information given in file record are not equal information
                             given in central directory record. You can try reading the file only from out of the local 
                             file header, but file comment and external attributes got lost. Alternatively you can try to
                             read the zipfile without verification.'."<br>\n";
          return false;
      }
    }
    $file_record = substr($file_record, $file_record_parts['filename_length']+$file_record_parts['extrafield_length']);
    if ($central_dir_record) {
      $central_dir_record = substr($central_dir_record, $central_dir_record_parts['filename_length']
                          + $central_dir_record_parts['extrafield_length']);
      $this->is_dir = (bool)($central_dir_record_parts['external_attributes'] & 16);
      $this->comment = substr($central_dir_record, 0, $central_dir_record_parts['comment_length']);
    }
    $this->compression_method = $file_record_parts['compression_method'];
    if (!$this->is_dir) {
      $this->data = substr($file_record, 0, $file_record_parts['compressed_datasize']);
    }
    if ($verify && crc32($this->uncompress()) != $file_record_parts['crc32']) {
      $this->errors[] = 'The zip entry seems to be corrupt: failed crc32-checksum validation You can try reading the file
                         without verification to skip this error.'."<br>\n";
      return false;
    }
    if ($verify && strlen($this->uncompress()) != $file_record_parts['datasize']) {
      $this->errors[] = 'The zip entry seems to be corrupt: uncompressed datasize is not eqaul the size specified in the
                         local file record. You can try reading the file without verification to skip this error.'."<br>\n";
      return false;
    }
    $this->crc32 = $file_record_parts['crc32'];
    $this->compressed_datasize = $file_record_parts['compressed_datasize'];
    $this->datasize = $file_record_parts['datasize'];
    $this->last_modified = mktime();
    $this->compression_method = $file_record_parts['compression_method'];
    return true;
  }
}




class zipfile {

  const end_of_central_dir_record_signature = "\x50\x4b\x05\x06";
  const COMPRESSION_BZIP2 = 12;
  const COMPRESSION_DEFLATE = 8;
  const COMPRESSION_NONE = 0;
  
  private $entries = array();
  private $errors = array();
  public $save_memory = false; //if true this class uses less memory but needs more time (in fact currently it's only relevant for uncompressing BZIP2 compressed data)
  public $default_compression_level = 6; //0 - 9 for deflate, 1 - 9 for BZIP2. It's recommened to use 0 as compressio method instead of deflating with level 0.
  public $default_compression_method = self::COMPRESSION_DEFLATE; //currently allowed: 0 = no compression, 8 = deflate, 12 = BZIP2
  public $comment;
  
  public function __toString() {
  }
  
  public function __get($name) {
    switch ($name) {
      case 'entries':
        return $this->entries;
      case 'last_entry':
        return end($this->entries);
      case 'errors':
        return $this->errors;
      case 'last_error':
        return end($this->errors);
      default:
        $this->errors[] = 'There is no property "'.$name.'".'."<br>\n";
        return false;
    }
  }
  
  public function add_data ($filename, $data='', $is_dir = false, $last_modified = false, $comment = '', 
                     $compression_method = 'default', $compression_level = 'default', $save_memory = 'default') {
    if ($last_modified === false) $last_modified = mktime();
    if ($compression_method == 'default') $compression_method = $this->default_compression_method;
    if ($compression_level == 'default') $compression_level = $this->default_compression_level;
    if ($save_memory == 'default') $save_memory = $this->save_memory;
    $this->entries[] = new zipentry($is_dir);
    $new_entry = &end($this->entries);
    $new_entry->filename = $filename;
    $new_entry->compression_method = $compression_method;
    $new_entry->compression_level = $compression_level;
    if (!$is_dir) {
      $new_entry->data = $data;
    }
    $new_entry->comment = $comment;
    $new_entry->last_modified = $last_modified;
    $new_entry->save_memory = $save_memory;
    if ($new_entry->last_error) {
      $error = 'Failed adding new entry "'.$filename.'" to zipfile: '."<br>\n";
      foreach ($new_entry->errors as $entry_error) {
        $error .= '  &nbsp;&nbsp;&nbsp;&nbsp;'.$entry_error;
      }
      $this->errors[] = $error;
      array_pop($this->entries);
      return false;
    }
    return true;
  }
  
  public function add_file ($file, $ignore_path = false, $comment = '', $compression_method = 'default', 
                     $compression_level = 'default', $save_memory = 'default') {
    if (!is_file($file)) {
      $this->errors[] = 'Failed adding file "'.$file.'": file not found.'."<br>\n";
      return false;
    }
    $data = file_get_contents($file);
    if (!$last_modified = @filemtime($file)) {
      $this->errors[] = 'Error while adding file "'.$file.'": could not specify last modified date.'."<br>\n";
    }
    if ($ignore_path) $file = basename($file);
    return $this->add_data ($file, $data, false, $last_modified, $comment, $compression_method, $compression_level, $save_memory);
  }
  
  public function add_dir ($name, $comment = '', $last_modified = false) {
    return $this->add_data ($name, '', true, $last_modified, $comment, 0);
  }
  
  public function add_zipfile ($file, $verify = false, $ignore_central_dir_record = false) {
    if (!is_file($file)) {
      $this->errors[] = 'Failed adding zipfile "'.$file.'": file not found.';
      return false;
    }
    $zipfile = file_get_contents($file);
    $end_of_cdr_parts = unpack('V/vnumber_of_disk/voverall_disk_count/ventries_this_disk/voverall_entries'
                                          .'/Vcentral_dir_length/Vstart_of_central_dir/vcomment_length/a*comment',
                                              strstr($zipfile, self::end_of_central_dir_record_signature));
    if ($end_of_cdr_parts['number_of_disk'] || $end_of_cdr_parts['overall_disk_count']) {
      $this->errors[] = 'Failed loading zipfile "'.$file.'": This class cannot handle split archives yet.'."<br>\n";
      return false;
    }
    if ($verify && $end_of_cdr_parts['entries_this_disk'] != $end_of_cdr_parts['overall_entries']) {
      $this->errors[] = 'Failed loading zipfile "'.$file.'": Number of entries on this disk does not equal the number of all
                        entries, but only one disk is used. You can try to load the zipfile without verification to skip
                        this error.'."<br>\n";
      return false;
    }
    if($verify && strlen($end_of_cdr_parts['comment']) > $end_of_cdr_parts['comment_length']) {
      $this->errors[] = 'Error while loading zipfile "'.$file.'": Comment found was longer than given comment-length. You
                        can try to load the zipfile without verification to skip this error.'."<br>\n";
      return false;
    }
    $this->comment .= $end_of_cdr_parts['comment'];
    $zipfile = substr($zipfile, 0, strpos($zipfile, self::end_of_central_dir_record_signature));
    $zipfile = explode (zipentry::central_file_header_signature, $zipfile, 2);
    if ($verify && strlen($zipfile[0]) != $end_of_cdr_parts['start_of_central_dir']) {
      $this->errors[] = 'Error while loading zipfile "'.$file.'": Local file headers section is smaller than specified.
                        You can try to load the zipfile without verification to skip this error.'."<br>\n";
      return false;
    }
    if ($verify && strlen($zipfile[1]) != $end_of_cdr_parts['central_dir_length']-4) {
      $this->errors[] = 'Error while loading zipfile "'.$file.'": Central diectory record is smaller than specified. You
                        can try to load the zipfile without verification to skip this error.'."<br>\n";
      return false;
    }
    $zipfile[0] = explode (zipentry::local_file_header_signature, $zipfile[0]);
    array_shift($zipfile[0]);
    $zipfile[1] = explode (zipentry::central_file_header_signature, $zipfile[1]);
    if (($verify || !$ignore_central_dir_record) && count($zipfile[0]) != count($zipfile[1])) {
      $this->errors[] = 'Error while loading zipfile "'.$file.'": Number of local file headers does not equal number of 
                         entries in the central directory record. You can try to load the zipfile without verification
                         and without loading the central directory record to skip this error.'."<br>\n";
    }
    if ($verify && count($zipfile[0]) != $end_of_cdr_parts['overall_entries']) {
      $this->errors[] = 'Error while loading zipfile "'.$file.'": Number of local file headers does not equal the number
                         of entries specified in the end of central directory record. You can try to load the zipfile 
                         without verification to skip this error.'."<br>\n";
    }
    $added_all_entries = true;
    while ($local_file_header = array_shift($zipfile[0])) {
      $central_dir_record = ($ignore_central_dir_record)?false:array_shift($zipfile[1]);
      $this->entries[] = new zipentry;
      $new_entry = &end($this->entries);
      $new_entry->zip_input(zipentry::local_file_header_signature.$local_file_header, 
                            zipentry::central_file_header_signature.$central_dir_record, $verify);
      if ($new_entry->last_error) {
        $error = 'Failed adding new entry '.((isset($new_entry->filename))?'"'.$new_entry->filename.'" ':'').' from "'.$file
                .'" to zipfile: '."<br>\n";
        foreach ($new_entry->errors as $entry_error) {
          $error .= '  &nbsp;&nbsp;&nbsp;&nbsp;'.$entry_error;
        }
        $this->errors[] = $error;
        array_pop($this->entries);
        $added_all_entries = false;
        continue;
      }
    }
    return $added_all_entries;
  }
  
  public function output() {
    $offsets = array(0);
    for ($i = 0, $entries = count($this->entries); $i < $entries; $i++) {
      $entry = &$this->entries[$i];
      echo $entry->file_record;
      $offsets[] = end($offsets) + 30 + strlen($entry->filename) + $entry->compressed_datasize;
    }
    $central_dir_length = 0;
    for ($i = 0, $entries = count($this->entries); $i < $entries; $i++) {
      $entry = &$this->entries[$i];
      echo $entry->get_central_dir_record($offsets[$i]);
      $central_dir_length += 46 + strlen($entry->filename) + strlen($entry->comment);
    }
    echo self::end_of_central_dir_record_signature;
    echo "\x00\x00"; //number of this disk
    echo "\x00\x00"; //number of the disk with the start of the central directory
    echo pack('v2V2v', 
         count($this->entries),
         count($this->entries),
         $central_dir_length,
         end($offsets),
         strlen($this->comment));
    echo $this->comment;
  }
  
  public function save($filename) {
    $file = fopen($filename, 'w+');
    $offsets = array(0);
    for ($i = 0, $entries = count($this->entries); $i < $entries; $i++) {
      $entry = &$this->entries[$i];
      fwrite ($file, $entry->file_record);
      $offsets[] = end($offsets) + 30 + strlen($entry->filename) + $entry->compressed_datasize;
    }
    $central_dir_length = 0;
    for ($i = 0, $entries = count($this->entries); $i < $entries; $i++) {
      $entry = &$this->entries[$i];
      fwrite ($file, $entry->get_central_dir_record($offsets[$i]));
      $central_dir_length += 46 + strlen($entry->filename) + strlen($entry->comment);
    }
    fwrite ($file, self::end_of_central_dir_record_signature);
    fwrite ($file, "\x00\x00"); //number of this disk
    fwrite ($file, "\x00\x00"); //number of the disk with the start of the central directory
    fwrite ($file, pack('v2V2v', 
         count($this->entries),
         count($this->entries),
         $central_dir_length,
         end($offsets),
         strlen($this->comment)));
    fwrite ($file, $this->comment);
    fclose($file);
  }
  
  public function unpack ($into_dir) {
    function recursive_mkdir($dir) {
      if (!is_dir(dirname($dir))) {
        recursive_mkdir(dirname($dir));
      }
      mkdir($dir);
    }
    
    for ($i = 0, $entries = count($this->entries); $i < $entries; $i++) {
      $entry = &$this->entries[$i];
      if ($entry->is_dir) {
        $dirname = $into_dir.'/'.$entry->filename;
        if (!is_dir($dirname)) recursive_mkdir($dirname);
      } else {
        $dirname = dirname($into_dir.'/'.$entry->filename);
        if (!is_dir($dirname)) recursive_mkdir($dirname);
        file_put_contents($into_dir.'/'.$entry->filename, $entry->data);
      }
    }
  }
  
  
  function getDirContent ($dirName = './') {
    if (is_dir($dirName)){
        if ($handle = opendir($dirName)){
          while (false !== ($file = readdir($handle))) {
            if (($file != '.') && ($file != '..')){
              $content[] = $dirName.$file;
            }
          }
          closedir($handle);
          return $content;
        }
      }
  }

  function addDirContent($dir = './',$addd=false,$notadd=array()) {
  	if($addd!=false)$this->add_dir($dir);
  	$content=$this->getDirContent($dir);
    foreach ($content as $input) {
    	$inputtmp=array_reverse(explode("/",$input));
    	$inputtmp=$inputtmp[0];
    	$inputtmp=array_search($inputtmp,$notadd);
	//	echo "-".(!is_int($inputtmp))."<br>";
    	if(is_file($input) && !is_int($inputtmp))$this->add_file($input);
		elseif(!is_int($inputtmp)){
			$this->addDirContent($input."/",false,$notadd);
		}
    }
  }
} 