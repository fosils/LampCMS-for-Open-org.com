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


namespace Lampcms\Controllers;

use Lampcms\WebPage;

/**
 * Controller to view Users' registered
 * Apps.
 * If User does not have any apps
 * then redirect to the /apiclient/ page
 * so that user may start creating new app
 *
 *
 * @author Dmitri Snytkine
 *
 */
class Viewapps extends WebPage
{
	/**
	 * Pre-check to deny non-logged in user
	 * access to this page
	 *
	 * @var bool
	 */
	protected $membersOnly = true;


	/**
	 *
	 * MongoCursor with all registered apps
	 * that belong to Viewer
	 * @var object of type MongoCursor
	 */
	protected $cursor;


	/**
	 * $layoutID 1 means no side-column on page
	 *
	 * @var int
	 */
	protected $layoutID = 1;



	protected function main(){
		$this->getApps()
		->setTitle()
		->setApps();
	}


	/**
	 * Find all apps that belong the the
	 * Viewer
	 * If none are found then redirect
	 * to the page to create a new app
	 * 
	 * @throws \Lampcms\RedirectException
	 */
	protected function getApps(){
		$this->cursor = $this->Registry->Mongo->API_CLIENTS->find(array('i_uid' => $this->Registry->Viewer->getUid()));
		if(0 === $this->cursor->count()){
			throw new \Lampcms\RedirectException('/index.php?a=editapp');
		}

		return $this;
	}


	protected function setTitle(){
		$this->title = $this->aPageVars['title'] = 'Manage your applications';

		return $this;
	}


	/**
	 * Set the 'body' of the page
	 * with the content of list of user's apps
	 *
	 * @return object $this
	 */
	protected function setApps(){

		$sApps = \tplApps::loop($this->cursor);

		$this->aPageVars['body'] = \tplViewapps::parse(array('apps' =>  $sApps) );

		return $this;
	}
}
