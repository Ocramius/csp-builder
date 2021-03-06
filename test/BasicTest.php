<?php
use ParagonIE\CSPBuilder\CSPBuilder;
/**
 * 
 */
class BasicTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $basic = CSPBuilder::fromFile(__DIR__.'/vectors/basic-csp.json');
        $basic->addSource('img-src', 'ytimg.com');
        $this->assertEquals(
            file_get_contents(__DIR__.'/vectors/basic-csp.out'),
            $basic->getCompiledHeader()
        );
        
        $noOld = file_get_contents(__DIR__.'/vectors/basic-csp-no-old.out');
        // We expect different output for ytimg.com when we disable legacy
        // browser support (i.e. Safari):
        $this->assertEquals(
            $noOld,
            $basic
                ->disableOldBrowserSupport()
                ->getCompiledHeader()
        );
        
        $array = $basic->getHeaderArray();
        $this->assertEquals(
            $array,
            [
                'Content-Security-Policy' => $noOld,
                'X-Content-Security-Policy' => $noOld,
                'X-Webkit-CSP' => $noOld
            ]
        );
        
        
        $array2 = $basic->getHeaderArray(false);
        $this->assertEquals(
            $array2,
            [
                'Content-Security-Policy' => $noOld
            ]
        );
    }
}
