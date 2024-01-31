<?php
namespace Rysun\DarkMode\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;


class Data extends AbstractHelper{


    /*
    public function __contruct(
        
    )
    {
       
    }
    */

    /**
     * Check Dark Mode for Architech pages
     */
    public function enabledDarkMode(){
        return true;
    }



   

}