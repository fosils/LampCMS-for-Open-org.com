<?php
/**
 * Copyright 2012 Open-org.com. All rights reserved.
 *
 *
 */

namespace Lampcms\Modules\Observers;


/**
 * This observer will listen for any new vote event
 * and write the data into the csv file.
 *
 */
 
class VoteToCSV extends \Lampcms\Event\Observer
{
	public function main(){
		switch($this->eventName){
			case 'onNewVote':
			$this->updateCSVFile();
			break;
		}
	}
	
	/**
	 * Set up the csv data in an array so that it can be written
	 * using fputcsv
	 */
	protected function getVoteData(){
		$voter = $this->Registry->Viewer ;
		$data = array(
		  'rid' => $this->obj->getResourceId(),
		  'rtype' => $this->obj->getResourceTypeId(),
		  'rtitle' => $this->obj->getTitle(),
		  'rurl' => $this->obj->getUrl(),
		  'uid' => $this->obj->getOwnerId(),
		  'uname' => $this->obj->getUsername(),
		  'votetype' => $this->aInfo['type'],
		  'voter' => $voter->getUid(),
		  'vname' => $voter->getDisplayName()
		);
		/**
		 * TODO:
		 *   the code to get the profit point amount 
		 *  should be handled at any single place.
		 */ 
		if('down' === $this->aInfo['type']){
			$points = \Lampcms\Points::DOWNVOTE;
		} elseif('QUESTION' === $data['rtype']){
			$points = \Lampcms\Points::UPVOTE_Q;
		} else {
			$points = \Lampcms\Points::UPVOTE_A;
		}
		$points = ($this->aInfo['isUndo'] ? 1 : -1) * $points;
		$data['profitpoint'] = $points;
		$data['time'] = date('Y-m-d H:i:s');
		return $data;
	}
	/**
	 * Place the votes information into the csv file
	 */
	protected function updateCSVFile(){
		try{
			$file = $this->Registry->Ini->getVar('VOTE_TO_CSV_FILE_PATH');
		}
		catch(\Lampcms\IniException $e){
            //l('Error: VoteToCSV: No VOTE_FILE_PATH Defined in !config.ini');
			throw new \Lampcms\DevException('No VOTE_FILE_PATH Defined in !config.ini, Specify currect path to csv file when enabling VoteToCSV');
		}
		/**
		 * create the file if it do not exists with header information
		 */
		if(!file_exists($file)){
			$fields = array(
				'Resource Id',
				'Resource Type',
				'Title',
				'Url',
				'Owner Id',
				'Owner',
				'Vote',
				'Voter Id',
				'Voter',
				'Profit Points',
				'Timestamp'
			);
			if ($fd = fopen( $file, 'w')){
				fputcsv($fd, $fields);
                fclose($fd);
			}
            else{
                //l('Error : VoteToCSV : failed to create votefile, check file path `' . $file . '` and permission');
				throw new \Lampcms\DevException('Write failed for file : '.$file.' unable to write CSV header');
            }
			fclose($fd);
		}
		/**
		 * Using file lock so that even on heavy traffic,
         * the data will be clean
		 */
		$data = $this->getVoteData();
		$fd = fopen( $file, 'a');
		if( $fd ){
            /**
             * wait till acquiring exclusive lock
             */
			if( flock($fd, LOCK_EX) ){
				fputcsv($fd, $data);
				flock($fd, LOCK_UN); //unlock
			}
			else{
                /**
                 * Some thing wrong happen,
                 * the execution should not reach here
                 */
                 //l('Fatal Error : VoteToCSV : accuring lock failed dumping data >>> ' . print_r($data) );
                 throw new \Lampcms\DevException('Lock failed : '.$file.' Accuring exclusive lock failed ');
			}
			fclose($fd);
		}
		else{
            //l('Error : VoteToCSV : opening csv file `' . $file . '` failed Check Permission');
			throw new \Lampcms\DevException('Opening file: '.$file.' for appending CSV data failed');
		}
	}

}
