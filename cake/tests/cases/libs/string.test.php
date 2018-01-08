<?php
/* SVN FILE: $Id$ */
/**
 * StringTest file
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package       cake
 * @subpackage    cake.tests.cases.libs
 * @since         CakePHP(tm) v 1.2.0.5432
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', 'StringClass');
/**
 * StringTest class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs
 */
class StringTest extends CakeTestCase {
/**
 * testUuidGeneration method
 *
 * @access public
 * @return void
 */
	function testUuidGeneration() {
		$result = StringClass::uuid();
		$pattern = "/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/";
		$match = preg_match($pattern, $result);
		$this->assertTrue($match);
	}
/**
 * testMultipleUuidGeneration method
 *
 * @access public
 * @return void
 */
	function testMultipleUuidGeneration() {
		$check = array();
		$count = mt_rand(10, 1000);
		$pattern = "/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/";

		for($i = 0; $i < $count; $i++) {
			$result = StringClass::uuid();
			$match = preg_match($pattern, $result);
			$this->assertTrue($match);
			$this->assertFalse(in_array($result, $check));
			$check[] = $result;
		}
	}
/**
 * testInsert method
 *
 * @access public
 * @return void
 */
	function testInsert() {
		$string = '2 + 2 = :sum. Cake is :adjective.';
		$expected = '2 + 2 = 4. Cake is yummy.';
		$result = StringClass::insert($string, array('sum' => '4', 'adjective' => 'yummy'));
		$this->assertEqual($result, $expected);

		$string = '2 + 2 = %sum. Cake is %adjective.';
		$result = StringClass::insert($string, array('sum' => '4', 'adjective' => 'yummy'), array('before' => '%'));
		$this->assertEqual($result, $expected);

		$string = '2 + 2 = 2sum2. Cake is 9adjective9.';
		$result = StringClass::insert($string, array('sum' => '4', 'adjective' => 'yummy'), array('format' => '/([\d])%s\\1/'));
		$this->assertEqual($result, $expected);

		$string = '2 + 2 = 12sum21. Cake is 23adjective45.';
		$expected = '2 + 2 = 4. Cake is 23adjective45.';
		$result = StringClass::insert($string, array('sum' => '4', 'adjective' => 'yummy'), array('format' => '/([\d])([\d])%s\\2\\1/'));
		$this->assertEqual($result, $expected);

		$string = ':web :web_site';
		$expected = 'www http';
		$result = StringClass::insert($string, array('web' => 'www', 'web_site' => 'http'));
		$this->assertEqual($result, $expected);

		$string = '2 + 2 = <sum. Cake is <adjective>.';
		$expected = '2 + 2 = <sum. Cake is yummy.';
		$result = StringClass::insert($string, array('sum' => '4', 'adjective' => 'yummy'), array('before' => '<', 'after' => '>'));
		$this->assertEqual($result, $expected);

		$string = '2 + 2 = \:sum. Cake is :adjective.';
		$expected = '2 + 2 = :sum. Cake is yummy.';
		$result = StringClass::insert($string, array('sum' => '4', 'adjective' => 'yummy'));
		$this->assertEqual($result, $expected);

		$string = '2 + 2 = !:sum. Cake is :adjective.';
		$result = StringClass::insert($string, array('sum' => '4', 'adjective' => 'yummy'), array('escape' => '!'));
		$this->assertEqual($result, $expected);

		$string = '2 + 2 = \%sum. Cake is %adjective.';
		$expected = '2 + 2 = %sum. Cake is yummy.';
		$result = StringClass::insert($string, array('sum' => '4', 'adjective' => 'yummy'), array('before' => '%'));
		$this->assertEqual($result, $expected);

		$string = ':a :b \:a :a';
		$expected = '1 2 :a 1';
		$result = StringClass::insert($string, array('a' => 1, 'b' => 2));
		$this->assertEqual($result, $expected);

		$string = ':a :b :c';
		$expected = '2 3';
		$result = StringClass::insert($string, array('b' => 2, 'c' => 3), array('clean' => true));
		$this->assertEqual($result, $expected);

		$string = ':a :b :c';
		$expected = '1 3';
		$result = StringClass::insert($string, array('a' => 1, 'c' => 3), array('clean' => true));
		$this->assertEqual($result, $expected);

		$string = ':a :b :c';
		$expected = '2 3';
		$result = StringClass::insert($string, array('b' => 2, 'c' => 3), array('clean' => true));
		$this->assertEqual($result, $expected);

		$string = ':a, :b and :c';
		$expected = '2 and 3';
		$result = StringClass::insert($string, array('b' => 2, 'c' => 3), array('clean' => true));
		$this->assertEqual($result, $expected);

		$string = '":a, :b and :c"';
		$expected = '"1, 2"';
		$result = StringClass::insert($string, array('a' => 1, 'b' => 2), array('clean' => true));
		$this->assertEqual($result, $expected);

		$string = '"${a}, ${b} and ${c}"';
		$expected = '"1, 2"';
		$result = StringClass::insert($string, array('a' => 1, 'b' => 2), array('before' => '${', 'after' => '}', 'clean' => true));
		$this->assertEqual($result, $expected);

		$string = '<img src=":src" alt=":alt" class="foo :extra bar"/>';
		$expected = '<img src="foo" class="foo bar"/>';
		$result = StringClass::insert($string, array('src' => 'foo'), array('clean' => 'html'));

		$this->assertEqual($result, $expected);

		$string = '<img src=":src" class=":no :extra"/>';
		$expected = '<img src="foo"/>';
		$result = StringClass::insert($string, array('src' => 'foo'), array('clean' => 'html'));
		$this->assertEqual($result, $expected);

		$string = '<img src=":src" class=":no :extra"/>';
		$expected = '<img src="foo" class="bar"/>';
		$result = StringClass::insert($string, array('src' => 'foo', 'extra' => 'bar'), array('clean' => 'html'));
		$this->assertEqual($result, $expected);

		$result = StringClass::insert("this is a ? string", "test");
		$expected = "this is a test string";
		$this->assertEqual($result, $expected);

		$result = StringClass::insert("this is a ? string with a ? ? ?", array('long', 'few?', 'params', 'you know'));
		$expected = "this is a long string with a few? params you know";
		$this->assertEqual($result, $expected);

		$result = StringClass::insert('update saved_urls set url = :url where id = :id', array('url' => 'http://www.testurl.com/param1:url/param2:id','id' => 1));
		$expected = "update saved_urls set url = http://www.testurl.com/param1:url/param2:id where id = 1";
		$this->assertEqual($result, $expected);

		$result = StringClass::insert('update saved_urls set url = :url where id = :id', array('id' => 1, 'url' => 'http://www.testurl.com/param1:url/param2:id'));
		$expected = "update saved_urls set url = http://www.testurl.com/param1:url/param2:id where id = 1";
		$this->assertEqual($result, $expected);

		$result = StringClass::insert(':me cake. :subject :verb fantastic.', array('me' => 'I :verb', 'subject' => 'cake', 'verb' => 'is'));
		$expected = "I :verb cake. cake is fantastic.";
		$this->assertEqual($result, $expected);

		$result = StringClass::insert(':I.am: :not.yet: passing.', array('I.am' => 'We are'), array('before' => ':', 'after' => ':', 'clean' => array('replacement' => ' of course', 'method' => 'text')));
		$expected = "We are of course passing.";
		$this->assertEqual($result, $expected);

		$result = StringClass::insert(
			':I.am: :not.yet: passing.',
			array('I.am' => 'We are'),
			array('before' => ':', 'after' => ':', 'clean' => true)
		);
		$expected = "We are passing.";
		$this->assertEqual($result, $expected);

		$result = StringClass::insert('?-pended result', array('Pre'));
		$expected = "Pre-pended result";
		$this->assertEqual($result, $expected);

		$string = 'switching :timeout / :timeout_count';
		$expected = 'switching 5 / 10';
		$result = StringClass::insert($string, array('timeout' => 5, 'timeout_count' => 10));
		$this->assertEqual($result, $expected);

		$string = 'switching :timeout / :timeout_count';
		$expected = 'switching 5 / 10';
		$result = StringClass::insert($string, array('timeout_count' => 10, 'timeout' => 5));
		$this->assertEqual($result, $expected);

		$string = 'switching :timeout_count by :timeout';
		$expected = 'switching 10 by 5';
		$result = StringClass::insert($string, array('timeout' => 5, 'timeout_count' => 10));
		$this->assertEqual($result, $expected);

		$string = 'switching :timeout_count by :timeout';
		$expected = 'switching 10 by 5';
		$result = StringClass::insert($string, array('timeout_count' => 10, 'timeout' => 5));
		$this->assertEqual($result, $expected);
	}
/**
 * test Clean Insert
 *
 * @return void
 **/
	function testCleanInsert() {
		$result = StringClass::cleanInsert(':incomplete', array('clean' => true, 'before' => ':', 'after' => ''));
		$this->assertEqual($result, '');

		$result = StringClass::cleanInsert(':incomplete', array(
			'clean' => array('method' => 'text', 'replacement' => 'complete'),
			'before' => ':', 'after' => '')
		);
		$this->assertEqual($result, 'complete');

		$result = StringClass::cleanInsert(':in.complete', array('clean' => true, 'before' => ':', 'after' => ''));
		$this->assertEqual($result, '');

		$result = StringClass::cleanInsert(':in.complete and', array('clean' => true, 'before' => ':', 'after' => ''));
		$this->assertEqual($result, '');

		$result = StringClass::cleanInsert(':in.complete or stuff', array('clean' => true, 'before' => ':', 'after' => ''));
		$this->assertEqual($result, 'stuff');

		$result = StringClass::cleanInsert('<p class=":missing" id=":missing">Text here</p>', array('clean' => 'html', 'before' => ':', 'after' => ''));
		$this->assertEqual($result, '<p>Text here</p>');
	}
/**
 * testTokenize method
 *
 * @access public
 * @return void
 */
	function testTokenize() {
		$result = StringClass::tokenize('A,(short,boring test)');
		$expected = array('A', '(short,boring test)');
		$this->assertEqual($result, $expected);

		$result = StringClass::tokenize('A,(short,more interesting( test)');
		$expected = array('A', '(short,more interesting( test)');
		$this->assertEqual($result, $expected);

		$result = StringClass::tokenize('A,(short,very interesting( test))');
		$expected = array('A', '(short,very interesting( test))');
		$this->assertEqual($result, $expected);

		$result = StringClass::tokenize('"single tag"', ' ', '"', '"');
		$expected = array('"single tag"');
		$this->assertEqual($expected, $result);

		$result = StringClass::tokenize('tagA "single tag" tagB', ' ', '"', '"');
		$expected = array('tagA', '"single tag"', 'tagB');
		$this->assertEqual($expected, $result);
	}
}
?>
