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
 *    the website's Questions/Answers functionality is powered by lampcms.com
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


/**
 * All interfaces combined into this one file
 *
 * @important Allways include this file! Don't rely
 * of autoloader to include it!
 */

namespace Lampcms\Interfaces;

/**
 * Authentication class
 * must return User object
 *
 * @author Dmitri Snytkine
 *
 */
interface AuthProvider
{
	public function getUser();
}

interface Runnable{
	public function run();
}

interface Tokenizer
{
	public function countTokens();

	public function nextToken();

	public function hasMoveTokens();

	public function parse();
}

/**
 * Both XliffCatalog and Translator
 * implement this interface
 * The only difference is that Translator
 * has messages that include strings
 * from default languate + general language
 * + locale-specific merged together
 * 
 * @author Dmitri Snytkine
 *
 */
interface Translator {
	
	/**
	 * Get name of locale (lang + country or just lang)
	 * for which this object has
	 * messages
	 * 
	 * @return string name of locale. For example: en_US or en
	 */
	public function getLocale();
	
	/**
	 * Get translated message
	 * 
	 * @param string $string message to be translated
	 * 
	 * @param array $vars array of replacement vars
	 * 
	 * @param string $default fallback value to use
	 * if message $string does not exist in the catalog
	 * By default the object uses the value of $string itself
	 * if translated message does not exist but it's also possible
	 * to pass the value of $default string to be used
	 * as a fallback value
	 * 
	 * @return string translated string (or fallback value)
	 */
	public function get($string, array $vars = null, $default = null);
	
	/**
	 * Get array of messages for this catalog
	 * 
	 * @return array in 'string' => 'translated string' format
	 */
	public function getMessages();
	
	/**
	 * 
	 * A test to see if the object
	 * has the string in its array of messages
	 * 
	 * @param string $string
	 * 
	 * @return bool true if object has this $string
	 * in the messages catalog, false otherwise
	 */
	public function has($string);
}




interface Cookie
{

	/**
	 * Returnes value of specific cookie name
	 *
	 * @param string $cookieName
	 *
	 * @param mixed $fallbackVal a value to return if cookie
	 * does not exist or its value is empty
	 *
	 * @return mixed value if cookie found or false
	 * if cookie not found
	 */
	public function get($cookieName, $fallbackVal = false);

	/**
	 * Sends cookie with expiration
	 * in the past, which will delete the cookie
	 *
	 * @param mixed $name a string
	 * or array of cookies to delete
	 *
	 * @throws LampcmsDevException if $name
	 * is not string and not array
	 *
	 */
	public function delete($name);

	/**
	 * Sends cookie
	 *
	 * @param string $name name of cookie
	 *
	 * @param string $val value of cookie
	 *
	 * @param string $ttl expiration time in seconds
	 * default is 63072000 means 2 years
	 *
	 * @param string $sDomain optional if set the setcookie will use
	 * this value instead of LAMPCMS_COOKIE_DOMAIN constant
	 *
	 * @throws LampcmsDevException in case cookie
	 * was not send to browser. Usually this happends when
	 * the output has already been started. The main cause
	 * of this is when the script has an echo() or print_r()
	 * somewhere for debugging purposes.
	 */
	public function set($name, $val, $ttl = 63072000, $sDomain = null);

	/**
	 * Function for setting or deleting login cookie
	 * the value of the s cookie is an md5 hash of user password
	 * the value of the uid cookie is the userID
	 *
	 * @param boolean $boolKeepSigned true if user checked 'remember me' box on login form
	 * @param integer $intUserId userID
	 * @param string $strPassword user's password
	 * @return void cookies are sent to browser
	 *
	 */
	public function sendLoginCookie($intUserId, $strSID, $cookieName = 'uid');

}


interface Cache
{

	/**
	 * Get value of a single cache key
	 * @param string $key
	 * @return unknown_type
	 */
	public function get($key);

	/**
	 * Get value for array of keys
	 * @param array $aKeys
	 * @return unknown_type
	 */
	public function getMulti(array $aKeys);

	/**
	 * Puts a value of $key into cache for $ttl seconds
	 * @param $key
	 * @param $value
	 * @param $ttl
	 * @return unknown_type
	 */
	public function set($key, $value, $ttl = 0, array $tags = null);


	public function setMulti(array $aItems, $ttl = 0);

	/**
	 * Deletes key from cache
	 * @param string $key
	 * @param integer $ttl time in seconds after which to remove the key
	 * @return void
	 */
	public function delete($key, $exptime = 0);

	/**
	 * Increment numeric value of $key
	 *
	 * @param $key
	 * @param $int
	 * @return unknown_type
	 */
	public function increment($key, $int = 1);


	/**
	 * Decrement numeric value of $key
	 * @param string $key
	 * @param int $int
	 */
	public function decrement($key, $int = 1);

	/**
	 * Removes all data from cache
	 * @return void
	 */
	public function flush();

	public function __toString();
}

/**
 * Basic object interface
 * must implement at least these 3 methods
 *
 * @author Dmitri Snytkine
 *
 */
interface LampcmsObject
{
	public function hashCode();

	public function getClass();

	public function __toString();

}

/**
 * Resource Interface
 * modeled after Zend_Resource_Interface
 *
 */
interface Resource
{
	/**
	 * Returns the string identifier of the Resource
	 *
	 * @return string
	 */
	public function getResourceId();
}


/**
 * User implements this, thus
 * this interface in this file, because
 * we know 100% that it will be used with every page request
 *
 */
interface RoleInterface
{
	/**
	 * Returns the string identifier of the Role
	 *
	 * @return string
	 */
	public function getRoleId();


	public function setRoleId($role);
}


/**
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Assert
{
	/**
	 * Returns true if and only if the assertion conditions are met
	 *
	 * This method is passed the ACL, Role, Resource, and privilege to which the authorization query applies. If the
	 * $role, $resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
	 * privileges, respectively.
	 *
	 * @param  Acl  $acl
	 * @param  AclRole     $role
	 * @param  Resource $resource
	 * @param  string  $privilege
	 * @return boolean
	 */
	public function assert(\Lampcms\Acl\Acl $acl, RoleInterface $role = null, Resource $resource = null, $privilege = null);
}

/**
 *
 * Every resource like blog post, album,
 * even a single image or blog comment
 * should implement this interface
 *
 * Even the page controller (currently viewed page object)
 * should implement this interface so that we can
 * determine which user the object (including a page,
 * in which case it goes by the owner of the blog)
 * belongs to
 *
 * @author Dmitri Snytkine
 *
 */
interface LampcmsResource extends Resource
{

	/**
	 * Returns type of resource
	 * the type of resource is a string stored in RESOURCE
	 * collection as res_type
	 *
	 * @return string
	 */
	public function getResourceTypeId();

	/**
	 * Returnes id of user (USERS.id)
	 * who owns the resource
	 * Which is usually the user who created it
	 * but doest not have to be.
	 * It is up to the individual class
	 * to decide who owns the resource.
	 *
	 * @return int
	 */
	public function getOwnerId();

	/**
	 * Get unix timestamp of
	 * when resource was last modified
	 * This includes any type of change made to a
	 *
	 * resource, including when new comments were added
	 * or new rating added ,etc.
	 *
	 * It's up to the implementer to decide what changes
	 * are significant enough to be considered modified
	 * but usually the on update CURRENT TIMESTAMP
	 * is a very good way to mark resouce as modified
	 *
	 * @return int last modified time in unix timestamp
	 *
	 */
	public function getLastModified();

	/**
	 * Every resource when deleted actually has
	 * a 'deleted' timestamp set
	 * to the time when it was deleted.
	 * This way we consider a resource as deleted
	 * but can also find and possibly show
	 * the date/time when resource was deleted
	 * and also have the option to 'undelete'
	 * by just switching the 'deleted' column value
	 * back to 0
	 *
	 * @return int
	 * 0 means not deleted or unix timestamp of when resource
	 * was marked as deleted
	 */
	public function getDeletedTime();

	/**
	 * Updates last modified timestamp
	 *
	 */
	public function touch();

}

/**
 * A Resource that may have comments
 * For example a blog post
 * but can also be any other type of resource
 * like a news feed article
 * or forum feed article
 *
 * @author Dmitri Snytkine
 *
 */
interface CommentedResource
{

	/**
	 * Get total number of comments
	 *
	 * @return int
	 */
	public function getCommentsCount();


	public function addComment(\Lampcms\CommentParser $Comment);


	public function deleteComment($id);

	/**
	 * Get array of all comments
	 * @return array all comments
	 */
	public function getComments();
}

/**
 * Resource that can be rated in the UP/Down fashion
 * like 'did you find this message helpful?'
 * or did you like this picture 'yes/no'?
 *
 * @author Dmitri Snytkine
 *
 */
interface UpDownRatable
{

	/**
	 *
	 * @param int $inc could be 1 for vote
	 * or -1 for 'unvote'
	 */
	public function addUpVote($inc = 1);

	/**
	 *
	 *
	 * @param int $inc could be 1 for vote
	 * or -1 for 'unvote'
	 */
	public function addDownVote($inc = 1);

	/**
	 * If resource has the UP/DOWN vote
	 * capability then return array with keys
	 * up=>countUpVotes
	 * down=>countDownVotes
	 *
	 * A resource that does not implement up/down voting
	 * should return an empty array or null
	 *
	 * @return array
	 */
	public function getVotesArray();

	/**
	 * Get total score which is usually
	 * a combination of up votes - down votes
	 *
	 * @return int
	 */
	public function getScore();

}

/**
 * User interface
 * every user has userID which is unique
 * except for a case of guest - all guests
 * have userID of 0
 *
 * @author Dmitri Snytkine
 *
 */
interface User
{
	/**
	 * Get id from USERS table
	 * it may also return 0 if id not available
	 * like when user is not logged in
	 * or when object is not an actual user per say
	 *
	 * @return int
	 */
	public function getUid();
}

/**
 * A Blog message is either an
 * original blog post
 * OR a comment to a blog
 * Each blog message must have a body
 * and userID of user who posted the message
 * and id of blog to which a message belongs
 * This is because a message can be posted
 * by someone who is not the owner of the blog,
 * for example a user may have permission
 * to post to company blog
 *
 * There are other important data for
 * each message like timestamp,
 * subject (title) and other things
 * but we must at least have these most
 * important parts of the message
 *
 * @author Dmitri Snytkine
 *
 */
interface BlogMessage
{
	/**
	 * Get the body of the message
	 *
	 * @return string body of this message
	 */
	public function getBody();

	/**
	 * Set $string to be the body of message
	 * possibly replacing already existing one
	 *
	 * @param string $string
	 * @return usually object $this
	 */
	public function setBody($string);

	/**
	 *
	 * @return int id of blog to which this message belongs
	 */
	public function getBlogId();
}


/**
 * Twitter user
 * user who had signed in with Twitter
 *
 * @author Dmitri Snytkine
 *
 */
interface TwitterUser
{
	/**
	 * Get oAuth token
	 * that we got from Twitter for this user
	 * @return string
	 */
	public function getTwitterToken();

	/**
	 * Get oAuth sercret that we got for this user
	 * @return string
	 */
	public function getTwitterSecret();

	/**
	 * Get twitter user_id
	 * @return int
	 */
	public function getTwitterUid();

	public function getTwitterUsername();

	public function revokeOauthToken();
}


/**
 * Tumblr user
 * user who has connected
 * Tumblr blog to account
 *
 * @author Dmitri Snytkine
 *
 */
interface TumblrUser
{
	/**
	 * Get oAuth token
	 * that we got from Twitter for this user
	 * @return string
	 */
	public function getTumblrToken();

	/**
	 * Get oAuth sercret that we got for this user
	 * @return string
	 */
	public function getTumblrSecret();

	/**
	 * Revoke token and secret - remove
	 * these values from User object
	 *
	 */
	public function revokeTumblrToken();

	/**
	 * Get html for the link to tumblr blog
	 * @return string html of link
	 */
	public function getTumblrBlogLink();

	/**
	 * Get array of all user's blogs
	 * @return mixed array of at least one blog | null
	 * if user does not have any blogs (not a usual situation)
	 *
	 */
	public function getTumblrBlogs();

	public function getTumblrBlogTitle();

	public function getTumblrBlogUrl();


	/**
	 * Get value of 'group' or 'private-id'
	 * This is used for indicating which blog
	 * the post will go to. It is needed
	 * in case when user has more than one blog
	 * on Tumblr.
	 * If user has only one blog we still use this param
	 * for consistancy
	 *
	 * @return string value to be used as 'group' param
	 * in WRITE API call
	 *
	 */
	public function getTumblrBlogId();

	public function setTumblrBlogs(array $blogs);
}


/**
 * Blogger user
 * user who has connected
 * Blogger blog to account
 *
 * @author Dmitri Snytkine
 *
 */
interface BloggerUser
{
	/**
	 * Get oAuth token
	 * that we got from Twitter for this user
	 * @return string
	 */
	public function getBloggerToken();

	/**
	 * Get oAuth sercret that we got for this user
	 * @return string
	 */
	public function getBloggerSecret();

	/**
	 * Revoke token and secret - remove
	 * these values from User object
	 *
	 */
	public function revokeBloggerToken();

	/**
	 * Get html for the link to Blogger blog
	 * @return string html of link
	 */
	public function getBloggerBlogLink();

	/**
	 * Get array of all user's blogs
	 * @return mixed array of at least one blog | null
	 * if user does not have any blogs (not a usual situation)
	 *
	 */
	public function getBloggerBlogs();

	/**
	 * Get title of default blog
	 *
	 */
	public function getBloggerBlogTitle();

	/**
	 *
	 * Get url of default blog
	 */
	public function getBloggerBlogUrl();


	/**
	 * @return string value to be used as '<blogid>' param
	 * in WRITE API call
	 *
	 */
	public function getBloggerBlogId();

	/**
	 * Set value of 'blogs' under the 'blogger' element
	 *
	 *
	 * @param array $blogs array of all blogs
	 * user has on Blogger. Each element is an array
	 * with 3 keys: id, url, title
	 */
	public function setBloggerBlogs(array $blogs);
}




/**
 * Linkedin user
 * user who has connected
 * Linkedin  account
 *
 * @author Dmitri Snytkine
 *
 */
interface LinkedinUser
{
	/**
	 * Get oAuth token
	 * that we got from Twitter for this user
	 * @return string
	 */
	public function getLinkedinToken();

	/**
	 * Get oAuth sercret that we got for this user
	 * @return string
	 */
	public function getLinkedinSecret();

	/**
	 * Revoke token and secret - remove
	 * these values from User object
	 *
	 */
	public function revokeLinkedinToken();

	/**
	 * Get html for the link to tumblr blog
	 * @return string html of link
	 */
	public function getLinkedinUrl();


	/**
	 * Get value of 'group' or 'private-id'
	 * This is used for indicating which blog
	 * the post will go to. It is needed
	 * in case when user has more than one blog
	 * on Linkedin.
	 * If user has only one blog we still use this param
	 * for consistancy
	 *
	 * @return string value to be used as 'group' param
	 * in WRITE API call
	 *
	 */
	public function getLinkedinId();

}

/**
 *
 * Enter description here ...
 * @author Dmitri Snytkine
 *
 */
interface FacebookUser
{
	public function revokeFacebookConnect();

	public function getFacebookUid();

	public function getFacebookToken();
}

/**
 * A Post is either a Question or an Answer
 *
 * @author admin
 *
 */
interface Post extends LampcmsResource
{
	public function getQuestionId();

	public function getTitle();

	public function getSeoUrl();

	public function getUrl($short = false);

	public function getBody();
}

/**
 * @author Dmitri Snytkine
 *
 */
interface Question extends Post
{
	/**
	 * Should return false if NOT closed
	 * otherwise either true or timestamp
	 * of when it was closed
	 */
	public function isClosed();

	/**
	 * Add userid of User to the list
	 * of contributors.
	 * A Contributor is anyone who
	 * has made an answer or a comment
	 * to a question
	 *
	 * @param mixed int | object $User object of type User
	 */
	public function addContributor($User);

	/**
	 * Remove user as question
	 * contributor
	 * This does not necessaraly remove user from
	 * array of contributors since that array is not unique
	 * in which case it will
	 * simply remove one of the elements
	 * from that array.
	 *
	 * @param mixed int | object $User object of type User
	 */
	public function removeContributor($User);

	/**
	 * Get total number of answers for this question
	 *
	 * @return int
	 */
	public function getAnswerCount();

	/**
	 * Get id of category
	 * @return int
	 */
	public function getCategoryId();

	/**
	 * Set time, reason for when question was closed
	 * as well as username and userid of user who closed it
	 *
	 * @param string $reason
	 * @param object $closer user who closed the question
	 */
	public function setClosed(\Lampcms\User $closer, $reason = null);

	/**
	 * Mark Question as deleted
	 *
	 * @param User $user
	 * @param string $reason
	 */
	public function setDeleted(\Lampcms\User $user, $reason = null);

	/**
	 * Must set the id of best_answer,
	 * also updates Answer (by side-effect)
	 *
	 * @param Answer $Answer object of type Answer
	 * which is being accepted as best answer
	 */
	public function setBestAnswer(\Lampcms\Answer $Answer);

	public function updateAnswerCount($int = 1);

	/**
	 *
	 * Adds the small array with link to last poster
	 * and time of last post and id of last answer
	 * to the a_latest element of the Question
	 *
	 * @param \Lampcms\User $User
	 * @param \Lampcms\Answer $Answer
	 */
	public function setLatestAnswer(\Lampcms\User $User, \Lampcms\Answer $Answer);

	/**
	 * Method to run when an answer is delete
	 * Deleting an Answer affects several values
	 * in Question like count of answers, status of question etc.
	 * even more so if that answer
	 * was also a "accepted" answer
	 * 
	 * @param \Lampcms\Answer $Answer
	 */
	public function removeAnswer(\Lampcms\Answer $Answer);
}

/**
 * Object represents one Answer
 *
 * @author Dmitri Snytkine
 *
 */
interface Answer extends Post
{

	public function getCategoryId();

	/**
	 * Get id of user that owns the question for which
	 * this is an answer
	 *
	 * @return int
	 */
	public function getQuestionOwnerId();

	/**
	 * Sets this answer status as accepted answer
	 *
	 *
	 */
	public function setAccepted();

	/**
	 * Unsets the accepted status for this answer
	 * Enter description here ...
	 */
	public function unsetAccepted();
}

interface Search
{
	public function __construct(\Lampcms\Registry $Registry);

	public function search($term = null);

	public function count();

	/**
	 * Find titles similar to the title
	 * This will be used for showing
	 * hints during composing of new question
	 * as well as for auto-complete for search
	 *
	 * @param bool $bBoolMode indicates that search
	 * should return items matching all words
	 *
	 * @param string title term used in search
	 */
	//public function getSimilarTitles($title, $bBoolMode = true);


	public function getSimilarQuestions(\Lampcms\Question $Question);

	public function getHtml();

	public function getPagerLinks();

}

/**
 * Indexer interface
 * Class implementing this interface
 * is responsible for adding
 * the contents of question (and maybe answer)
 * to the search index, removing question
 * from Search index and updating Search index
 * when contents of question change
 *
 * @author Dmitri Snytkine
 *
 */
interface Indexer
{
	public function __construct(\Lampcms\Registry $Registry);

	public function indexQuestion(\Lampcms\Question $Question);

	public function removeQuestion(\Lampcms\Question $Question);

	public function updateQuestion(\Lampcms\Question $Question);

	/**
	 * Remove all records from Search index
	 * that belong to particular user by user id
	 *
	 *
	 * @param int $uid
	 */
	public function removeByUserId($uid);
}

/**
 * Submitted comment must
 * implement this interface
 *
 * Comment may be submitted via web, or
 * via some type of API client or possibly
 * via email
 *
 * @author Dmitri Snytkine
 *
 */
interface SubmittedComment extends LampcmsResource
{
	public function __construct(\Lampcms\Registry $Registry, \Lampcms\Interfaces\LampcmsResource $Resource = null);

	/**
	 *
	 * Must return parsed body
	 * body must be sanitized and parsed
	 * for Markdown and in guaranteed
	 * utf8 encoding
	 *
	 * @return string utf8 html
	 */
	public function getBody();


	/**
	 * Get value of _id of question
	 * In case this comment if for an answer,
	 * it must return value of i_qid from the
	 * Answer object
	 *
	 * @return int
	 */
	public function getQuestionId();


	/**
	 * Get Resource object for which
	 * this comment is made
	 * This is either Answer or Question resoure
	 *
	 * @return object of type Question or Answer
	 */
	public function getResource();

	/**
	 * Get IP address from where
	 * the comment was submitted
	 *
	 * @return string ip address
	 */
	public function getIP();

	/**
	 *
	 * Get id of parent comment
	 * will return null if not a reply
	 * or value of _id of comment for which
	 * this comment is a reply
	 */
	public function getParentId();

	/**
	 * Get object of type User of user
	 * who posted the answer
	 *
	 * @return object of type User
	 */
	public function getUserObject();

	/**
	 * Implemeting class may return
	 * some extra data like name of API
	 * that was used to submit comment
	 *
	 * At minumum it must return empty array
	 *
	 * @return array associative array
	 */
	public function getExtraData();

	public function getCollectionName();
	
	/**
	 * Get name of the app that was used
	 * for submitting this Comment
	 * May also return an empty string in case
	 * of API used with Basic Auth where user
	 * credentials are passed to API but
	 * there is not any app id
	 * 
	 * @return string 
	 */
	public function getApp();
	
	/**
	 * Get ip of app that was used for submitting
	 * this comment
	 * 
	 * @return mixed null if 'web' or
	 * unregistere app (just like in case of Basic Auth) | int in case
	 * of the actual registered app.
	 * 
	 */
	public function getAppId();

}
