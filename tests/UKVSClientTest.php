<?php
/**
 *
 * User: chipwasson
 * Date: 1/17/17
 * Time: 10:49 AM
 */

require_once __DIR__.'/../vendor/autoload.php';

use UKVS\Client\UKVSClient;
use UKVS\Client\UKVSObject;
use UKVS\Client\UKVSRelated;


class UKVSClientTest extends PHPUnit_Framework_TestCase
{

    public function testLoad()
    {
        $ukvs = new UKVSClient("bar");
        $result = $ukvs->get("bing");
        $this->assertEquals("bing", $result->id);
        $this->assertInstanceOf(UKVSObject::class, $result);
        $this->assertInstanceOf(stdClass::class, $result->obj);
    }

    public function testRelated()
    {
        $ukvs = new UKVSClient("bar");
        $result = $ukvs->getRelated("bing");
        $this->assertEquals("bing", $result->id);
        $this->assertInstanceOf(UKVSRelated::class, $result);
        $this->assertGreaterThan(0, sizeof($result->related));
        $this->assertInstanceOf(UKVSObject::class, $result->related[0]);
        $this->assertInstanceOf(stdClass::class, $result->related[0]->obj);
    }
}
