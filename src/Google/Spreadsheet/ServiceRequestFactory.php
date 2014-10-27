<?php
/**
 * Copyright 2013 Asim Liaquat
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * ServiceRequestFactory
 *
 * @package    Google
 * @subpackage Spreadsheet
 * @author     Asim Liaquat <asimlqt22@gmail.com>
 */
class Google_Spreadsheet_ServiceRequestFactory
{
    private static $instance;

    /**
     * [setInstance description]
     * 
     * @param Google_Spreadsheet_ServiceRequestInterface $instance
     */
    public static function setInstance(Google_Spreadsheet_ServiceRequestInterface $instance = null)
    {
        self::$instance = $instance;
    }

    /**
     * [getInstance description]
     * 
     * @return Google_Spreadsheet_ServiceRequestInterface
     * 
     * @throws Google_Spreadsheet_Exception
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            throw new Google_Spreadsheet_Exception();
        }
        return self::$instance;
    }
}