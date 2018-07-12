<?php

namespace Tests\vdeApps\phpCore;

use PHPUnit\Framework\TestCase;
use vdeApps\phpCore\Helper;

class HelperTest extends TestCase {
    public function testFormatted() {
        $val = 123.4567;
        $bigVal = 7123456789.824568284;
        
        $this->assertEquals('', Helper::formatted(''));
        
        $this->assertEquals(123, Helper::formatted($val));
        
        
        $this->assertEquals('123,46', Helper::formatted($val, 2));
        $this->assertEquals('123,457', Helper::formatted($val, 3));
        
        $this->assertEquals('7#123#456#789,82', Helper::formatted($bigVal, 2, '#'));
        $this->assertEquals('7#123#456#789,825', Helper::formatted($bigVal, 3, '#'));
        
        $this->assertEquals('7 123 456 789/82', Helper::formatted($bigVal, 2, ' ', '/'));
        $this->assertEquals('7 123 456 789/825', Helper::formatted($bigVal, 3, ' ', '/'));
    }
    
    public function testCamelCase() {
        
        $str1 = 'test.coucou';
        $str2 = 'test-coucou';
        $str3 = 'test_coucou.hello.DARLING';
        $str4 = 'TEST avec Des espAces';
        
        $this->assertEquals('testCoucou', Helper::camelCase($str1));
        $this->assertEquals('testCoucou', Helper::camelCase($str2));
        $this->assertEquals('testCoucouHelloDarling', Helper::camelCase($str3));
        
        $this->assertEquals('TestCoucou', Helper::camelCase($str1, 'ucfirst'));
        $this->assertEquals('TestCoucou', Helper::camelCase($str2, 'ucfirst'));
        $this->assertEquals('testAvecDesEspaces', Helper::camelCase($str4));
    }
    
    public function testBase64_encode() {
        $str = 'Ceci est mon message';
        $b64Stripped = 'Q2VjaSBlc3QgbW9uIG1lc3NhZ2U';
        $b64NotStripped = 'Q2VjaSBlc3QgbW9uIG1lc3NhZ2U=';
        
        $this->assertEquals($b64Stripped, Helper::base64_encode($str));
        $this->assertEquals($b64Stripped, Helper::base64_encode($str, true));
        $this->assertEquals($b64NotStripped, Helper::base64_encode($str, false));
        
    }
    
    public function testBase64_decode() {
        $str = 'Ceci est mon message';
        $b64Stripped = 'Q2VjaSBlc3QgbW9uIG1lc3NhZ2U';
        $b64NotStripped = 'Q2VjaSBlc3QgbW9uIG1lc3NhZ2U=';
        $this->assertEquals($str, Helper::base64_decode($b64Stripped));
        $this->assertEquals($str, Helper::base64_decode($b64NotStripped));
    }
    
    public function testRand() {
        
        $start = 10;
        $end = 30;
        $tb = [];
        $tbUniq = [];
        for ($i = 0; $i < 15; $i++) {
            $tb[] = Helper::rand($start, $end, $tbUniq);
        }
        $this->assertEquals(15, count($tb));
        
        // Nombre d'iteration trop grand
        try {
            $start = 400;
            $end = 450;
            $tb = [];
            $tbUniq = [];
            for ($i = 0; $i < 55; $i++) {
                $tb[] = Helper::rand($start, $end, $tbUniq);
            }
        }
        catch (\Exception $ex) {
            $this->assertEquals($ex->getCode(), 5);
        }
        
        
    }
    
    public function testCompareValues() {
        $val1 = 'val1';
        $val2 = 'val2';
        $val3 = ['val1','val2'];
        $val4 = ['val4'];
        $val5 = ['val1','val4'];
    
        $ret = Helper::compareValues($val1,$val1);
        $this->assertEquals(true, $ret);
    
        $ret = Helper::compareValues($val1,$val2);
        $this->assertNotEquals([$val2], $ret);
    
        $ret = Helper::compareValues($val3,$val3);
        $this->assertEquals(true, $ret);
    
        $ret = Helper::compareValues($val2,$val3);
        $this->assertNotEquals(false,$ret);
    
        $ret = Helper::compareValues($val4,$val3);
        $this->assertEquals(false, $ret);
    
        $ret = Helper::compareValues($val4,$val3, 'NOTIN');
        $this->assertEquals(true, $ret);
    
        $ret = Helper::compareValues($val4,$val5, 'NOTIN');
        $this->assertEquals(false, $ret);
    
        $ret = Helper::compareValues($val5,$val3, 'NOTIN');
        $this->assertEquals(true, $ret);
    
        $ret = Helper::compareValues($val4,$val4, 'IN');
        $this->assertEquals(true, $ret);
    
        $ret = Helper::compareValues($val3,$val3, 'EQ');
        $this->assertEquals(true, $ret);
    }
}