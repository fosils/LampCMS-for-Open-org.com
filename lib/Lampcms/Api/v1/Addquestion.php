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
 * ATTRIBUTION REQUIRED
 * 4. All web pages generated by the use of this software, or at least
 * 	  the page that lists the recent questions (usually home page) must include
 *    a link to the http://www.lampcms.com and text of the link must indicate that
 *    the website\'s Questions/Answers functionality is powered by lampcms.com
 *    An example of acceptable link would be "Powered by <a href="http://www.lampcms.com">LampCMS</a>"
 *    The location of the link is not important, it can be in the footer of the page
 *    but it must not be hidden by style attibutes
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
 * This product includes GeoLite data created by MaxMind,
 *  available from http://www.maxmind.com/
 *
 *
 * @author     Dmitri Snytkine <cms@lampcms.com>
 * @copyright  2005-2011 (or current year) ExamNotes.net inc.
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU LESSER GENERAL PUBLIC LICENSE (LGPL) version 3
 * @link       http://www.lampcms.com   Lampcms.com project
 * @version    Release: @package_version@
 *
 *
 */


namespace Lampcms\Api\v1;

use \Lampcms\Api\Api;
use \Lampcms\QuestionParser;
use \Lampcms\String\HTMLString;

/**
 * Controller for adding new Quetion via
 * API POST
 *
 *
 * @author Dmitri Snytkine
 *
 */
class Addquestion extends Api
{
	protected $bRequirePost = true;

	protected $membersOnly = true;

	protected $permission = 'ask';
	
	/**
	 * Object of newly created Question
	 *
	 * @var object of type \Lampcms\Question
	 */
	protected $Question;

	protected $aRequired = array('qbody', 'title');

	/**
	 * Object representing data of the submitted
	 * question.
	 *
	 * @var Object of type SubmittedQuestionApi
	 * extends SubmittedQuestionWWW
	 *
	 */
	protected $Submitted;


	protected function main(){

		$this->Submitted = new SubmittedQuestion($this->Registry);

		$this->validateTitle()
		->validateBody()
		->validateTags()
		->process()
		->setOutput();
	}


	/**
	 * Validate title length
	 *
	 * @return object $this
	 */
	protected function validateTitle(){
		$t = $this->Submitted->getTitle();
		$min = $this->Registry->Ini->MIN_TITLE_CHARS;
		d('min title: '.$min);
		if($this->Submitted->getTitle()->htmlentities()->trim()->length() < $min){
			throw new \Lampcms\HttpResponseCodeException('Title must contain at least '.$min.' letters', 400);
		}

		return $this;
	}


	/**
	 * Validate min number of words in question
	 * and min number of chars in question
	 *
	 * @return object $this
	 */
	protected function validateBody(){

		$minChars = $this->Registry->Ini->MIN_QUESTION_CHARS;
		$minWords = $this->Registry->Ini->MIN_QUESTION_WORDS;
		$body = $this->Submitted->getBody();
		$oHtmlString = HTMLString::factory($body);
		$wordCount = $oHtmlString->getWordsCount();
		$len = $oHtmlString->length();

		if($len < $minChars){
			throw new \Lampcms\HttpResponseCodeException('Question must contain at least '.$minChars.' letters', 400);
		}

		if($wordCount < $minWords){
			throw new \Lampcms\HttpResponseCodeException('Question must contain at least '.$minWords.' words', 400);
		}

		return $this;
	}


	/**
	 * Validate to enforce at least one tag
	 * and not more that value MAX_QUESTION_TAGS in settings
	 *
	 * @return object $this
	 */
	protected function validateTags(){
		$min = $this->Registry->Ini->MIN_QUESTION_TAGS;
		$max = $this->Registry->Ini->MAX_QUESTION_TAGS;
		
		$aTags = $this->Submitted->getTagsArray();
		$count = count($aTags);

		if($count > $max){
			throw new \Lampcms\HttpResponseCodeException('Question cannot have more than '.$max.' tags. Please remove some tags', 400);
		}

		if($count < $min){
			throw new \Lampcms\HttpResponseCodeException('Question must have at least '.$min.' tag(s)', 400);
		}

		return $this;
	}


	/**
	 *
	 * Process submitted form values
	 * and create the $this->Question object
	 */
	protected function process(){

		$oAdapter = new QuestionParser($this->Registry);
		try{
			$this->Question = $oAdapter->parse($this->Submitted);
			d('cp created new question');
			d('title: '.$this->Question['title']);
		} catch (\Lampcms\QuestionParserException $e){
			$err = $e->getMessage();
			d('$err: '.$err);

			throw new \Lampcms\HttpResponseCodeException($err, 400);
		}

		return $this;
	}


	/**
	 * Entire Question data will be returned
	 * in request
	 *
	 * @return object $this
	 */
	protected function setOutput(){

		$this->Output->setData($this->Question->getArrayCopy());

		return $this;
	}

}
