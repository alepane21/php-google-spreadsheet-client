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
 * Worksheet.
 *
 * @package    Google
 * @subpackage Spreadsheet
 * @author     Asim Liaquat <asimlqt22@gmail.com>
 */
class Google_Spreadsheet_Worksheet
{
    /**
     * A worksheet xml object
     * 
     * @var SimpleXMLElement
     */
    private $xml;

    private $postUrl;

    private $editCellPostUrl;

    /**
     * Initializes the worksheet object.
     * 
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $xml->registerXPathNamespace('gs', 'http://schemas.google.com/spreadsheets/2006');
        $this->xml = $xml;
    }

    /**
     * Get the worksheet id. Returns the full url. 
     * 
     * @return string
     */
    public function getId()
    {
        return (string) $this->xml->id;
    }

    /**
     * Get the updated date
     * 
     * @return DateTime
     */
    public function getUpdated()
    {
        return new DateTime((string) $this->xml->updated);
    }

    /**
     * Get the title of the worksheet
     * 
     * @return string
     */
    public function getTitle()
    {
        return (string) $this->xml->title;
    }

    /**
     * Get the number of rows in the worksheet
     *
     * @return int
     */
    public function getRowCount()
    {
        $el = current($this->xml->xpath('//gs:rowCount'));
        return (int) ((string) $el);
    }

    /**
     * Get the number of columns in the worksheet
     *
     * @return int
     */
    public function getColCount()
    {
        $el = current($this->xml->xpath('//gs:colCount'));
        return (int) ((string) $el);
    }

    /**
     * Get the list feed of this worksheet
     * 
     * @return Google_Spreadsheet_ListFeed
     */
    public function getListFeed()
    {
        $res = Google_Spreadsheet_ServiceRequestFactory::getInstance()->get($this->getListFeedUrl());
        return new Google_Spreadsheet_ListFeed($res);
    }

    /**
     * Get the cell feed of this worksheet
     * 
     * @return Google_Spreadsheet_CellFeed
     */
    public function getCellFeed()
    {
        $res = Google_Spreadsheet_ServiceRequestFactory::getInstance()->get($this->getCellFeedUrl());
        return new Google_Spreadsheet_CellFeed($res);
    }

    /**
     * Delete this worksheet
     *
     * @return null
     */
    public function delete()
    {
        Google_Spreadsheet_ServiceRequestFactory::getInstance()->delete($this->getEditUrl());
    }

    public function setPostUrl($url)
    {
        $this->postUrl = $url;
    }

    /**
     * Get the edit url of the worksheet
     * 
     * @return string
     */
    public function getEditUrl()
    {
        return Google_Spreadsheet_Util::getLinkHref($this->xml, 'edit');
    }

    /**
     * The url which is used to fetch the data of a worksheet as a list
     * 
     * @return string
     */
    public function getListFeedUrl()
    {
        return Google_Spreadsheet_Util::getLinkHref($this->xml, 'http://schemas.google.com/spreadsheets/2006#listfeed');
    }

    /**
     * Get the cell feed url
     * 
     * @return stirng
     */
    public function getCellFeedUrl()
    {
        return Google_Spreadsheet_Util::getLinkHref($this->xml, 'http://schemas.google.com/spreadsheets/2006#cellsfeed');
    }
}