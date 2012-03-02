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


namespace Lampcms;

class MockUser extends User
{
	const PASS = 'abc12345';

	protected $JSON_ENCODED = '{"username":"ladada","username_lc":"ladada","email":"ladada123@mailinator.com","rs":"1297206315.3591az0q3KyOT1cp96s6ulBwtzuYz81sDo35G","role":"registered","tz":"Atlantic\/Azores","pwd":"c84a41b784503c9b4e766f4af56968d68e99c6edd3a069d7baada054da55576d","i_reg_ts":1305227187,"date_reg":"Thu, 12 May 2011 14:06:27 -0500","i_fv":1297206315,"lang":"en","locale":"en_US","i_pp":1,"cc":"US","cn":"United States","state":"PA","city":"Stroudsburg","zip":"18301","_id":26,"i_ts_login":1305469732,"i_lm_ts":1305469733,"a_f_u":[3],"i_f_u":1,"a_f_t":["stub","mongodb"],"i_f_t":2,"fn":"John","mn":"D","ln":"Doe","url":"http:\/\/www.lampcms.com","dob":"1990\/1\/3","gender":"M","description":"I am the test user","avatar":"1A.jpg"}';

	public function __construct(Registry $Registry, $collectionName = null, array $a = array(), $default = ''){
		$a = json_decode($this->JSON_ENCODED, true);
		parent::__construct($Registry, 'USERS', $a);
	}

	public static function factory(Registry $Registry, array $a = array()){
		$o = new static($Registry);
		//$o->applyDefaults();

		return $o;
	}
}
