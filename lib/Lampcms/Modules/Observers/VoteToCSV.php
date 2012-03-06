<?php
/**
 *
 * License, TERMS and CONDITIONS
 *
 * This software is lisensed under the GNU LESSER GENERAL PUBLIC LICENSE (LGPL) version 3
 * Please read the license here : http://www.gnu.org/licenses/lgpl-3.0.txt
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR "AS IS" AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE FREEBSD PROJECT OR CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *
 *
 * @author     nbb.hss@gmail.com
 * @copyright  2012 Open-org.com
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU LESSER GENERAL PUBLIC LICENSE (LGPL) version 3
 * @link       http://www.lampcms.com   Lampcms.com project
 * @version    Release: @package_version@
 *
 *
 */

namespace Lampcms\Modules\Observers;


/**
 * This observer will listen for any new vote event
 * and write the data into the csv file.
 *
 * @author Boban Pulinchery
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
		$file = $this->Registry->Ini->getVar('VOTE_TO_CSV_FILE_PATH');
		if(!$file){
			throw new \Lampcms\DevException('No VOTE_FILE_PATH Defined in !config.ini, Specify currect path to csv file when enabling VoteToCSV');
		}
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
		$data = $this->getVoteData();
		/**
		 * create the file if it do not exists with header information
		 */
		if(!file_exists( $file )){
			if (false === file_put_contents($file, implode( ",", $fields) . "\n")){
				throw new \Lampcms\DevException('Write failed for file : '.$file.' unable to write CSV header');
			}
		};
		/**
		 * Using file lock
		 */
		$fd = fopen( $file, 'a');
		if( $fd ){
			if( flock($fd, LOCK_EX) ){
				fputcsv($fd, $data);
				flock($fd, LOCK_UN);
			}
			else{
				throw new \Lampcms\DevException('Lock failed : '.$file.' Accuring exclusive lock failed ');
			}
			fclose($fd);
		}
		else{
			throw new \Lampcms\DevException('Opening file: '.$file.' for appending CSV data failed');
		}
	}

}
